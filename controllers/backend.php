<?php

class CmcBackendController extends CmcBaseController {
    public function __construct() {
        parent::__construct();
        add_action('admin_menu', [$this, 'cmc_admin_menu']);
        echo $this->run();
    }
    
    public function settings() {
        $template_obj = new simpleTemplate();
        $api = new CmcApi();
        $api_key = $api->getApiKey();
        $template_obj->render('admin', [
            'cmc_api_key' => $api_key,
        ]);
    }
    
    public function updatesettings() {
        $api_key = $this->getPost('cmc_api_key', '');
        if (!empty($api_key)) {
            $api = new CmcApi();
            $api->setApiKey($api_key);
        }
        $this->redirect();
    }
    
    public function cmc_admin_menu() {
        add_menu_page('CMC Currency Exchange',
                      'CMC Currency Exchange',
                      'manage_options',
                      'cconvert/cconvert.php',
                      [$this, 'settings'],
                      'dashicons-tickets',
                      6);
    }
    
    public function redirect($location='') {
        header('Location: '.admin_url('admin.php?page=cconvert/cconvert.php&cmcaction='.$location));
        die();
    }
}
