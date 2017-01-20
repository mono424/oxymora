<?php namespace template\oxymora_landingpage;
use KFall\oxymora\pageBuilder\template\iTemplateElement;
use KFall\oxymora\pageBuilder\template\iTemplateElementSettings;

class FlatHeader implements iTemplateElement, iTemplateElementSettings{

  private $img;
  private $title;

  private $htmlText = '
  <header class="flat">
  <div class="img" style="background-image: url({img});"></div>
  </header>
  ';

  public function setSetting($key, $value){
    $this->$key = $value;
  }

  public function getHtml(){
    $html = $this->htmlText;
    $html = str_replace("{img}", $this->encode($this->img), $html);
    return $html;
  }

  public function encode($url){
    return str_replace(" ", "%20", $url);
  }

}
