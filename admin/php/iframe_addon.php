<?php
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\memberSystem\MemberSystem;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();

$name = isset($_GET['addon']) ? $_GET['addon'] : die('Plugin not found!');
$page = isset($_GET['page']) ? $_GET['page'] : 'index.php';
if(!preg_match("/^[A-Za-z0-9\-\_]*$/",$page)){die('Illigal Page!');}

$addon = AddonManager::find($name);

if(!$addon['installed']){die('Plugin not installed!');}
if(!$addon['installed']['active']){die('Plugin not active!');}

if(!file_exists($addon['html'])){
  die("html Folder not found!");
}


chdir($addon['html']);
require "$page.php";
