<?php
use KFall\oxymora\database\modals\DBStatic;
use KFall\oxymora\memberSystem\MemberSystem;
require_once '../php/admin.php';
loginCheck();

$answer['type'] = "error";
$answer['message'] = "Illigal Request!";

if(isset($_GET['a'])){

  if($_GET['a'] == "database"){
    $answer['type'] = (DBNavigation::displayUp($_GET['id'])) ? "success" : "error";
    $answer['message'] = "";
  }

  if($_GET['a'] == "template"){
    try{
      foreach($_POST as $key => $val){
        DBStatic::saveVar($key, $val);
      }
      $answer['type'] = "success";
      $answer['message'] = "";
    }catch(Exception $e){
      $answer['type'] = "error";
      $answer['message'] = $e->getMessage();
    }
  }

}

echo json_encode($answer);
