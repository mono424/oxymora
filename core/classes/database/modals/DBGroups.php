<?php namespace KFall\oxymora\database\modals;
use PDO;
use KFall\oxymora\database\DB;
use KFall\oxymora\database\modals\DBGrouppermissions;
use KFall\oxymora\config\Config;

class DBGroups{

  public static function listGroups(){
    $prep = DB::pdo()->prepare("SELECT * FROM `".Config::get()['database-tables']['groups']);
    $out = [];
    if($prep->execute() && $prep->rowCount() > 0){
      $out = $prep->fetchAll(PDO::FETCH_ASSOC);
    }
    return $out;
  }

  public static function addGroup($name, $permissions = []){
    $prep = DB::pdo()->prepare("INSERT INTO `".Config::get()['database-tables']['groups']."(`name`) VALUES (:name)");
    $prep->bindValue(':name',$name,PDO::PARAM_STR);
    if($prep->execute()){
      $id = DB::pdo()->lastInsertId();
      foreach($permissions as $permission){
        DBGrouppermissions::addPermission($groupid, $permission);
      }
      return $id;
    }else{
      return false;
    }
  }

  public static function removeGroup($groupid){
    DBGrouppermissions::removeAllPermissions($groupid);
    $prep = DB::pdo()->prepare("DELETE FROM `".Config::get()['database-tables']['groups']." WHERE `groupid`=:groupid)");
    $prep->bindValue(':groupid',$groupid,PDO::PARAM_STR);
    return $prep->execute();
  }


}
?>
