<?php namespace KFall\oxymora\system;
use \PDO;
use \Exception;
use \ZipArchive;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;
use KFall\oxymora\logs\Logger;
use KFall\oxymora\config\Config;
use KFall\oxymora\database\DB;
use KFall\oxymora\helper\Crypter;
use KFall\oxymora\addons\AddonManager;

class Reseter{
  private static $deleteDirs = [
    ADMIN_DIR."/profil",
    FILE_DIR
  ];

  public static function reset(){
    // Get Config
    $config = Config::get();

    // Delete Database
    $stmt = DB::pdo()->prepare('DROP DATABASE `'.$config['database']['db'].'`');
    $stmt->execute();

    // Delete Config File
    unlink(ROOT_DIR."config.json");

    // Delete Files
    foreach(self::$deleteDirs as $dir){
      self::removeDirectory($dir);
      mkdir($dir);
    }

    return true;
  }

  public static function removeDirectory($path){
    $files = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $path).'/{,.}*', GLOB_BRACE);
    foreach ($files as $file) {
      if ($file == $path.'/.' || $file == $path.'/..') { continue; } // skip special dir entries
      is_dir($file) ? self::removeDirectory($file) : unlink($file);
    }
    rmdir($path);
    return true;
  }

}
