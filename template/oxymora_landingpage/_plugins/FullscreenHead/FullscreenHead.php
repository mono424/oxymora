<?php namespace template\oxymora_landingpage;
use KFall\oxymora\pageBuilder\template\iTemplatePlugin;
use KFall\oxymora\pageBuilder\template\iTemplatePluginSettings;

class FullscreenHead implements iTemplatePlugin, iTemplatePluginSettings{

  private $img;
  private $title;

  private $htmlText = '
  <header style="background: url({img}) no-repeat top center fixed;">
  <div class="ov"></div>
  {titles}

  <!-- <div class="arrow-down">
  <i class="fa fa-angle-double-down" aria-hidden="true"></i>
  </div> -->
  </header>
  <script>
  (function(){

    // Threading ;)
    setTimeout(function(){
      let head = $("header");
      let textelements = $("header h1");
      let currentItem = 0;

      if(textelements.length > 1){

        showNextLine();

        function showNextLine(){
          currentItem = (currentItem >= textelements.length) ? 0 : currentItem;

          $(textelements).fadeOut(400);
          $(textelements[currentItem]).fadeIn(800, function(){
            setTimeout(function(){
              currentItem++;
              showNextLine();
            }, $(textelements[currentItem]).data("time"));
          });

        }

      }
    }, 0);
  })();
  </script>
  ';

  public function setSetting($key, $value){
    $this->$key = $value;
  }

  public function getHtml(){
    $html = $this->htmlText;
    $html = str_replace("{img}", $this->img, $html);
    $html = str_replace("{titles}", $this->generateTitlesHtml(), $html);
    return $html;
  }

  private function generateTitlesHtml(){
    $html = "";
    foreach($this->titles as $title){
      $html .= "<h1 data-time=\"".($title['time'] * 1000)."\">".$title['title']."</h1>\n";
    }
    return $html;
  }
}
