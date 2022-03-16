<?php

include_once __DIR__.'/base.php';
include_once __DIR__.'/backend.php';
include_once __DIR__.'/frontend.php';

class CmcController {
    
    private $_controller;
    
    /**
     * @description
     * This is main plugin Controller
     * it something like fabric method
     * created to determine which front or backend 
     * is operated
     **/
    public function __construct(bool $is_admin) {
        if ($is_admin) {
            $this->_controller = new CmcBackendController();
        } else {
            $this->_controller = new CmcFrontendController();
        }
    }
    
    public function run() {
        $this->_controller->run();
    }
}
