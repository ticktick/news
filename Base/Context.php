<?php
class Base_Context {

    /** @var Base_Context */
    private static $instance;

    /** @var Base_Request */
    private $request;
    /** @var Base_Config */
    private $config;
    /** @var Base_DB_Interface */
    private $dbDriver;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance() {
        if (is_object(self::$instance)) {
            return self::$instance;
        }
        return self::$instance = new self();
    }

    public function setRequest($request){
        $this->request = $request;
    }

    public function getRequest(){
        return $this->request;
    }

    public function setConfig($config){
        $this->config = $config;
    }

    public function getConfig(){
        return $this->config;
    }

    public function setDbDriver($driver){
        $this->dbDriver = $driver;
    }

    public function getDbDriver(){
        return $this->dbDriver;
    }
}