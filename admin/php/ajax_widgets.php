<?php
use KFall\oxymora\database\modals\DBWidgets;
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\permissions\UserPermissionSystem;
require_once '../php/admin.php';
loginCheck();

// Check Permissions
if(!UserPermissionSystem::checkPermission("oxymora_dashboard")) die(error("You do not have the required rights to continue!"));

if(!isset($_GET['action'])) error('Illigal Request!');

$userid = MemberSystem::init()->member->id;

if($_GET['action'] == "getDashboard"){
  $addons = DBWidgets::get($userid);
  $answer = ["error"=>false,"data"=>$addons];
  echo json_encode($answer);die();
}

if($_GET['action'] == "get"){
  $addons = AddonManager::listAll();
  $addons = array_values(array_filter($addons, function($item){
    if(!$item['installed'] || $item['installed']['active'] == false) return false;
    if($item['config']['type'] != ADDON_WIDGET) return false;
    return true;
  }));
  $answer = ["error"=>false,"data"=>$addons];
  echo json_encode($answer);die();
}

if($_GET['action'] == "add" && isset($_GET['widget'])){
  if(!AddonManager::find($_GET['widget'])) error('Not found.');
  $answer = DBWidgets::add($userid, $_GET['widget']);
  if($answer != false){
    $answer = ["error"=>false,"data"=>$answer];
  }else{
    $answer = ["error"=>true,"data"=>null];
  }
  echo json_encode($answer);die();
}


if($_GET['action'] == "delete" && isset($_GET['widget'])){
  $answer = DBWidgets::remove($userid, $_GET['widget']);
  if($answer != false){
    $answer = ["error"=>false,"data"=>$answer];
  }else{
    $answer = ["error"=>true,"data"=>null];
  }
  echo json_encode($answer);die();
}

if($_GET['action'] == "down" && isset($_GET['widget'])){
  $answer = DBWidgets::displayDown($userid, $_GET['widget']);
  if($answer != false){
    $answer = ["error"=>false,"data"=>null];
  }else{
    $answer = ["error"=>true,"data"=>null];
  }
  echo json_encode($answer);die();
}

if($_GET['action'] == "up" && isset($_GET['widget'])){
  $answer = DBWidgets::displayUp($userid, $_GET['widget']);
  if($answer != false){
    $answer = ["error"=>false,"data"=>null];
  }else{
    $answer = ["error"=>true,"data"=>null];
  }
  echo json_encode($answer);die();
}







error('Illigal Request!');
// THIS RUNS WHEN SOMETHING BAD HAPPEND :S
function error($message){
  die(json_encode(["error"=>true,"data"=>$message]));
}
