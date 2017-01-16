<?php
use KFall\oxymora\database\modals\DBMember;
use KFall\oxymora\database\modals\DBGroups;
use KFall\oxymora\database\modals\DBGrouppermissions;
use KFall\oxymora\upload\ProfileUpload;
use KFall\oxymora\permissions\UserPermissionSystem;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();

$action = (isset($_GET['a'])) ? $_GET['a'] : ((isset($_POST['a'])) ? $_POST['a'] : error("No Action set.. What are you doing??"));
$answer = ["error"=>false,"data"=>""];

switch ($action) {
  case 'addMember':
  $username   = (isset($_POST['username']) && !empty($_POST['username'])) ? $_POST['username'] : error("No username set.. What are you doing??");
  $password   = (isset($_POST['password']) && !empty($_POST['password'])) ? $_POST['password'] : error("No password set.. What are you doing??");
  $email      = (isset($_POST['email']) && !empty($_POST['email'])) ? $_POST['email'] : error("No email set.. What are you doing??");
  $groupid    = (isset($_POST['groupid']) && !empty($_POST['groupid'])) ? $_POST['groupid'] : error("No groupid set.. What are you doing??");
  $imageName  = (isset($_FILES["image"])) ? "profil/".ProfileUpload::upload($_FILES["image"]) : null;
  $res = DBMember::addMember($username, $password, $email, $imageName, $groupid);
  if($res === false){error('Something went wrong!');}
  $member = DBMember::getMember($res);
  $answer['data'] = html_userItem($member['id'],$member['username'],$m['email'],$member['image'],$member['groupid'],$member['groupcolor']);
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

  case 'savePermissions':
  $id = (isset($_GET['id'])) ? $_GET['id'] : error("No ID set.. What are you doing??");
  $permissions = (isset($_GET['permissions'])) ? $_GET['permissions'] : error("No Permissions set.. What are you doing??");
  DBGrouppermissions::removeAllPermissions($id);
  foreach($permissions as $key){
      DBGrouppermissions::addPermission($id,$key);
  }
  $answer['data'] = "Success";
  break;

  case 'getGroups':
  $answer['data'] = DBGroups::listGroups();
  $answer['data'] = array_map(function($n){
    $n['permissions'] = UserPermissionSystem::listPermissions($n['id']);
    return $n;
  }, $answer['data']);
  break;

  default:
  error('Invalid action!');
}

echo json_encode($answer);


// THIS RUNS WHEN SOMETHING BAD HAPPEND :S
function error($message){
  die(json_encode(["error"=>true,"data"=>$message]));
}
