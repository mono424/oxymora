<?php
use KFall\oxymora\system\Reseter;
use KFall\oxymora\database\modals\DBStatic;
use KFall\oxymora\database\modals\DBMember;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\config\Config;
use KFall\oxymora\upload\ProfileUpload;
require_once '../php/admin.php';
loginCheck();

$answer['type'] = "error";
$answer['message'] = "Illigal Request!";

try{

  if(isset($_GET['a'])){

    if($_GET['a'] == "reset"){
      if(!DBMember::approvePassword(MemberSystem::init()->member->id, $_POST['pass'])) error('Wrong Password!');
      Reseter::reset();
      $answer['type'] = "success";
      $answer['message'] = "";
    }

    if($_GET['a'] == "changeImage"){
      $imageName  = (isset($_FILES["image"])) ? "profil/".ProfileUpload::upload($_FILES["image"]) : null;
      DBMember::changePicture(MemberSystem::init()->member->id, $imageName);
      MemberSystem::init()->updateMember(); // useless if nothing happens after here, but security first ;)
      $answer['type'] = "success";
      $answer['message'] = $imageName;
    }

    if($_GET['a'] == "changepass"){
      if(!DBMember::approvePassword(MemberSystem::init()->member->id, $_POST['oldpass'])) error('Wrong Password!');
      if($_POST['newpass'] !== $_POST['newpass_again']) error('Wrong Password repeat!');
      MemberSystem::init()->member->password->setValue(password_hash($_POST['newpass'], PASSWORD_BCRYPT));
      MemberSystem::init()->updateDatabase();
      $answer['type'] = "success";
      $answer['message'] = "";
    }

    if($_GET['a'] == "deleteaccount"){
      $answer['type'] = "success";
      $answer['message'] = DBMember::removeMember(MemberSystem::init()->member->id) ? "" : error('Something went wrong!');
      MemberSystem::init()->logout();
    }

    if($_GET['a'] == "database"){
      $arr = ['database' => []];
      $arr['database']['host'] = $_POST['host'];
      $arr['database']['user'] = $_POST['user'];
      $arr['database']['pass'] = "";
      if(isset($_POST['pass']) && !empty($_POST['pass'])) $arr['database']['pass'] = $_POST['pass'];
      $arr['database']['db'] = $_POST['db'];
      Config::overwrite($arr);
      Config::save();
      $answer['type'] = "success";
      $answer['message'] = "";
    }

    if($_GET['a'] == "template"){
      try{
        foreach($_POST as $key => $val){
          DBStatic::saveVar($key, $val);
        }
        $answer['type'] = "success";
        $answer['message'] = "";
      }catch(Exception $e){
        $answer['type'] = "error";
        $answer['message'] = $e->getMessage();
      }
    }

  }

}catch(Exception $e){
  error($e->getMessage());
}

echo json_encode($answer);





// THIS RUNS WHEN SOMETHING BAD HAPPEND :S
function error($message){
  die(json_encode(["error"=>true,"message"=>$message]));
}
