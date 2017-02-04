<?php namespace template\oxymora_landingpage;
use KFall\oxymora\pageBuilder\template\iTemplateElement;
use KFall\oxymora\pageBuilder\template\iTemplateElementSettings;

class Downloader implements iTemplateElement, iTemplateElementSettings{

  private $html, $file, $text, $thanks_text, $seconds;

  public function __construct(){
    $this->html = file_get_contents(__DIR__."/element.html");
  }

  public function setSetting($key, $value){
    if(property_exists($this, $key)){
      $this->$key = $value;
    }
  }

  public function getHtml(){
    $html = $this->html;
    preg_match_all('/{(.*?)}/', $this->html, $matches);
    foreach($matches[1] as $match){
      if(property_exists($this, $match)){
        $html = str_replace("{{$match}}", $this->$match, $html);
      }
    }
    return $html;

  }

}
