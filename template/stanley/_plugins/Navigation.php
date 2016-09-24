<?php namespace template\stanley;
use KFall\oxymora\pageBuilder\template\iTemplatePlugin as iTemplatePlugin;
use KFall\oxymora\pageBuilder\template\iTemplateNavigation as iTemplateNavigation;

class Navigation implements iTemplatePlugin, iTemplateNavigation{

private $menuItems;
private $htmlSekeleton = '<ul class="nav navbar-nav navbar-right">{items}</ul>';


public function getHtml(){

  $list = "";
  foreach($this->menuItems as $item){
    $list .= '<li><a href="'.$item->url.'">'.$item->title.'</a></li>'."\n";
  }
  $html = str_replace("{items}", $list, $this->htmlSekeleton);
  return $html;

}

public function setMenuItems($items){
  $this->menuItems = $items;
}


}
