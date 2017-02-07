<?php
use KFall\oxymora\config\Config;
use KFall\oxymora\database\DB;

$table_users = "oxymora_packagemanager_users";
$table_templates = "oxymora_packagemanager_templates";
$pdo = DB::pdo();



// API CALLS
if(isset($_POST['page'])){

}

// GET PACKAGES
$pdo = DB::pdo();
$prep = $pdo->prepare("SELECT * FROM `$table_templates`
                       LEFT JOIN `$table_users` ON `$table_users`.`id`=`author`
                       GROUP BY `name`");
$success = $prep->execute();
if(!$success){throw new Exception('Oxymora suffered from a database failure.');}
$packages = $prep->fetchAll(PDO::FETCH_ASSOC);

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
  <p>This app isnt finished yet, its just for overview.</p>
  <div id="app"></div>
  <script type="text/javascript">
  var packages = JSON.parse('<?php echo json_encode($packages); ?>');
  </script>
  <script src="js/components.js" charset="utf-8"></script>
  <script src="js/app.js" charset="utf-8"></script>
</body>
</html>
