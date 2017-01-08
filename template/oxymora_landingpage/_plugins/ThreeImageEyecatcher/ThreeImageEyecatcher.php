<?php namespace template\oxymora_landingpage;
use KFall\oxymora\pageBuilder\template\iTemplatePlugin;
use KFall\oxymora\pageBuilder\template\iTemplatePluginSettings;

class ThreeImageEyecatcher implements iTemplatePlugin, iTemplatePluginSettings{

  private $info = [];

  public function __construct(){
    $this->html = file_get_contents(__DIR__."/element.html");
  }

  public function setSetting($key, $value){
    $this->info[$key] = $value;
  }

  public function getHtml(){
    $html = $this->html;
    preg_match_all('/{(.*?)}/', $this->html, $matches);
    foreach($matches[1] as $match){
      if(isset($this->info[$match])){
        $html = str_replace("{{$match}}", $this->info[$match], $html);
      }else{
        $html = str_replace("{{$match}}", "", $html);
      }
    }
    return $html;

  }

}
