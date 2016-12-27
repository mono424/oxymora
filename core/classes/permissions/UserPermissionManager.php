<?php namespace KFall\oxymora\permissions;
use KFall\oxymora\permissions\UserPermissionSystem;

class UserPermissionManager{
  private $currentPermissions = null;
  private $prefix;

  public function __construct($prefix){
    $this->prefix = $prefix;
  }

  public function checkPermission($permission){
    return UserPermissionSystem::checkPermission($this->prefix.$permission);
  }

  public function register($permissionkey){
    return UserPermissionSystem::register($this->prefix.$permission);
  }

  public function unregister($permissionkey){
    return UserPermissionSystem::unregister($this->prefix.$permission);
  }

  public function unregisterAll(){
    return UserPermissionSystem::removeByPrefix($this->prefix);
  }
}


?>
