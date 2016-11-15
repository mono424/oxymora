<?php
// core stuff
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\permissions\UserPermissionSystem;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';

// Check Login
loginCheck();

// Check Permissions
if(!UserPermissionSystem::checkPermission("oxymora_files")) die(html_error("You do not have the required rights to continue!"));

// Tab Change event
AddonManager::triggerEvent(ADDON_EVENT_TABCHANGE, 'files');
 ?>
<!-- <div class="headerbox flat-box">
<h1>File-Manager</h1>
<h3>This is the place where your files live.</h3>
</div> -->
<div id="fileManager" class="noselect">
  <div class="path">
    <ul>
    </ul>
    <div class="trash" data-role="trash">
      <i class="fa fa-trash-o" aria-hidden="true"></i>
    </div>
  </div>
  <div class="search">
    <input class="oxinput" type="text" placeholder="Search">
  </div>
  <div class="dirs"></div>
  <div class="files"></div>
</div>

<script type="text/javascript">
  fileManager.init();
</script>
