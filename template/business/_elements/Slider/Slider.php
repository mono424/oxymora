<?php namespace template\business;
use KFall\oxymora\pageBuilder\template\iTemplateElement;
use KFall\oxymora\pageBuilder\template\iTemplateElementSettings;

class Slider implements iTemplateElement, iTemplateElementSettings{

  private $info = [];

  private $imageItem = '<div class="item">
  <img class="img-responsive img-full" src="{url}" alt="">
  </div>';

  private $htmlSlider = '<div class="row">
  <div class="box">
  <div class="col-lg-12 text-center">
  <div id="carousel-example-generic" class="carousel slide">
  <!-- Indicators -->
  <ol class="carousel-indicators hidden-xs">
  <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
  <li data-target="#carousel-example-generic" data-slide-to="1"></li>
  <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
  <div class="item active">
  {images}
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
  <span class="icon-prev"></span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
  <span class="icon-next"></span>
  </a>
  </div>
  <h2 class="brand-before">
  <small></small>
  </h2>
  <h1 class="brand-name">{title}</h1>
  <hr class="tagline-divider">
  <h2>
  <small>
  {subtitle}
  </small>
  </h2>
  </div>
  </div>
  </div>
  ';

  public function setSetting($key, $value){
    $this->info[$key] = $value;
  }

  public function getHtml(){
    $html = $this->htmlSlider;
    foreach($this->info as $key => $value){
      $tempHtml = "";
      if($key == 'images'){
        foreach($value as $img){
          $tempHtml .= str_replace("{url}", $img['file'], $this->imageItem);
        }
      }else{
        $tempHtml = $value;
      }

      $html = str_replace("{{$key}}", $tempHtml, $html);
    }
    return $html;
  }


}
