<?php
use KFall\oxymora\database\modals\DBStatic;
use KFall\oxymora\database\modals\DBContent;
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\pageBuilder\PageEditor;
use KFall\oxymora\pageBuilder\TemplateElementManager;
require_once '../php/admin.php';
loginCheck();

// Current Page
$action = (isset($_GET['a'])) ? $_GET['a'] : error("No Action set.. What do you try to do??");
$answer = ["error"=>false,"data"=>""];

switch ($action) {
  case 'save':
    $url = (isset($_POST['url'])) ? $_POST['url'] : error("No Url set.. What do you try to do??");
    $plugins = (isset($_POST['plugins'])) ? $_POST['plugins'] : [];
    $areaSortedArray = [];
    $areas = DBContent::getPageAreas($url);
    // MAKE SURE EVERY AREA IS IN ARRAY
    foreach($areas as $area){;
      $areaSortedArray[$area['area']] = [];
    }
    // ADD PLUGINS TO AREA KEY
    foreach($plugins as $plugin){
      $areaSortedArray[$plugin['area']][] = $plugin;
    }
    foreach($areaSortedArray as $area => $areaPlugins){
      $answer["error"] = !DBContent::overwriteArea($url, $area, $areaPlugins);
    }
    break;

  case 'getPlugins':
    $answer["data"] = TemplateElementManager::listElements(TEMPLATE,false);
    break;

  case 'pluginSettings':
    $pluginName = (isset($_POST['plugin'])) ? $_POST['plugin'] : error("No Plugin set.. What do you try to do??");
    $pluginSettings = (isset($_POST['id'])) ? $_POST['id'] : ""; // todo: get current Settings if ID is set
    $plugin = TemplateElementManager::findElement(TEMPLATE,$pluginName);
    $answer["data"] = $plugin['config']['settings'];
    break;

  case 'renderPluginPreview':
      $plugin = (isset($_POST['plugin'])) ? $_POST['plugin'] : error("No Plugin set.. What do you try to do??");
      $pluginId = (isset($_POST['id'])) ? $_POST['id'] : "";
      $pluginSettings = (isset($_POST['settings'])) ? $_POST['settings'] : "";
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
