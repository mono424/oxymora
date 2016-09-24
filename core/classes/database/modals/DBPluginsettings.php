<?php namespace KFall\oxymora\database\modals;
use PDO;
use PDOException;
use KFall\oxymora\database\DB;
use KFall\oxymora\config\Config;


class DBPluginsettings{

public static function getSettings($pluginid){
  $sth = DB::pdo()->prepare('SELECT * FROM `'.Config::get()['database-tables']['pluginsettings'].'` WHERE `pluginid`=:pluginid');
  $sth->bindValue(':pluginid',$pluginid,PDO::PARAM_STR);
  $sth->execute();
  $result = $sth->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}


}
