<?php
use KFall\oxymora\system\Exporter;
require_once '../php/admin.php';
loginCheck();

$answer['type'] = "error";
$answer['message'] = "Illigal Request!";

if(isset($_GET['create'])){
  $exportConfig = (isset($_GET['exportConfig']) && $_GET['exportConfig'] == 'true') ? true : false;
  $password = (isset($_GET['password']) && !empty($_GET['password'])) ? $_GET['password'] : "";
  try{
    $file = Exporter::export($exportConfig, $password);
    $file = explode('/', $file);
    $answer['type'] = "success";
    $answer['message'] = $file[count($file)-1];
  } catch (Exception $e) {
    $answer['type'] = "error";
    $answer['message'] = $e->getMessage();
  }
}elseif(isset($_GET['download']) && isset($_GET['file']) && preg_match('/^[a-zA-Z0-9]*$/', $_GET['file']) && file_exists(TEMP_DIR."/exports/".$_GET['file'])){
  header("Content-Disposition: attachment; filename=oxymora_backup.oxybackup");
  header("Content-Type: binary/octet-stream");
  readfile(TEMP_DIR."/exports/".$_GET['file']);
  unlink(TEMP_DIR."/exports/".$_GET['file']);
  die();
}


  echo json_encode($answer);
