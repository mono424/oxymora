<?php
use KFall\oxymora\database\modals\DBContent;
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();
$name = isset($_GET['addon']) ? $_GET['addon'] : error('Plugin not found!');
AddonManager::triggerEvent('onTabChange', 'addon-'.$_GET['addon']);

$addon = AddonManager::find($name);

var_dump($addon);

function error($text){
  die($text);
}
 ?>
