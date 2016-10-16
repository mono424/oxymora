<?php
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
require_once '../php/admin.php';
loginCheck();
AddonManager::triggerEvent('onTabChange', 'member');
 ?>
<div class="headerbox purple-box">
<h1>Member</h1>
<h3>Be a real admin! Manage user!</h3>
</div>
