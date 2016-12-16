<?php namespace KFall\oxymora\addons;
use KFall\oxymora\database\modals\DBAddons;
use KFall\oxymora\logs\Logger;
use \ZipArchive;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;
use \Exception;

class AddonManager{
  public static $installZipError=null;

  public static function triggerEvent($event, $args, $specificAddon = false){
    $triggeredSuccessful = false;
    $addons = self::listAll();
    foreach($addons as $addon){
      try {
        if(!$specificAddon || $specificAddon == $addon['name']){
          if(($event !== ADDON_EVENT_INSTALLATION && $event !== ADDON_EVENT_ENABLE && $event !== ADDON_EVENT_DISABLE) && ($addon['installed'] === false || $addon['installed']['active'] == false)){
            continue;
          }
          $addonObj = self::load($addon['file']);
          if($addonObj){
            $addonObj->$event($args);
            $triggeredSuccessful = true;
          }
        }
      } catch (Exception $e) {
        Logger::log($e->getMessage(), 'error', 'addon-'.$addon['name'].'.log');
      }
    }
    return $triggeredSuccessful;
  }

  public static function listAll($showHidden = true, $showNotInstalled = true, $showNotActive = true){
    try {
      $mainpath = ADDON_DIR;
      $all = scandir($mainpath);
      $dirs = [];
      foreach($all as $item){
        $path = $mainpath."/".$item;
        $pathHTML = $mainpath."/".$item."/html";
        $file = $path."/$item.php";
        if(strlen(trim($item, ".")) > 0 && is_dir($path) && file_exists($file)){
          $assoc['name'] = $item;
          $assoc['file'] = $file;
          $assoc['path'] = $path;
          $assoc['html'] = $pathHTML;
          $assoc['installed'] = DBAddons::getInfo($item);
          $assoc['config'] = json_decode(file_get_contents($path."/config.json"), true);
          $assoc['icon'] = (file_exists($path."/icon.png"));
          $assoc['iconUrl'] = ($assoc['icon']) ? "addons/".$item."/icon.png" : null ;
          if(($showHidden || $assoc['config']['menuentry']['visible']) && ($showNotInstalled || $assoc['installed'] !== false)  && ($showNotActive || $assoc['installed']['active'] != false)){
            $dirs[] = $assoc;
          }
        }
      }
      return $dirs;
    } catch (Exception $e) {
      Logger::log($e->getMessage(), 'error', 'addonManager.log');
      throw $e;
    }
  }

  public static function find($name){
    try {
      $templatePath = ADDON_DIR;
      $all = scandir($templatePath);
      foreach($all as $item){
        $path = $templatePath."/".$item;
        $pathHTML = $templatePath."/".$item."/html";
        $file = $path."/$item.php";
        if(strlen(trim($item, ".")) > 0 && is_dir($path) && file_exists($file)){
          if($item == $name){
            $assoc['name'] = $item;
            $assoc['file'] = $file;
            $assoc['path'] = $path;
            $assoc['html'] = $pathHTML;
            $assoc['installed'] = DBAddons::getInfo($item);
            $assoc['config'] = json_decode(file_get_contents($path."/config.json"), true);
            $assoc['icon'] = (file_exists($path."/icon.png"));
            $assoc['iconUrl'] = ($assoc['icon']) ? "addons/".$item."/icon.png" : null;
            return $assoc;
          }
        }
      }
      return false;
    } catch (Exception $e) {
      Logger::log($e->getMessage(), 'error', 'addonManager.log');
      throw $e;
    }
  }

  public static function load($name){
    try {
      $is_path = file_exists($name) ? true : false;
      $file = ($is_path) ? $name : ((($addon = self::find($name)) !== false) ? $addon['file'] : false);
      $name = ($is_path) ? basename($name, ".php") : $name;
      if($file !== false){
        require_once $file;
        if(!class_exists($name)){return false;}
        $obj = new $name;
        if(!$obj instanceof iAddon && !$obj instanceof iWidget){
          return false;
        }
        return $obj;
      }
      return false;
    } catch (Exception $e) {
      Logger::log($e->getMessage(), 'error', 'addonManager.log');
      throw $e;
    }
  }

  public static function installZip($path){
    try {
      $zip = new ZipArchive;
      if ($zip->open($path) === TRUE) {
        $config = json_decode($zip->getFromName('config.json'), true);
        if(!preg_match("/[A-Za-z0-9]{2,}/", $config['name'])){self::$installZipError = "Invalid package name!";return false;}
        $out_path = ADDON_DIR."/".$config['name'];
        if(file_exists($out_path)){self::$installZipError = "Addon exists!";return false;}
        if(!@mkdir($out_path)){self::$installZipError = "Creating folder failed!";return false;}
        $zip->extractTo($out_path);
        $zip->close();
        if(!self::install($config['name'], false)){self::delete_directory($out_path);self::$installZipError = "Installation failed!";return false;}
        return $config['name'];
      } else {
        return false;
      }
    } catch (Exception $e) {
      Logger::log($e->getMessage(), 'error', 'addonManager.log');
      throw $e;
    }
  }

  public static function extractZip($plugin){
    try {
      $addon = self::find($plugin);
      $rootPath = realpath($addon['path']);

      $zip = new ZipArchive();
      $tmp_file = tempnam(TEMP_DIR,'');
      $zip->open($tmp_file, ZipArchive::CREATE);

      $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath),RecursiveIteratorIterator::LEAVES_ONLY);

      foreach ($files as $name => $file){
        if (!$file->isDir()){
          $filePath = $file->getRealPath();
          $relativePath = substr($filePath, strlen($rootPath) + 1);
          $zip->addFile($filePath, $relativePath);
        }
      }

      $zip->close();
      return $tmp_file;
    } catch (Exception $e) {
      Logger::log($e->getMessage(), 'error', 'addonManager.log');
      throw $e;
    }
  }

  public static function install($name, $active = true){
    try {
      if(!DBAddons::install($name, $active)){return false;}
      if(self::triggerEvent(ADDON_EVENT_INSTALLATION, null, $name)){
        return true;
      }else{
        DBAddons::uninstall($name);
      }
    } catch (Exception $e) {
      Logger::log($e->getMessage(), 'error', 'addonManager.log');
      throw $e;
    }
  }

  public static function disable($name){
    try {
      if(!DBAddons::disable($name)){return false;}
      return self::triggerEvent(ADDON_EVENT_DISABLE, null, $name);
    } catch (Exception $e) {
      Logger::log($e->getMessage(), 'error', 'addonManager.log');
      throw $e;
    }
  }

  public static function enable($name){
    try {
      if(!DBAddons::enable($name)){return false;}
      return self::triggerEvent(ADDON_EVENT_ENABLE, null, $name);
    } catch (Exception $e) {
      Logger::log($e->getMessage(), 'error', 'addonManager.log');
      throw $e;
    }
  }


  private static function delete_directory($dirname) {
    try {
      if (is_dir($dirname))
      $dir_handle = opendir($dirname);
      if (!$dir_handle)
      return false;
      while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
          if (!is_dir($dirname."/".$file)){
            unlink($dirname."/".$file);
          }else{
            self::delete_directory($dirname.'/'.$file);
          }
        }
      }
      closedir($dir_handle);
      rmdir($dirname);
      return true;
    } catch (Exception $e) {
      Logger::log($e->getMessage(), 'error', 'addonManager.log');
      throw $e;
    }
  }


}
