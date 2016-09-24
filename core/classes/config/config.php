<?php namespace KFall\oxymora\config;


class Config{

  private static $config;

  public static function set($config){
    self::$config = $config;
  }

  public static function get(){
    return self::$config;
  }

}
