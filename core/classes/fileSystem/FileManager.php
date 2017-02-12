<?php namespace KFall\oxymora\fileSystem;
use KFall\oxymora\database\modals\DBAddons;
use \Exception;
use \ZipArchive;
use \DirectoryIterator;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;

class FileManager{
  private static $folderPrefix = "_oxy_";
  private static $trash = "trash/";

  public static function moveUploadedFile($file, $output){
    $output = self::translatePath($output);
    if(!$file || !$output){return false;}
    $output .= "/".$file['name'];
    move_uploaded_file($file['tmp_name'],$output);
    return $output;
  }

  public static function moveFile($file, $output){
    $file = self::translatePath($file);
    $output = self::translatePath($output);
    if(!$file || !$output){return false;}
    return rename($file, $output."/".basename($file));
  }

  public static function renameFile($file, $newFile){
    $file = self::translatePath($file);
    $newFile = self::translatePath($newFile);
    if(!$file || !$newFile){return false;}
    if(file_exists($newFile)){return false;}
    return rename($file, $newFile);
  }

  public static function moveFileToTrash($file){
    // Move to trash folder -> after todo: trash folder view
    // $output = self::$folderPrefix.self::$trash."/";
    // self::createDir($output);
    // return self::moveFile($file, $output);
    return unlink($file);
  }

  public static function createDir($path){
    $path = self::translatePath($path);
    if(!file_exists($path)) return mkdir($path);
    return false;
  }

  public static function listFiles($path = ""){
    $path = ($path == "") ? "/" : "/".trim($path, "/")."/";
    $dirs = [];
    $files = [];
    if(!preg_match("/\.\./", $path) && file_exists(FILE_DIR.$path)){
      $iterator = new DirectoryIterator(FILE_DIR.$path);

      foreach ($iterator as $name => $file) {
        $temp = [];
        $temp['path'] = $path;
        $temp['fullpath'] = $path.$file->getFilename();
        $temp['filename'] = $file->getFilename();

        if ($file->isDir()){
          if($temp['filename'] == ".." || $temp['filename'] == "." || strpos($temp['filename'], self::$folderPrefix) === 0){continue;}
          $dirs[] = $temp;
        }else{
          $files[] = $temp;
        }
      }

    }

    return ["dirs" => $dirs, "files" => $files];
  }

  public static function searchFiles($path = "", $search = ""){
    $path = ($path == "") ? "/" : "/".trim($path, "/")."/";
    $dirs = [];
    $files = [];

    if(!preg_match("/\.\./", $path) && file_exists(FILE_DIR.$path)){
      $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(FILE_DIR.$path),RecursiveIteratorIterator::SELF_FIRST);

      foreach ($iterator as $name => $file) {
        $temp = [];
        $temp['path'] = self::getRelativeFromAbsolute(pathinfo($file->getRealPath())['dirname']);
        $temp['fullpath'] = self::getRelativeFromAbsolute($file->getRealPath());
        $temp['filename'] = $file->getFilename();

        if($search != "" && strpos(strtolower($temp['filename']), $search) === false){continue;}

        if ($file->isDir()){
          if($temp['filename'] == ".." || $temp['filename'] == "."){continue;}
          $dirs[] = $temp;
        }else{
          $files[] = $temp;
        }
      }
    }

    return ["dirs" => $dirs, "files" => $files];
  }

  public static function translatePath($path = ""){
    if(preg_match("/\.\./", $path)){
      return false;
    }
    $path = FILE_DIR."/".trim($path, "/");
    return $path;
  }

  private static function getRelativeFromAbsolute($absolutePath, $path = ""){
    return str_replace("\\", "/", substr($absolutePath, strlen(preg_replace("/\\\\[^\\\\]*\\\\[\.]{2}/","",str_replace("/", "\\", FILE_DIR.$path)))));
  }

  public static function readFile($file){
    $path = self::translatePath($file);
    if(!$path || !file_exists($path)) throw new Exception('File not found!', 1);
    return file_get_contents($path);
  }

  public static function getMimeType($file){
    $path = self::translatePath($file);
    if(!$path || !file_exists($path)) throw new Exception('File not found!', 1);
    $mimes = new \Mimey\MimeTypes;
    $ext = preg_replace('/^.*\./', "", $file);
    return $mimes->getMimeType($ext); ;
  }

  public static function printFile($file, $setHeaderContentType = false){
    $content = self::readFile($file);
    if($setHeaderContentType){
      $type = self::getMimeType($file);
      header("Content-Type:$type");
    }
    echo $content;
  }

}
