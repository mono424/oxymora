<?php
use KFall\oxymora\addons\iBackupableDB;
use KFall\oxymora\addons\iAddon;
use KFall\oxymora\database\DB;
use \Exception;

class pageLocker implements iAddon, iBackupableDB{

  // ========================================
  //  VARS
  // ========================================
  private $table = "pageLocker_locked";

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
    $pdo->exec("CREATE TABLE `".$this->table."` (
      `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `page` VARCHAR(256)
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
      $pdo = DB::pdo();

      // GET LOCKED PAGES
      $prep = $pdo->prepare("SELECT `page` FROM `".$this->table."`");
      $success = $prep->execute();
      if(!$success){die('something went wrong!');}
      $pagesLocked = $prep->fetchAll(PDO::FETCH_COLUMN, 0);

      if(in_array($page->page, $pagesLocked) == true){
        throw new Exception('Site access denied.',403);
      }
    }

    // Backup
    public function getBackupTables(){
      return [$this->table];
    }


  }
