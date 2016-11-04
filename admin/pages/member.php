<?php
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\database\modals\DBMember;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();
AddonManager::triggerEvent(ADDON_EVENT_TABCHANGE, 'member');
?>
<!-- <div class="headerbox flat-box">
<h1>Member</h1>
<h3>Be a real admin! Manage user!</h3>
</div> -->

<div class="tabContainer light">
  <ul>
    <li><a data-tab="user">User</a></li>
    <li><a data-tab="groups">Groups</a></li>
  </ul>
  <div class="tabContent">

    <div class="tab" data-tab="user">
      <div class="dataContainer" id="userContainer">
        <?php
        $member = DBMember::getList();
        foreach($member as $m){
          echo html_userItem($m['username'], $m['image']);
        }
        ?>
      </div>
      <button id="addUserButton" class="oxbutton-float" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
    </div>


    <div class="tab" data-tab="groups">
      <div class="dataContainer" id="groupContainer">
        <?php

        ?>
      </div>
      <button id="addGroupButton" class="oxbutton-float" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
    </div>


  </div>
</div>



<script type="text/javascript">
  initControls();



  function initControls(){
    $('#addUserButton').on('click', function(){
      showAddUserDialog();
    });

    $('#userContainer').on('click', '.user-item', function(){
      console.log(this)
    });
  }

  function showAddUserDialog(){
    var html  = lightboxQuestion('Add new User');
        html += lightboxInput('username', 'text', 'Username');
        html += lightboxInput('email', 'email', 'E-Mail');
        html += lightboxInput('image', 'file', 'Image');
        html += lightboxInput('password', 'password', 'Password');
        html += lightboxInput('password_repeat', 'password', 'Password repeat');
        html += lightboxInput('role', 'text', 'Group');

    showLightbox(html, function(){

    }, null, "Add", "Cancel", "memberDialog");
  }





</script>
