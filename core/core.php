<?php
// ================================================
// Namespaces
// ================================================

use KFall\oxymora\config\Config;
use KFall\oxymora\database\DB;

// ================================================
// Some Static Stuff
// ================================================

require_once __DIR__.'/statics.php';

// ================================================
// Autoloader & Rootdir
// ================================================

require_once __DIR__.'/autoload.php';


// ================================================
// Config
// ================================================
if(file_exists(ROOT_DIR.'/config.json')){
  Config::set(json_decode(file_get_contents(ROOT_DIR.'/config.json'), true));
}else{
  $path = (defined(WEB_REL_ROOT)) ? WEB_REL_ROOT : "../";
  header('location: '.$path.'setup');
}



// ================================================
// TEMPLATE
// ================================================
define('TEMPLATE', "oxymora_landingpage");



// ================================================
// Database
// ================================================
if(!DB::connect(Config::get()['database']['host'],Config::get()['database']['user'],Config::get()['database']['pass'],Config::get()['database']['db'])){
  die("There is a problem with your database!");
}
