<?php
use KFall\oxymora\fileSystem\FileManager;
require_once 'core/core.php';

$file = isset($_GET['file']) ? $_GET['file'] : notFound();
try{
  $content = FileManager::printFile($file, true);
}catch(Exception $e){
  if($e->getCode() == 1){
    notFound();
  }elseif($e->getCode() == 2){
    accessDenied();
  }else{
    internalError($e->getMessage());
  }
}





function notFound(){
  header('HTTP/1.0 404 Not Found');
  echo "<h1>Error 404 Not Found</h1>";
  echo "The page that you have requested could not be found.";
  die();
}

function internalError($err){
  header('HTTP/1.0 500 Internal Server Error');
  echo "<h1>Error 500 Internal Server Error</h1>";
  echo $err;
  die();
}

function accessDenied(){
  header('HTTP/1.0 403 Forbidden');
  echo "<h1>Error 403 Forbidden</h1>";
  echo "You don't have permission to access the requested page.";
  die();
}
