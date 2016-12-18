<?php namespace KFall\oxymora\database\modals;
use PDO;
use PDOException;
use KFall\oxymora\database\DB;
use KFall\oxymora\config\Config;
use KFall\oxymora\memberSystem\Member;
use KFall\oxymora\memberSystem\Attribute;
use KFall\oxymora\memberSystem\MemberSystem;


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

    public static function getMember($id){
      $userTable = Config::get()['database-tables']['user'];
      $groupTable = Config::get()['database-tables']['groups'];
      $prep = DB::pdo()->prepare("SELECT `$userTable`.*,`$groupTable`.name as 'groupname',`$groupTable`.color as 'groupcolor'
        FROM `$userTable`
        JOIN `$groupTable`
        ON `$userTable`.`groupid`=`$groupTable`.`id`
        WHERE `$userTable`.`id`=:id
        ");
        $prep->bindValue(":id", $id);

        $success = $prep->execute();
        $result = ($success && $prep->rowCount() > 0) ? $prep->fetch(PDO::FETCH_ASSOC) : false;
        return $result;
      }

      public static function addMember($username, $password, $email, $image = null, $groupid = null){
        $m = new Member();
        $m->addAttr(new Attribute('username', $username));
        $m->addAttr(new Attribute('password', $password));
        $m->addAttr(new Attribute('email', $email));
        $m->addAttr(new Attribute('image', $image));
        $m->addAttr(new Attribute('groupid', $groupid));

        // REGISTER MEMBER
        try {
          $id = MemberSystem::init()->registerMember($m);
          return $id;
        } catch (Exception $e) {
          return false;
        }
      }

      public static function removeMember($id){
        try {
          return MemberSystem::init()->unregisterMember($id);
        } catch (Exception $e) {
          return false;
        }
      }


    }
