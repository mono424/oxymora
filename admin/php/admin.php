<?php
require_once dirname(__FILE__).'/../../core/core.php';

use KFall\oxymora\config\Config;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\memberSystem\Member;
use KFall\oxymora\memberSystem\Attribute;
use KFall\oxymora\permissions\UserPermissionSystem;

// CONFIG
$config = Config::get();

// SETUP MEMBERSYSTEM
MemberSystem::init([
  "database" => $config['database']['db'],
  "member-table" => $config['database-tables']['user'],
  "attempt-table" => $config['database-tables']['membersystem_attempt'],
  "session-table" => $config['database-tables']['membersystem_session'],
  "column-id" => "id",
  "column-username" => "username",
  "column-password" => "password"
]);

// CHECK LOGGED IN
MemberSystem::init()->loginByCookie();

/*
// ########################### FIRST SETUP STUFF ###########################
// DATABASE SETUP
try {
  MemberSystem::init()->setupdb([
    "member-columns" => [
      ["name" => "role", "type" => "VARCHAR", "length" => 24, "extra" => "NOT NULL"],
      ["name" => "email", "type" => "VARCHAR", "length" => 256, "extra" => "NOT NULL"],
      ["name" => "firstname", "type" => "VARCHAR", "length" => 256],
      ["name" => "lastname", "type" => "VARCHAR", "length" => 256]
    ]
  ]);
} catch (Exception $e) {
  echo "Error occured while setting up the Database: ".$e->getMessage()."<br>";
}


// REGISTER ADMIN
$m = new Member();
$m->addAttr(new Attribute('username', "admin"));
$m->addAttr(new Attribute('password', "0000"));
$m->addAttr(new Attribute('role', "admin"));
$m->addAttr(new Attribute('email', "admin@admin.com"));
try {
  MemberSystem::init()->registerMember($m);
} catch (Exception $e) {
  // echo "Error occured while registering a new Member: ".$e->getMessage()."<br>";
}*/
// UserPermissionSystem::register('oxymora_addons', "Addon-Manager-Page Access");
// UserPermissionSystem::register('oxymora_dashboard', "Dashboard-Page Access");
// UserPermissionSystem::register('oxymora_files', "File-Manager-Page Access");
// UserPermissionSystem::register('oxymora_member', "Member Access");
// UserPermissionSystem::register('oxymora_pages', "Pages-and-Navi-Page Access");
// UserPermissionSystem::register('oxymora_settings', "Settings-Page Access");





function loginCheck($onLogin=false){
  if(!MemberSystem::init()->isLoggedIn() && $onLogin === false){
    header("Location: login.php");
    die();
  }elseif(MemberSystem::init()->isLoggedIn() && $onLogin){
    header("Location: index.php");
    die();
  }
}
