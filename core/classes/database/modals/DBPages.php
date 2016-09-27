<?php namespace KFall\oxymora\database\modals;
use PDO;
use PDOException;
use KFall\oxymora\database\DB;
use KFall\oxymora\config\Config;


class DBPages{

public static function getPageAreas($url){
  $sth = DB::pdo()->prepare('SELECT * FROM `'.Config::get()['database-tables']['pages'].'` WHERE `url`=:url');
  $sth->bindValue(':url',$url,PDO::PARAM_STR);
  $sth->execute();
  $results = $sth->fetchAll(PDO::FETCH_ASSOC);
  $keyOrederedArray = [];
  foreach($results as $area){
    $keyOrederedArray[$area['area']] = $area;
  }
  return $keyOrederedArray;
}

public static function getPages(){
  $sth = DB::pdo()->query('SELECT * FROM `'.Config::get()['database-tables']['pages'].'` GROUP BY `url` ORDER BY `url` ASC');
  $results = $sth->fetchAll(PDO::FETCH_ASSOC);
  return $results;
}


}
