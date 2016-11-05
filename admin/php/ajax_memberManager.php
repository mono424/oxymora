<?php
use KFall\oxymora\database\modals\DBGroups;
require_once '../php/admin.php';
loginCheck();

$action = (isset($_GET['a'])) ? $_GET['a'] : error("No Action set.. What are you doing??");
$answer = ["error"=>false,"data"=>""];

switch ($_GET['a']) {
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
