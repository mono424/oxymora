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
<div class="dashboard">
  <div class="widget-container"></div>
</div>

<script type="text/javascript">
dashboard.init(document.querySelector('.dashboard'));
</script>
