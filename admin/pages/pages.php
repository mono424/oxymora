<?php
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\memberSystem\MemberSystem;
require_once '../php/admin.php';
loginCheck();
 ?>
<div class="headerbox purple-box">
<h1>Pages</h1>
<h3>Your website starts here, manage your pages..</h3>
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
          ?>
          <div data-display="<?php echo $navItem->display; ?>" data-id="<?php echo $navItem->id; ?>" class="navitem">
            <div class="title">
              <?php echo $navItem->title; ?>
            </div>
            <div class="url">
              <?php echo $navItem->url; ?>
            </div>
            <div class="buttonbar">
              <button data-action="displayUp" type="button"><i class="fa fa-arrow-up" aria-hidden="true"></i></button>
              <button data-action="displayDown" type="button"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
              <button data-action="edit" type="button"><i class="fa fa-pencil" aria-hidden="true"></i></button>
              <button data-action="delete" class="red" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
            </div>
          </div>
        <?php
        }
       ?>
    </div>

    <div class="tab" data-tab="pages">
      Global Tab
    </div>


  </div>
</div>
