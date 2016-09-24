<?php
use KFall\oxymora\memberSystem\MemberSystem;

require 'admin.php';

$answer['type'] = "error";
$answer['message'] = "Illigal Request!";

if(isset($_GET['user']) && isset($_GET['pass'])){
  if(MemberSystem::init()->login($_GET['user'],$_GET['pass']) === true){
    $answer['type'] = "success";
    $answer['message'] = "";
  }else{
    $answer['type'] = "error";
    $answer['message'] = MemberSystem::init()->login_error;
  }
}


echo json_encode($answer);
