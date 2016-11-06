<?php
use KFall\oxymora\database\modals\DBGroups;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();

$action = (isset($_GET['a'])) ? $_GET['a'] : error("No Action set.. What are you doing??");
$answer = ["error"=>false,"data"=>""];

switch ($_GET['a']) {
  case 'removeGroup':
    $id = (isset($_GET['id'])) ? $_GET['id'] : error("No ID set.. What are you doing??");
    $answer['data'] = DBGroups::removeGroup($id) ? "" : error('Something went wrong!');
  break;

  case 'addGroup':
    $name = (isset($_GET['name'])) ? $_GET['name'] : error("No Name set.. What are you doing??");
    $color = (isset($_GET['color'])) ? $_GET['color'] : "";
    $res = DBGroups::addGroup($name,$color);
    if($res === false){error('Something went wrong!');}
    $info = DBGroups::getGroupInfo($res);
    $answer['data'] = html_groupItem($info['id'], $info['name'], $info['color']);
  break;

  case 'getGroups':
    $answer['data'] = DBGroups::listGroups();
  break;
  default:
  error('Invalid action!');
}

echo json_encode($answer);


// THIS RUNS WHEN SOMETHING BAD HAPPEND :S
function error($message){
  die(json_encode(["error"=>true,"data"=>$message]));
}
