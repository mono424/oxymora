<?php namespace KFall\oxymora\database\modals;
use PDO;
use PDOException;
use KFall\oxymora\database\DB;
use KFall\oxymora\config\Config;


class DBAddons{

public static function getInfo($name){
  $prep = DB::pdo()->prepare('SELECT * FROM `'.Config::get()['database-tables']['addons'].'` WHERE `name`=? LIMIT 1');
  $prep->bindValue(1,$name,PDO::PARAM_STR);
  $success = $prep->execute();
  $result = ($success && $prep->rowCount() > 0) ? $prep->fetchAll(PDO::FETCH_ASSOC) : false;
  return $result;
}

public static function install($name, $active = true){
  $prep = DB::pdo()->prepare('INSERT INTO `'.Config::get()['database-tables']['addons'].'`(`name`,`active`,`installed`) VALUES (:name,:active,NOW())');
  $prep->bindValue(':name',$name,PDO::PARAM_STR);
  $prep->bindValue(':active',$active,PDO::PARAM_BOOL);
  return $prep->execute();
}

public static function enable($name){
  $prep = DB::pdo()->prepare('UPDATE `'.Config::get()['database-tables']['addons'].'` SET `active`=:active');
  $prep->bindValue(':active',true,PDO::PARAM_BOOL);
  return $prep->execute();
}

public static function disable($name){
  $prep = DB::pdo()->prepare('UPDATE `'.Config::get()['database-tables']['addons'].'` SET `active`=:active');
  $prep->bindValue(':active',false,PDO::PARAM_BOOL);
  return $prep->execute();
}


}
