<?php
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\memberSystem\MemberSystem;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();

$name = isset($_GET['addon']) ? $_GET['addon'] : die('Plugin not found!');

// CREATE TEMP ARCHIVE
$tmp_file = AddonManager::extractZip($name);

header('Content-disposition: attachment; filename='.$_GET['addon'].'.oxa');
header('Content-type: application/zip');
readfile($tmp_file);
