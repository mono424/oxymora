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
<div class="headerbox purple-box">
<object class="logo" data="img/oxy.svg" type="image/svg+xml">
    <p>It would look nice, but your browser denies is! Want a nice look? Get newest Chrome Browser ;)</p>
</object>
<h1>Welcome to Oxymora!</h1>
<h3>A lightweight CMS you will love..</h3>
</div>
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
