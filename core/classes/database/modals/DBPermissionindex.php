<?php namespace KFall\oxymora\database\modals;
use PDO;
use KFall\oxymora\database\DB;
use KFall\oxymora\config\Config;

class DBPermissionindex{

  public static function get(){
    $prep = DB::pdo()->prepare("SELECT * FROM `".Config::get()['database-tables']['permissionindex']."`");
    $out = [];
    if($prep->execute() && $prep->rowCount() > 0){
      $out = $prep->fetchAll(PDO::FETCH_ASSOC);
    }
    return $out;
  }

  public static function add($key, $title){
    $prep = DB::pdo()->prepare("INSERT INTO `".Config::get()['database-tables']['permissionindex']."` VALUES (:key,:title)");
    $prep->bindValue(':key',$key,PDO::PARAM_STR);
    $prep->bindValue(':title',$title,PDO::PARAM_STR);
    return $prep->execute();
  }

  public static function remove($key){
    $prep = DB::pdo()->prepare("DELETE FROM `".Config::get()['database-tables']['permissionindex']."` WHERE `key`=:key");
    $prep->bindValue(':key',$key,PDO::PARAM_STR);
    return $prep->execute();
  }

  public static function removeByPrefix($prefix){
    $prefix .= "%";
    $prep = DB::pdo()->prepare("DELETE FROM `".Config::get()['database-tables']['permissionindex']."` WHERE `key` LIKE :key");
    $prep->bindValue(':key',$prefix,PDO::PARAM_STR);
    return $prep->execute();
  }

}
?>
