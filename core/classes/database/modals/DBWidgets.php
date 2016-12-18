<?php namespace KFall\oxymora\database\modals;
use PDO;
use PDOException;
use KFall\oxymora\database\DB;
use KFall\oxymora\config\Config;
use KFall\oxymora\addons\Widget;


class DBWidgets{

  private static $widgets = null;

  public static function get($id = null){
    if(is_null(self::$widgets)){
      self::load();
    }
    if(!is_null($id)){
      foreach(self::$widgets as $widget){
        if($widget->id == $id){
          return $widget;
        }
      }
      return false;
    }else{
      return self::$widgets;
    }
  }

  public static function load(){
    $sth = DB::pdo()->query('SELECT * FROM `'.Config::get()['database-tables']['widgets'].'` ORDER BY `displayid`');
    $result = $sth->fetchAll();
    $resultItems=[];
    foreach($result as $item){
      $ritem = new Widget;
      $ritem->id = $item['id'];
      $ritem->widget = $item['widget'];
      $ritem->display = $item['displayid'];
      $resultItems[] = $ritem;
    }
    self::$widgets = $resultItems;
  }

  public static function remove($id){
    $pr = DB::pdo()->prepare('DELETE FROM `'.Config::get()['database-tables']['widgets'].'` WHERE `id`=:id');
    $pr->bindValue(':id',$id);
    return $pr->execute();
  }

  public static function add($widget){
    $items = self::get();
    $pr = DB::pdo()->prepare('INSERT INTO `'.Config::get()['database-tables']['widgets'].'`(`widget`,`displayid`) VALUES (:widget,:displayid)');
    $pr->bindValue(':widget',$widget);
    $pr->bindValue(':displayid',count($items));
    if($pr->execute()){
      $ritem = new Widget;
      $ritem->id = DB::pdo()->lastInsertId();
      $ritem->widget = $widget;
      $ritem->display = count($items);
      self::$widgets[] = $ritem;
      return $ritem;
    }else{
      return false;
    }
  }

  public static function saveItem($item){
    $prep = DB::pdo()->prepare('UPDATE `'.Config::get()['database-tables']['navigation'].'` SET `widget`=:widget, `displayid`=:displayid WHERE `id`=:id');
    $prep->bindValue(':widget',$item->widget,PDO::PARAM_STR);
    $prep->bindValue(':displayid',$item->displayid,PDO::PARAM_INT);
    $prep->bindValue(':id',$item->id,PDO::PARAM_INT);
    $prep->execute();
  }

  public static function displayUp($id){
    $items = self::get();
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
    $items = self::get();
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
