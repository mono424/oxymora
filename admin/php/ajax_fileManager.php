<?php
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\fileSystem\FileManager;
require_once '../php/admin.php';
loginCheck();

$action = (isset($_GET['a'])) ? $_GET['a'] : error("No Action set.. What are you doing??");
$answer = ["error"=>false,"data"=>""];



switch ($_GET['a']) {

  case 'index':
  $dir = (isset($_GET['dir'])) ? $_GET['dir'] : "";
  $search = (isset($_GET['s'])) ? $_GET['s'] : "";
  $answer['data'] = ($search) ? FileManager::searchFiles($dir, $search) : FileManager::listFiles($dir);
  break;

  default:
  error('Invalid action!');
}

echo json_encode($answer);


// THIS RUNS WHEN SOMETHING BAD HAPPEND :S
function error($message){
  die(json_encode(["error"=>true,"data"=>$message]));
}
