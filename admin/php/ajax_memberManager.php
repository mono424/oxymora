<?php
use KFall\oxymora\database\modals\DBMember;
use KFall\oxymora\database\modals\DBGroups;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();

$action = (isset($_GET['a'])) ? $_GET['a'] : ((isset($_POST['a'])) ? $_POST['a'] : error("No Action set.. What are you doing??"));
$answer = ["error"=>false,"data"=>""];

switch ($action) {
  case 'addMember':
  $username = (isset($_POST['username'])) ? $_POST['username'] : error("No username set.. What are you doing??");
  $password = (isset($_POST['password'])) ? $_POST['password'] : error("No password set.. What are you doing??");
  $email = (isset($_POST['email'])) ? $_POST['email'] : error("No email set.. What are you doing??");
  $groupid = (isset($_POST['groupid'])) ? $_POST['groupid'] : error("No groupid set.. What are you doing??");
  $res = DBMember::addMember($username, $password, $email, "profil/default.jpg", $groupid);
  if($res === false){error('Something went wrong!');}
  $member = DBMember::getMember($res);
  $answer['data'] = html_userItem($member['id'],$member['username'],$member['image'],$member['groupcolor']);
  break;

  case 'removeMember':
  $id = (isset($_GET['id'])) ? $_GET['id'] : error("No ID set.. What are you doing??");
  $answer['data'] = DBMember::removeMember($id) ? "" : error('Something went wrong!');
  break;

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

  case 'editGroup':
  $id = (isset($_GET['id'])) ? $_GET['id'] : error("No ID set.. What are you doing??");
  $name = (isset($_GET['name'])) ? $_GET['name'] : error("No Name set.. What are you doing??");
  $color = (isset($_GET['color'])) ? $_GET['color'] : null;
  $res = DBGroups::editGroup($id,$name,$color);
  if($res === false){error('Something went wrong!');}
  $info = DBGroups::getGroupInfo($id);
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
