<?php namespace KFall\oxymora\pageBuilder;
use KFall\oxymora\database\modals\DBStatic;

class CurrentTemplate{
  public static $config = null;

  public static function getStaticSettings(){
    self::refreshConfig(true);
    $settings = self::$config['settings'];
    $settings = array_map(function($setting){
      $values = DBStatic::getVars();
      $setting['value'] = (is_array($values) && array_key_exists($setting['key'],$values)) ? $values[$setting['key']] : null;
      return $setting;
    }, $settings);
    return $settings;
  }

  public function refreshConfig($onlyOnNull = false){
    if($onlyOnNull && !is_null(self::$config)) return false;
    $tempDir = ROOT_DIR."../template/".TEMPLATE;
    if(file_exists($tempDir)){
      self::$config = json_decode(file_get_contents($tempDir.'/config.json'), true);
      return true;
    }else{
      return false;
    }
  }

}
