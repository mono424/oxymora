<?php namespace KFall\oxymora\pageBuilder;
use KFall\oxymora\database\modals\DBContent;
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
  public static $htmlSkeleton;

  public static function loadTemplate($name){
    $tempDir = ROOT_DIR."../template/".$name;
    if(file_exists($tempDir)){
      self::$templateName = $name;
      self::$templateDirectory = $tempDir;
      self::$htmlSkeleton = file_get_contents($tempDir.'/index.html');
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


  protected static function replaceAllPaths($html){
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
    if(self::checkPlaceholderType($placeholder, PLACEHOLDER_INDENT_PLUGIN)){
      // IS PLUGIN
      $value = self::getPlaceholderPlugin($placeholder);
    }elseif(self::checkPlaceholderType($placeholder, PLACEHOLDER_INDENT_AREA)){
      // IS AREA
      $value = self::getPlaceholderArea($placeholder);
    }elseif(self::checkPlaceholderType($placeholder, PLACEHOLDER_INDENT_STATIC)){
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

  protected static function getPlaceholderPlugin($placeholder, $customSettings = false){
    $pluginInfo = self::getPlaceholderValue($placeholder);
    if(is_array($pluginInfo)){
      $pluginName = $pluginInfo[0];
      $pluginId = $pluginInfo[1];
    }else{
      $pluginName = $pluginInfo;
      $pluginId = false;
    }

    $value = self::getPluginHTML($pluginName,$pluginId,$customSettings);

    return $value;
  }

  public static function getPluginHTML($pluginName, $pluginId, $customSettings = false){
    $plugin = TemplatePluginManager::loadPlugin(self::$templateName,$pluginName);

    if($plugin === false) return "";

    if($plugin instanceof iTemplateNavigation){
      $plugin->setMenuItems(self::$menuItems);
    }

    if($plugin instanceof iTemplatePluginSettings && $pluginId !== false){
      // Load Plugin Settings
      $settings = ($customSettings === false) ? DBPluginSettings::getSettings($pluginId) : $customSettings;
      if(is_array($settings) && count($settings) > 0){
        foreach($settings as $setting){
          // The Setting information
          $key = $setting['settingkey'];
          $type = $setting['settingtype'];
          $value = isset($setting['settingvalue']) ? $setting['settingvalue'] : "";

          // Refactor Value it if list or file
          $value = self::refactorSettingsValue($value, $type);

          // deliver it to the plugin
          $plugin->setSetting($key,$value);
        }
      }
    }
    return $plugin->getHtml();
  }

  protected static function refactorSettingsValue($value, $type){
    // IF LIST MAKE IT NICER ONLY FOR THE DEVELOPER OF PLUGIN! NOTHING WITH DATABASE OR OTHER OXYMORA STUFF
    switch(strtolower($type)){

      case 'list':
      $list = [];
      $id = 0;
      if(is_array($value) && count($value) > 0){
        foreach($value as $li){
          if(is_array($li) && count($li) > 0){
            foreach($li as $i){
              $itype = $i['settingtype'];
              $ivalue = $i['settingvalue'];
              $list[$id][$i['settingkey']] = self::refactorSettingsValue($ivalue, $itype);
            }
            $id++;
          }
        }
      }
      $value = $list;
      break;

      case 'file':
      $value = "/file$value";
      break;

    }

    return $value;
  }


  public static function getPlaceholder($html, $filter = ""){
    $filter = ($filter == "") ? "" : $filter.":";
    if(preg_match_all("/(\{".$filter.".*?\})/", $html, $matches, PREG_PATTERN_ORDER)){
      return $matches[1];
    }else{
      return [];
    }
  }

  public static function checkPlaceholderType($placeholder, $type){
    return preg_match("/\{".$type.":.*?\}/is", $placeholder);
  }

  public static function getPlaceholderValue($placeholder){
    $placeholder = trim($placeholder, "{}");
      $placeholderInfo = explode(":", $placeholder);
      if(count($placeholderInfo) >= 3){
        array_shift($placeholderInfo);
        return $placeholderInfo;
      }elseif(count($placeholderInfo) >= 2){
        return $placeholderInfo[1];
      }else{
        return "";
      }
    }


    protected static function generateAreaContent($area){
      // todo: load different areas
      $html = self::$currentPageAreas[$area]['content'];

      // Replace Placeholder
      $html = self::replaceAllPlaceholder($html);

      return $html;
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
      self::$currentPageAreas = DBContent::getPageAreas($page);
    }


  }
