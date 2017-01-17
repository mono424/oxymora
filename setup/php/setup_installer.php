<?php
use KFall\oxymora\config\Config;
use KFall\oxymora\database\DB;
use KFall\oxymora\database\modals\DBGroups;
use KFall\oxymora\database\modals\DBMember;
use KFall\oxymora\database\modals\DBWidgets;
use KFall\oxymora\permissions\UserPermissionSystem;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;

$step = (isset($_GET['step'])) ? $_GET['step'] : "";

switch($step){
  case 'createConfig':
  if(!(isset($_POST['host']) && isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['db']) && isset($_POST['template']))) throw new Exception('Missing Parameter!');
  $config = getDefaultConfig();
  $config['template']         = $_POST['template'];
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



  case 'registerPermissions':
  // DATABASE WORKS NOW CUZ DATABASE EXISTS :P
  Config::load();
  $config = Config::get();
  $success = DB::connect($config['database']['host'], $config['database']['user'], $config['database']['pass'], $config['database']['db']);
  if(!$success) throw new Exception('Cant connect to Database!');
  DBGroups::addGroup('admin', '', ['root']);
  DBGroups::addGroup('Moderator', '', ['oxymora_dashboard', 'oxymora_files', 'oxymora_pages']);
  UserPermissionSystem::register('oxymora_addons', "Addon-Manager-Page Access");
  UserPermissionSystem::register('oxymora_dashboard', "Dashboard-Page Access");
  UserPermissionSystem::register('oxymora_files', "File-Manager-Page Access");
  UserPermissionSystem::register('oxymora_member', "Member Access");
  UserPermissionSystem::register('oxymora_pages', "Pages-and-Navi-Page Access");
  UserPermissionSystem::register('oxymora_settings', "Settings-Page Access");
  success();
  break;



  case 'registerUser':
  if(!(isset($_POST['user']) && isset($_POST['email']) && isset($_POST['pass']))) throw new Exception('Missing Parameter!');
  // CONFIG & DB
  Config::load();
  $config = Config::get();
  $success = DB::connect($config['database']['host'], $config['database']['user'], $config['database']['pass'], $config['database']['db']);
  if(!$success) throw new Exception('Cant connect to Database!');
  // ADMIN GROUP IS THE ONLY GROUP EXISTS SO ID HAS TO BE 1 /// WE ARE SAVING QUERYS :P
  $adminGroupId = '1';
  // SETUP MEMBERSYSTEM
  MemberSystem::init([
    "database" => $config['database']['db'],
    "member-table" => $config['database-tables']['user'],
    "attempt-table" => $config['database-tables']['membersystem_attempt'],
    "session-table" => $config['database-tables']['membersystem_session'],
    "column-id" => "id",
    "column-username" => "username",
    "column-password" => "password"
  ]);
  // CREATE USER
  if(!DBMember::addMember($_POST['user'],$_POST['pass'],$_POST['email'], null, $adminGroupId)) throw new Exception('Cant create User!');
  // setup is finished
  success();
  break;



  case 'installAddons':
  // LOAD CORE
  require ROOT_DIR."core.php";

  // ADMIN IS THE ONLYONE WHO EXISTS SO ID HAS TO BE 1 /// WE ARE SAVING QUERYS :P
  $adminId = '1';

  // INSTALLING ADDONS
  $addons = AddonManager::listAll();
  foreach($addons as $addon){
    if($addon['name'] == "welcomeWidget"){
      AddonManager::install($addon['name'], true); //TRUE cuz: If default will change to false in futur it still installs ;)
      DBWidgets::add($adminId, "welcomeWidget");
    }else{
      AddonManager::install($addon['name']);
    }
  }

  // SETUP IS FINISHED
  session_destroy();
  success();
  break;


  default:
  throw new Exception('invalid step');
}
