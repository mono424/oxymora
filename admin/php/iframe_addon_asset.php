<?php
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\memberSystem\MemberSystem;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();

$name = isset($_GET['addon']) ? $_GET['addon'] : die('Element not found!');
$type = isset($_GET['type']) ? $_GET['type'] : die('Type not found!');
$file = isset($_GET['file']) ? $_GET['file'] : die('Type not found!');
if(!preg_match("/^[A-Za-z0-9\-\_]*$/",$file)){die('Illigal File!');}

$addon = AddonManager::find($name);

if(!$addon['installed']){die('Element not installed!');}
if(!$addon['installed']['active']){die('Element not active!');}

$filepath = $addon['html'];
switch($type){
  case ADDON_ASSET_CSS:
  header("Content-Type: text/css");
  $filepath .= "/css/$file.css";
  break;

  case ADDON_ASSET_JS:
  header("Content-Type: application/javascript");
  $filepath .= "/js/$file.js";
  break;
  default:
  die('Illigal Type!');
}

if(file_exists($filepath)){
  die(file_get_contents($filepath));
}else{
  die('File not found!');
}
