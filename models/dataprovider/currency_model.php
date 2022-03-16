<?php

class CurrencyModel {
    private $_db = null;
    private $_tablename = null;
    
    public function __construct() {
        global $wpdb;
        $this->_db = $wpdb;
        $this->_tablename = $this->_db->prefix.'cmc_rates';
    }
    
    public function getAll() {
        return $this->_db->get_results('SELECT * FROM '.$this->_tablename
                                       .' ORDER BY cmc_rank ASC');
    }
    
    public function getByTicker($ticker) {
        $sql = $this->_db->prepare('SELECT * FROM '.$this->_tablename
                                   .' WHERE `ticker`=%s LIMIT 1',
                                   $ticker);
        return $this->_db->get_row($sql);
    }
    
    public function updateRateByTicker($ticker, $rate) {
        $existed = $this->getByTicker($ticker);
        if ($existed) {
            $this->_db->update($this->_tablename,
                               ['price'=>$rate->price],
                               ['id'=>$existed['id']]
            );
        } else {
            $this->_db->insert($this->_tablename,
                               [
                                    'ticker'=>$rate->ticker,
                                    'name'=>$rate->name,
                                    'cmc_rank'=>$rate->cmc_rank,
                                    'price'=>$rate->price
                               ]
            );
        }
    }
}
