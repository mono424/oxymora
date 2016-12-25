<?php
use KFall\oxymora\database\modals\DBStatic;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\config\Config;
require_once '../php/admin.php';
loginCheck();

$answer['type'] = "error";
$answer['message'] = "Illigal Request!";

if(isset($_GET['a'])){

  if($_GET['a'] == "database"){
    $arr = ['database' => []];
    $arr['database']['host'] = $_POST['host'];
    $arr['database']['user'] = $_POST['user'];
    if(isset($_POST['pass']) && !empty($_POST['pass'])) $arr['database']['pass'] = $_POST['pass'];
    $arr['database']['db'] = $_POST['db'];
    Config::overwrite($arr);
    Config::save();
    $answer['type'] = "success";
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
