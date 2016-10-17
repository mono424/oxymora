<?php
use KFall\oxymora\addons\AddonManager;
require_once '../php/admin.php';
loginCheck();


$addons = AddonManager::listAll(false,false,false);
$answer = ["error"=>false,"data"=>$addons];
echo json_encode($answer);


// THIS RUNS WHEN SOMETHING BAD HAPPEND :S
function error($message){
  die(json_encode(["error"=>true,"data"=>$message]));
}
