<?php namespace KFall\oxymora\config;


class Config{

  private static $config;

  public static function set($config){
    self::$config = $config;
  }

  public static function overwrite($config){
    self::$config = array_merge(self::$config, $config);
  }

  public static function get(){
    return self::$config;
  }

  public static function load(){
    self::$config = json_decode(file_get_contents(ROOT_DIR.'config.json'), true);
  }

  public static function save(){
    return file_put_contents(ROOT_DIR."config.json", json_encode(self::$config));
  }

}
