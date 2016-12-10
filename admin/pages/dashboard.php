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
if(!UserPermissionSystem::checkPermission("oxymora_dashboard")) die(html_error("You do not have the required rights to continue!"));

// Tab Change event
AddonManager::triggerEvent(ADDON_EVENT_TABCHANGE, 'dashboard');
 ?>
<div class="widget-container">

  <div class="widget">
    <div class="widget-placeholder">Click to choose a Widget</div>
  </div>
  <div class="widget">
    <div class="widget-placeholder">Click to choose a Widget</div>
  </div>
  <div class="widget">
    <div class="widget-placeholder">Click to choose a Widget</div>
  </div>

  <div class="widget">
    <div class="widget-placeholder">Click to choose a Widget</div>
  </div>
  <div class="widget">
    <div class="widget-placeholder">Click to choose a Widget</div>
  </div>
  <div class="widget">
    <div class="widget-placeholder">Click to choose a Widget</div>
  </div>

</div>
