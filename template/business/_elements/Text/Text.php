<?php namespace template\business;
use KFall\oxymora\pageBuilder\template\iTemplateElement;
use KFall\oxymora\pageBuilder\template\iTemplateElementSettings;

class Text implements iTemplateElement, iTemplateElementSettings{

  private $title = 'Beautiful boxes
  <strong>to showcase your content</strong>';

  private $content = "<p>Use as many boxes as you like, and put anything you want in them! They are great for just about anything, the sky's the limit!</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc placerat diam quis nisl vestibulum dignissim. In hac habitasse platea dictumst. Interdum et malesuada fames ac ante ipsum primis in faucibus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
  ";

  private $htmlText = '        <div class="row">
  <div class="box">
  <div class="col-lg-12">
  <hr>
  <h2 class="intro-text text-center">{title}
  </h2>
  <hr>
  {content}
  </div>
  </div>
  </div>';

  public function setSetting($key, $value){
    if($key === "title"){
      $this->title = $value;
    }elseif($key === "content"){
      $this->content = $value;
    }
  }

  public function getHtml(){
    $html = $this->htmlText;
    $html = str_replace("{title}", $this->title, $html);
    $html = str_replace("{content}", $this->content, $html);
    return $html;
  }


}
