<?php
class Base_Exception_Redirect extends Exception {

    private $url;

    public function __construct($url){
        $this->url = $url;
    }

    public function getUrl(){
        return $this->url;
    }
}