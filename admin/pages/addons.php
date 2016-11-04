<?php
use KFall\oxymora\database\modals\DBContent;
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();
AddonManager::triggerEvent(ADDON_EVENT_TABCHANGE, 'addons');

?>
<div class="dropzone">

  <!-- <div class="headerbox flat-box">
  <h1>Addon-Manager</h1>
  <h3>To get more out of Oxymora, just make it advanced!</h3>
</div> -->


<div class="tabContainer light">
  <ul>
    <li><a data-tab="addons">Addons</a></li>
    <li><a data-tab="market">Addon Market</a></li>
  </ul>
  <div class="tabContent">



    <div class="tab" data-tab="addons">
      <div class="dataContainer" id="pageContainer">
        <?php
        $addons = AddonManager::listAll();
        foreach ($addons as $addon) {

          echo html_addonItem($addon);

        }
        ?>
      </div>
    </div>

    <div class="tab" data-tab="market">
      <div class="dataContainer" id="pageContainer">
        <center>Cooming Soon</center>
      </div>
    </div>

  </div>
</div>

<div class="overlay drop">Drop to install</div>
<div class="overlay upload">Uploading...</div>
</div>

<script type="text/javascript">
  addonManager.fileDragInit(document.querySelector('.dropzone'));
</script>
