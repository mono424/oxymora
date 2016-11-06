<?php
use KFall\oxymora\addons\iAddon;
use KFall\oxymora\database\DB;

class accounting implements iAddon{

  // ========================================
  //  VARS
  // ========================================
  private $table = "accounting_invoices";


  // ========================================
  //  EVENTS
  // ========================================

  // Start/Stop Events
  public function onInstallation(){
    $pdo = DB::pdo();
    $pdo->exec("CREATE TABLE `".$this->table."` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `file` VARCHAR(256),
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP
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
