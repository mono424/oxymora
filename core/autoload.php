<?php

function __autoload($className) {

  // Directories to search
  $classDirs = [
    "classes/",
    "interfaces/"
  ];

  // Check if file exists and require
  foreach ($classDirs as $dir) {
    $className = str_replace("KFall\\oxymora\\","",$className);
    $className = str_replace("\\","/",$className);
    $path =  ROOT_DIR.$dir.$className.'.php';
    if (file_exists($path)) {
      require_once $path;
      return true;
    }
  }

  return false;
}

?>
