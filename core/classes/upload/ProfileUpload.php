<?php namespace KFall\oxymora\upload;
use KFall\oxymora\helper\Random;
use KFall\oxymora\helper\ImageHelper;
use \Exception;

class ProfileUpload{

  public static $max_size = 20971520; //20mb
  public static $extensions = ['jpg','jpeg','png','svg'];

  public static function upload($file, $filename = null){

    // CHECK MAX FILESIZE
    if ($file["size"] > self::$max_size) throw new Exception('File is too big');

    // CHECK EXTENSION
    $imageExt = pathinfo($file["name"],PATHINFO_EXTENSION);
    if(!in_array(strtolower($imageExt), self::$extensions)) throw new Exception('"'.$imageExt.'" extension not allowed!');

    // GET IMAGE INFO // CHECK IF IMAGE
    $info = getimagesize($file["tmp_name"]);
    if(!$info){error('"file" is corrupted');}
    list($width, $height, $type, $attr) = $info;

    // GET OUTPUT PATH
    $path = ROOT_DIR."../admin/profil";
    $filename = ($filename) ? $filename : Random::filename($path, $imageExt);
    $outputname = $path.'/'.$filename;

    // MOOOOVE IT IT <3
    ImageHelper::easyImageCrop($file["tmp_name"],$outputname,300, 300);

    return $filename;
  }

}


?>
