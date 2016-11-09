<?php
use KFall\oxymora\addons\iAddon;
use KFall\oxymora\database\DB;

class accounting implements iAddon{

  // ========================================
  //  VARS
  // ========================================
  private $table = "accounting_invoices";
  private $table_customer = "accounting_customer";


  // ========================================
  //  EVENTS
  // ========================================

  // Start/Stop Events
  public function onInstallation(){
    $pdo = DB::pdo();

    // Invoice Table
    $pdo->exec("CREATE TABLE `".$this->table."` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `customer` INT(11),
    `items` TEXT,
    `file` VARCHAR(256),
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP
  ) DEFAULT CHARSET=utf8;");
    $pdo->exec("ALTER TABLE `".$this->table."` AUTO_INCREMENT = 100001;");

    // Customer Table
    $pdo->exec("CREATE TABLE `".$this->table_customer."` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `firstname` VARCHAR(256),
    `lastname` VARCHAR(256),
    `street` VARCHAR(256),
    `plz` INT(8),
    `ort` VARCHAR(256),
    `email` VARCHAR(256),
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP
  ) DEFAULT CHARSET=utf8;");
    $pdo->exec("ALTER TABLE `".$this->table_customer."` AUTO_INCREMENT = 440001;");
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
