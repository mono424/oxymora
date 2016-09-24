<?php
// ================================================
// Namespaces
// ================================================

use KFall\oxymora\config\Config;
use KFall\oxymora\database\DB;
use KFall\oxymora\pageBuilder\PageBuilder;
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\database\modals\DBStatic;


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
// Database
// ================================================
if(!DB::connect(Config::get()['database']['host'],Config::get()['database']['user'],Config::get()['database']['pass'],Config::get()['database']['db'])){
  die("There is a problem with your database!");
}







// ================================================
// Page Builder
// ================================================

// Current Page
$page = (isset($_GET['page'])) ? $_GET['page'] : "index";

// Template
if(!PageBuilder::loadTemplate("business")){
  die("There is a problem with your template!");
}

// Get & Set Menu
PageBuilder::setMenuItems(DBNavigation::getItems());

// Get & Set Template Vars
PageBuilder::setTemplateVars(DBStatic::getVars());

// Load Current Page
PageBuilder::loadCurrentPage($page);

// ECHOS THE HTML OF PAGE
echo PageBuilder::getHtml();
