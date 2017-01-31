<?php
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\pageBuilder\PageBuilder;
use KFall\oxymora\pageBuilder\ErrorBuilder;
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\database\modals\DBStatic;
use KFall\oxymora\addons\Args;

define('WEB_REL_ROOT', '');
require_once 'core/core.php';

// ================================================
// Page Builder
// ================================================

try{
// Current Page
$page = (isset($_GET['page'])) ? $_GET['page'] : "index.html";

// Template
if(!PageBuilder::loadTemplate(TEMPLATE)){
  die("There is a problem with your template!");
}

// Get & Set Menu
PageBuilder::setMenuItems(DBNavigation::getItems());

// Get & Set Template Vars
PageBuilder::setTemplateVars(DBStatic::getVars());

// Load Current Page
PageBuilder::loadCurrentPage($page);

// Get the HTML and create page Object
$obj = new stdClass();
$obj->page = $page;
$obj->html = PageBuilder::getHtml();

// Run Addon Event
AddonManager::triggerEvent(ADDON_EVENT_PAGEOPEN, $obj);

// ECHOS THE HTML OF PAGE
echo $obj->html;

}catch(Exception $e){
  $page = isset($page) ? $page : null;
  ErrorBuilder::throwError($e->getCode(), $e->getMessage(), $page);
}
