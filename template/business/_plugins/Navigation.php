<?php namespace template\business;
use KFall\oxymora\pageBuilder\template\iTemplatePlugin as iTemplatePlugin;
use KFall\oxymora\pageBuilder\template\iTemplateNavigation as iTemplateNavigation;

class Navigation implements iTemplatePlugin, iTemplateNavigation{

private $menuItems;
private $htmlSekeleton = '<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- navbar-brand is hidden on larger screens, but visible when the menu is collapsed -->
            <a class="navbar-brand" href="index.html">Business Casual</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
              {items}
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>';


public function getHtml(){

  $list = "";
  foreach($this->menuItems as $item){
    $list .= '<li><a href="'.$item->url.'">'.$item->title.'</a></li>'."\n";
  }
  $html = str_replace("{items}", $list, $this->htmlSekeleton);
  return $html;

}

public function setMenuItems($items){
  $this->menuItems = $items;
}


}
