<?php namespace KFall\oxymora\database\modals;
use PDO;
use PDOException;
use KFall\oxymora\database\DB;
use KFall\oxymora\config\Config;
use KFall\oxymora\pageBuilder\PageBuilder;


class DBPages{

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
    $prep = DB::pdo()->prepare('DELETE FROM `'.Config::get()['database-tables']['content'].'` WHERE `pageurl`=:pageurl');
    $prep->bindValue(':pageurl',$url,PDO::PARAM_INT);
    if(!$prep->execute()){
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


}
