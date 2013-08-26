<?php

class Base_DB_DriverMultiSource extends Base_DB_DriverBase implements Base_DB_Interface {

    /** @var Base_DB_Interface */
    private $readSource;
    /** @var Base_DB_Interface[] */
    private $writeSources;

    public function __construct(Base_Config $config){
        parent::__construct($config);
        $configSources = $config->getConfigSection(Base_Config::TYPE_DB_MULTISOURCE);

        $readSourceClass = isset($configSources['read']) ? $configSources['read'] : null;
        if (class_exists($readSourceClass)) {
            $this->readSource = new $readSourceClass($config);
        }
        if (!$this->checkSource($this->readSource)) {
            throw new Exception('invalid multisource read source: '.$readSourceClass);
        }

        $writeSourceClasses = isset($configSources['write']) ? $configSources['write'] : null;
        $this->writeSources = array();
        foreach($writeSourceClasses as $writeSourceClass) {
            $writeSource = null;
            if (class_exists($writeSourceClass)) {
                $writeSource = new $writeSourceClass($config);
            }
            if (!$this->checkSource($writeSource)) {
                trigger_error('invalid multisource write source: '.$writeSourceClass, E_USER_WARNING);
                continue;
            }
            $this->writeSources[] = $writeSource;
        }
        if (empty($this->writeSources)) {
            throw new Exception('no multisource write sources');
        }
    }

    public function getAll($table, $limit, $offset=0){
        return $this->readSource->getAll($table, $limit, $offset);
    }

    public function getById($table, $id){
        return $this->readSource->getById($table, $id);
    }

    public function create($table, $data){
        $id = null;
        foreach ($this->writeSources as $source) {
            $id = $source->create($table, $data);
        }
        return $id;
    }

    public function deleteById($table, $id){
        foreach ($this->writeSources as $source) {
            $source->deleteById($table, $id);
        }
        return true;
    }

    public function updateById($table, $id, $data){
        foreach ($this->writeSources as $source) {
            $source->updateById($table, $id, $data);
        }
        return true;
    }

    private function checkSource($source){
        return $source instanceof Base_DB_Interface;
    }
}