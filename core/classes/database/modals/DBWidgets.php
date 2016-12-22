<?php namespace KFall\oxymora\database\modals;
use PDO;
use PDOException;
use KFall\oxymora\database\DB;
use KFall\oxymora\config\Config;
use KFall\oxymora\addons\Widget;


class DBWidgets{

  private static $widgets = null;

  public static function get($userid, $id = null){
    if(is_null(self::$widgets)){
      self::load($userid);
    }
    if(!is_null($id)){
      foreach(self::$widgets as $widget){
        if($widget->id === $id){
          return $widget;
        }
      }
      return false;
    }else{
      usort(self::$widgets, array("self", "sortArray"));
      return self::$widgets;
    }
  }

  public static function load($userid){
    $pr = DB::pdo()->prepare('SELECT * FROM `'.Config::get()['database-tables']['widgets'].'` WHERE `userid`=:userid ORDER BY `displayid`');
    $pr->bindValue(':userid',$userid);
    $pr->execute();
    $result = $pr->fetchAll();
    $resultItems=[];
    foreach($result as $item){
      $ritem = new Widget;
      $ritem->id = $item['id'];
      $ritem->widget = $item['widget'];
      $ritem->display = $item['displayid'];
      $ritem->userid = $item['userid'];
      $resultItems[] = $ritem;
    }
    self::$widgets = $resultItems;
  }

  public static function remove($userid, $id){
    $pr = DB::pdo()->prepare('DELETE FROM `'.Config::get()['database-tables']['widgets'].'` WHERE `id`=:id AND `userid`=:userid');
    $pr->bindValue(':userid',$userid);
    $pr->bindValue(':id',$id);
    return $pr->execute();
  }

  public static function add($userid, $widget){
    $items = self::get($userid);
    $pr = DB::pdo()->prepare('INSERT INTO `'.Config::get()['database-tables']['widgets'].'`(`widget`,`displayid`,`userid`) VALUES (:widget,:displayid,:userid)');
    $pr->bindValue(':widget',$widget);
    $pr->bindValue(':displayid',count($items));
    $pr->bindValue(':userid',$userid);
    if($pr->execute()){
      $ritem = new Widget;
      $ritem->id = DB::pdo()->lastInsertId();
      $ritem->widget = $widget;
      $ritem->display = count($items);
      $ritem->userid = $userid;
      self::$widgets[] = $ritem;
      return $ritem;
    }else{
      return false;
    }
  }

  public static function saveItem($userid, $item){
    $prep = DB::pdo()->prepare('UPDATE `'.Config::get()['database-tables']['widgets'].'` SET `widget`=:widget, `displayid`=:displayid WHERE `id`=:id AND `userid`=:userid');
    $prep->bindValue(':widget',$item->widget,PDO::PARAM_STR);
    $prep->bindValue(':displayid',$item->display,PDO::PARAM_INT);
    $prep->bindValue(':id',$item->id,PDO::PARAM_INT);
    $prep->bindValue(':userid',$userid);
    return $prep->execute();
  }

  public static function displayUp($userid, $id){
    $items = self::get($userid);
    $last = null;
    foreach($items as $item){
      if($item->id === $id){
        if(!is_null($last)){
          $last->display++;
          $item->display--;
          self::saveItem($userid,$last);
          self::saveItem($userid,$item);
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

  public static function displayDown($userid, $id){
    $items = self::get($userid);
    $last = null;
    foreach($items as $item){
      if(is_null($last)){
        if($item->id === $id){
          $last = $item;
        }
      }else{
        $last->display++;
        $item->display--;
        self::saveItem($userid,$last);
        self::saveItem($userid,$item);
        return true;
      }
    }
    return false;
  }

  private static function sortArray($a,$b){
    $al = strtolower($a->display);
    $bl = strtolower($b->display);
    if ($al == $bl) {
      return 0;
    }
    return ($al > $bl) ? +1 : -1;
  }

}
