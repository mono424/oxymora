<?php
use KFall\oxymora\database\DB;

class Customer{

private $info = [];

public function __construct($id = null){
  if(!is_null($id)){
    $this->info['id'] = $id;
    $this->load();
  }
}

public function __set($key, $value){
  $this->info[$key] = $value;
}
public function __get($key){
  return (key_exists($key, $this->info)) ? $this->info[$key] : null;
}

public function getAssoc(){
  return $this->info;
}

public function load(){
  $info = $this->info;
  $customerTable = $this->customerTable;
  if(!isset($info['id'])) return false;
  $pdo = DB::pdo();
  $prep = $pdo->prepare("SELECT * FROM `".TABLE_CUSTOMER."` WHERE `id`=:id");
  $prep->bindValue(':id', $info['id']);
  $prep->execute();
  $this->info = $prep->fetch(PDO::FETCH_ASSOC);
  return true;
}

public function save(){
  $info = $this->info;
  $customerTable = $this->customerTable;
  $pdo = DB::pdo();

  if(isset($info['id'])){
    // Update User
    $updateString = implode(",", array_map(array($this, 'sqlUpdateString'), array_keys($info)));
    $prep = $pdo->prepare("UPDATE `".TABLE_CUSTOMER."` SET $updateString WHERE `id`=:id");
    foreach($info as $skey => $value){
      $ps->bindValue(":$skey", $value);
    }
    return $prep->execute();

  }else{
    // Add User
    $columns = implode(",", array_map(array($this, 'escapeSqlColumns'), array_keys($info)));
    $values = $this->valueSqlString(array_keys($info));
    $ps = $pdo->prepare("INSERT INTO `".TABLE_CUSTOMER."` ($columns) VALUES ($values)");
    foreach($info as $skey => $value){
      $ps->bindValue(":$skey", $value);
    }
    if(!$ps->execute()) return false;
    $this->info['id'] = $pdo->lastInsertId();
    return true;
  }

}

private function sqlUpdateString($v){
  $v = preg_replace('/[^a-z0-9\_\-]/i', "", $v);
  return "`$v`=:$v";
}

private function escapeSqlColumns($v){
  $v = preg_replace('/[^a-z0-9\_\-]/i', "", $v);
  return "`$v`";
}

private function valueSqlString($columns){
  $index = implode(",",array_map(function($a){return ":$a";},$columns));
  return $index;
}

}


 ?>
