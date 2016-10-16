<?php
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
require_once '../php/admin.php';
loginCheck();
AddonManager::triggerEvent('onTabChange', 'dashboard');
 ?>
<div class="headerbox purple-box">
<object class="logo" data="img/oxy.svg" type="image/svg+xml">
    <p>It would look nice, but your browser denies is! Want a nice look? Get newest Chrome Browser ;)</p>
</object>
<h1>Welcome to Oxymora!</h1>
<h3>A lightweight CMS you will love..</h3>
</div>
