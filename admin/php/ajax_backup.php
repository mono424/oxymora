<?php
use KFall\oxymora\system\Exporter;
use KFall\oxymora\permissions\UserPermissionSystem;
require_once '../php/admin.php';
loginCheck();

// Check Permissions
if(!UserPermissionSystem::checkPermission("oxymora_settings")) die(error("You do not have the required rights to continue!"));

$answer['type'] = "error";
$answer['message'] = "Illigal Request!";

if(isset($_GET['create'])){
  $exportConfig = (isset($_GET['exportConfig']) && $_GET['exportConfig'] == 'true') ? true : false;
  $password = (isset($_GET['password']) && !empty($_GET['password'])) ? $_GET['password'] : "";
  try{
    $file = Exporter::export($exportConfig, $password);
    $answer['type'] = "success";
    $answer['message'] = basename($file);
  } catch (Exception $e) {
    $answer['type'] = "error";
    $answer['message'] = $e->getMessage();
  }
}elseif(isset($_GET['download']) && isset($_GET['file']) && preg_match('/^[a-zA-Z0-9]*(\.tmp)?$/', $_GET['file']) && file_exists(TEMP_DIR."/exports/".$_GET['file'])){
  header("Content-Disposition: attachment; filename=oxymora_backup.oxybackup");
  header("Content-Type: binary/octet-stream");
  readfile(TEMP_DIR."/exports/".$_GET['file']);
  unlink(TEMP_DIR."/exports/".$_GET['file']);
  die();
}


  echo json_encode($answer);


  // THIS RUNS WHEN SOMETHING BAD HAPPEND :S
  function error($message){
    die(json_encode(["error"=>true,"data"=>$message]));
  }
