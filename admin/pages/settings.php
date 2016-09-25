<?php
use KFall\oxymora\memberSystem\MemberSystem;
require_once '../php/admin.php';
loginCheck();
 ?>
<div class="headerbox purple-box">
<h1>Settings</h1>
<h3>Settings all over the place, but be careful!</h3>
</div>

<div class="tabContainer">
  <ul>
    <li><a data-tab="info">Info</a></li>
    <li><a data-tab="global">Global</a></li>
    <li><a data-tab="template">Template</a></li>
    <li><a data-tab="account">Account</a></li>
  </ul>
  <div class="tabContent">

    <div class="tab" data-tab="info">
      Info Tab
    </div>

    <div class="tab" data-tab="global">
      Global Tab
    </div>

    <div class="tab" data-tab="template">
      Template Tab
    </div>

    <div class="tab" data-tab="account">
      Account Tab
    </div>

  </div>
</div>
