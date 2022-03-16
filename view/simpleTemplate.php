<?php

class simpleTemplate {
    private $base_path = null;
    
    public function __construct() {
        $this->base_path = realpath(__DIR__.'/../templates');
    }
    
    public function render($template, $data=[]) {
        echo $this->get($template, $data);
    }
    
    public function get($template, $data=[]) {
        $template_name = $this->base_path.'/'.$template.'.php';
        if (!is_file($template_name)) {
            throw new CmcException('Template '.$template.' not found.');
        }
        ob_start();
        extract($data);
        include $template_name;
        $res = ob_get_clean();
        return $res;
    }
}
