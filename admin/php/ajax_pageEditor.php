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
    $elements = (isset($_POST['elements'])) ? $_POST['elements'] : [];
    $areaSortedArray = [];
    $areas = DBContent::getPageAreas($url);
    // MAKE SURE EVERY AREA IS IN ARRAY
    foreach($areas as $area){;
      $areaSortedArray[$area['area']] = [];
    }
    // ADD PLUGINS TO AREA KEY
    foreach($elements as $element){
      $areaSortedArray[$element['area']][] = $element;
    }
    foreach($areaSortedArray as $area => $areaElements){
      $answer["error"] = !DBContent::overwriteArea($url, $area, $areaElements);
    }
    break;

  case 'getElements':
    $answer["data"] = TemplateElementManager::listElements(TEMPLATE,false);
    break;

  case 'elementSettings':
    $elementName = (isset($_POST['element'])) ? $_POST['element'] : error("No Element set.. What do you try to do??");
    $elementSettings = (isset($_POST['id'])) ? $_POST['id'] : ""; // todo: get current Settings if ID is set
    $element = TemplateElementManager::findElement(TEMPLATE,$elementName);
    $answer["data"] = $element['config']['settings'];
    break;

  case 'renderElementPreview':
      $element = (isset($_POST['element'])) ? $_POST['element'] : error("No Element set.. What do you try to do??");
      $elementId = (isset($_POST['id'])) ? $_POST['id'] : "";
      $elementSettings = (isset($_POST['settings'])) ? $_POST['settings'] : "";
      $answer["data"] = renderElementPreview($element,$elementId,$elementSettings);
      break;

  default:
    $answer["error"] = true;
    $answer["data"] = "Invalid Action!";
    break;
}

echo json_encode($answer);



function renderElementPreview($element, $id, $settings){
  if(!PageEditor::loadTemplate(TEMPLATE)){
    die("There is a problem with your template!");
  }
  PageEditor::setCustomPath("../../");
  PageEditor::setMenuItems(DBNavigation::getItems());
  PageEditor::setTemplateVars(DBStatic::getVars());
  // PageEditor::loadCurrentPage($page);

  // ECHOS THE HTML OF PLUGIN
  $html = PageEditor::getElementHTML($element,"",$settings);
  return PageEditor::editorElement($element,$id,$html,$settings);
}





// THIS RUNS WHEN SOMETHING BAD HAPPEND :S
function error($message){
  die(json_encode(["error"=>true,"data"=>$message]));
}
