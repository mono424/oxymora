<?php
use KFall\oxymora\config\Config;
use KFall\oxymora\database\DB;

$pdo = DB::pdo();

switch($_POST['api']){

  case 'delete':
  $prep = $pdo->prepare("SELECT * FROM `$table_builds` WHERE `id`=:id ");
  $prep->bindValue(':id', $_POST['id']);
  if(!$prep->execute()) die('0');
  $item = $prep->fetch(PDO::FETCH_ASSOC);
  unlink($item['file']);

  $prep = $pdo->prepare("DELETE FROM `$table_builds` WHERE `id`=:id");
  $prep->bindValue(':id', $_POST['id']);
  echo ($prep->execute()) ? "1" : "0";
  break;

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
    $packtype = ($_POST['fullpack'] == "1") ? "full" : "update";

    $prep = $pdo->prepare("INSERT INTO `$table_builds`(`version`, `description`, `packtype`, `filesize`, `hash`, `file`) VALUES (:version, :description, :packtype, :filesize, :hash, :file)");
    $prep->bindValue(':version', $_POST['version']);
    $prep->bindValue(':description', $_POST['description']);
    $prep->bindValue(':packtype', $packtype);
    $prep->bindValue(':filesize', $filesize);
    $prep->bindValue(':hash', $hash);
    $prep->bindValue(':file', $filename);
    $success = $prep->execute();
    echo "1";
  }
  break;

}
?>
