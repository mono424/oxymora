<?php
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\permissions\UserPermissionSystem;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();

// Check Permissions
if(!UserPermissionSystem::checkPermission("oxymora_addons")) die(error("You do not have the required rights to continue!"));

$name = isset($_GET['addon']) ? $_GET['addon'] : die('Element not found!');

// CREATE TEMP ARCHIVE
$tmp_file = AddonManager::extractZip($name);

header('Content-disposition: attachment; filename='.$_GET['addon'].'.oxa');
header('Content-type: application/zip');
readfile($tmp_file);
