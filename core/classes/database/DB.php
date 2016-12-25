<?php namespace KFall\oxymora\database;
use PDO;
use PDOException;


class DB{

  public static $connectionError;
  private static $pdo;

  public static function pdo(){
    return self::$pdo;
  }

  public static function connect($host, $user, $pass, $db){
    try {
      self::$pdo = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
      self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      self::$pdo->exec('SET NAMES UTF8');
      self::$connectionError = null;
      return true;
    } catch (PDOException $e) {
      self::$connectionError = $e;
      return false;
    }
  }

}
