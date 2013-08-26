<?php
class Base_Request {

    private $action;

    public function __construct(){
        $this->action = $this->determineActionName();
    }

    public function setAction($action){
        $this->action = $action;
    }

    public function getAction(){
        return $this->action;
    }

    public function p($nm){
        return isset($_REQUEST[$nm]) ? $_REQUEST[$nm] : null;
    }

    public function get($nm){
        return isset($_GET[$nm]) ? $_GET[$nm] : null;
    }

    public function post($nm){
        return isset($_POST[$nm]) ? $_POST[$nm] : null;
    }

    private function determineActionName(){
        return isset($_GET['action']) ? $_GET['action'] : 'index';
    }
}