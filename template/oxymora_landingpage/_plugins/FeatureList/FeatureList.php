<?php namespace template\oxymora_landingpage;
use KFall\oxymora\pageBuilder\template\iTemplatePlugin;
use KFall\oxymora\pageBuilder\template\iTemplatePluginSettings;

class FeatureList implements iTemplatePlugin, iTemplatePluginSettings{

  private $slides, $lowPerformance;

  private $htmlText = '
  <div class="fsContainer">
  <div class="slideContainer">
  <div class="background"></div>
  {slides}
  </div>
  </div>



  <script type="text/javascript">
  {script}
  </script>
  ';

  private $script_high = '
    setTimeout(function(){
    $(window).on("load",function(){
      let didScroll = false;
      let slideContainer = $(\'.slideContainer\');
      let background = $(\'.slideContainer .background\');
      let slides = slideContainer.find(\'.slide\');

      console.log(123);
      $(slides[0]).css("display", "inline-block");
      let slidesHeight = slides[0].offsetHeight;console.log(slides[0].offsetHeight)
      let heightBuffer = slidesHeight * 0.2;
      setHeight();

      $(window).on(\'resize\', function(e){
        slidesHeight = slides[0].offsetHeight;console.log(slides[0].offsetHeight)
        heightBuffer = slidesHeight * 0.2;
        setHeight();
      });

      $(window).on(\'scroll\', function(){

        // Slide Position
        let screenHeight = $(window).height();
        let scrollPos = $(window).scrollTop();
        let containerScroll = scrollPos - slideContainer.offset().top;
        let customScroll = containerScroll + screenHeight / 2 - slidesHeight / 2;
        customScroll = (customScroll > heightBuffer) ? heightBuffer : customScroll;
        customScroll = (customScroll < 0) ? 0 : customScroll;
        slides.css("top",customScroll + "px");

      });


      function setHeight(){
        slideContainer.css(\'height\', (slidesHeight+heightBuffer)+"px");
        slideContainer.css(\'margin-top\', "-"+heightBuffer+"px");
        background.css(\'top\', (heightBuffer)+"px");
      }
    });
  }, 0);
  ';

  private $script_low = '
    setTimeout(function(){
    $(window).on("load",function(){
      let didScroll = false;
      let slideContainer = $(\'.slideContainer\');
      let background = $(\'.slideContainer .background\');
      let slides = slideContainer.find(\'.slide\');

      $(slides[0]).css("display", "inline-block");
      let slidesHeight = slides[0].offsetHeight;console.log(slides[0].offsetHeight)
      let heightBuffer = slidesHeight * 0.2;
      setHeight();

      $(window).on(\'resize\', function(e){
        slidesHeight = slides[0].offsetHeight;console.log(slides[0].offsetHeight)
        heightBuffer = slidesHeight * 0.2;
        setHeight();
      });

      $(window).on(\'scroll\', doThisStuffOnScroll);

      function doThisStuffOnScroll() {
        didScroll = true;
      }

      setInterval(function() {
        if(didScroll) {
          // Slide Position
          let screenHeight = $(window).height();
          let scrollPos = $(window).scrollTop();
          let containerScroll = scrollPos - slideContainer.offset().top;
          let customScroll = containerScroll + screenHeight / 2 - slidesHeight / 2;
          customScroll = (customScroll > heightBuffer) ? heightBuffer : customScroll;
          customScroll = (customScroll < 0) ? 0 : customScroll;
          slides.css("top",customScroll + "px");
        }
      }, 100);

      function setHeight(){
        slideContainer.css(\'height\', (slidesHeight+heightBuffer)+"px");
        slideContainer.css(\'margin-top\', "-"+heightBuffer+"px");
        background.css(\'top\', (heightBuffer)+"px");
      }
    });
  }, 0);
  ';






  public function setSetting($key, $value){
    if(property_exists($this, $key)){
      $this->$key = $value;
    }
  }

  public function getHtml(){
    $html = $this->htmlText;
    $html = str_replace("{slides}", $this->generateTitlesHtml(), $html);
    $html = str_replace("{script}", $this->generateScript(), $html);
    return $html;
  }

  private function generateScript(){
    if($this->lowPerformance){
      return $this->script_low;
    }else{
      return $this->script_high;
    }
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
