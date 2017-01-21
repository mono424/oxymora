<?php namespace KFall\oxymora\database\modals;
use PDO;
use PDOException;
use KFall\oxymora\database\DB;
use KFall\oxymora\config\Config;
use KFall\oxymora\pageBuilder\PageBuilder;


class DBContent{

public static function overwriteArea($pageurl, $area, $plugins){
  // Start Transaction
  DB::pdo()->beginTransaction();

  // DELETE Old Plugins
  self::clearAreaContent($pageurl, $area);

  $string = "";
  foreach($plugins as $plugin){
    // GET INFO
    $pluginName = $plugin['plugin'];
    $newId = (null === $plugin['id'] || empty($plugin['id']));
    $pluginId = (!$newId) ? $plugin['id']: self::generatePluginId();
    $pluginSettings = (isset($plugin['settings'])) ? $plugin['settings'] : [];

    if(!DBPluginSettings::addSettings($pluginId,$pluginSettings, false)){
      // ERROR, ROLL BACK
      DB::pdo()->rollBack();
      return false;
    }

    // ADD TO AREA CONTENT STRING
    $string .= "{".PLACEHOLDER_INDENT_PLUGIN.":$pluginName:$pluginId}";
  }

  // UDPATE CONTENT
  if(!self::updateArea($pageurl, $area,$string)){
    // ERROR, ROLL BACK
    DB::pdo()->rollBack();
    return false;
  }

  // All done successfully
  DB::pdo()->commit();
  return true;
}

public static function updateArea($pageurl, $area, $content){
  $prep = DB::pdo()->prepare('UPDATE `'.Config::get()['database-tables']['content'].'` SET `content`=:content WHERE `pageurl`=:pageurl AND `area`=:area');
  $prep->bindValue(':pageurl',$pageurl,PDO::PARAM_STR);
  $prep->bindValue(':area',$area,PDO::PARAM_STR);
  $prep->bindValue(':content',$content,PDO::PARAM_STR);
  return $prep->execute();
}

public static function getPageAreas($url){
  $sth = DB::pdo()->prepare('SELECT * FROM `'.Config::get()['database-tables']['content'].'` WHERE `pageurl`=:url');
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
  $sth = DB::pdo()->query('SELECT * FROM `'.Config::get()['database-tables']['pages'].'`');
  $results = $sth->fetchAll(PDO::FETCH_ASSOC);
  return $results;
}

public static function removePage($url){
  // Start Transaction
  DB::pdo()->beginTransaction();

  // DELETE PAGE
  $prep = DB::pdo()->prepare('DELETE FROM `'.Config::get()['database-tables']['pages'].'` WHERE `url`=:url');
  $prep->bindValue(':url',$url,PDO::PARAM_INT);
  if($prep->execute()){
    // DELETE AREA CONTENT
    if(!self::removePageContent($url)){
      // ERROR, ROLL BACK
      DB::pdo()->rollBack();
      return false;
    }
    // All done successfully
    DB::pdo()->commit();
    return true;
  }else{
    // ERROR, ROLL BACK
    DB::pdo()->rollBack();
    return false;
  }
}

public static function clearAreaContent($url, $area){
  // LOOP THROUGH PLUGINS AND DELTE SETTINGS
  $prep = DB::pdo()->prepare('SELECT * FROM `'.Config::get()['database-tables']['content'].'` WHERE `pageurl`=:pageurl  AND `area`=:area');
  $prep->bindValue(':area',$area,PDO::PARAM_STR);
  $prep->bindValue(':pageurl',$url,PDO::PARAM_STR);
  if(!$prep->execute()) return false;
  // Content String
  $content = $prep->fetchObject()->content;
  if($content) self::_clearAreaPluginSettings($content);

  // DELETE AREA CONTENT
  $prep = DB::pdo()->prepare('UPDATE `'.Config::get()['database-tables']['content']."` SET `content`='' WHERE `pageurl`=:pageurl  AND `area`=:area");
  $prep->bindValue(':area',$area,PDO::PARAM_STR);
  $prep->bindValue(':pageurl',$url,PDO::PARAM_STR);
  return $prep->execute();
}

public static function removePageContent($url){
  // LOOP THROUGH PLUGINS AND DELTE SETTINGS
  $prep = DB::pdo()->prepare('SELECT * FROM `'.Config::get()['database-tables']['content'].'` WHERE `pageurl`=:pageurl');
  $prep->bindValue(':pageurl',$url,PDO::PARAM_STR);
  if(!$prep->execute()) return false;
  // Content String
  $content = $prep->fetchObject()->content;
  if($content) self::_clearAreaPluginSettings($content);

  // DELETE AREA CONTENT
  $prep = DB::pdo()->prepare('DELETE FROM `'.Config::get()['database-tables']['content'].'` WHERE `pageurl`=:pageurl');
  $prep->bindValue(':pageurl',$url,PDO::PARAM_STR);
  return $prep->execute();
}

private static function _clearAreaPluginSettings($contentString){
  // Parse the Plugins
  $placeholder = PageBuilder::getPlaceholder($contentString);
  foreach($placeholder as $p){
    $pluginInfo = PageBuilder::getPlaceholderValue($p);
    if(is_array($pluginInfo)){
      $pluginId = $pluginInfo[1];
      DBPluginSettings::clearSettings($pluginId);
    }
  }
}

public static function addPage($url){
  // Start Transaction
  DB::pdo()->beginTransaction();

  // Insert Page
  $prep = DB::pdo()->prepare('INSERT INTO `'.Config::get()['database-tables']['pages'].'` VALUES (:url)');
  $prep->bindValue(':url',$url,PDO::PARAM_STR);
  if($prep->execute()){
    // NOW ADD AREA CONTENTS
    PageBuilder::loadTemplate(TEMPLATE);
    $placeHolder = PageBuilder::getPlaceholder(PageBuilder::$htmlSkeleton, PLACEHOLDER_INDENT_AREA);
    foreach($placeHolder as $item){
      $areaName = PageBuilder::getPlaceholderValue($item);
      $prep = DB::pdo()->prepare('INSERT INTO `'.Config::get()['database-tables']['content'].'` (`pageurl`, `area`) VALUES (:pageurl, :area)');
      $prep->bindValue(':pageurl',$url,PDO::PARAM_STR);
      $prep->bindValue(':area',$areaName,PDO::PARAM_STR);
      if(!$prep->execute()){
        // ERROR, ROLL BACK
        DB::pdo()->rollBack();
        return false;
      }
    }
    // All done successfully
    DB::pdo()->commit();
    return true;
  }else{
    // ERROR, ROLL BACK
    DB::pdo()->rollBack();
    return false;
  }
}

public static function renamePage($url, $newUrl){
  $prep = DB::pdo()->prepare('UPDATE `'.Config::get()['database-tables']['pages'].'` SET `url`=:pageurlnew WHERE `url`=:pageurl');
  $prep->bindValue(':pageurl',$url,PDO::PARAM_STR);
  $prep->bindValue(':pageurlnew',$newUrl,PDO::PARAM_STR);
  if($prep->execute()){
    $prep = DB::pdo()->prepare('UPDATE `'.Config::get()['database-tables']['content'].'` SET `pageurl`=:pageurlnew WHERE `pageurl`=:pageurl');
    $prep->bindValue(':pageurl',$url,PDO::PARAM_STR);
    $prep->bindValue(':pageurlnew',$newUrl,PDO::PARAM_STR);
    $prep->execute();
    return true;
  }else{
    return false;
  }
}

private static function generatePluginId(){
  return uniqid("",true);
}


}
