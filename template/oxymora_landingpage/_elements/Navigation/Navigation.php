<?php namespace template\oxymora_landingpage;
use KFall\oxymora\pageBuilder\template\iTemplateElement;
use KFall\oxymora\pageBuilder\template\iTemplateNavigation;

class Navigation implements iTemplateElement, iTemplateNavigation{

private $menuItems;
private $htmlSekeleton = '<button class="navToggle"><i class="fa fa-bars" aria-hidden="true"></i></button>
                          <nav><ul>{items}</ul></nav>
                          <script>
                          $(\'.navToggle\').on(\'click\', function(){
                            $(\'nav\').toggleClass(\'visible\');
                            $(this).toggleClass(\'visible\');
                          });
                          </script>';

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
