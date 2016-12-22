<?php namespace KFall\oxymora\helper;

class Random{

  public static function filename($outputpath, $extension){
    do{
      $randName = self::str(16).'.'.$extension;
    }while(in_array($randName, scandir($outputpath)));
    return $randName;
  }

  public static function str($len = 16, $strong = false) {
  	$chars = ($strong) ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`~!@#$%^&*()-=_+' : 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  	$l = strlen($chars) - 1;
  	$str = '';
  	for ($i = 0; $i < $len; ++$i) {
  		$str .= $chars[rand(0, $l)];
   	}
  	return $str;
  }

}
