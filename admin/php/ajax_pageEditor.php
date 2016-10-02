<?php
use KFall\oxymora\database\modals\DBStatic;
use KFall\oxymora\database\modals\DBContent;
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\pageBuilder\PageEditor;
use KFall\oxymora\pluginManager\PluginManager;
require_once '../php/admin.php';
loginCheck();

// Current Page
$action = (isset($_GET['a'])) ? $_GET['a'] : error("No Action set.. What do you try to do??");
$answer = ["error"=>false,"data"=>""];

switch ($action) {
  case 'save':
    $url = (isset($_GET['url'])) ? $_GET['url'] : error("No Url set.. What do you try to do??");
    $plugins = (isset($_GET['plugins'])) ? $_GET['plugins'] : error("No Plugins set.. What do you try to do??");
    $areaSortedArray = [];
    foreach($plugins as $plugin){
      $areaSortedArray[$plugin['area']][] = $plugin;
    }
    foreach($areaSortedArray as $area => $areaPlugins){
      $answer["error"] = !DBContent::overwriteArea($url, $area, $areaPlugins);
    }
    break;

  case 'getPlugins':
    $answer["data"] = PluginManager::listPlugins(TEMPLATE,false);
    break;

  case 'pluginSettings':
    $pluginName = (isset($_GET['plugin'])) ? $_GET['plugin'] : error("No Plugin set.. What do you try to do??");
    $pluginSettings = (isset($_GET['id'])) ? $_GET['id'] : ""; // todo: get current Settings if ID is set
    $plugin = PluginManager::findPlugin(TEMPLATE,$pluginName);
    $answer["data"] = $plugin['config']['settings'];
    break;

  case 'renderPluginPreview':
      $plugin = (isset($_GET['plugin'])) ? $_GET['plugin'] : error("No Plugin set.. What do you try to do??");
      $pluginId = (isset($_GET['id'])) ? $_GET['id'] : "";
      $pluginSettings = (isset($_GET['settings'])) ? $_GET['settings'] : "";
      $answer["data"] = renderPluginPreview($plugin,$pluginId,$pluginSettings);
      break;

  default:
    $answer["error"] = true;
    $answer["data"] = "Invalid Action!";
    break;
}

echo json_encode($answer);



function renderPluginPreview($plugin, $id, $settings){
  if(!PageEditor::loadTemplate(TEMPLATE)){
    die("There is a problem with your template!");
  }
  PageEditor::setCustomPath("../../");
  PageEditor::setMenuItems(DBNavigation::getItems());
  PageEditor::setTemplateVars(DBStatic::getVars());
  // PageEditor::loadCurrentPage($page);

  // ECHOS THE HTML OF PLUGIN
  $html = PageEditor::getPluginHTML($plugin,"",$settings);
  return PageEditor::editorPlugin($plugin,$id,$html,$settings);
}





// THIS RUNS WHEN SOMETHING BAD HAPPEND :S
function error($message){
  die(json_encode(["error"=>true,"data"=>$message]));
}
