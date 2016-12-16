<?php
use KFall\oxymora\database\modals\DBWidgets;
require_once '../php/admin.php';
loginCheck();


if(!isset($_GET['action'])) error('Illigal Request!');



if($_GET['action'] == "get"){
  $addons = DBWidgets::get();
  $answer = ["error"=>false,"data"=>$addons];
  echo json_encode($answer);die();
}





error('Illigal Request!');
// THIS RUNS WHEN SOMETHING BAD HAPPEND :S
function error($message){
  die(json_encode(["error"=>true,"data"=>$message]));
}
