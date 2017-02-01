<?php
// ================================================
// Namespaces
// ================================================

use KFall\oxymora\config\Config;
use KFall\oxymora\database\DB;
use KFall\oxymora\pageBuilder\CurrentTemplate;

// ================================================
// Some Static Stuff
// ================================================

require_once __DIR__.'/statics.php';



// ================================================
// Autoloader & Rootdir
// ================================================

require_once __DIR__.'/autoload.php';


// ================================================
// Create default folder
// ================================================

if(!file_exists(TEMP_DIR)) mkdir(TEMP_DIR);



// ================================================
// Config
// ================================================
if(file_exists(ROOT_DIR.'/config.json')){
  Config::load();
}else{
  $path = (defined('WEB_REL_ROOT')) ? WEB_REL_ROOT : "../";
  header('location: '.$path.'setup');
}



// ================================================
// TEMPLATE
// ================================================
CurrentTemplate::set(Config::get()['template']);



// ================================================
// Database
// ================================================
if(!DB::connect(Config::get()['database']['host'],Config::get()['database']['user'],Config::get()['database']['pass'],Config::get()['database']['db'])){
  die("There is a problem with your database!");
}
