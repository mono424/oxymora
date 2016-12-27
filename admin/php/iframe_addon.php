<?php
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\permissions\UserPermissionManager;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();

$name = isset($_GET['addon']) ? $_GET['addon'] : die('Plugin not found!');
$page = isset($_GET['page']) ? $_GET['page'] : 'index.php';
if(!preg_match("/^[A-Za-z0-9\-\_]*$/",$page)){die('Illigal Page!');}

$addon = AddonManager::find($name);
if(!$addon['installed']){die('Addon not installed!');}
if(!$addon['installed']['active']){die('Addon not active!');}

if(!file_exists($addon['html'])){
  die("html Folder not found!");
}

chdir($addon['html']);

if($addon['config']['template'] === ADDON_TEMPLATE_DEFAULT){
  ?>
  <!DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Oxymora | Addon</title>
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/master.css">
    <link rel="stylesheet" href="../../css/menuToggle.css">
    <link rel="stylesheet" href="../../css/content.css">
    <link rel="stylesheet" href="../../css/widget.css">
    <?php
    foreach(scandir('css') as $css){
      if($css == "." || $css == "..") continue;
      echo '<link rel="stylesheet" href="css/'.$css.'">'."\n";
    }
    ?>
    <script src="../../js/lib/jquery-3.1.1.min.js"></script>
    <script type="text/javascript">
      let oxymora = window.parent;
    </script>
  </head>
  <body>
    <?php
  }

  $permissionManager = new UserPermissionManager($name);

  require "$page.php";


  if($addon['config']['template'] === ADDON_TEMPLATE_DEFAULT){
    ?>
    <script src="../../js/master/functions.js" charset="utf-8"></script>
    <?php
    foreach(scandir('js') as $js){
      if($js == "." || $js == "..") continue;
      echo '<script src="js/'.$js.'"></script>'."\n";
    }
    ?>
  </body>
  </html>
  <?php
}
?>
