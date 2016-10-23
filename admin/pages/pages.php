<?php
use KFall\oxymora\database\modals\DBContent;
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();
AddonManager::triggerEvent(ADDON_EVENT_TABCHANGE, 'pages');
?>

<div class="headerbox flat-box">
  <h1>Pages n' Navigation</h1>
  <h3>Actually this is what your website is made of.</h3>
</div>

<div class="tabContainer">
  <ul>
    <li><a data-tab="pages">Pages</a></li>
    <li><a data-tab="navigation">Navigation</a></li>
  </ul>
  <div class="tabContent">

    <div class="tab" data-tab="pages">
      <div class="dataContainer" id="pageContainer">
        <?php
        $pages = DBContent::getPages();
        foreach($pages as $page){
          echo html_pageItem($page['url']);
        } ?>
        <div class="clear:both"></div>

        <button id="addPageButton" class="oxbutton-float" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
      </div>
    </div>


    <div class="tab" data-tab="navigation">
      <div class="dataContainer" id="navContainer">
        <?php
        $navItems = DBNavigation::getItems();
        foreach($navItems as $navItem){
          echo html_navItem($navItem->display, $navItem->id, $navItem->title, $navItem->url);
        }
        ?>
        <button id="addNavButton" class="oxbutton-float" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
      </div>
    </div>


  </div>
</div>
