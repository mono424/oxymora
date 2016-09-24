<?php
require_once dirname(__FILE__).'\..\..\core\core.php';

use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\memberSystem\Member;
use KFall\oxymora\memberSystem\Attribute;

// SETUP MEMBERSYSTEM
MemberSystem::init([
  "database" => "oxymora",
  "member-table" => "user",
  "attempt-table" => "attempts",
  "session-table" => "session",
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


function loginCheck($onLogin=false){
  if(!MemberSystem::init()->isLoggedIn() && $onLogin === false){
    header("Location: login.php");
    die();
  }elseif(MemberSystem::init()->isLoggedIn() && $onLogin){
    header("Location: index.php");
    die();
  }
}
