<?php
use KFall\oxymora\system\Exporter;
require '../../core/autoload.php';
require '../../core/statics.php';

$action = (isset($_GET['action'])) ? $_GET['action'] : "";

switch($action){
  case 'checkDB':
  try{
    $host = (isset($_POST['host'])) ? $_POST['host'] : "";
    $user = (isset($_POST['user'])) ? $_POST['user'] : "";
    $pass = (isset($_POST['pass'])) ? $_POST['pass'] : "";
    connectDB($host,$user,$pass);
    success(null);
  } catch(Exception $e){
    error($e->getMessage());
  }
  break;

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


function connectDB($host, $user, $pass){
  $pdo = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec('SET NAMES UTF8');
  return $pdo;
}

function success($message){
  die(json_encode(['error' => false, 'message' => $message]));
}
function error($message){
  die(json_encode(['error' => true, 'message' => $message]));
}

?>
