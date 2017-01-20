<?php

function html_userItem($id, $username, $email, $img, $groupid, $color){
  return '<div data-id="'.htmlspecialchars($id).'" data-email="'.htmlspecialchars($email).'" data-username="'.htmlspecialchars($username).'" data-group="'.htmlspecialchars($groupid).'" class="user-item">
  <div class="image" style="background-image:url('.htmlspecialchars($img).');"></div>
  <div class="info">
  <h3 style="background:'.htmlspecialchars($color).';">'.htmlspecialchars($username).'</h3>
  <button class="edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
  <button class="delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
  </div>
  </div>';
}

function html_groupItem($groupid,$groupname,$color){
  return '<div class="group-item" data-groupid="'.htmlspecialchars($groupid).'">
  <div class="actions">
  <button type="button" data-action="premission"><i class="fa fa-key" aria-hidden="true"></i></button>
  <button type="button" data-action="edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
  <button type="button" data-action="delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
  </div>
  <div class="info">
  <i style="background:'.htmlspecialchars($color).';" class="fa fa-users" aria-hidden="true"></i>
  <span>'.htmlspecialchars($groupname).'</span>
  </div>
  </div>';
}

function html_navItem($display, $id, $title, $url){
  return '<div data-display="'.htmlspecialchars($display).'" data-id="'.htmlspecialchars($id).'" class="navitem">
  <div class="title">'.htmlspecialchars($title).'</div>
  <div class="url">'.htmlspecialchars($url).'</div>
  <div class="buttonbar">
  <button data-action="displayUp" type="button"><i class="fa fa-arrow-up" aria-hidden="true"></i></button>
  <button data-action="displayDown" type="button"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
  <button data-action="edit" type="button"><i class="fa fa-pencil" aria-hidden="true"></i></button>
  <button data-action="remove" class="red" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
  </div>
  </div>';
}

function html_pageItem($url){
  return '<div data-page="'.htmlspecialchars($url).'" class="pageitem">
  <button class="deletePageButton" type="button" title="Delete"><i class="fa fa-times" aria-hidden="true"></i></button>
  <button class="renamePageButton" type="button" title="Rename"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
  <button class="navPageButton" type="button" title="Navigation-Item"><i class="fa fa-bars" aria-hidden="true"></i></button>
  <button class="openPageButton" type="button" title="Open in new Tab"><i class="fa fa-external-link" aria-hidden="true"></i></button>
  <div class="icon"><i class="fa fa-chrome" aria-hidden="true"></i></div>
  <div class="title">'.htmlspecialchars($url).'</div>
  </div>
  ';
}


function html_addonItem($addon){
  return '<div class="addon-item" data-name="'.htmlspecialchars($addon['name']).'">
  <h2>'.htmlspecialchars($addon['config']['menuentry']['displayname']).'</h2>
  <h3>'.htmlspecialchars($addon['config']['menuentry']['description']).'</h3>
  '.(($addon['config']['exportable']) ? '<button onclick="addonManager.downloadAddon(this,\''.htmlspecialchars($addon['name']).'\')" class="downloadAddon"><i class="fa fa-download" aria-hidden="true"></i></button>' : '').'
  <button onclick="addonManager.buttonHandler(this,\''.htmlspecialchars($addon['name']).'\', this.dataset.action)" class="oxbutton" data-action="'.(($addon['installed'] !== false) ? ((htmlspecialchars($addon['installed']['active'])) ? "disable" : "enable") : "install").'">'.
  (($addon['installed'] !== false) ? ((htmlspecialchars($addon['installed']['active'])) ? "Disable" : "Enable") : "Install").
  '</button>
  </div>
  ';
}

function html_error($text){
  return '<div class="fs-error">
    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
    <p>
    '.$text.'
    </p>
  </div>';
}
