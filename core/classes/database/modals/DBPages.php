<?php namespace KFall\oxymora\database\modals;
use PDO;
use PDOException;
use KFall\oxymora\database\DB;
use KFall\oxymora\config\Config;


class DBPages{

public static function getPage($url){
  $sth = DB::pdo()->prepare('SELECT * FROM `'.Config::get()['database-tables']['pages'].'` WHERE `url`=:url');
  $sth->bindValue(':url',$url,PDO::PARAM_STR);
  $sth->execute();
  $result = $sth->fetch(PDO::FETCH_ASSOC);
  return $result;
}


}
