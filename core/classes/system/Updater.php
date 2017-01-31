<?php namespace KFall\oxymora\system;
use \Exception;

class Updater{

  public static function getInfo(){
    try{
      $res = file_get_contents(OXY_UPDATE_API);
      $answer = json_decode($res, true);
    }catch(Exception $e){
      throw new Exception('Can\'t reach the Update-Server!');
    }
    if($answer['error']) throw new Exception($answer['message']);
    if(!$answer['message']) return false;

    if($answer['message']['version'] > OXY_VERSION){
      return $answer['message'];
    }else{
      return false;
    }
  }


}
