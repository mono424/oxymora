<?php namespace KFall\oxymora\fileSystem;
use KFall\oxymora\database\modals\DBAddons;
use \ZipArchive;
use \DirectoryIterator;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;

class FileManager{

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
          if($temp['filename'] == ".." || $temp['filename'] == "."){continue;}
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

  public static function getPath($file = ""){
    $path = "/".trim($file, "/");
    return FILE_DIR.$file;
  }

  private static function getRelativeFromAbsolute($absolutePath, $path = ""){
    return str_replace("\\", "/", substr($absolutePath, strlen(preg_replace("/\\\\[^\\\\]*\\\\[\.]{2}/","",str_replace("/", "\\", FILE_DIR.$path)))));
  }

}
