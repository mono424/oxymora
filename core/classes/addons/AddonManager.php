<?php namespace KFall\oxymora\addons;
use KFall\oxymora\database\modals\DBAddons;

class AddonManager{

  public static function triggerEvent($event, $args, $specificAddon = false){
    $addons = self::listAll();
    foreach($addons as $addon){
      if($addon['config']['type'] == ADDON_ADDON && (!$specificAddon || $specificAddon == $addon['name'])){
        $addonObj = self::load($addon['file']);
        if($addonObj){
          $addonObj->$event($args);
        }
      }
    }
  }

  public static function listAll($showHidden = true, $showNotInstalled = true, $showNotActive = true){
    $mainpath = ROOT_DIR."..\\addons";
    $all = scandir($mainpath);
    $dirs = [];
    foreach($all as $item){
      $path = $mainpath."\\".$item;
      $file = $path."\\$item.php";
      if(strlen(trim($item, ".")) > 0 && is_dir($path) && file_exists($file)){
        $assoc['name'] = $item;
        $assoc['file'] = $file;
        $assoc['installed'] = DBAddons::getInfo($item);
        $assoc['config'] = json_decode(file_get_contents($path."\\config.json"), true);
        $assoc['icon'] = (file_exists($path."\\icon.png"));
        $assoc['iconUrl'] = ($assoc['icon']) ? "addons/".$item."/icon.png" : null ;
        if(($showHidden || $assoc['config']['menuentry']['visible']) && ($showNotInstalled || $assoc['installed'] !== false)  && ($showNotActive || $assoc['installed']['active'] != false)){
          $dirs[] = $assoc;
        }
      }
    }
    return $dirs;
  }

  public static function find($name){
    $templatePath = ROOT_DIR."..\\addons";
    $all = scandir($templatePath);
    foreach($all as $item){
      $path = $templatePath."\\".$item;
      $file = $path."\\$item.php";
      if(strlen(trim($item, ".")) > 0 && is_dir($path) && file_exists($file)){
        if($item == $name){
          $assoc['name'] = $item;
          $assoc['file'] = $file;
          $assoc['installed'] = DBAddons::getInfo($item);
          $assoc['config'] = json_decode(file_get_contents($path."\\config.json"), true);
          $assoc['icon'] = (file_exists($path."\\icon.png"));
          $assoc['iconUrl'] = ($assoc['icon']) ? "addons/".$item."/icon.png" : null;
          return $assoc;
        }
      }
    }
    return false;
  }

  public static function load($name){
    $is_path = file_exists($name) ? true : false;
    $file = ($is_path) ? $name : ((($addon = self::find($name)) !== false) ? $addon['file'] : false);
    $name = ($is_path) ? basename($name, ".php") : $name;
    if($file !== false){
      require_once $file;
      $obj = new $name;
      if(!$obj instanceof iAddon && !$obj instanceof iWidget){
        return false;
      }
      return $obj;
    }
    return false;
  }

  public static function install($name){
    if(!DBAddons::install($name)){return false;}
    self::triggerEvent('onInstallation', null, $name);
    return true;
  }

  public static function disable($name){
    if(!DBAddons::disable($name)){return false;}
    self::triggerEvent('onDisable', null, $name);
    return true;
  }

  public static function enable($name){
    if(!DBAddons::enable($name)){return false;}
    self::triggerEvent('onEnable', null, $name);
    return true;
  }


}
