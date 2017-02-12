<?php
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\permissions\UserPermissionSystem;
require_once '../php/admin.php';
loginCheck();

// Check Permissions - No Permissions to check.
// if(!UserPermissionSystem::checkPermission("oxymora_addons")) die(error("You do not have the required rights to continue!"));

$addons = AddonManager::listAll(false,false,false);
$answer = ["error"=>false,"data"=>$addons];
echo json_encode($answer);


// THIS RUNS WHEN SOMETHING BAD HAPPEND :S
function error($message){
  die(json_encode(["error"=>true,"data"=>$message]));
}
