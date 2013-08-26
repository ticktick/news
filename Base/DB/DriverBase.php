<?php

abstract class Base_DB_DriverBase implements Base_DB_Interface {

    /** @var $profiler Base_Profiler */
    protected $profiler;

    public function __construct(Base_Config $config){
        $this->profiler = new Base_Profiler($config->getConfigSection(Base_Config::TYPE_PROFILER_ENABLED));
    }

    protected function profile($text){
        return $this->profiler->log(get_class($this), $text);
    }

    abstract public function create($table, $data);

    abstract public function getAll($table, $limit, $offset=0);

    abstract public function getById($table, $id);

    abstract public function deleteById($table, $id);

    abstract public function updateById($table, $id, $data);
}