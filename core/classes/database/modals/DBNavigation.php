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
    $sth = DB::pdo()->query('SELECT * FROM `'.Config::get()['database-tables']['navigation'].'` ORDER BY `display`');
    $result = $sth->fetchAll();
    $resultItems=[];
    foreach($result as $item){
      $ritem = new MenuItem;
      $ritem->id = $item['id'];
      $ritem->title = $item['title'];
      $ritem->url = $item['url'];
      $ritem->display = $item['display'];
      $resultItems[] = $ritem;
    }
    self::$navigationItems = $resultItems;
  }

  public static function saveItem($item){
    $prep = DB::pdo()->prepare('UPDATE `'.Config::get()['database-tables']['navigation'].'` SET `title`=:title, `url`=:url, `display`=:display WHERE `id`=:id');
    $prep->bindValue(':title',$item->title,PDO::PARAM_STR);
    $prep->bindValue(':url',$item->url,PDO::PARAM_STR);
    $prep->bindValue(':display',$item->display,PDO::PARAM_INT);
    $prep->bindValue(':id',$item->id,PDO::PARAM_INT);
    $prep->execute();
  }

  public static function displayUp($id){
    $items = self::getItems();
    $last = null;
    foreach($items as $item){
      if($item->id === $id){
        if(!is_null($last)){
          $last->display++;
          $item->display--;
          self::saveItem($last);
          self::saveItem($item);
          return true;
        }else{
          return false;
        }
      }else{
        $last = $item;
      }
    }
    return false;
  }

  public static function displayDown($id){
    $items = self::getItems();
    $last = null;
    foreach($items as $item){
      if(is_null($last)){
        if($item->id === $id){
          $last = $item;
        }
      }else{
        $last->display++;
        $item->display--;
        self::saveItem($last);
        self::saveItem($item);
        return true;
      }
    }
    return false;
  }

}
