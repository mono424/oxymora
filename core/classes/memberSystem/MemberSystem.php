<?php namespace KFall\oxymora\memberSystem;
use Exception;
use PDO;
use KFall\oxymora\database\DB;

class MemberSystem{

  /*
  ----------------------------------------
  SETUP STUFF
  ----------------------------------------
  */

  // Predefined Member Columns
  private $pdc_id, $pdc_username, $pdc_password;

  // Predefined session Columns
  private $pdc_session_memberid, $pdc_session_sessionid, $pdc_session_token, $pdc_session_updated;

  // Predefined Attempt Columns
  private $pdc_attempt_memberid, $pdc_attempt_ip, $pdc_attempt_time;

  // Mysqli Connection
  private $con,$db,$tables;

  // Loggedin User
  public $member, $login_error, $sessionId, $sessionToken;

  // COOKIE INFO
  private $cookieName, $cookieExpire, $cookieDelmiter;

  // ATTEMPT SYSTEM INFO
  private $attemptCount, $attemptBlockExpire;

  // INIT - SINGLETON
  private static $init;
  public static function init($options = null){
    if(!isset(self::$init)){
      self::$init = new self($options);
    }
    return self::$init;
  }

  // CONSTRUCT
  private function __construct($options){
    // DEFAULT TABLES
    $this->tables['member'] = "member";
    $this->tables['session'] = "sessions";
    $this->tables['attempt'] = "login_attempts";
    // DEFAULT MEMBER COLUMNS
    $this->pdc_id = "id";
    $this->pdc_username = "username";
    $this->pdc_password = "password";
    // DEFAULT SESSION COLUMNS
    $this->pdc_session_memberid = "memberid";
    $this->pdc_session_sessionid = "session";
    $this->pdc_session_token = "token";
    $this->pdc_session_updated = "updated";
    // DEFAULT ATTEMPT COLUMNS
    $this->pdc_attempt_memberid = "memberid";
    $this->pdc_attempt_ip = "ip";
    $this->pdc_attempt_time = "time";
    // DEFAULT DB
    $this->db = "memberSystem";
    // NULL Values
    $this->member = null;
    $this->sessionId = null;
    $this->sessionToken = null;
    $this->login_error = null;
    // DEFAULT COOKIE
    $this->cookieName = "currentMember";
    $this->cookieExpire = 2147483647;
    $this->cookieDelmiter = '/';
    // DEFAULT ATTEMPT
    $this->attemptCount = 5;
    $this->attemptBlockExpire = 300; // 5min

    // OPTION OVERWRITE
    if(is_array($options)){
      foreach($options as $key => $option){
        switch(strtolower($key)){
          case 'cookie-name':
          $this->cookieName = $option;
          break;

          case 'cookie-expire':
          $this->cookieExpire = $option;
          break;

          case 'cookie-delmiter':
          $this->cookieDelmiter = $option;
          break;

          case 'database':
          $this->db = $option;
          break;

          case 'member-table':
          $this->tables['member'] = $option;
          break;

          case 'session-table':
          $this->tables['session'] = $option;
          break;

          case 'attempt-table':
          $this->tables['attempt'] = $option;
          break;

          case 'column-id':
          $this->pdc_id = $option;
          break;

          case 'column-username':
          $this->pdc_username = $option;
          break;

          case 'column-password':
          $this->pdc_password = $option;
          break;

          case 'session-column-memberid':
          $this->pdc_session_memberid = $option;
          break;

          case 'session-column-sessionid':
          $this->pdc_session_sessionid = $option;
          break;

          case 'session-column-token':
          $this->pdc_session_token = $option;
          break;

          case 'session-column-updated':
          $this->pdc_session_updated = $option;
          break;

          case 'attempt-column-memberid':
          $this->pdc_attempt_memberid = $option;
          break;

          case 'attempt-column-ip':
          $this->pdc_attempt_ip = $option;
          break;

          case 'attempt-column-time':
          $this->pdc_attempt_time = $option;
          break;

          case 'attempt-block-expire':
          $this->attemptBlockExpire = $option;
          break;

          case 'attempt-count':
          $this->attemptCount = $option;
          break;
        }
      }
    }
    DB::pdo()->query("USE ".$this->db);
  }




  /*
  ----------------------------------------
  DATABASE SETUP
  ----------------------------------------
  */

  public function setupdb($options = null){
    $tables = $this->tables;

    // Create Member Table
    $memberColumns = [
      ["name" => $this->pdc_id, "type" => "INT", "length" => 11, "extra" => "UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL"],
      ["name" => $this->pdc_username, "type" => "VARCHAR", "length" => 64, "extra" => "UNIQUE NOT NULL"],
      ["name" => $this->pdc_password, "type" => "VARCHAR", "length" => 64, "extra" => "NOT NULL"]
    ];
    if(isset($options) && isset($options['member-columns'])){
      foreach($options['member-columns'] as $column){
        $memberColumns[] = $column;
      }
    }

    $res = $this->createTable($tables['member'], $memberColumns);
    if(!$res){throw new Exception("Error while creating Member Table");}


    // Create session Table
    $sessionColumns = [
      ["name" => $this->pdc_session_memberid, "type" => "INT", "length" => 11, "extra" => "UNSIGNED NOT NULL"],
      ["name" => $this->pdc_session_sessionid, "type" => "VARCHAR", "length" => 64, "extra" => "UNIQUE NOT NULL"],
      ["name" => $this->pdc_session_token, "type" => "VARCHAR", "length" => 64, "extra" => "NOT NULL"],
      ["name" => $this->pdc_session_updated, "type" => "DATETIME", "extra" => "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"]
    ];
    $memberidColumn = $this->escape($this->pdc_session_memberid);
    $extraSql = "INDEX ($memberidColumn), FOREIGN KEY ($memberidColumn) REFERENCES ".$this->escape($tables['member'])."(".$this->escape($this->pdc_id).") ON UPDATE CASCADE ON DELETE CASCADE";

    $res = $this->createTable($tables['session'], $sessionColumns, $extraSql);
    if(!$res){throw new Exception("Error while creating session Table");}


    // Create attempt Table
    $attemptColumns = [
      ["name" => $this->pdc_attempt_memberid, "type" => "INT", "length" => 11, "extra" => "UNSIGNED NOT NULL"],
      ["name" => $this->pdc_attempt_ip, "type" => "VARCHAR", "length" => 16, "extra" => "NOT NULL"],
      ["name" => $this->pdc_attempt_time, "type" => "DATETIME", "extra" => "DEFAULT CURRENT_TIMESTAMP NOT NULL"]
    ];
    $memberidColumn = $this->escape($this->pdc_attempt_memberid);
    $extraSql = "INDEX ($memberidColumn), FOREIGN KEY ($memberidColumn) REFERENCES ".$this->escape($tables['member'])."(".$this->escape($this->pdc_id).") ON UPDATE CASCADE ON DELETE CASCADE";

    $res = $this->createTable($tables['attempt'], $attemptColumns, $extraSql);
    if(!$res){throw new Exception("Error while creating attempt Table");}

  }




  /*
  ----------------------------------------
  MEMBER FUNCTIONS
  ----------------------------------------
  */

  public function login($username, $password, $cookie = true){
    $table = $this->tables['member'];
    $res = $this->querySelect($table, [$this->pdc_username=>$username]);
    if($res && $res->rowCount() > 0){
      // INFO TO MEMBER-OBJECT
      $member = new Member($res->fetch(PDO::FETCH_ASSOC));
      // CHECK ATTEMPTS
      $attempts = $this->countAttempts($member->id);
      if($attempts >= $this->attemptCount){
        $this->login_error = "Too many failed attempts!";
        return false;
      }
      // CHECK PASSWORD AND (USERNAME AGAIN)
      if(password_verify($password, $member->getAttrValue($this->pdc_password)) && $member->getAttrValue($this->pdc_username) === $username){
        $this->member = $member;
        $this->clearAttempts();
        $this->createSession($cookie);
        return true;
      }else{
        $this->addAttempt($member->id);
        $this->login_error = "Wrong Username or Password!";
        return false;
      }
    }else{
      $this->login_error = "Wrong Username or Password!";
      return false;
    }
  }

  public function logout(){
    // delete session
    $this->deleteSession();
    // unset
    unset($this->member);
  }

  public function isLoggedIn(){
    return !empty($this->member);
  }

  // Update Database-Entry of loggedIn User
  public function updateDatabase(){
    // check logged in
    if(!$this->checkLoggedIn()){
      throw new Exception("No Member is logged in, please call Login-Function first!");
    }

    // get current Member
    $member = $this->member;

    // Get Attributes which have changed!
    $updateAttributes = [];
    foreach($member->getAttrsObject() as $attribute){
      if($attribute->valueChanged){
        $updateAttributes[] = $attribute;
      }
    }

    if(count($updateAttributes) > 0){
      // Create Updater Array
      $updateArray = [];
      foreach($updateAttributes as $attribute){
        $updateArray[$attribute->name] = $attribute->value;
      }
      // Update Query
      $res = $this->queryUpdate($this->tables['member'], $updateArray, [$this->pdc_id => $this->member->id->value]);
      if(!$res){
        throw new Exception("UPDATE ERROR");
      }
    }

    return true;
  }

  // Update Loggedin User from Database-Entry
  public function updateMember(){
    // check logged in
    if(!$this->checkLoggedIn()){
      throw new Exception("No Member is logged in, please call Login-Function first!");
    }

    // get Member
    $member = $this->member;

    // update Member
    $table = $this->tables['member'];
    $res = $this->querySelect($table, [$this->pdc_id=>$this->member->id->value]);
    if($res && $res->rowCount() > 0){
      $vars = $res->fetch(PDO::FETCH_ASSOC);
      $legitKeys = [];
      foreach($vars as $key => $value){
        if(!$member->attrExists($key)){
          $member->addAttr(new Attribute($key, $value));
        }else{
          $member->getAttr($key)->setValue($value, true);
        }
        $legitKeys[] = $key;
      }
      foreach($member->getAttrsObject() as $attribute){
        $found = false;
        foreach($legitKeys as $key){
          if($attribute->name == $key){
            $found = true;
            break;
          }
        }
        if(!$found){
          $member->removeAttr($attribute->name);
        }
      }
    }else{
      throw new Exception("REMOVE ERROR");
    }
  }

  public function memberExists($username){
    $res = $this->querySelect($this->tables['member'], [$this->pdc_username=>$username]);
    if($res && $res->rowCount() > 0){
      return true;
    }else{
      return false;
    }
  }

  public function checkLoggedIn(){
    if(is_null($this->member) || !$this->member){
      return false;
    }else{
      return true;
    }
  }




  /*
  ----------------------------------------
  REGISTER FUNCTIONS
  ----------------------------------------
  */

  public function registerMember($member){
    if ($member instanceof Member) {
      // TABLE
      $table = $this->tables['member'];

      // GET ATTRIBUTES
      $attrs = $member->getAttrsAssoc();

      // CHECK MUST HAVE PARAMS
      if(!isset($attrs[$this->pdc_username]) || empty($attrs[$this->pdc_username])){throw new Exception("Please set '$this->pdc_username'!");}
      if(!isset($attrs[$this->pdc_password]) || empty($attrs[$this->pdc_password])){throw new Exception("Please set '$this->pdc_password'!");}

      // CHECK IF EXISTS
      if($this->memberExists($attrs[$this->pdc_username]['value'])){throw new Exception("Member with Username '".$attrs[$this->pdc_username]['value']."' does already exist!");}

      // PASSWORD HASH
      $attrs[$this->pdc_password]['value'] = password_hash($attrs[$this->pdc_password]['value'], PASSWORD_BCRYPT);

      // CREATE INSERT ARRAY
      foreach($attrs as $attr){
        $arr[$attr['name']] = $attr['value'];
      }

      // INSERT
      if(!$this->queryInsert($table,$arr)){
        throw new Exception("INSERT ERROR");
      }else{
        return DB::pdo()->lastInsertId();
      }

    }else{throw new Exception('$member has to be a Member-Object');}
  }

  public function unregisterMember($id){
    // TABLE
    $table = $this->tables['member'];
    // DELETE
    $res = $this->query("DELETE FROM ".$this->escape($table)." WHERE ".$this->escape($this->pdc_id)."=".$this->escape($id, true, "'"));
    if(!$res){throw new Exception("DELETE ERROR");}
    return true;
  }




  /*
  ----------------------------------------
  SESSION FUNCTIONS
  ----------------------------------------
  */

  public function loginByCookie(){
    $success = $this->parseSessionCookie();
    if(!$success){return false;}
    $memberid = $this->getMemberIdFromSession($this->sessionId, $this->sessionToken);
    if(!$memberid){$this->login_error = "Cookie is invalid!"; return false;}

    $table = $this->tables['member'];
    $res = $this->querySelect($table, [$this->pdc_id=>$memberid]);
    if($res && $res->rowCount() > 0){
      // INFO TO MEMBER-OBJECT
      $member = new Member($res->fetch(PDO::FETCH_ASSOC));
      $this->member = $member;
      // $this->updateSession(); // Lower Security but doesnt work witrh all the ajax stuff ..
      return true;
    }else{
      $this->login_error = "User not found!";
      return false;
    }
  }

  public function updateSession(){
    // check logged in
    if(!$this->checkLoggedIn()){
      throw new Exception("No Member is logged in, please call Login-Function first!");
    }

    // get Member
    $member = $this->member;

    // get Vars
    $table = $this->tables['session'];
    $session = $this->sessionId;

    // Update Session
    $token = $this->randomPass(64);
    $res = $this->queryUpdate($table, ["token" => $token], ["session" => $session, "memberid" => $member->id->value]);
    if(!$res){
      throw new Exception("UPDATE ERROR");
    }

    // update MemberSystem
    $this->sessionToken = $token;
    $this->updateSessionCookie();
  }

  public function deleteSession(){
    // TODO: delete Session
    $this->deleteSessionCookie();
  }

  private function createSession($cookie = true){
    // check logged in
    if(!$this->checkLoggedIn()){
      throw new Exception("No Member is logged in, please call Login-Function first!");
    }

    // get Member
    $member = $this->member;

    // get Table
    $table = $this->tables['session'];

    // Create Session
    $session = $this->generateSession();
    $token = $this->randomPass(64);
    $res = $this->queryInsert($table, ["memberid" => $member->id->value,"session" => $session, "token" => $token]);
    if(!$res){
      throw new Exception("INSERT ERROR");
    }

    // update MemberSystem
    $this->sessionId = $session;
    $this->sessionToken = $token;
    $this->updateSessionCookie($cookie, true);
  }

  private function generateSession(){
    //CREATE SESSION ID
    do{
      $session = $this->randomPass(64);
    }while($this->sessionExists($session));
    return $session;
  }

  private function sessionExists($session){
    // TABLE
    $table = $this->tables['session'];

    // check if exists
    $res = $this->querySelect($table, ["session" => $session]);
    if($res && $res->rowCount() > 0){
      return true;
    }else{
      return false;
    }
  }

  private function getMemberIdFromSession($session,$token){
    // TABLE
    $table = $this->tables['session'];

    // check if exists
    $res = $this->querySelect($table, ["session" => $session, "token" => $token], "memberid");
    if($res && $res->rowCount() > 0){
      $arr = $res->fetch(PDO::FETCH_ASSOC);
      return $arr["memberid"];
    }else{
      return false;
    }
  }

  private function updateSessionCookie($cookie = true, $forceOverwriteExpire = false){
    // check logged in
    if(!$this->checkLoggedIn()){
      throw new Exception("No Member is logged in, please call Login-Function first!");
    }

    // check session is set
    if(!empty($this->sessionId) && !empty($this->sessionToken)){
      // VARS
      $session = $this->sessionId;
      $token = $this->sessionToken;

      // CREATE VALUE
      $expire = null;
      if(isset($_COOKIE[$this->cookieName]) && !$forceOverwriteExpire){
        $expldd = explode($this->cookieDelmiter, $_COOKIE[$this->cookieName], 3);
        if(count($expldd) == 3 && is_numeric($expldd[2])){
          $expire = $expldd[2];
        }
      }
      if(is_null($expire)){
        $expire = $cookie ? $this->cookieExpire : 0;
      }

      $value = $session.$this->cookieDelmiter.$token.$this->cookieDelmiter.$expire;

      // SET COOKIE
      setcookie($this->cookieName, $value, $expire, "/");
    }else{
      throw new Exception("Session not set!");
    }
  }

  function parseSessionCookie(){
    $cookiename = $this->cookieName;
    $delimiter = $this->cookieDelmiter;

    // IF COOKIE EXISTS
    if(!isset($_COOKIE[$cookiename])){return false;}

    $info = explode($delimiter, $_COOKIE[$cookiename], 3);

    // CHECK COOKIE CORRUPTED
    if(count($info)<2){$this->deleteSessionCookie();return false;}

    // I BRING SOME STRUCTURE IN IT :)
    $this->sessionId = $info[0];
    $this->sessionToken = $info[1];

    return true;
  }

  private function deleteSessionCookie(){
    $cookiename = $this->cookieName;
    if(isset($_COOKIE[$cookiename])){unset($_COOKIE[$cookiename]);setcookie($cookiename, null, -1, '/');}
  }




  /*
  ----------------------------------------
  ATTEMPT-SYSTEM FUNCTIONS
  ----------------------------------------
  */

  private function addAttempt($memberid){
    // TABLE
    $table = $this->tables['attempt'];
    // CLIENT IP
    $ip = $this->getClientIP();
    // INSERT
    $res = $this->queryInsert($table, [
      $this->pdc_attempt_ip => $ip,
      $this->pdc_attempt_memberid => $memberid
    ]);
    if(!$res){throw new Exception("INSERT ERROR");}
    return true;
  }

  public function countAttempts($memberid){
    // TABLE
    $table = $this->tables['attempt'];

    // CALC ATTEMPT VALID TIME
    $ts = time(); // Current Time as Timestamp
    $ts -= $this->attemptBlockExpire; // minus the config time
    $date = date("Y-m-d H:i:s", $ts); // to human datetime

    // WHERE STRING
    $where = $this->escape($this->pdc_attempt_memberid)."=".$this->escape($memberid,true,"'")." AND ".$this->escape($this->pdc_attempt_time).">".$this->escape($date, true, "'");

    // SELECT
    $res = $this->querySelect($table, $where, $this->pdc_attempt_memberid, $limit = false);
    if(!$res){throw new Exception("SELECT ERROR");}

    // COUNT & RETURN
    return $res->rowCount();
  }

  public function clearAttempts(){
    // TABLE
    $table = $this->tables['attempt'];

    // CHECK MEMBER IS SET
    if(!$this->checkLoggedIn()){
      throw new Exception("No Member is logged in, please call Login-Function first!");
    }

    // DELETE
    $res = $this->query("DELETE FROM ".$this->escape($table)." WHERE ".$this->escape($this->pdc_attempt_memberid)."=".$this->escape($this->member->id, true, "'"));
    if(!$res){throw new Exception("DELETE ERROR");}
    return true;
  }




  /*
  ----------------------------------------
  PRIVATE MYSQL FUNCTIONS
  ----------------------------------------
  */

  private function createTable($table, $columns, $extraColumnSql = null){
    // escape table
    $table = $this->escape($table);

    // sql start
    $sql = "CREATE TABLE IF NOT EXISTS $table";

    // create columns
    $columnsSql = "( ";

    // Columns
    foreach($columns as $column){
      $name = $this->escape($column['name']);
      $type = $column['type'];
      if((isset($column['length']) && !empty($column['length'])) && !is_numeric($column['length'])){throw new Exception('Length has to be Numeric!');}
      $length = (isset($column['length']) && !empty($column['length'])) ? "(".$column['length'].")" : "";
      $extra = (isset($column['extra']) && !empty($column['extra'])) ? " ".$column['extra'] : "";
      $columnsSql .= "$name $type$length$extra, ";
    }

    // add to sql
    $sql .= substr($columnsSql, 0, -2);

    // add extra sql
    if(!empty($extraColumnSql)){
      $sql .= ", ".$extraColumnSql;
    }

    // finish sql
    $sql .= ") CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB;";
    // do query
    return $this->query($sql);
  }

  private function query($sql){
    // Get Database Connection and Check
    $con = DB::pdo();
    if(!$con){throw new Exception('Database Connection not setup!');}

    // query and return
    $res = $con->query($sql);
    return $res;
  }

  private function queryInsert($table, $valuepairs){
    // table
    $table = $this->escape($table);

    // Create Columns & Values
    if(count($valuepairs) < 1){throw new Exception('Empty $valuepairs!');}
    $columns = "";
    $values = "";
    foreach($valuepairs as $key => $value){
      $columns .= ",".$this->escape($key);
      $values .= ",".$this->escape($value, true, "'");
    }
    $columns = substr($columns,1);
    $values = substr($values,1);

    // create SQL
    $sql = "INSERT INTO $table ($columns) VALUES ($values)";

    // do query
    return $this->query($sql);
  }

  private function queryUpdate($table, $valuepairs, $indentifier){
    // table
    $table = $this->escape($table);

    // Create Columns & Values
    if(count($valuepairs) < 1){throw new Exception('Empty $valuepairs!');}
    $setSql = "";
    foreach($valuepairs as $key => $value){
      $setSql .= ",".$this->escape($key)."=".$this->escape($value, true, "'");
    }
    $setSql = substr($setSql,1);

    // Create Where SQL
    if(count($indentifier) < 1){throw new Exception('Empty $valuepairs!');}
    $whereSql = "";
    foreach($indentifier as $key => $value){
      $whereSql = ",".$this->escape($key)."=".$this->escape($value, true, "'");
    }
    $whereSql = substr($whereSql,1);

    // create SQL
    $sql = "UPDATE $table SET $setSql WHERE $whereSql";

    // do query
    return $this->query($sql);
  }

  private function querySelect($table, $where, $what = "/*", $limit = false){
    // table
    $table = $this->escape($table);

    // create WHAT SQL
    if(is_array($what)){
      foreach($what as $w){
        $whatString .= ",".$this->escape($w);
      }
      $whatString = substr($whatString,1);
    }else{
      $whatString = $this->escape($what);
    }

    // create WHERE SQL
    if(is_array($where)){
      foreach($where as $key => $value){
        $whereString = "AND ".$this->escape($key)."=".$this->escape($value, true, "'");
      }
      $whereString = substr($whereString,4);
    }elseif($where){
      $whereString = $where;
    }else{
      $whereString = "1=1";
    }

    // create LIMIT SQL
    if($limit && is_numeric($limit)){
      $whereString .= " LIMIT $limit";
    }elseif($limit){
      throw new Exception('Limit is not numeric!');
    }

    $sql = "SELECT $whatString FROM $table WHERE $whereString";
    // do query
    return $this->query($sql);
  }

  // ESCAPE IF NOT STARTS WITH /. IF FORCE IS TRUE IT ESCAPES STRINGS STARTING WITH / AS WELL
  private function escape($string, $force = false, $outChar = "`"){
    // Get Database Connection and Check
    $con = DB::pdo();
    if(!$con){throw new Exception('Database Connection not setup!');}

    // Escape
    if(is_string($string) && strpos($string, '/') === 0 && !$force){
      $outstring = substr($string, 1);
    }else{
      $string = trim($con->quote($string),"'");
      $outstring = $outChar.$string.$outChar;
    }
    return $outstring;
  }





  /*
  ----------------------------------------
  PRIVATE HELP FUNCTIONS
  ----------------------------------------
  */

  // generates random string
  private function randomPass($len = 32) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $l = strlen($chars) - 1;
    $str = '';
    for ($i = 0; $i < $len; ++$i) {
      $str .= $chars[mt_rand(0, $l)];
    }
    return $str;
  }

  // GET CLIENT IP
  private function getClientIP(){
    $ip = null;
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }

}























class Member{
  private $attrs = [];

  /*
  ----------------------------------------
  CONSTRUCT
  ----------------------------------------
  */

  public function __construct($assoc = null){
    if(!empty($assoc)){
      foreach($assoc as $key => $value){
        $this->addAttr(new Attribute($key, $value));
      }
    }
  }




  /*
  ----------------------------------------
  GET / SET
  ----------------------------------------
  */

  public function __get($key){
    return $this->getAttr($key);
  }

  public function __set($key, $value){
    return $this->setAttr($key,$value);
  }




  /*
  ----------------------------------------
  ADD/REMOVE ATTRIBUTES
  ----------------------------------------
  */

  public function addAttr($attr){
    if ($attr instanceof Attribute) {
      if($this->attrExists($attr->name)){
        throw new Exception('Attribute with name "'.$attr->name.'" already exists!');
      }
      $this->attrs[$attr->name] = $attr;
      return true;
    }else{
      throw new Exception('$attr as to be a Attribute-Object!');
    }
  }

  public function removeAttr($name){
    if($this->attrExists($name)){
      unset($this->attrs[$name]);
      return true;
    }else{
      throw new Exception('Attribute with name "'.$name.'" not found!');
    }
  }




  /*
  ----------------------------------------
  SET ATTRIBUTES
  ----------------------------------------
  */

  public function setAttr($name, $value){
    $attr = $this->findAttr($name);
    if($attr !== false){
      $attr->value = $value;
      return true;
    }else{
      throw new Exception('Attribute with name "'.$name.'" not found!');
    }
  }




  /*
  ----------------------------------------
  GET ATTRIBUTES
  ----------------------------------------
  */

  public function getAttr($name){
    $attr = $this->findAttr($name);
    if($attr !== false){
      return $attr;
    }else{
      throw new Exception('Attribute with name "'.$name.'" not found!');
    }
  }

  public function getAttrValue($name){
    $attr = $this->findAttr($name);
    if($attr !== false){
      return $attr->value;
    }else{
      throw new Exception('Attribute with name "'.$name.'" not found!');
    }
  }

  public function getAttrColumn($name){
    $attr = $this->findAttr($name);
    if($attr !== false){
      return $attr->value;
    }else{
      throw new Exception('Attribute with name "'.$name.'" not found!');
    }
  }

  public function getAttrAssoc($name){
    $attr = $this->findAttr($name);
    if($attr !== false){
      return $attr->assoc;
    }else{
      throw new Exception('Attribute with name "'.$name.'" not found!');
    }
  }

  public function getAttrsAssoc(){
    foreach($this->attrs as $key => $attr){
      $out[$key] = $attr->assoc();
    }
    return $out;
  }

  public function getAttrsObject(){
    return $this->attrs;
  }

  public function attrExists($name){
    return array_key_exists($name, $this->attrs);
  }


  /*
  ----------------------------------------
  PRIVATE FUNCTIONS
  ----------------------------------------
  */

  private function findAttr($name){
    if($this->attrExists($name)){
      return $this->attrs[$name];
    }
    return false;
  }

}


















class Attribute{
  private $name, $oValue, $valueChanged, $value, $type;

  public function __construct($name, $value = null){
    $this->name = $name;
    $this->setValue($value, true);
    $this->hasChanged = false;
  }

  public function __get($name){
    if($name == "name"){
      return $this->name;
    }elseif($name == "valueChanged"){
      return $this->valueChanged;
    }elseif($name == "value"){
      return $this->value;
    }else{
      throw new Exception("Requested Property does not Exist!");
    }
  }

  public function __set($name, $value){
    if($name == "name"){
      throw new Exception('Not allowed to change the Name of an Attribute!');
    }elseif($name == "valueChanged"){
      if($value == true){
        $this->valueChanged = true;
      }elseif($value == false){
        $this->resetValueChanged();
      }else{
        throw new Exception('Given Value is not BOOL!');
      }
    }elseif($name == "value"){
      $this->setValue($value);
    }
  }

  public function __tostring(){
    return $this->value;
  }

  public function assoc(){
    $assoc['name'] = $this->name;
    $assoc['value'] = $this->value;
    return $assoc;
  }

  private function resetValueChanged(){
    if($this->type = "String"){
      $this->oValue = crc32($this->value);
    }else{
      $this->oValue = $this->value;
    }
    $this->valueChanged = false;
  }

  private function checkChanged(){
    if($this->type = "String"){
      $this->valueChanged = (crc32($this->value) !== $this->oValue);
    }else{
      $this->valueChanged = ($this->value !== $this->oValue);
    }
  }

  public function setValue($value, $original = false){
    if(is_string($value)){
      $this->type = "String";
    }elseif(is_integer($value)){
      $this->type = "Integer";
    }elseif(is_bool($value)){
      $this->type = "Boolean";
    }elseif(is_null($value)){
      $this->type = "NULL";
    }else{
      throw new Exception("Only NULL, String, Integer & Boolean allowed as Value");
    }

    if($this->value !== $value || $original){
      $this->value = $value;
      if($original){
        $this->resetValueChanged();
      }else{
        $this->checkChanged();
      }
    }
  }

}


?>
