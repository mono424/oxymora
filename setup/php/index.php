<?php
use KFall\oxymora\system\Exporter;
require '../../core/autoload.php';
require '../../core/statics.php';

if(configExists()) error('Oxymora seems already setup ...');

define('BACKUP_FILE', __DIR__."/upload/backup.oxybackup");
$action = (isset($_GET['action'])) ? $_GET['action'] : "";

switch($action){
  case 'setup':
  try{
    require 'setup_installer.php';
  } catch(Exception $e){
    error($e->getMessage());
  }
  break;

  case 'restore':
  try{
    require 'backup_installer.php';
  } catch(Exception $e){
    error($e->getMessage());
  }
  break;

  case 'checkBackupDB':
  try{
    if(!file_exists(BACKUP_FILE)) error('No Backup-Container found.');
    $info = Exporter::getConfig(BACKUP_FILE);
    if($info === false || !isset($info['database'])) error('No valid Config found!');
    $host = $info['database']['host'];
    $user = $info['database']['user'];
    $pass = $info['database']['pass'];
    connectDB($host,$user,$pass);
    success(null);
  } catch(Exception $e){
    error($e->getMessage());
  }
  break;

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
    move_uploaded_file($_FILES['file']['tmp_name'], BACKUP_FILE);
    if($password) Crypter::decryptFile(BACKUP_FILE, $password);
    $info = Exporter::getInfo(BACKUP_FILE);
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

function getDefaultConfig(){
  return json_decode(file_get_contents(__DIR__."/config/config.json"), true);
}

function setConfig($assoc){
  return file_put_contents(ROOT_DIR.'config.json', json_encode($assoc));
}

function configExists(){
  return file_exists(ROOT_DIR.'config.json');
}

function success($message){
  die(json_encode(['error' => false, 'message' => $message]));
}
function error($message){
  die(json_encode(['error' => true, 'message' => $message]));
}

?>
