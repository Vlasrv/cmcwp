<?php

class CmcCurrencies {
    private $_api = null;
    private $_model = null;
    
    public function __construct() {
        $this->_api = new CmcApi();
        $this->_model = new CurrencyModel();
    }
    
    public function getCurrencies() {
        return $this->_model->getAll();
    }
    
    public function Convert($convert_from,
                            $convert_to,
                            $amount) {
        $currency_from = $this->_model->getByTicker($convert_from);
        $currency_to = $this->_model->getByTicker($convert_to);
        
        if (!$currency_from || !$currency_to) {
            throw new CmcException404();
        }
        
        return ($amount * $currency_from->price) / $currency_to->price;
    }
    
    public function updateFromCmc() {
        $rates = $this->_api->getRates();
        foreach ($rates as $rate) {
            $this->_model->updateRateByTicker($rate->ticker, $rate);
        }
    }
}

class CmcRate {
    public $ticker = '';
    public $name = '';
    public $cmc_rank = '';
    public $price = '';
}
