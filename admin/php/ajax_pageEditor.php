<?php
use KFall\oxymora\database\modals\DBStatic;
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
  case 'getPlugins':
    $answer["data"] = PluginManager::listPlugins(TEMPLATE);
    break;

  default:
    # code...
    break;
}

echo json_encode($answer);




// THIS RUNS WHEN SOMETHING BAD HAPPEND :S
function error($message){
  die(json_encode(["error"=>true,"data"=>$message]));
}
