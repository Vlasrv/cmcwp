<?php

class CmcApi {
    private $_api_key = null;
    
    public function __construct() {
        $this->_api_key = get_option('cmc_api_key');
    }
    
    public function getApiKey() {
        return $this->_api_key;
    }
    
    public function setApiKey($api_key) {
        $this->_api_key = $api_key;
        update_option('cmc_api_key', $this->_api_key);
    }
    
    public function getRates() {
        $rates = [];
        $call_res = $this->_api_call();
        if (!$call_res) {
            return $rates;
        }
        foreach ($call_res as $rate) {
            $rates[] = $this->_map_to_cmc_rate($rate);
        }
        return $rates;
    }
    
    private function _api_call() {
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $parameters = [
            'start' => '1',
            'limit' => '5000',
            'convert' => 'BTC',
        ];

        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: '.$this->_api_key,
        ];
        $qs = http_build_query($parameters);
        $request = "{$url}?{$qs}";


        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $request,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => 1
        ]);

        $response = curl_exec($curl);
        $errno = curl_errno($curl);
        curl_close($curl);
        if ($errno == 0) {
            $data = json_decode($response, true);
            return !empty($data['data']) ? $data['data'] : false;
        }
        return false;
    }

    private function _map_to_cmc_rate($rate) {
        $rate_obj = new CmcRate();
        $rate_obj->ticker = $rate['symbol'];
        $rate_obj->name = $rate['name'];
        $rate_obj->cmc_rank = $rate['cmc_rank'];
        $rate_obj->price = floatval($rate['quote']['BTC']['price']);
        return $rate_obj;
    }
}
