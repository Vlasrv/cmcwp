<?php

class HistoryModel {
    private $_db = null;
    private $_tablename = null;
    
    public function __construct() {
        global $wpdb;
        $this->_db = $wpdb;
        $this->_tablename = $this->_db->prefix.'cmc_history';
    }
    
    public function getSegment($count, $order_direction) {
        $order_direction = $order_direction == 'ASC' ? 'ASC' : 'DESC';
        $count = (int)$count;
        return $this->_db->get_results('SELECT * FROM '
                                       .$this->_tablename
                                       .' ORDER BY id '
                                       .$order_direction
                                       .' LIMIT 0,'.$count);
    }
    
    public function addHistory($convert_from, $convert_to, $amount) {
        $sql = $this->_db->prepare('INSERT INTO '.$this->_tablename
                                   .' set convert_from=%s, '
                                   .'convert_to=%s, '
                                   .'amount=%s',
                                   $convert_from,
                                   $convert_to,
                                   $amount);
        return $this->_db->query($sql);
    }
} 
