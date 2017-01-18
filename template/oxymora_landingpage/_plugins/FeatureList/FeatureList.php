<?php namespace template\oxymora_landingpage;
use KFall\oxymora\pageBuilder\template\iTemplatePlugin;
use KFall\oxymora\pageBuilder\template\iTemplatePluginSettings;

class FeatureList implements iTemplatePlugin, iTemplatePluginSettings{

  private $htmlContainer, $htmlItem, $slides;

  public function __construct(){
    $this->htmlContainer = file_get_contents(__DIR__."/element.html");
    $this->htmlItem = file_get_contents(__DIR__."/item.html");
  }

  public function setSetting($key, $value){
    if(property_exists($this, $key)){
      $this->$key = $value;
    }
  }

  public function getHtml(){
    $content = "";
    foreach($this->slides as $slide){
      $item = $this->htmlItem;
      preg_match_all('/{(.*?)}/', $item, $matches);
      foreach($matches[1] as $match){
        $item = str_replace("{{$match}}", $slide[$match], $item);
      }
      $content .= $item;
    }

    $html = str_replace("{content}", $content, $this->htmlContainer);
    return $html;
  }

}
