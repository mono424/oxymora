<?php namespace KFall\oxymora\permissions;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\database\modals\DBGrouppermissions;
use KFall\oxymora\database\modals\DBPluginSettings;

class UserPermissionSystem{
  private static $currentPermissions = null;

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
}


?>
