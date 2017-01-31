<?php
use KFall\oxymora\addons\iBackupableDB;
use KFall\oxymora\addons\iPageErrorHandler;
use KFall\oxymora\addons\iAddon;
use KFall\oxymora\database\DB;

class updateManager implements iAddon, iBackupableDB, iPageErrorHandler{

  // ========================================
  //  VARS
  // ========================================
  private $table_builds = "oxymora_builds";

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
    $pdo->exec("CREATE TABLE `".$this->table_builds."` (
      `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `version` varchar(4) NOT NULL,
      `packtype` varchar(32) NOT NULL,
      `description` TEXT,
      `filesize` INT(12) NOT NULL,
      `hash` VARCHAR(128) NOT NULL,
      `file` VARCHAR(128),
      `added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
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
      // We reroute the url "oxy-api-update-*.html"
      if(preg_match('/^oxy\-api\-update\-(.*)\.html$/i',$error->page, $matches)){
        // Now we can do stuff we wanna do like output the newest update for oxymora
        $error->ignore();
        $action = $matches[1];
        $answer;

        try{
          switch($action){

            case 'newest':
            $answer = $this->answer($this->getNewestUpdate());
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

    public function getNewestUpdate(){
      $pdo = DB::pdo();
      $prep = $pdo->prepare("SELECT `version`,`description`,`packtype`,`filesize`,`hash`,`added` FROM `".$this->table_builds."` ORDER BY `id` DESC LIMIT 1");
      $success = $prep->execute();
      if(!$success){throw new Exception('Oxymora suffered from a database failure.');}
      return $prep->fetch(PDO::FETCH_ASSOC);
    }

  }
