<?php namespace KFall\oxymora\database\modals;
use PDO;
use PDOException;
use KFall\oxymora\database\DB;
use KFall\oxymora\config\Config;


class DBNavigation{

private static $navigationItems = null;

public static function getItems(){
  if(is_null(self::$navigationItems)){
    self::loadItems();
  }
  return self::$navigationItems;
}

public static function loadItems(){
  $sth = DB::pdo()->query('SELECT * FROM `'.Config::get()['database-tables']['navigation'].'`');
  $result = $sth->fetchAll();
  $resultItems=[];
  foreach($result as $item){
    $ritem = new MenuItem;
    $ritem->id = $item['id'];
    $ritem->title = $item['title'];
    $ritem->url = $item['url'];
    $resultItems[] = $ritem;
  }
  self::$navigationItems = $resultItems;
}

}
