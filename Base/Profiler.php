<?php
class Base_Profiler {

    /** @var $enabled bool */
    private $enabled;
    private static $logs = array();

    public function __construct($enabled){
        $this->enabled = (bool)$enabled;
    }

    public function log($class, $text=''){
        if (!$this->enabled) {
            return false;
        }
        self::$logs[] = array($class, $text);
        return true;
    }

    public static function getLogs(){
        return self::$logs;
    }
}