<?php namespace KFall\oxymora\system;
use \Exception;
use \ZipArchive;

class Updater{

  public static function install(){
    // Download newest version
    $file = TEMP_DIR."/update.zip";
    if(file_exists($file)) unlink($file);
    file_put_contents($file, fopen(OXY_UPDATE_DOWNLOAD, 'r'));

    // Open Update
    $zip = new ZipArchive;
    $res = $zip->open($file);
    if ($res !== TRUE) throw new Exception('Cant open Update-Container!');

    // Unpack Container
    $res = $zip->extractTo(WEB_ROOT_DIR);
    if ($res !== TRUE) throw new Exception('Cant extract Update-Container!');
    return true;
  }

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
