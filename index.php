<?php
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\pageBuilder\PageBuilder;
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\database\modals\DBStatic;

require_once 'core/core.php';

// ================================================
// Page Builder
// ================================================

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

// Run Addon Event
AddonManager::triggerEvent(ADDON_EVENT_PAGEOPEN, $page);

// ECHOS THE HTML OF PAGE
echo PageBuilder::getHtml();
