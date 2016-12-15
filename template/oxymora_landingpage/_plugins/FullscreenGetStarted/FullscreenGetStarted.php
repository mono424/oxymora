<?php namespace template\oxymora_landingpage;
use KFall\oxymora\pageBuilder\template\iTemplatePlugin;
use KFall\oxymora\pageBuilder\template\iTemplatePluginSettings;

class FullscreenGetStarted implements iTemplatePlugin, iTemplatePluginSettings{

  private $text;

  private $htmlText = '
  <div class="fsContainer">
  <div class="getstartedContainer">
  <div class="ov"></div>
  <button>{text}</button>
  </div>
  </div>



  <script type="text/javascript">
  (function(){

  })();
  </script>
  ';

  public function setSetting($key, $value){
    $this->$key = $value;
  }

  public function getHtml(){
    $html = $this->htmlText;
    $html = str_replace("{text}", $this->text, $html);
    return $html;
  }

}
