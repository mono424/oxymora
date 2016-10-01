<?php
use KFall\oxymora\database\modals\DBPages;
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\memberSystem\MemberSystem;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();
 ?>
<div class="headerbox purple-box">
<h1>Pages n' Navigation</h1>
<h3>Actually this is what your website is made of.</h3>
</div>

<div class="tabContainer">
  <ul>
    <li><a data-tab="navigation">Navigation</a></li>
    <li><a data-tab="pages">Pages</a></li>
  </ul>
  <div class="tabContent">

    <div class="tab" data-tab="navigation">
      <?php
        $navItems = DBNavigation::getItems();
        foreach($navItems as $navItem){
          echo html_navItem($navItem->display, $navItem->id, $navItem->title, $navItem->url);
        }
       ?>
       <button id="addNavButton" class="oxbutton-float" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
    </div>

    <div class="tab cf" data-tab="pages">
      <?php
        $pages = DBPages::getPages();
        foreach($pages as $page){
      ?>
      <div data-page="<?php echo $page['url']; ?>" class="pageitem">
        <div class="icon"><i class="fa fa-chrome" aria-hidden="true"></i></div>
        <div class="title"><?php echo $page['url']; ?></div>
      </div>
      <?php } ?>

      <button id="addPageButton" class="oxbutton-float" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
    </div>


  </div>
</div>
