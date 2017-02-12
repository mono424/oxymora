<?php
use KFall\oxymora\database\modals\DBContent;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\permissions\UserPermissionSystem;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();

// Check Permissions
if(!UserPermissionSystem::checkPermission("oxymora_pages")) die(error("You do not have the required rights to continue!"));

$answer['type'] = "error";
$answer['message'] = "Illigal Request!";

if(isset($_GET['action'])){

  if($_GET['action'] == "remove" && isset($_GET['url'])){
    $answer['type'] = (DBContent::removePage($_GET['url'])) ? "success" : "error";
    $answer['message'] = "";
  }

  if($_GET['action'] == "add" && isset($_GET['filename'])){
    $url = $_GET['filename'].".html";
    $res = DBContent::addPage($url);
    $answer['type'] = ($res) ? "success" : "error";
    $answer['message'] = ($res) ? html_pageItem($url) : "";
  }

  if($_GET['action'] == "rename" && isset($_GET['filename']) && isset($_GET['newfilename'])){
    $url = $_GET['filename'].".html";
    $urlnew = $_GET['newfilename'].".html";
    $res = DBContent::renamePage($url,$urlnew);
    $answer['type'] = ($res) ? "success" : "error";
    $answer['message'] = ($res) ? html_pageItem($urlnew) : "";
  }

}

echo json_encode($answer);
