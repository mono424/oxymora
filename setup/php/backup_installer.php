<?php
use KFall\oxymora\config\Config;
use KFall\oxymora\system\Exporter;
use KFall\oxymora\database\DB;
use KFall\oxymora\addons\AddonManager;

$step = (isset($_GET['step'])) ? $_GET['step'] : "";



switch($step){
  case 'createConfig':
  if(!isset($_POST['backup']) && !(isset($_POST['host']) && isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['db']))) throw new Exception('Missing Parameter!');
  if(isset($_POST['backup']) && $_POST['backup'] == "1"){
    $config = Exporter::getConfig(BACKUP_FILE);
  }else{
    $config = getDefaultConfig();
    $config['database']['host'] = $_POST['host'];
    $config['database']['user'] = $_POST['user'];
    $config['database']['pass'] = $_POST['pass'];
    $config['database']['db']   = $_POST['db'];
    if($_POST['prefix']){
      $config['database-tables'] = array_map(function($value){
        return $_POST['prefix'].$value;
      }, $config['database-tables']);
    }
  }
  if(setConfig($config)) success();
  else throw new Exception('Cant write Config File!');
  break;




  case 'setupDB':
  if(!file_exists(__DIR__."/sql/db.sql")) throw new Exception('SQL-File not found!');
  // Get Data
  $sql = file_get_contents(__DIR__."/sql/db.sql");
  Config::load();
  $config = Config::get();
  // Replace Placeholder in SQL
  $sql = str_replace("{db}", $config['database']['db'], $sql);
  foreach($config['database-tables'] as $key => $val){
    $sql = str_replace("{{$key}}", $val, $sql);
  }
  // Connect and Install
  $pdo = connectDB($config['database']['host'], $config['database']['user'], $config['database']['pass']);
  if(!$pdo) throw new Exception('Cant connect to Database!');
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
  if(!$pdo->exec($sql)) throw new Exception('Failed to install Database!');
  success();
  break;




  case 'installAddons':
  // DATABASE
  Config::load();
  $config = Config::get();
  $success = DB::connect($config['database']['host'], $config['database']['user'], $config['database']['pass'], $config['database']['db']);
  if(!$success) throw new Exception('Cant connect to Database!');
  // INSTALLING ADDONS
  $addons = AddonManager::listAll();
  foreach($addons as $addon){
    AddonManager::install($addon['name']);
  }
  success();
  break;



  case 'restoreBackup':
  // CONFIG & DB
  Config::load();
  $config = Config::get();
  $success = DB::connect($config['database']['host'], $config['database']['user'], $config['database']['pass'], $config['database']['db']);
  if(!$success) throw new Exception('Cant connect to Database!');

  $res = Exporter::import(BACKUP_FILE);

  // SETUP IS FINISHED IF $res === TRUE
  if($res){
    session_destroy();
    success();
  }else{
    throw new Exception('cant load backup!');
  }
  break;


  default:
  throw new Exception('invalid step');
}
?>
