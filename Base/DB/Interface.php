<?php

interface Base_DB_Interface {
   public function create($table, $data);
   public function getAll($table, $limit, $offset=0);
   public function getById($table, $id);
   public function deleteById($table, $id);
   public function updateById($table, $id, $data);
}