<?php
// core stuff
use KFall\oxymora\database\modals\DBContent;
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\permissions\UserPermissionSystem;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';

// Check Login
loginCheck();

// requested Plugin
$name = isset($_GET['addon']) ? $_GET['addon'] : die(html_error('Plugin not found!'));

// Check Permissions
if(!UserPermissionSystem::checkPermission("oxymora_addon_$name")) die(html_error("You do not have the required rights to continue!"));

// Find Plugin
$addon = AddonManager::find($name);

// Check if installed and active
if(!$addon['installed']){die(html_error('Plugin not installed!'));}
if(!$addon['installed']['active']){die(html_error('Plugin not active!'));}

// Tab Change event
AddonManager::triggerEvent(ADDON_EVENT_TABCHANGE, 'addon-'.$_GET['addon']);

// Create Iframe
echo '<iframe class="addonIframe" frameborder="0" src="addon/'.$_GET['addon'].'/index.php"></iframe>';

 ?>
