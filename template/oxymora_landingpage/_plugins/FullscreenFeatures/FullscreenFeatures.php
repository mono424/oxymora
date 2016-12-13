<?php namespace template\oxymora_landingpage;
use KFall\oxymora\pageBuilder\template\iTemplatePlugin;
use KFall\oxymora\pageBuilder\template\iTemplatePluginSettings;

class FullscreenFeatures implements iTemplatePlugin, iTemplatePluginSettings{

  private $slides;

  private $htmlText = '
  <div class="fsContainer">
  <div class="slideContainer">
  <div class="background"></div>
  {slides}
  </div>
  </div>



  <script type="text/javascript">
  (function(){
    let slideContainer = $(\'.slideContainer\');
    let slides = slideContainer.find(\'.slide\');
    let slidesCount = slides.length;
    let minTop = 0;
    createHeight();

    $(window).on(\'scroll\', function(e){
      // Slide Position
      let screenHeight = $(window).height();
      let containerHeight = slideContainer.outerHeight();
      let slidesHeight = $(slides[0]).outerHeight();
      let maxTop = containerHeight - slidesHeight;
      let scrollPos = $(window).scrollTop();
      let containerScroll = scrollPos - slideContainer.offset().top;
      let slidePos = containerScroll + (screenHeight - slidesHeight) / 2;
      slidePos = (slidePos < minTop) ? minTop  : slidePos;
      slidePos = (slidePos > maxTop) ? maxTop  : slidePos;
      slides.css(\'top\', slidePos+\'px\');

      // Slide Opacity
      let percent = 100 / maxTop * slidePos;
      slideVisible(percent);
    });


    function slideVisible(percent){
      let info = [];

      slides.each(function(){
        let distance = Math.abs((percent - $(this).data(\'percent\')));
        let opc = 1 / 100 * (100 - distance);
        $(this).css(\'opacity\', opc);
        info.push({distance, element:$(this)});
      });

      info.sort(function(a, b){
        if(a.distance < b.distance) return -1;
        if(a.distance > b.distance) return 1;
        return 0;
      });

      for(let i=0;i<info.length;i++){
        info[i].element.css(\'z-index\', info.length - i);
        if(i == 0){info[i].element.find(\'div\').fadeIn(100);}else{
          info[i].element.find(\'div\').fadeOut(100);
        }
      }
    }

    function createHeight(nr){
      let size = 0;
      slides.each(function(){
        size += $(this).outerHeight();
      });
      slideContainer.css(\'height\', size+"px");
    }
  })();
  </script>
  ';

  public function setSetting($key, $value){
    $this->$key = $value;
  }

  public function getHtml(){
    $html = $this->htmlText;
    $html = str_replace("{slides}", $this->generateTitlesHtml(), $html);
    return $html;
  }

  private function generateTitlesHtml(){
    $html = "";
    $total = count($this->slides);
    $i = 0;
    foreach($this->slides as $slide){
      $percent = 100 / ($total-1) * $i;
      $html .= '<div class="slide" data-percent="'.$percent.'">
      <img class="image-shadow" src="'.$slide['image'].'" alt="">
      <img class="image" src="'.$slide['image'].'" alt="">
      <div>'.$slide['content'].'</div>
      <div style="clear:both;"></div>
      </div>';
      $i++;
    }
    return $html;
  }
}
