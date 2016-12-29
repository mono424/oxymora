<?php
use KFall\oxymora\addons\AddonManager;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();

$action = (isset($_GET['a'])) ? $_GET['a'] : error("No Action set.. What are you doing??");
$answer = ["error"=>false,"data"=>""];

switch ($_GET['a']) {
  case 'upload':
  $errors = [];
  $html = "";
  foreach($_FILES as $file){
    $filenameInfo = pathinfo($file['name']);
    if($filenameInfo['extension'] == "oxa" || $filenameInfo['extension'] == "zip"){
      $addon = AddonManager::installZip($file['tmp_name']);
      if($addon === false){
        $errors[] = $file['name'].": ".AddonManager::$installZipError;
      }else{
        $html .= html_addonItem(AddonManager::find($addon));
      }
    }
  }
  $answer['data'] = $html;
  $answer['error'] = $errors;
  break;

  case 'install':
  $addonName = (isset($_GET['addon'])) ? $_GET['addon'] : error("No Addon set.. What are you doing??");
  if(!AddonManager::install($addonName)){error('Installation failed!');}
  break;

  case 'disable':
  $addonName = (isset($_GET['addon'])) ? $_GET['addon'] : error("No Addon set.. What are you doing??");
  if(!AddonManager::disable($addonName)){error('Disable failed!');}
  break;

  case 'enable':
  $addonName = (isset($_GET['addon'])) ? $_GET['addon'] : error("No Addon set.. What are you doing??");
  if(!AddonManager::enable($addonName)){error('Enable failed!');}
  break;

  default:
  error('Invalid action!');
}

echo json_encode($answer);


// THIS RUNS WHEN SOMETHING BAD HAPPEND :S
function error($message){
  die(json_encode(["error"=>true,"data"=>$message]));
}
