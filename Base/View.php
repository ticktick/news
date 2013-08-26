<?php
class Base_View{

    private $_path;
    private $_layout;
    private $_tplContent;

    public function setPath($path){
        $this->_path = $path;
    }

    public function setLayout($layout){
        $this->_layout = $layout;
    }

    public function render($name, $withLayout=true){
        ob_start();

        if($withLayout && $this->_layout) {
            $this->_tplContent = $this->render($name, false);
            include $this->findFile($this->_layout);
        } else {
            include $this->findFile($name);
        }

        return ob_get_clean();
    }

    private function findFile($name){
        if (is_readable($this->_path . $name)) {
            return $this->_path . $name;
        }
        throw new Exception('template not found');
    }

    public function __get($key)
    {
        if ($this->_strictVars) {
            trigger_error('Key "' . $key . '" does not exist', E_USER_NOTICE);
        }

        return null;
    }

    public function __isset($key)
    {
        if ('_' != Utf::substr($key, 0, 1)) {
            return isset($this->$key);
        }

        return false;
    }

    public function __set($key, $val)
    {
        if ('_' != substr($key, 0, 1)) {
            $this->$key = $val;
            return;
        }
        throw new Exception('Setting private or protected class members is not allowed');
    }
}