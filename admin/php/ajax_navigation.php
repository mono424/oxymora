<?php
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\memberSystem\MemberSystem;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();

$answer['type'] = "error";
$answer['message'] = "Illigal Request!";

if(isset($_GET['action'])){

  if($_GET['action'] == "displayUp" && isset($_GET['id'])){
    $answer['type'] = (DBNavigation::displayUp($_GET['id'])) ? "success" : "error";
    $answer['message'] = "";
  }

  if($_GET['action'] == "displayDown" && isset($_GET['id'])){
    $answer['type'] = (DBNavigation::displayDown($_GET['id'])) ? "success" : "error";
    $answer['message'] = "";
  }

  if($_GET['action'] == "remove" && isset($_GET['id'])){
    $answer['type'] = (DBNavigation::remove($_GET['id'])) ? "success" : "error";
    $answer['message'] = "";
  }

  if($_GET['action'] == "edit" && isset($_GET['id']) && isset($_GET['title']) && isset($_GET['url'])){
    $answer['type'] = (DBNavigation::changeTitle($_GET['id'], $_GET['title']) && (DBNavigation::changeUrl($_GET['id'], $_GET['url']))) ? "success" : "error";
    $answer['message'] = "";
  }

  if($_GET['action'] == "add" && isset($_GET['title']) && isset($_GET['url'])){
    $navItem = DBNavigation::add($_GET['title'], $_GET['url']);
    $answer['type'] = ($navItem === false) ? "error" : "success";
    $answer['message'] = ($navItem === false) ? "" : html_navItem($navItem->display, $navItem->id, $navItem->title, $navItem->url);
  }

}

echo json_encode($answer);
