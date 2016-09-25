<?php
use KFall\oxymora\database\modals\DBStatic;
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\pageBuilder\PageBuilder;
require_once '../php/admin.php';
loginCheck();

// Current Page
$page = (isset($_GET['page'])) ? $_GET['page'] : "index.html";

// Template
if(!PageBuilder::loadTemplate("business")){
  die("There is a problem with your template!");
}

// Custom Path
PageBuilder::setCustomPath("../../");

// Get & Set Menu
PageBuilder::setMenuItems(DBNavigation::getItems());

// Get & Set Template Vars
PageBuilder::setTemplateVars(DBStatic::getVars());

// Load Current Page
PageBuilder::loadCurrentPage($page);

// ECHOS THE HTML OF PAGE
echo PageBuilder::getHtml();
