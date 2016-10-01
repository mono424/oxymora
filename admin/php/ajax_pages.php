<?php
use KFall\oxymora\database\modals\DBPages;
use KFall\oxymora\memberSystem\MemberSystem;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();

$answer['type'] = "error";
$answer['message'] = "Illigal Request!";

if(isset($_GET['action'])){

  if($_GET['action'] == "remove" && isset($_GET['url'])){
    $answer['type'] = (DBPages::removePage($_GET['url'])) ? "success" : "error";
    $answer['message'] = "";
  }

  if($_GET['action'] == "add" && isset($_GET['filename'])){
    $url = $_GET['filename'].".html";
    $res = DBPages::addPage($url);
    $answer['type'] = ($res) ? "success" : "error";
    $answer['message'] = ($res) ? html_pageItem($url) : "";
  }

}

echo json_encode($answer);
