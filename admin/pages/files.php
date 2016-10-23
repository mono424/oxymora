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
<div class="file-manager">
  <form class="search">
    <input class="oxinput" type="text" placeholder="Search">
  </form>
  <div class="dirs">
    <div class="dir active">
      <i class="fa fa-folder" aria-hidden="true"></i></i>
      <h3>Images</h3>
    </div>
  </div>
  <div class="files">
    <div class="file">
      <canvas class="preview"></canvas>
      <h3><i class="fa fa-file-image-o" aria-hidden="true"></i> testfile.png</h3>
    </div>
  </div>
</div>
