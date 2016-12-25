<?php namespace KFall\oxymora\database\modals;
use PDO;
use PDOException;
use KFall\oxymora\database\DB;
use KFall\oxymora\config\Config;


class DBStatic{

private static $staticVars = null;

public static function getVars(){
  if(is_null(self::$staticVars)){
    self::loadItems();
  }
  return self::$staticVars;
}

public static function loadItems(){
  $sth = DB::pdo()->query('SELECT * FROM `'.Config::get()['database-tables']['staticVars'].'`');
  $result = $sth->fetchAll(PDO::FETCH_ASSOC);
  $resultItems=[];
  foreach($result as $item){
    $resultItems[$item['placeholder']] = $item['value'];
  }
  self::$staticVars = $resultItems;
}

public static function saveVar($key, $val){
  $ps = DB::pdo()->prepare('UPDATE `'.Config::get()['database-tables']['staticVars'].'` SET `value`=:val WHERE `placeholder`=:key');
  $ps->bindValue(':key', $key);
  $ps->bindValue(':val', $val);
  return $ps->execute();
}

}
