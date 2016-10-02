<?php namespace KFall\oxymora\database\modals;
use PDO;
use PDOException;
use KFall\oxymora\database\DB;
use KFall\oxymora\config\Config;


class DBPluginsettings{

  public static function addSettings($pluginid, $settings, $transaction = true){
    // Start Transaction
    if($transaction){DB::pdo()->beginTransaction();}

    foreach($settings as $setting){
      if(!self::addSetting($pluginid, $setting['settingkey'], $setting['settingvalue'])){
        // ERROR, ROLL BACK
        if($transaction){DB::pdo()->rollBack();}
        return false;
      }
    }

    // All done successfully
    if($transaction){DB::pdo()->commit();}
    return true;
  }

  public static function addSetting($pluginid, $settingkey, $settingvalue){
    $sth = DB::pdo()->prepare('INSERT INTO `'.Config::get()['database-tables']['pluginsettings'].'`(`pluginid`,`settingkey`,`settingvalue`) VALUES (:pluginid,:settingkey,:settingvalue)');
    $sth->bindValue(':pluginid',$pluginid,PDO::PARAM_STR);
    $sth->bindValue(':settingkey',$settingkey,PDO::PARAM_STR);
    $sth->bindValue(':settingvalue',$settingvalue,PDO::PARAM_STR);
    return $sth->execute();
  }

  public static function getSettings($pluginid){
    $sth = DB::pdo()->prepare('SELECT * FROM `'.Config::get()['database-tables']['pluginsettings'].'` WHERE `pluginid`=:pluginid');
    $sth->bindValue(':pluginid',$pluginid,PDO::PARAM_STR);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  public static function clearSettings($pluginid){
    $sth = DB::pdo()->prepare('DELETE FROM `'.Config::get()['database-tables']['pluginsettings'].'` WHERE `pluginid`=:pluginid');
    $sth->bindValue(':pluginid',$pluginid,PDO::PARAM_STR);
    return $sth->execute();
  }


}
