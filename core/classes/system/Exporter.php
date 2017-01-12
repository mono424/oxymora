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

class Exporter{

  private static $truncateIgnore = [
    "user",
    "membersystem_attempt",
    "membersystem_session"
  ];

  private static $backupDirs = [
    ['dir' => TEMPLATE_DIR, 'name' => 'OXY_TEMPLATE'],
    ['dir' => ADDON_DIR, 'name' => 'OXY_ADDONS'],
    ['dir' => ADMIN_DIR."/profil", 'name' => 'OXY_PROFILEPICTURES'],
    ['dir' => FILE_DIR, 'name' => 'OXY_FILE']
  ];

  private static $databaseFileName = "OXY_DB/db.sql";
  private static $configFileName = "OXY_CONFIG/config.json";
  private static $infoFileName = "info.txt";


  public static function getConfig($path, $pass = ""){
    // ==========================================
    // Extract ZIP
    // ==========================================
    try {
      // Return Value
      $config = false;

      // Decrypt File
      if($pass) Crypter::decryptFile($path, $pass);

      // Open ZIP
      $zip = new ZipArchive;
      $res = $zip->open($path);
      if ($res !== TRUE) return false;

      // Try to export Config
      if($zip->locateName(self::$configFileName)){
        $zip->extractTo(TEMP_DIR, self::$configFileName);
        $config = json_decode(file_get_contents(TEMP_DIR."/".self::$configFileName), true);
        unlink(TEMP_DIR."/".self::$configFileName);
      }

      // Encrypt File again
      // maybe todo for later, create second undecrypted file and delete afterwards,
      // would be faster i guess ;)
      if($pass) Crypter::encryptFile($path, $pass);

      return $config;
    } catch (Exception $e) {
      Logger::log($e->getMessage(), 'error', 'addonManager.log');
      return false;
    }
  }

  public static function getInfo($path, $pass = ""){
    // ==========================================
    // Extract ZIP
    // ==========================================
    try {
      // Return Value
      $info = [];

      // Decrypt File
      if($pass) Crypter::decryptFile($path, $pass);

      // Open ZIP
      $zip = new ZipArchive;
      $res = $zip->open($path);
      if ($res !== TRUE) return false;

      // Has Config ?
      $info['hasConfig'] = ($zip->locateName(self::$configFileName) !== false);

      // Install Folders If Exists in ZIP
      $info['backupDirs'] = [];
      foreach(self::$backupDirs as $bdir){
        $info['backupDirs'][$bdir['dir']] = true;
      }

      // Install Database if Datbase-File exists
      $info['hasDatabase'] = ($zip->locateName(self::$databaseFileName) !== false);

      // Infos
      if($zip->locateName(self::$infoFileName) !== false){
        $zip->extractTo(TEMP_DIR, self::$infoFileName);
        $info['info'] = json_decode(file_get_contents(TEMP_DIR."/".self::$infoFileName), true);
        unlink(TEMP_DIR."/".self::$infoFileName);
      }else{
        $info['info'] = null;
      }

      // Encrypt File again
      // maybe todo for later, create second undecrypted file and delete afterwards,
      // would be faster i guess ;)
      if($pass) Crypter::encryptFile($path, $pass);

      return $info;
    } catch (Exception $e) {
      Logger::log($e->getMessage(), 'error', 'addonManager.log');
      return false;
    }
  }


  public static function import($path, $pass = "", $db = null, $exportConfig = true, $customTables = []){
    // ==========================================
    // Extract ZIP
    // ==========================================
    $db = (is_null($db)) ? DB::pdo() : $db;
    try {
      // Decrypt File
      if($pass) Crypter::decryptFile($path, $pass);

      // Open ZIP
      $zip = new ZipArchive;
      if ($zip->open($path) !== TRUE) return false;

      // Install Config If Exists in ZIP
      if($exportConfig && $zip->locateName(self::$configFileName) !== false) $zip->extractTo(ROOT_DIR."config.json", self::$configFileName);

      // Install Folders If Exists in ZIP
      foreach(self::$backupDirs as $bdir){
        if($zip->locateName($bdir['name']) !== false){
          self::deleteDirContent();
          $zip->extractTo($bdir['dir'], $bdir['name']);
        }
      }

      // Install Database if Datbase-File exists
      if($zip->locateName(self::$databaseFileName) !== false){
        $tmp_db_file = TEMP_DIR."/".self::$databaseFileName;
        $zip->extractTo(TEMP_DIR, self::$databaseFileName);
        $sql = file_get_contents($tmp_db_file);
        unlink($tmp_db_file);
        rmdir(substr($tmp_db_file, 0,strlen($tmp_db_file) - strlen(basename($tmp_db_file))));

        if($customTables){
          $tables = $customTables;
        }else{
          Config::load();
          $config = Config::get();
          $tables = $config['database-tables'];
        }
        var_dump($tables);

        foreach($config['database-tables'] as $key => $val){
          $sql = str_replace("{{$key}}", $val, $sql);
        }
        var_dump($sql);

        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
        $db->exec($sql);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
      }

      return true;
    } catch (Exception $e) {
      Logger::log($e->getMessage(), 'error', 'exporter.log');
      return false;
    }
  }


  public static function export($exportConfig = true, $pass = "") {

    // ==========================================
    // Create ZIP
    // ==========================================
    try {
      // Output Dir
      $outputdir = TEMP_DIR."/exports/";
      if(!file_exists($outputdir)) mkdir($outputdir);

      // Create ZIP
      $zip = new ZipArchive();
      $tmp_file = tempnam($outputdir,'');
      $zip->open($tmp_file, ZipArchive::CREATE);

      // Add Database
      $tmp_db_file = tempnam($outputdir,'');
      file_put_contents($tmp_db_file, self::backupDatabase());
      $zip->addFile($tmp_db_file, self::$databaseFileName);

      // Add Folder
      foreach(self::$backupDirs as $bdir){
        $rootPath = realpath($bdir['dir']);
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath),RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($files as $name => $file){
          if (!$file->isDir()){
            $filePath = $file->getRealPath();
            $zipPath = $bdir['name']."/".substr($filePath, strlen($rootPath) + 1);
            $zip->addFile($filePath, $zipPath);
          }
        }
      }

      // Add Config
      if($exportConfig) $zip->addFile(ROOT_DIR."config.json", self::$configFileName);

      // Add extra infos
      $tmp_info_file = tempnam($outputdir,'');
      $info = ['created' => date('Y-m-d H:i:s')];
      file_put_contents($tmp_info_file, json_encode($info));
      $zip->addFile($tmp_info_file, self::$infoFileName);

      // Close & Create ZIP
      $zip->close();

      // Delete Temp-Database-File if created
      if($tmp_db_file) unlink($tmp_db_file);

      // Delete Info-File if created
      if($tmp_info_file) unlink($tmp_info_file);

      // Crypt if password set
      if($pass) Crypter::encryptFile($tmp_file, $pass);

      return $tmp_file;
    } catch (Exception $e) {
      Logger::log($e->getMessage(), 'error', 'exporter.log');
      throw $e;
    }
  }



  public static function deleteDirContent($dir){
    $dir = 'samples' . DIRECTORY_SEPARATOR . 'sampledirtree';
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach($files as $file) {
      if ($file->isDir()){
        rmdir($file->getRealPath());
      } else {
        unlink($file->getRealPath());
      }
    }
  }





  public static function backupDatabase() {
    try {
      $config = Config::get();
      $tables = $config['database-tables'];
      $tables = array_merge($tables, AddonManager::getBackupTables()[1]);
      foreach ($tables as $key => $table) {
        // TABLE PLACEHOLDER
        // IF NUMERIC THAN DONT REPLACE CUZ ITS NOT OXYMORA TABLE (ADDON TABLE)
        $placeholder = (!is_numeric($key)) ? "{{$key}}" : $table;
        // Create Table SQL
        $tableCreateInfo = DB::pdo()->query('SHOW CREATE TABLE ' . $table)->fetch(PDO::FETCH_NUM);
        $createSQL = $tableCreateInfo[1];
        $createSQL = str_replace('CREATE TABLE','CREATE TABLE IF NOT EXISTS',$createSQL);
        $createSQL = str_replace("`$table`","`$placeholder`",$createSQL);
        $output .= "\n\n" . $createSQL . ";\n\n";

        if(!in_array($key, self::$truncateIgnore)){
          $output .= 'TRUNCATE TABLE `' . $placeholder . '`;';
        }

        // Add Entries SQL
        $result = DB::pdo()->query('SELECT * FROM ' . $table);
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
          $output .= 'INSERT INTO `' . $placeholder . '` VALUES(';
          foreach($row as $value){
            $output .= DB::pdo()->quote($value).",";
          }
          $output = substr($output, 0, -1);
          $output.= ");\n";
        }

      }
      // Return
      $output.="\n\n\n";
      return $output;
    } catch (Exception $e) {
      Logger::log($e->getMessage(), 'error', 'exporter.log');
      return false;
    }

    return $output;
  }

}
