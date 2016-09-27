<?php namespace KFall\oxymora\pageBuilder;
use KFall\oxymora\database\modals\DBPages;
use KFall\oxymora\database\modals\DBPluginSettings;
use KFall\oxymora\pageBuilder\template\iTemplateNavigation;
use KFall\oxymora\pageBuilder\template\iTemplatePluginSettings;

class PageBuilder{

  // Template Data
  protected static $menuItems;
  protected static $templateVars;
  protected static $currentPageAreas;

  // Extra Data
  protected static $customPath = "";

  // Template
  public static $templateName;
  public static $templateDirectory;
  protected static $htmlSkeleton;

  public static function loadTemplate($name){
    $tempDir = ROOT_DIR."..\\template\\".$name;
    if(file_exists($tempDir)){
      self::$templateName = $name;
      self::$templateDirectory = $tempDir;
      self::$htmlSkeleton = file_get_contents($tempDir.'\\index.html');
      return true;
    }else{
      return false;
    }
  }


  public static function setCustomPath($path){
    self::$customPath = $path;
  }


  public static function getHtml(){
    $html = self::$htmlSkeleton;

    // Replace Placeholder
    $html = self::replaceAllPlaceholder($html);

    // Replace all Paths
    $html = self::replaceAllPaths($html);

    return $html;
  }


  protected function replaceAllPaths($html){
    // Find href Paths
    $paths = [];
    if(preg_match_all("/href.*?=.*?[\"\'](.*?)[\"\']/", $html, $matches, PREG_PATTERN_ORDER)){
      for($i = 0; $i < count($matches[0]); $i++){
        $paths[] = [
          'full' => $matches[0][$i],
          'path' => $matches[1][$i]
        ];
      }
    }
    // Find src Paths
    if(preg_match_all("/src.*?=.*?[\"\'](.*?)[\"\']/", $html, $matches, PREG_PATTERN_ORDER)){
      for($i = 0; $i < count($matches[0]); $i++){
        $paths[] = [
          'full' => $matches[0][$i],
          'path' => $matches[1][$i]
        ];
      }
    }

    // Replace Paths
    foreach($paths as $pathInfo){
      $full = $pathInfo['full'];
      $path = $pathInfo['path'];
      if(str_replace("://","",$path) === $path && strpos($path, "/") !== 0){
        $newFull = str_replace($path, self::$customPath."template/".self::$templateName."/".$path, $full);
        $html = str_replace($full, $newFull, $html);
      }
    }
    return $html;
  }

  protected static function replaceAllPlaceholder($html, $exceptions = false){
    $allPlaceholder = self::getPlaceholder($html);
    if($allPlaceholder){
      foreach($allPlaceholder as $placeholder){
        $replace = true;
        if($exceptions !== false){
          foreach($exceptions as $exception){
            if(self::checkPlaceholderType($placeholder, $exception)){
              $replace = false;
              break;
            }
          }
        }
        if($replace){$html = self::replacePlaceholder($html, $placeholder);}
      }
    }
    return $html;
  }


  protected static function replacePlaceholder($html, $placeholder){
    if(self::checkPlaceholderType($placeholder, "plugin")){
      // IS PLUGIN
      $value = self::getPlaceholderPlugin($placeholder);
    }elseif(self::checkPlaceholderType($placeholder, "area")){
      // IS AREA
      $value = self::getPlaceholderArea($placeholder);
    }elseif(self::checkPlaceholderType($placeholder, "static")){
      // IS VARIABLE
      $value = self::getPlaceholderVariable($placeholder);
    }
    $html = str_replace($placeholder,$value,$html);
    return $html;
  }

  protected static function getPlaceholderVariable($placeholder){
    $varName = self::getPlaceholderValue($placeholder);
    $value = (isset(self::$templateVars[$varName])) ? self::$templateVars[$varName] : "";
    return $value;
  }

  protected static function getPlaceholderArea($placeholder){
    $areaName = self::getPlaceholderValue($placeholder);
    $value = self::generateAreaContent($areaName);
    return $value;
  }

  protected static function getPlaceholderPlugin($placeholder){
    $pluginInfo = self::getPlaceholderValue($placeholder);
    if(is_array($pluginInfo)){
      $pluginName = $pluginInfo[0];
      $pluginId = $pluginInfo[1];
    }else{
      $pluginName = $pluginInfo;
      $pluginId = false;
    }

    $plugin = self::loadPlugin($pluginName);
    if($plugin instanceof iTemplateNavigation){
      $plugin->setMenuItems(self::$menuItems);
    }

    if($plugin instanceof iTemplatePluginSettings && $pluginId !== false){
      // Load Plugin Settings
      $settings = DBPluginsettings::getSettings($pluginId);
      foreach($settings as $setting){
        $plugin->setSetting($setting['settingkey'],$setting['settingvalue']);
      }
    }
    $value = $plugin->getHtml();
    return $value;
  }




  protected static function getPlaceholder($html, $filter = ""){
    $filter = ($filter == "") ? "" : $filter.":";
    if(preg_match_all("/(\{".$filter.".*?\})/", $html, $matches, PREG_PATTERN_ORDER)){
      return $matches[1];
    }else{
      return false;
    }
  }

  protected static function checkPlaceholderType($placeholder, $type){
    return preg_match("/\{".$type.":.*?\}/is", $placeholder);
  }

  protected static function getPlaceholderValue($placeholder){
    $placeholder = trim($placeholder, "{}");
    $placeholderInfo = split(":", $placeholder);
    if(count($placeholderInfo) >= 3){
      array_shift($placeholderInfo);
      return $placeholderInfo;
    }elseif(count($placeholderInfo) >= 2){
      return $placeholderInfo[1];
    }else{
      return "";
    }
  }


  protected function generateAreaContent($area){
    // todo: load different areas
    $html = self::$currentPageAreas[$area]['content'];

    // Replace Placeholder
    $html = self::replaceAllPlaceholder($html);

    return $html;
  }


  protected static function loadPlugin($name){
    $file = self::$templateDirectory."\\_plugins\\$name\\$name.php";
    $class = "template\\".self::$templateName."\\$name";
    if(file_exists($file)){
      require_once $file;
      return new $class;
    }
    return false;
  }

  public static function setMenuItems($items){
    self::$menuItems = $items;
  }

  public static function setTemplateVars($vars){
    self::$templateVars = $vars;
  }

  public static function loadCurrentPage($page){
    // Select Menu Item
    foreach(self::$menuItems as $item){
      if(strtolower($page) == strtolower($item->title)){
        $item->selected = true;
      }
    }
    self::$currentPageAreas = DBPages::getPageAreas($page);
  }


}
