<?php
// core stuff
use KFall\oxymora\database\modals\DBGroups;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\database\modals\DBMember;
use KFall\oxymora\permissions\UserPermissionSystem;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';

// Check Login
loginCheck();

// Check Permissions
if(!UserPermissionSystem::checkPermission("oxymora_member")) die(html_error("You do not have the required rights to continue!"));

// Tab Change event
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
          echo html_userItem($m['id'], $m['username'], $m['email'], $m['image'], $m['groupid'], $m['groupcolor']);
        }
        ?>
      </div>
      <button id="addUserButton" class="oxbutton-float" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
    </div>


    <div class="tab" data-tab="groups">
      <div class="dataContainer" id="groupContainer">
        <?php
        $groups = DBGroups::listGroups();
        foreach($groups as $g){
          echo html_groupItem($g['id'], $g['name'], $g['color']);
        }
        ?>
      </div>
      <button id="addGroupButton" class="oxbutton-float" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
    </div>


  </div>
</div>



<script type="text/javascript">
  memberManager.init();
</script>
