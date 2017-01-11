<?php
$step = (isset($_GET['step'])) ? $_GET['step'] : "";

switch($step){
  case 'createConfig':
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
  else err('Cant write Config File!');
  break;

  case 'setupDB':

  break;

  case 'registerUser':

  break;

  default:
  error('invalid step');
}
