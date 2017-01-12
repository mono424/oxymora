<?php
use KFall\oxymora\config\Config;
use KFall\oxymora\system\Exporter;
use KFall\oxymora\database\DB;

$step = (isset($_GET['step'])) ? $_GET['step'] : "";

switch($step){
  case 'createConfig':
  if(!(isset($_POST['host']) && isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['db']))) throw new Exception('Missing Parameter!');
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
  if(setConfig($config)) success();
  else throw new Exception('Cant write Config File!');
  break;

  case 'restoreBackup':
  $useBackupConfig = (isset($_POST['backupConfig']) && $_POST['backupConfig'] == "1");
  if($useBackupConfig){
    if(!file_exists(BACKUP_FILE)) throw new Exception('No Backup-Container found.');
    $config = Exporter::getConfig(BACKUP_FILE);
    if($config === false || !isset($config['database'])) throw new Exception('No valid Config found!');
  }else{
    Config::load();
    $config = Config::get();
  }
  $pdo = connectDB($config['database']['host'], $config['database']['user'], $config['database']['pass']);
  if(!$pdo) throw new Exception('Cant connect to Database!');
  // WE DONT ESCAPE IN SETUP :) MAYBE A PLUS FEATURE IN FUTUR
  $pdo->exec('CREATE DATABASE IF NOT EXISTS `'.$config['database']['db'].'` DEFAULT CHARACTER SET utf8 COLLATE=utf8_unicode_ci;');
  $pdo->exec('USE `'.$config['database']['db'].'`;');

  $res = Exporter::import(BACKUP_FILE, "", $pdo, $useBackupConfig);

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
