<?php
use KFall\oxymora\system\Updater;
require_once '../php/admin.php';
loginCheck();

$answer['type'] = "error";
$answer['message'] = "Illigal Request!";

if(isset($_GET['a'])){
  try{
    switch($_GET['a']){

      case 'install':
      Updater::install();
      $answer['type'] = "success";
      $answer['message'] = "";
      break;

      case 'info':
      $info = Updater::getInfo();
      if(!($info && $info['version'] > OXY_VERSION)) $info = false;
      $answer['type'] = "success";
      $answer['message'] = $info;
      break;

      default:
      throw new Exception('Illigal Request');
    }
  } catch (Exception $e) {
    $answer['type'] = "error";
    $answer['message'] = $e->getMessage();
  }
}


echo json_encode($answer);
