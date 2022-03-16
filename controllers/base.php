<?php

class CmcBaseController {
    protected $action = null;
    
    public function __construct() {
        $this->action = isset($_GET['cmcaction']) ? $_GET['cmcaction'] : 'index';
        // TODO add data validation to prevent SQL-injection, XSS, etc
        $this->query = $_GET;
        $this->form_data = $_POST;
    }
    
    /**
     * @description Return request variable specified with name or default value
     * 
     * @param $name request variable name
     * @param $default_value optional default value assigned if value not set
     * @param $cast optional cast input value to one of type [str, int, float]
     * @return mixed
     */
    public function run() {
        if (!is_null($this->action) && method_exists($this, $this->action)) {
            return $this->{$this->action}();
        }
        return null;
    }
        
    /**
     * @description Return request variable specified with name or default value
     * 
     * @param $name request variable name
     * @param $default_value optional default value assigned if value not set
     * @param $cast optional cast input value to one of type [str, int, float]
     * @return mixed
     */
    public function getGet($name, $default_value='', $cast=false) {
        return $this->_get_request($this->query, $name, $default_value, $cast);
    }
    
    /**
     * @description Return POST variable specified with name or default value
     * 
     * @param $name request variable name
     * @param $default_value optional default value assigned if value not set
     * @param $cast optional cast input value to one of type [str, int, float]
     * @return mixed
     */
    public function getPost($name, $default_value='', $cast=false) {
        return $this->_get_request($this->form_data, $name, $default_value, $cast);
    }
    
    public function jsonify($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    private function _get_request($data, $name, $default_value, $cast) {
        $item = isset($data[$name]) ? $data[$name] : $default_value;
        if ($cast) {
            switch ($cast) {
                case 'str':
                    $item = strval($item);
                    break;
                case 'int':
                    $item = intval($item);
                    break;
                case 'float':
                    $item = floatval($item);
                    break;
                default:
                    $item = strval($item);
                    break;
            }
        }
        return $item;
    }
}
