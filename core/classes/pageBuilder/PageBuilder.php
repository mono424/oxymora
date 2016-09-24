<?php namespace KFall\oxymora\pageBuilder;
use KFall\oxymora\database\modals\DBPages;
use KFall\oxymora\database\modals\DBPluginSettings;
use KFall\oxymora\pageBuilder\template\iTemplateNavigation;
use KFall\oxymora\pageBuilder\template\iTemplatePluginSettings;

class PageBuilder{

  // Template Data
  private static $menuItems;
  private static $templateVars;
  private static $currentPage;

  // Template
  public static $templateName;
  public static $templateDirectory;
  private static $htmlSkeleton;

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



  public static function getHtml(){
    $html = self::$htmlSkeleton;

    // Replace Placeholder
    $html = self::replaceAllPlaceholder($html);

    // Replace all Paths
    $html = self::replaceAllPaths($html);

    return $html;
  }


  private function replaceAllPaths($html){
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
        $newFull = str_replace($path, "template/".self::$templateName."/".$path, $full);
        $html = str_replace($full, $newFull, $html);
      }
    }
    return $html;
  }

  private static function replaceAllPlaceholder($html){
    if(preg_match_all("/\{(.*?)\}/", $html, $matches, PREG_PATTERN_ORDER)){
      foreach($matches[1] as $match){
        $html = self::replacePlaceholder($html, $match);
      }
    }
    return $html;
  }


  private static function replacePlaceholder($html, $placeholder){
    if(str_replace("plugin:", "", $placeholder) !== $placeholder){
      // IS PLUGIN
      $pluginInfo = split(":", $placeholder);
      $pluginName = $pluginInfo[1];
      $pluginId = (count($pluginInfo) == 3) ? $pluginInfo[2] : false;

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

      $html = str_replace('{'.$placeholder.'}',$plugin->getHtml(),$html);
    }elseif($placeholder == "body"){
      $html = str_replace('{'.$placeholder.'}',self::generatePageContent(),$html);
    }else{
      // IS VARIABLE
      $value = (isset(self::$templateVars[$placeholder])) ? self::$templateVars[$placeholder] : "";
      $html = str_replace('{'.$placeholder.'}',$value,$html);
    }
    return $html;
  }

  private function generatePageContent(){
    $html = self::$currentPage['content'];

    // Replace Placeholder
    $html = self::replaceAllPlaceholder($html);

    return $html;
  }


  private static function loadPlugin($name){
    $file = self::$templateDirectory."\\_plugins\\$name.php";
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
    self::$currentPage = DBPages::getPage($page);
  }


}
