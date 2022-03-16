<?php

class CmcFrontendController extends CmcBaseController {
    public function __construct() {
        parent::__construct();
        add_shortcode('cmc_converter', [$this, 'run']);
        wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js');
        wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js');
        wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
    }
    public function index() {
        $currencies_obj = new CmcCurrencies();
        $currencies = $currencies_obj->getCurrencies();
        $template_obj = new simpleTemplate();
        return $template_obj->get('converter', [
            'currencies' => $currencies,
        ]);
    }

    public function convert() {
        $currencies_obj = new CmcCurrencies();
        $convert_from = $this->getGet('convert_from', 'BTC');
        $convert_to = $this->getGet('convert_to', 'USD');
        $amount = $this->getGet('amount', 1, 'float');
        $converted_amount = $currencies_obj->Convert(
            $convert_from,
            $convert_to,
            $amount);
        $history_obj = new CmcHistory();
        $history_obj->addHistory(
            $convert_from,
            $convert_to,
            $amount);
        $this->jsonify($converted_amount);
    }
    
    public function get_history() {
        $history_obj = new CmcHistory();
        $history_len = $this->getGet('history_len', 10, 'int');
        $this->jsonify(
            $history_obj->getHistory($history_len)
        );
    }
}
