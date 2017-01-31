<?php
use KFall\oxymora\config\Config;
use KFall\oxymora\database\DB;

$tablePages = Config::get()['database-tables']['pages'];
$pdo = DB::pdo();

switch($_POST['api']){

  case 'get':
  $prep = $pdo->prepare("SELECT * FROM `$table_builds` ORDER BY `id` DESC");
  $success = $prep->execute();
  if(!$success){die('something went wrong!');}
  $builds = $prep->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($builds);
  break;

  case 'add':
  if(!file_exists($uploadDir)) mkdir($uploadDir);
  if(isset($_FILES['file'])){

    if($_FILES['file']['type']!=='application/x-zip-compressed') die('error');

    do{
      $filename = $uploadDir.'/oxymora_'.uniqid().".zip";
    }while(file_exists($filename));

    move_uploaded_file($_FILES['file']['tmp_name'], $filename);
    $hash = hash_file('md5', $filename);
    $filesize = filesize($filename);

    $prep = $pdo->prepare("INSERT INTO `$table_builds`(`version`, `description`, `filesize`, `hash`, `file`) VALUES (:version, :description, :filesize, :hash, :file)");
    $prep->bindValue(':version', $_POST['version']);
    $prep->bindValue(':description', $_POST['description']);
    $prep->bindValue(':filesize', $filesize);
    $prep->bindValue(':hash', $hash);
    $prep->bindValue(':file', $filename);
    $success = $prep->execute();
    echo "1";
  }
  break;

}
?>
