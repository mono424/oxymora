<?php
use KFall\oxymora\addons\iBackupableDB;
use KFall\oxymora\addons\iPageErrorHandler;
use KFall\oxymora\addons\iAddon;
use KFall\oxymora\database\DB;

class packageManager implements iAddon, iBackupableDB, iPageErrorHandler{

  // ========================================
  //  VARS
  // ========================================
  private $table_users = "oxymora_packagemanager_users";
  private $table_packages = "oxymora_packagemanager_packages";

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
    $pdo->exec("CREATE TABLE `".$this->table_packages."` (
      `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `name` VARCHAR(64) NOT NULL,
      `version` INT(4) NOT NULL,
      `author` INT(9) NOT NULL,
      `type` VARCHAR(32) NOT NULL,
      `exportable` TINYINT(1) NOT NULL,
      `displayname` VARCHAR(64) NOT NULL,
      `description` TEXT,
      `menuicon` VARCHAR(32),
      `hash` VARCHAR(128) NOT NULL,
      `approved` TINYINT(1) NOT NULL DEFAULT 0,
      `approved_by` INT(9),
      `approved_time` TIMESTAMP,
      `added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
      )");
    $pdo->exec("CREATE TABLE `".$this->table_users."` (
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
      return [$this->table_packages, $this->table_users];
    }

    // Reroute specific errors
    public function onPageError($error){
      // We reroute the url "oxy-api-package-*.html"
      if(preg_match('/^oxy\-api\-update\-(.*)\.html$/i',$error->page, $matches)){
        // Now we can do stuff we wanna do like output the newest update for oxymora
        $error->ignore();
        $action = $matches[1];
        $answer;

        try{
          switch($action){

            case 'download':
            $answer = $this->answer([]);
            break;

            case 'list':
            $answer = $this->answer([]);
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
    public function answer($message, $error=false){
      return json_encode(['message' => $message, 'error' => $error]);
    }

    public function getNewestUpdate($intern = false){
      $pdo = DB::pdo();
      $info = ($intern) ? "*" : "`version`,`description`,`packtype`,`filesize`,`hash`,`added`";
      $prep = $pdo->prepare("SELECT $info FROM `".$this->table_builds."` ORDER BY `id` DESC LIMIT 1");
      $success = $prep->execute();
      if(!$success){throw new Exception('Oxymora suffered from a database failure.');}
      return $prep->fetch(PDO::FETCH_ASSOC);
    }

  }
