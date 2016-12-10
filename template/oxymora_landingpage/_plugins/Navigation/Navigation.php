<?php namespace template\oxymora_landingpage;
use KFall\oxymora\pageBuilder\template\iTemplatePlugin;
use KFall\oxymora\pageBuilder\template\iTemplateNavigation;

class Navigation implements iTemplatePlugin, iTemplateNavigation{

private $menuItems;
private $htmlSekeleton = '<nav><ul>{items}</ul></nav>';


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
