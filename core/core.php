<?php
// ================================================
// Namespaces
// ================================================

use KFall\oxymora\config\Config;
use KFall\oxymora\database\DB;

// ================================================
// Some Static Stuff
// ================================================
// Oxymora Version Details
define('OXY_VERSION', 0.1);
// Placeholder
define('PLACEHOLDER_INDENT_AREA', 'area');
define('PLACEHOLDER_INDENT_STATIC', 'static');
define('PLACEHOLDER_INDENT_PLUGIN', 'plugin');
// Addon Types
define('ADDON_ADDON', 'addon');
define('ADDON_WIDGET', 'widget');
// Addon Events
define('ADDON_EVENT_INSTALLATION', 'onInstallation');
define('ADDON_EVENT_ENABLE', 'onEnable');
define('ADDON_EVENT_DISABLE', 'onDisable');
define('ADDON_EVENT_OPEN', 'onOpen');
define('ADDON_EVENT_TABCHANGE', 'onTabChange');
define('ADDON_EVENT_PAGEOPEN', 'onPageOpen');
// Addon Templates
define('ADDON_TEMPLATE_NONE', 'none');
define('ADDON_TEMPLATE_DEFAULT', 'default');
// Addon Asset Types
define('ADDON_ASSET_CSS', 'css');
define('ADDON_ASSET_JS', 'js');
// DIRS
define('ROOT_DIR', dirname(__FILE__).'/'); // DIR OF CORE FOLDER
define('ADMIN_DIR', dirname(__FILE__).'/../admin');
define('TEMP_DIR', dirname(__FILE__).'/../temp');
define('ADDON_DIR', dirname(__FILE__).'/../addons');
define('FILE_DIR', dirname(__FILE__).'/../files');
define('LOGS_DIR', dirname(__FILE__).'/../logs');
// PREFIX
define('PREFIX_SETTINGS_LIST', '--oxylist--'); // has to be Regex conform


// ================================================
// Autoloader & Rootdir
// ================================================

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
define('TEMPLATE', "oxymora_landingpage");



// ================================================
// Database
// ================================================
if(!DB::connect(Config::get()['database']['host'],Config::get()['database']['user'],Config::get()['database']['pass'],Config::get()['database']['db'])){
  die("There is a problem with your database!");
}
