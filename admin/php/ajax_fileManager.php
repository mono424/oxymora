<?php
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\fileSystem\FileManager;
use KFall\oxymora\image\ImageHelper;
require_once '../php/admin.php';
loginCheck();

$action = (isset($_GET['a'])) ? $_GET['a'] : error("No Action set.. What are you doing??");
$answer = ["error"=>false,"data"=>""];



switch ($_GET['a']) {

  case 'index':
  $dir = (isset($_GET['dir'])) ? $_GET['dir'] : "";
  $search = (isset($_GET['s'])) ? $_GET['s'] : "";
  $answer['data'] = ($search) ? FileManager::searchFiles($dir, $search) : FileManager::listFiles($dir);
  break;

  case 'preview':
  $file = (isset($_GET['file'])) ? $_GET['file'] : error("No File set.. What are you doing??");
  $height = (isset($_GET['h'])) ? $_GET['h'] : 250;
  $width = (isset($_GET['w'])) ? $_GET['w'] : 160;
  $path = FileManager::getPath($file);
  $pathInfo = pathinfo($path);
  if(strtolower($pathInfo['extension']) == "jpg" || strtolower($pathInfo['extension'] == "jpeg")){
    $image = ImageHelper::easyImageCrop($path, false, $width, $height);
    header('Content-Type: image/jpeg');
    imagejpeg($image);
  }elseif(strtolower($pathInfo['extension']) == "svg"){
    header('Content-type: image/svg+xml');
    readfile($path);
  }elseif(strtolower($pathInfo['extension']) == "gif"){
    header('Content-type: image/gif');
    readfile($path);
  }else{
    header('Content-Type: image/*');
    readfile($path);
  }
  die();
  break;

  default:
  error('Invalid action!');
}

echo json_encode($answer);


// THIS RUNS WHEN SOMETHING BAD HAPPEND :S
function error($message){
  die(json_encode(["error"=>true,"data"=>$message]));
}
