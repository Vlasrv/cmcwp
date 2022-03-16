<?php

class CmcHistory {
    private $_model = null;
    
    public function __construct() {
        $this->_model = new HistoryModel();
    }
    
    public function getHistory($history_len) {
        $items = $this->_model->getSegment($history_len, 'DESC');
        foreach ($items as &$item) {
            $item->amount = $this->format_amount($item->amount);
        }
        return $items;
    }
    
    public function addHistory($convert_from, $convert_to, $amount) {
        return $this->_model->addHistory($convert_from, $convert_to, $amount);
    }
    
    private function format_amount($amount) {
        if (intval($amount) == $amount) {
            return intval($amount);
        } else {
            return rtrim($amount, 0);
        }
    }
}
