<?php namespace template\vuejs;
use KFall\oxymora\pageBuilder\template\iTemplatePlugin;
use KFall\oxymora\pageBuilder\template\iTemplatePluginSettings;

class Thumbnails implements iTemplatePlugin{

  public function getHtml(){
    return file_get_contents(__DIR__."/element.html");
  }

}
