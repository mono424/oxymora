<?php
use KFall\oxymora\system\Exporter;
require '../../core/autoload.php';
require '../../core/statics.php';

$action = (isset($_GET['action'])) ? $_GET['action'] : "";

switch($action){
  case 'uploadBackup':
  if(isset($_FILES['file'])){
    $password = (isset($_POST['password'])) ? $_POST['password'] : "";
    move_uploaded_file($_FILES['file']['tmp_name'], "upload/backup.oxybackup");
    $info = Exporter::getInfo(__DIR__."/upload/backup.oxybackup", $password);
    if($info === false) error('Wrong Password or broken Backup Container!');
    success($info);
  }
  break;

  default:
  error('invalid action');
}





function success($message){
  die(json_encode(['error' => false, 'message' => $message]));
}
function error($message){
  die(json_encode(['error' => true, 'message' => $message]));
}

 ?>
