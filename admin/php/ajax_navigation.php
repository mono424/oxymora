<?php
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\memberSystem\MemberSystem;
require_once '../php/admin.php';
loginCheck();

$answer['type'] = "error";
$answer['message'] = "Illigal Request!";

if(isset($_GET['id']) && isset($_GET['action'])){

  if($_GET['action'] == "displayUp"){
    $answer['type'] = (DBNavigation::displayUp($_GET['id'])) ? "success" : "error";
    $answer['message'] = "";
  }

  if($_GET['action'] == "displayDown"){
    $answer['type'] = (DBNavigation::displayDown($_GET['id'])) ? "success" : "error";
    $answer['message'] = "";
  }

}

echo json_encode($answer);
