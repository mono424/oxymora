<?php
use KFall\oxymora\addons\iAddon;
use KFall\oxymora\database\DB;

class accounting implements iAddon{

  // ========================================
  //  VARS
  // ========================================
  private $table = "accounting_invoice";


  // ========================================
  //  EVENTS
  // ========================================

  // Start/Stop Events
  public function onInstallation(){

    // for testing
    throw new Exception("Error Processing Request", 1);

    $pdo = DB::pdo();
    $pdo->exec("CREATE TABLE `".$this->table."` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `page` VARCHAR(256),
    `ip` VARCHAR(30),
    `browser` VARCHAR(30),
    `time` DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    $pdo->exec("ALTER TABLE `".$this->table."` AUTO_INCREMENT = 100001;");
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


}
