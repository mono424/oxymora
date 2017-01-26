<?php
use KFall\oxymora\fileSystem\FileManager;
use KFall\oxymora\pageBuilder\ErrorBuilder;
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
  ErrorBuilder::printOut('404', 'Error 404 Not Found');
  die();
}

function internalError($err){
  ErrorBuilder::printOut('500', '500 Internal Server Error');
  die();
}

function accessDenied(){
  ErrorBuilder::printOut('403', 'Error 403 Forbidden');
  die();
}
