<?php
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\memberSystem\MemberSystem;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();

$name = isset($_GET['addon']) ? $_GET['addon'] : die('Plugin not found!');
$addon = AddonManager::find($name);

$rootPath = realpath($addon['path']);

// CREATE TEMP ARCHIVE
$zip = new ZipArchive();
$tmp_file = tempnam('../../temp','');
$zip->open($tmp_file, ZipArchive::CREATE);


$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    if (!$file->isDir()){
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);
        $zip->addFile($filePath, $relativePath);
    }
}

$zip->close();

header('Content-disposition: attachment; filename='.$_GET['addon'].'.oxa');
header('Content-type: application/zip');
readfile($tmp_file);
