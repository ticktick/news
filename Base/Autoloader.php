<?php

class Base_Autoloader {
    public function __construct(){
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    public function autoload($class){
        if ($this->_autoload($class)) {
            return true;
        }
        return false;
    }

    private function _autoload($class){
        try {
            $this->loadClass($class);
            return $class;
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
            return false;
        }
    }

    private function loadClass($class){
        if (class_exists($class, false) || interface_exists($class, false)) {
            return;
        }

        $file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

        include $file;

        if (!class_exists($class, false) && !interface_exists($class, false)) {
            throw new Exception('File ".'.$file.'" does not exist or class "'.$class.'" was not found in the file');
        }
    }
}