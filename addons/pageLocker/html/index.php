<?php
use KFall\oxymora\config\Config;
use KFall\oxymora\database\DB;

$table = "pageLocker_locked";
$tablePages = Config::get()['database-tables']['pages'];
$pdo = DB::pdo();

// GET LOCKED PAGES
$prep = $pdo->prepare("SELECT `page` FROM `$table`");
$success = $prep->execute();
if(!$success){die('something went wrong!');}
$pagesLocked = $prep->fetchAll(PDO::FETCH_COLUMN, 0);



// API CALLS
if(isset($_POST['page'])){
  try{
    if(in_array($_POST['page'], $pagesLocked) == true){
      $prep = $pdo->prepare("DELETE FROM `$table` WHERE `page`=:page");
      $prep->bindValue(':page', $_POST['page']);
      $success = $prep->execute();
      die('0'); // new lock state
    }else{
      $prep = $pdo->prepare("INSERT INTO `$table`(`page`) VALUES (:page)");
      $prep->bindValue(':page', $_POST['page']);
      $success = $prep->execute();
      die('1'); // new lock state
    }
  }catch(Exception $e){
    die('Error while writing to database. '.$e->getMessage());
  }

}




// GET LATEST OPENDED PAGES
$prep = $pdo->prepare("SELECT * FROM `$tablePages`");
$success = $prep->execute();
if(!$success){die('something went wrong!');}
$pages = $prep->fetchAll(PDO::FETCH_ASSOC);


$pages = array_map(function($item){
  global $pagesLocked;
  $item['locked'] = (in_array($item['url'], $pagesLocked));
  return $item;
},$pages);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.2.0/css/mdb.min.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js" charset="utf-8"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.js" charset="utf-8"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
  <div id="app">

    <pagelist :pages='<?php echo (json_encode($pages)); ?>'></pagelist>

  </div>
  <script type="text/javascript">
  let pages = <?php echo json_encode($pages); ?>;
  </script>
  <script src="js/components.js" charset="utf-8"></script>
  <script src="js/app.js" charset="utf-8"></script>
</body>
</html>
