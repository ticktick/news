<?php

class Base_DB_DriverMysql extends Base_DB_DriverBase implements Base_DB_Interface {

    /** @var \PDO */
    private static $connection;

    public function __construct(Base_Config $config){
        parent::__construct($config);

        if (!self::$connection) {
            $connectionConfig = $config->getConfigSection(Base_Config::TYPE_DB_MYSQL_CONNECTION_PARAMS);
            if (count($connectionConfig) < 3) {
                throw new Exception('invalid mysql connection params');
            }
            self::$connection = new PDO($connectionConfig[0], $connectionConfig[1], $connectionConfig[2]);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->query('SET NAMES "'.$config->getConfigSection(Base_Config::TYPE_DB_MYSQL_CHARSET).'"');
        }
    }

    public function create($table, $data){
        if (empty($data)) {
            return false;
        }
        $statement = 'INSERT INTO '.$table.' ('.join(',', array_keys($data)).')
            VALUES ('.join(',', array_map(function($item) {return ':'.$item;}, array_keys($data))).')';
        $query = self::$connection->prepare($statement);
        $params = array();
        foreach($data as $k => $v) {
            $params[':'.$k] = $v;
        }
        $res = $query->execute($params);
        if ($res) {
            return self::$connection->lastInsertId();
        }
        return false;
    }

    public function getAll($table, $limit, $offset=0){
        $statement = 'SELECT * FROM '.$table.' ORDER BY id DESC';
        $query = self::$connection->prepare($statement);
        $query->execute();
        $this->profile($statement);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($table, $id){
        $statement = 'SELECT * FROM '.$table.' WHERE id = :id';
        $query = self::$connection->prepare($statement);
        $query->execute(array(':id' => $id));
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteById($table, $id){
        $statement = 'DELETE FROM '.$table.' WHERE id = :id';
        $query = self::$connection->prepare($statement);
        return $query->execute(array(':id' => $id));
    }

    public function updateById($table, $id, $data){
        if (empty($data)) {
            return false;
        }
        $set = array();
        $values = array();
        foreach ($data as $k => $v) {
            $set[] = "$k = :$k";
            $values[':'.$k] = $v;
        }
        $values[':id'] = $id;

        $statement = 'UPDATE '.$table.' SET '.join(',', $set).' WHERE id = :id';
        $query = self::$connection->prepare($statement);
        return $query->execute($values);
    }
}