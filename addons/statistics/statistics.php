<?php
use KFall\oxymora\addons\iBackupableDB;
use KFall\oxymora\addons\iAddon;
use KFall\oxymora\database\DB;

class statistics implements iAddon, iBackupableDB{

  // ========================================
  //  VARS
  // ========================================
  private $table = "statistics_visits";

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
    `page` VARCHAR(256),
    `ip` VARCHAR(30),
    `browser` VARCHAR(30),
    `time` DATETIME DEFAULT CURRENT_TIMESTAMP
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
    $this->addVisit($page->page);
  }

  // Backup
  public function getBackupTables(){
    return [$this->table];
  }


  // ========================================
  //  STATISTICS FUNCTIONS
  // ========================================

  function addVisit($page){
    $pdo = DB::pdo();
    $ip = $this->getIP();
    $browser = $this->getBrowserName($_SERVER['HTTP_USER_AGENT']);

    $prep = $pdo->prepare("INSERT INTO `".$this->table."`(`page`, `ip`, `browser`) VALUES (:page,:ip,:browser)");
    $prep->bindValue(':page', $page, PDO::PARAM_STR);
    $prep->bindValue(':ip', $ip, PDO::PARAM_STR);
    $prep->bindValue(':browser', $browser, PDO::PARAM_STR);
    $prep->execute();
  }

  function getBrowserName($user_agent){
    if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
    elseif (strpos($user_agent, 'Edge')) return 'Edge';
    elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
    elseif (strpos($user_agent, 'Safari')) return 'Safari';
    elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
    elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';
    return 'Other';
  }

  // Function to get the client IP address
  function getIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
    $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
    $ipaddress = 'UNKNOWN';
    if($ipaddress == "::1"){$ipaddress = "localhost";}
    return $ipaddress;
  }

}
