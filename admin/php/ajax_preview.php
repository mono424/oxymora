<?php
use KFall\oxymora\database\modals\DBStatic;
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\pageBuilder\PageEditor;
require_once '../php/admin.php';
loginCheck();

// Current Page
$page = (isset($_GET['page'])) ? $_GET['page'] : "index.html";

// Template
if(!PageEditor::loadTemplate(TEMPLATE)){
  die("There is a problem with your template!");
}

// Custom Path
PageEditor::setCustomPath("../../");

// Get & Set Menu
PageEditor::setMenuItems(DBNavigation::getItems());

// Get & Set Template Vars
PageEditor::setTemplateVars(DBStatic::getVars());

// Load Current Page
PageEditor::loadCurrentPage($page);

// ECHOS THE HTML OF PAGE
echo PageEditor::getEditorHtml();
