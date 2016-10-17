<?php
use KFall\oxymora\database\modals\DBContent;
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();
$name = isset($_GET['addon']) ? $_GET['addon'] : error('Plugin not found!');

$addon = AddonManager::find($name);

if(!$addon['installed']){error('Plugin not installed!');}
if(!$addon['installed']['active']){error('Plugin not active!');}

AddonManager::triggerEvent(ADDON_EVENT_TABCHANGE, 'addon-'.$_GET['addon']);

echo '<iframe class="addonIframe" frameborder="0" src="php/iframe_addon.php?addon='.$_GET['addon'].'"></iframe>';

function error($text){
  die($text);
}
 ?>
