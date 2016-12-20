<?php namespace template\oxymora_landingpage;
use KFall\oxymora\pageBuilder\template\iTemplatePlugin;
use KFall\oxymora\pageBuilder\template\iTemplatePluginSettings;

class FullscreenButton implements iTemplatePlugin, iTemplatePluginSettings{

  private $text,$link;

  private $htmlText = '
  <div class="fsContainer">
  <div class="getstartedContainer">
  <div class="ov"></div>
  <button>{text}</button>
  </div>
  </div>

  <script type="text/javascript">
  (function(){
    $(".getstartedContainer button").on("click", function(){
      $(".getstartedContainer").css("background", "rgb(235, 17, 240)");
      setTimeout(function(){window.location.href="{link}";}, 500);
    });
  })();
  </script>
  ';

  public function setSetting($key, $value){
    if(property_exists($this, $key)){
      $this->$key = $value;
    }
  }

  public function getHtml(){
    $html = $this->htmlText;
    $html = str_replace("{text}", $this->text, $html);
    $html = str_replace("{link}", $this->link, $html);
    return $html;
  }

}
