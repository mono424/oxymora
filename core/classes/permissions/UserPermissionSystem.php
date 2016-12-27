<?php namespace KFall\oxymora\permissions;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\database\modals\DBGrouppermissions;
use KFall\oxymora\database\modals\DBPermissionindex;

class UserPermissionSystem{
  private static $currentPermissions = null;

  public static function listPermissions($groupid = null){
    $permissions = DBPermissionindex::get();
    if(!is_null($groupid)){
      $permissions = array_map(function($n){
        $n['active'] = self::checkPermission($n['key']);
        return $n;
      }, $permissions);
    }
    return $permissions;
  }

  public static function checkPermission($permission){
    if(self::$currentPermissions === null) self::update();
    return (in_array($permission, self::$currentPermissions) || in_array("root", self::$currentPermissions));
  }

  public static function update(){
    self::$currentPermissions = [];
    if(!MemberSystem::init()->isLoggedIn()) return;

    $res = DBGrouppermissions::getPermissions(MemberSystem::init()->member->groupid);
    foreach($res as $pm){
      self::$currentPermissions[] = $pm['permission'];
    }
  }

  public static function register($permissionkey, $title){
    return DBPermissionindex::add($permissionkey, $title);
  }

  public static function remove($permissionkey, $title){
    return DBPermissionindex::add($permissionkey, $title);
  }

  public static function removeByPrefix($prefix){
    return DBPermissionindex::removeByPrefix($prefix);
  }
}


?>
