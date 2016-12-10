<?php namespace template\oxymora_landingpage;
use KFall\oxymora\pageBuilder\template\iTemplatePlugin;
use KFall\oxymora\pageBuilder\template\iTemplateNavigation;

class FullscreenHead implements iTemplatePlugin{

  private $img;
  private $title;

  private $htmlText = '
  <header>
  <h1>{title}</h1>
  <div class="ov" style="background-image: url({img});"></div>

  <div class="arrow-down">
  <i class="fa fa-angle-double-down" aria-hidden="true"></i>
  </div>
  </header>
  ';

  public function setSetting($key, $value){
    echo $value;
    $this->$key = $value;
  }

  public function getHtml(){
    $html = $this->htmlText;
    $html = str_replace("{img}", $this->img, $html);
    $html = str_replace("{title}", $this->title, $html);
    return $html;
  }

}
