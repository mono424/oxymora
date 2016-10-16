<?php
use KFall\oxymora\database\modals\DBContent;
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();
AddonManager::triggerEvent('onTabChange', 'addons');

?>
<div class="headerbox purple-box">
  <h1>Addon-Manager</h1>
  <h3>To get more out of Oxymora, just make it advanced!</h3>
</div>


<?php
$addons = AddonManager::listAll();
foreach ($addons as $addon) {

  echo html_addonItem($addon);

 }
 ?>
