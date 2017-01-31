<?php
use KFall\oxymora\addons\iBackupableDB;
use KFall\oxymora\addons\iAddon;
use KFall\oxymora\database\DB;

class updateManager implements iAddon, iBackupableDB{

  // ========================================
  //  VARS
  // ========================================
  private $table_downloads = "oxymora_build_downloads";
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
      `version` INT(4) NOT NULL,
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


  }
