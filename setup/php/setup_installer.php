<?php
use KFall\oxymora\config\Config;
use KFall\oxymora\database\DB;
use KFall\oxymora\memberSystem\Member;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\database\modals\DBGroups;

$step = (isset($_GET['step'])) ? $_GET['step'] : "";

switch($step){
  case 'createConfig':
  if(!(isset($_POST['host']) && isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['db']))) error('Missing Parameter!');
  $config = getDefaultConfig();
  $config['database']['host'] = $_POST['host'];
  $config['database']['user'] = $_POST['user'];
  $config['database']['pass'] = $_POST['pass'];
  $config['database']['db']   = $_POST['db'];
  if($_POST['prefix']){
    $config['database-tables'] = array_map(function($value){
      return $_POST['prefix'].$_POST['db'];
    }, $config['database-tables']);
  }
  if(setConfig($config)) success();
  else error('Cant write Config File!');
  break;



  case 'setupDB':
  if(!file_exists(__DIR__."/sql/db.sql")) error('SQL-File not found!');
  // Get Data
  $sql = file_get_contents(__DIR__."/sql/db.sql");
  $config = Config::get();
  // Replace Placeholder in SQL
  $sql = str_replace("{db}", $config['database']['db'], $sql);
  foreach($config['database-tables'] as $key => $val){
    $sql = str_replace("{{$key}}", $val, $sql);
  }
  // Connect and Install
  $pdo = connectDB($config['database']['host'], $config['database']['user'], $config['database']['pass']);
  if(!$pdo) error('Cant connect to Database!');
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
  $pdo->exec($sql);
  success();
  break;



  case 'registerPermissions':
  // DATABASE WORKS NOW CUZ DATABASE EXISTS :P
  $config = Config::get();
  $success = DB::connect($config['database']['host'], $config['database']['user'], $config['database']['pass'], $config['database']['db']);
  if(!$pdo) error('Cant connect to Database!');
  DBGroups::addGroup('admin', '', ['root']);
  DBGroups::addGroup('Moderator', '', ['oxymora_dashboard', 'oxymora_files', 'oxymora_pages']);
  UserPermissionSystem::register('oxymora_addons', "Addon-Manager-Page Access");
  UserPermissionSystem::register('oxymora_dashboard', "Dashboard-Page Access");
  UserPermissionSystem::register('oxymora_files', "File-Manager-Page Access");
  UserPermissionSystem::register('oxymora_member', "Member Access");
  UserPermissionSystem::register('oxymora_pages', "Pages-and-Navi-Page Access");
  UserPermissionSystem::register('oxymora_settings', "Settings-Page Access");
  break;



  case 'registerUser':
  if(!(isset($_POST['user']) && isset($_POST['email']) && isset($_POST['pass']))) error('Missing Parameter!');
  // CONFIG
  $config = Config::get();
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
  // ADMIN GROUP IS THE ONLY GROUP EXISTS SO ID HAS TO BE 1 /// WE ARE SAVING QUERYS :P
  $adminGroupId = 1;
  // CREATE USER
  $m = new Member();
  $m->addAttr(new Attribute('username', $_POST['user']));
  $m->addAttr(new Attribute('password', $_POST['pass']));
  $m->addAttr(new Attribute('groupid', $adminGroupId));
  $m->addAttr(new Attribute('email', $_POST['email']));
  MemberSystem::init()->registerMember($m);
  break;



  default:
  error('invalid step');
}
