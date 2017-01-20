<?php namespace template\oxymora_landingpage;
use KFall\oxymora\pageBuilder\template\iTemplateElement;
use KFall\oxymora\database\modals\DBStatic;

class Footer implements iTemplateElement{

private $menuItems;
private $htmlSekeleton = "<footer>{copy}</footer>";


public function getHtml(){
  $vars = DBStatic::getVars();
  $html = str_replace("{copy}", "Copyright 2017 ".$vars['author'], $this->htmlSekeleton);
  return $html;
}


}
