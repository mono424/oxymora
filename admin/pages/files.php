<?php
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
require_once '../php/admin.php';
loginCheck();
AddonManager::triggerEvent(ADDON_EVENT_TABCHANGE, 'member');
 ?>
<!-- <div class="headerbox flat-box">
<h1>File-Manager</h1>
<h3>This is the place where your files live.</h3>
</div> -->
<div id="fileManager" class="noselect">
  <div class="path">
    <ul>
    </ul>
    <div class="trash">
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
