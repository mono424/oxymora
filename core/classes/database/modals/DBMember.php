<?php namespace KFall\oxymora\database\modals;
use PDO;
use PDOException;
use KFall\oxymora\database\DB;
use KFall\oxymora\config\Config;


class DBMember{

private $searchcolumns = ['username','email','role'];

public static function getList($searchCol = false, $search = false){
  $doSearch = ($searchCol && $search && in_array($searchCol,self::$searchcolumns));
  $s = $doSearch ? $s = " `$searchCol`=?" : "";

  $userTable = Config::get()['database-tables']['user'];
  $groupTable = Config::get()['database-tables']['groups'];
  $prep = DB::pdo()->prepare("SELECT `$userTable`.*,`$groupTable`.name as 'groupname',`$groupTable`.color as 'groupcolor'
                              FROM `$userTable`
                              JOIN `$groupTable`
                              ON `$userTable`.`groupid`=`$groupTable`.`id`
                              ".$s);
  if($doSearch){$prep->bindValue(1,$search,PDO::PARAM_STR);}

  $success = $prep->execute();
  $result = ($success && $prep->rowCount() > 0) ? $prep->fetchAll(PDO::FETCH_ASSOC) : false;
  return $result;
}


}
