<?php namespace KFall\oxymora\addons;
use KFall\oxymora\addons\AddonManager;

class Widget{

  public $id;
  public $widget;
  public $display;

  public function info(){
    return AddonManager::find($this->widget);
  }

}




 ?>
