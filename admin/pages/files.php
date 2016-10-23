<?php
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
require_once '../php/admin.php';
loginCheck();
AddonManager::triggerEvent(ADDON_EVENT_TABCHANGE, 'member');
 ?>
<div class="headerbox flat-box">
<h1>File-Manager</h1>
<h3>This is the place where your files live.</h3>
</div>
<div id="fileManager" class="noselect">
  <div class="path">
    <ul>
      <li><a href="#">My Files</a></li>>
      <li><a href="#">Test</a></li>>
      <li><a href="#">New Folder</a></li>
    </ul>
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
