<?php
// ================================================
// Namespaces
// ================================================

use KFall\oxymora\config\Config;
use KFall\oxymora\database\DB;

// ================================================
// Some Static Stuff
// ================================================
// Placeholder
define('PLACEHOLDER_INDENT_AREA', 'area');
define('PLACEHOLDER_INDENT_STATIC', 'static');
define('PLACEHOLDER_INDENT_PLUGIN', 'plugin');
// Addon Types
define('ADDON_ADDON', 'addon');
define('ADDON_WIDGET', 'widget');


// ================================================
// Autoloader & Rootdir
// ================================================

define('ROOT_DIR', dirname(__FILE__).'\\'); // DIR OF CORE FOLDER
require_once 'autoload.php';


// ================================================
// Config
// ================================================
if(file_exists(ROOT_DIR.'/config.json')){
  Config::set(json_decode(file_get_contents(ROOT_DIR.'/config.json'), true));
}else{
  die('There is a problem with your config file!');
}



// ================================================
// TEMPLATE
// ================================================
define('TEMPLATE', "business");



// ================================================
// Database
// ================================================
if(!DB::connect(Config::get()['database']['host'],Config::get()['database']['user'],Config::get()['database']['pass'],Config::get()['database']['db'])){
  die("There is a problem with your database!");
}
