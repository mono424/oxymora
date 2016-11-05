<?php namespace KFall\oxymora\database\modals;
use PDO;
use KFall\oxymora\database\DB;
use KFall\oxymora\config\Config;

class DBGrouppermissions{

  public static function getPermissions($groupid){
    $prep = DB::pdo()->prepare("SELECT * FROM `".Config::get()['database-tables']['grouppermission']." WHERE `groupid`=:groupid");
    $prep->bindValue(':groupid',$groupid,PDO::PARAM_STR);
    $out = [];
    if($prep->execute() && $prep->rowsCount() > 0){
      $out = $prep->fetchAll(PDO::FETCH_ASSOC);
    }
    return $out;
  }

  public static function removeAllPermissions($groupid){
    $prep = DB::pdo()->prepare("DELETE FROM `".Config::get()['database-tables']['grouppermission']." WHERE `groupid`=:groupid)");
    $prep->bindValue(':groupid',$groupid,PDO::PARAM_STR);
    return $prep->execute();
  }

  public static function addPermission($groupid, $permission){
    $prep = DB::pdo()->prepare("INSERT INTO `".Config::get()['database-tables']['grouppermission']." VALUES (:groupid,:permission)");
    $prep->bindValue(':groupid',$groupid,PDO::PARAM_STR);
    $prep->bindValue(':permission',$permission,PDO::PARAM_STR);
    return $prep->execute();
  }

  public static function removePermission($groupid, $permission){
    $prep = DB::pdo()->prepare("DELETE FROM `".Config::get()['database-tables']['grouppermission']." WHERE `groupid`=:groupid AND `permission`=:permission)");
    $prep->bindValue(':groupid',$groupid,PDO::PARAM_STR);
    $prep->bindValue(':permission',$permission,PDO::PARAM_STR);
    return $prep->execute();
  }


}
?>
