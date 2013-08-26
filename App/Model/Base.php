<?php

abstract class App_Model_Base {

    /** @var Base_DB_Interface */
    protected static $db;

    protected $id;
    protected $name;
    protected $properties = array();
    protected $mandatoryProperties = array();
    protected $changedProperties = array();

    public function __construct($id=0, $data=null) {
        if (!(self::$db instanceof Base_DB_Interface)) {
            self::$db = Base_Context::getInstance()->getDbDriver();
        }

        $this->id = (int)$id;
        if (!$this->id) {
            return false;
        }
        if(is_null($data)) {
            return $this->readObjectData();
        } else {
            return $this->setObjectData($data);
        }
    }

    protected function makeObjectFromArray($array) {
        $class = get_class($this);
        return new $class($array['id'], $array);
    }

    protected function makeCollectionFromArray($array) {
        $collection = array();
        foreach($array as $item) {
            $collection[] = self::makeObjectFromArray($item);
        }
        return $collection;
    }

    protected function setObjectData($data) {
        $this->properties = $data;
        return true;
    }

    protected function readObjectData() {
        $data = self::$db->getById($this->name, $this->id);
        if (empty($data)) {
            $this->id = 0;
            return false;
        }
        $data['id'] = $this->id;
        return $this->setObjectData($data);
    }

    public function __get($nm) {
        if (array_key_exists($nm, $this->properties)) {
            return $this->properties[$nm];
        } else {
            throw new Exception('Trying to get non-existing property "' . $nm . '"');
        }
    }

    public function __set($nm, $val) {
        if (array_key_exists($nm, $this->properties)) {
            if ($this->properties[$nm] != $val) {
                if (!in_array($nm, $this->changedProperties)) {
                    $this->changedProperties[] = $nm;
                }
                return $this->properties[$nm] = $val;
            }
            return true;
        } else {
            throw new Exception('Trying to set non-existing property');
        }
    }

    public function isExists(){
        return $this->id > 0;
    }

    public function create($data) {
        $filteredData = array();
        foreach($this->mandatoryProperties as $prop) {
            if (!isset($data[$prop])) {
                throw new Exception('can\'t create object, property '.$prop.' doesn\'t specified');
            }
            $filteredData[$prop] = $data[$prop];
        }
        $newId = self::$db->create($this->name, $data);
        if ($newId) {
            $this->id = $newId;
            $this->setObjectData($data);
            return true;
        }
        return false;
    }

    public function save() {
        $propertiesToSave = array();
        foreach ($this->changedProperties as $prop) {
            $propertiesToSave[$prop] = $this->properties[$prop];
        }
        if ($propertiesToSave) {
            return self::$db->updateById($this->name, $this->id, $propertiesToSave);
        }
        return false;
    }

    public function delete() {
        $res = self::$db->deleteById($this->name, $this->id);
        if ($res) {
            $this->id = 0;
        }
        return $res;
    }

    public function getAll($limit, $offset=0){
        $data = self::$db->getAll($this->name, $limit, $offset);
        return self::makeCollectionFromArray($data);
    }
}