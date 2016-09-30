<?php namespace KFall\oxymora\pluginManager;

class PluginManager{

  public static function listPlugins($template){
    $templatePath = ROOT_DIR."..\\template\\".$template."\\_plugins";
    $all = scandir($templatePath);
    $dirs = [];
    foreach($all as $item){
      $path = $templatePath."\\".$item;
      if(strlen(trim($item, ".")) > 0 && is_dir($path)){
        $assoc['name'] = $item;
        $assoc['config'] = json_decode(file_get_contents($path."\\config.json"));
        $assoc['thumb'] = (file_exists($path."\\thumb.jpg"));
        $assoc['thumbUrl'] = ($assoc['thumb']) ? "template/".$template."/_plugins/".$item."/thumb.jpg" : null ;
        $dirs[] = $assoc;
      }
    }
    return $dirs;
  }

  public static function findPlugin($template, $name){
    $templatePath = ROOT_DIR."..\\template\\".$template."\\_plugins";
    $all = scandir($templatePath);
    foreach($all as $item){
      $path = $templatePath."\\".$item;
      if(strlen(trim($item, ".")) > 0 && is_dir($path)){
        if($item == $name){
          $assoc['name'] = $item;
          $assoc['config'] = json_decode(file_get_contents($path."\\config.json"));
          $assoc['thumb'] = (file_exists($path."\\thumb.jpg"));
          $assoc['thumbUrl'] = ($assoc['thumb']) ? "template/".$template."/_plugins/".$item."/thumb.jpg" : null ;
          return $assoc;
        }
      }
    }
    return false;
  }

  public static function loadPlugin($template, $name){
    $templatePath = ROOT_DIR."..\\template\\".$template;
    $file = $templatePath."\\_plugins\\$name\\$name.php";
    $class = "template\\".$template."\\$name";
    if(file_exists($file)){
      require_once $file;
      return new $class;
    }
    return false;
  }
}
