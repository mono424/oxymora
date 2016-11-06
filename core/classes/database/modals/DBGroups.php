<?php namespace KFall\oxymora\database\modals;
use PDO;
use KFall\oxymora\database\DB;
use KFall\oxymora\database\modals\DBGrouppermissions;
use KFall\oxymora\config\Config;

class DBGroups{

  public static function listGroups(){
    $prep = DB::pdo()->prepare("SELECT * FROM `".Config::get()['database-tables']['groups']."`");
    $out = [];
    if($prep->execute() && $prep->rowCount() > 0){
      $out = $prep->fetchAll(PDO::FETCH_ASSOC);
    }
    return $out;
  }

  public static function getGroupInfo($id){
    $prep = DB::pdo()->prepare("SELECT * FROM `".Config::get()['database-tables']['groups']."` WHERE `id`=:id");
    $prep->bindValue(':id',$id,PDO::PARAM_INT);
    if($prep->execute() && $prep->rowCount() > 0){
      return $prep->fetch(PDO::FETCH_ASSOC);
    }
    return false;
  }

  public static function addGroup($name, $color = "", $permissions = []){
    if(!$color){$color = "rgb(77, 186, 193)";}
    $prep = DB::pdo()->prepare("INSERT INTO `".Config::get()['database-tables']['groups']."`(`name`,`color`) VALUES (:name,:color)");
    $prep->bindValue(':name',$name,PDO::PARAM_STR);
    $prep->bindValue(':color',$color,PDO::PARAM_STR);
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

  public static function removeGroup($id){
    DBGrouppermissions::removeAllPermissions($id);
    $prep = DB::pdo()->prepare("DELETE FROM `".Config::get()['database-tables']['groups']."` WHERE `id`=:id");
    $prep->bindValue(':id',$id,PDO::PARAM_INT);
    return $prep->execute();
  }


}
?>
