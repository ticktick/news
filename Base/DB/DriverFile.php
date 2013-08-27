<?php

class Base_DB_DriverFile extends Base_DB_DriverBase implements Base_DB_Interface {

    private $filesPath;
    private $autoIncrementFile = 'auto_increment';

    public function __construct(Base_Config $config){
        parent::__construct($config);
        $this->filesPath = $config->getConfigSection(Base_Config::TYPE_DB_FILE_PATH);
    }

    public function create($table, $data){
        $newId = $this->getLastId($table);
        $newId++;
        $this->putDataToFile($this->getFileName($table, $newId), $data);
        $this->setLastId($table, $newId);
        return $newId;
    }

    public function getAll($table, $limit, $offset=0){
        $dir = new DirectoryIterator($this->getDirName($table));
        $files = array();
        /** @var $file DirectoryIterator */
        foreach ($dir as $file) {
            if (in_array($file->getFilename(), array('.', '..', $this->autoIncrementFile))) {
                continue;
            }
            $files[] = $file->getFilename();
        }
        rsort($files);

        $data = array();
        foreach($files as $id) {
            $row = $this->getDataFromFile($this->getFileName($table, $id));
            $row['id'] = $id;
            $data[] = $row;
        }
        $this->profile('fetch limit '.$limit.' offset '.$offset);
        return $data;
    }

    public function getById($table, $id){
        if(!file_exists($this->getFileName($table, $id))) {
            return false;
        }
        $this->profile('fetch id '.$id);
        return $this->getDataFromFile($this->getFileName($table, $id));
    }

    public function deleteById($table, $id){
        if(!file_exists($this->getFileName($table, $id))) {
            return true;
        }
        return unlink($this->getFileName($table, $id));
    }

    public function updateById($table, $id, $data){
        $objectData = $this->getById($table, $id);
        $objectData = array_merge($objectData, $data);
        return $this->putDataToFile($this->getFileName($table, $id), $objectData);
    }

    private function setLastId($table, $lastId){
        return $this->putDataToFile($this->getFileName($table, $this->autoIncrementFile), $lastId);
    }

    private function getLastId($table){
        $file = $this->getFileName($table, $this->autoIncrementFile);
        if (!file_exists($file)) {
            return 0;
        }
        return (int)$this->getDataFromFile($file);
    }

    private function getDirName($table){
        return $this->filesPath.DIRECTORY_SEPARATOR.$table;
    }

    private function getFileName($table, $id){
        return $this->filesPath.DIRECTORY_SEPARATOR.$table.DIRECTORY_SEPARATOR.$id;
    }

    private function putDataToFile($file, $data){
        return file_put_contents($file, serialize($data));
    }

    private function getDataFromFile($file){
        return unserialize(file_get_contents($file));
    }
}