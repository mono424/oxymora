<?php
use KFall\oxymora\addons\iBackupableDB;
use KFall\oxymora\addons\iPageErrorHandler;
use KFall\oxymora\addons\iAddon;
use KFall\oxymora\database\DB;

class templateManager implements iAddon, iBackupableDB, iPageErrorHandler{

  // ========================================
  //  VARS
  // ========================================
  private $table_users = "oxymora_packagemanager_users";
  private $table_templates = "oxymora_packagemanager_templates";

  // ========================================
  //  CONSTRUCT
  // ========================================
  public function __construct($permissionManager){

  }

  // ========================================
  //  EVENTS
  // ========================================

  // Start/Stop Events
  public function onInstallation(){
    $pdo = DB::pdo();
    $pdo->exec("CREATE TABLE `".$this->table_templates."` (
      `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `name` VARCHAR(64) NOT NULL,
      `version` INT(4) NOT NULL,
      `author` INT(9) NOT NULL,
      `displayname` VARCHAR(64) NOT NULL,
      `hash` VARCHAR(128) NOT NULL,
      `filesize` INT(12) NOT NULL,
      `file` VARCHAR(256) NOT NULL,
      `approved` TINYINT(1) NOT NULL DEFAULT 0,
      `approved_by` INT(9),
      `approved_time` TIMESTAMP NULL,
      `added` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      )");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `".$this->table_users."` (
      `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `username` VARCHAR(64) NOT NULL,
      `firstname` VARCHAR(128),
      `lastname` VARCHAR(128),
      `website` VARCHAR(128)
      )");
    }

    public function onEnable(){

    }
    public function onDisable(){

    }

    // CMS
    public function onOpen(){

    }
    public function onTabChange($tab){

    }

    // Page
    public function onPageOpen($page){

    }

    // Backup
    public function getBackupTables(){
      return [$this->table_templates, $this->table_users];
    }

    // Reroute specific errors
    public function onPageError($error){
      // We reroute the url "oxy-api-package-*.html"
      if(preg_match('/^oxy\-api\-template\-(.*)\.html$/i',$error->page, $matches)){
        // Now we can do stuff we wanna do like output the newest update for oxymora
        $error->ignore();
        $action = $matches[1];
        $answer;

        try{
          switch($action){

            case 'download':
            // Time limit
            set_time_limit(1800); // max 30min.
            $update = $this->getSpecific($_GET['id']);
            if(!$update) die();
            $maxRead = 1 * 1024 * 1024; // 1MB
            $fh = fopen($update['file'], 'r');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$update['name'].'.zip"');
            while (!feof($fh)) {
              echo fread($fh, $maxRead);
              ob_flush();
            }
            die();
            break;

            case 'list':
            $answer = $this->answer($this->getList());
            break;

            default:
            throw new Exception('Command does not exists.');
          }
        }catch(Exception $e){
          $answer = $this->answer($e->getMessage(), true);
        }

        echo $answer;
      }
    }

    // Api Functions
    private function answer($message, $error=false){
      return json_encode(['message' => $message, 'error' => $error]);
    }

    private function getList(){
      $pdo = DB::pdo();
      $prep = $pdo->prepare("SELECT `".$this->table_templates."`.*, `".$this->table_users."`.`firstname`,
                             `".$this->table_users."`.`lastname`, `".$this->table_users."`.`username`
                             FROM `".$this->table_templates."`
                             LEFT JOIN `".$this->table_users."` ON `".$this->table_users."`.`id`=`author`
                             GROUP BY `name`");
      $success = $prep->execute();
      if(!$success){throw new Exception('Oxymora suffered from a database failure.');}
      $arr = $prep->fetchAll(PDO::FETCH_ASSOC);
      $arr = array_map(function($a){
        unset($a['file']);
        return $a;
      },$arr);
      return $arr;
    }

    private function getSpecific($id){
      $pdo = DB::pdo();
      $prep = $pdo->prepare("SELECT `".$this->table_templates."`.*, `".$this->table_users."`.`firstname`,
                             `".$this->table_users."`.`lastname`, `".$this->table_users."`.`username`
                             FROM `".$this->table_templates."`
                             LEFT JOIN `".$this->table_users."` ON `".$this->table_users."`.`id`=`author`
                             WHERE `".$this->table_templates."`.`id`=:id");
      $prep->bindValue(':id', $id);
      $success = $prep->execute();
      if(!$success){throw new Exception('Not found.');}
      $arr = $prep->fetch(PDO::FETCH_ASSOC);
      return $arr;
    }

  }
