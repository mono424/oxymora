<?php namespace KFall\oxymora\pageBuilder;

class TemplatePluginManager{

  public static function listPlugins($template, $showHidden = true){
    $templatePath = ROOT_DIR."../template/".$template."/_plugins";
    $all = scandir($templatePath);
    $dirs = [];
    foreach($all as $item){
      $path = $templatePath."/".$item;
      if(strlen(trim($item, ".")) > 0 && is_dir($path)){
        $assoc['name'] = $item;
        $assoc['config'] = json_decode(file_get_contents($path."/config.json"), true);
        $assoc['thumb'] = (file_exists($path."/thumb.jpg"));
        $assoc['thumbUrl'] = ($assoc['thumb']) ? "template/".$template."/_plugins/".$item."/thumb.jpg" : null ;
        if($showHidden || $assoc['config']['visible']){
          $dirs[] = $assoc;
        }
      }
    }
    return $dirs;
  }

  public static function findPlugin($template, $name){
    $templatePath = ROOT_DIR."../template/".$template."/_plugins";
    $all = scandir($templatePath);
    foreach($all as $item){
      $path = $templatePath."/".$item;
      if(strlen(trim($item, ".")) > 0 && is_dir($path)){
        if($item == $name){
          $assoc['name'] = $item;
          $assoc['config'] = json_decode(file_get_contents($path."/config.json"), true);
          $assoc['thumb'] = (file_exists($path."/thumb.jpg"));
          $assoc['thumbUrl'] = ($assoc['thumb']) ? "template/".$template."/_plugins/".$item."/thumb.jpg" : null ;
          return $assoc;
        }
      }
    }
    return false;
  }

  public static function loadPlugin($template, $name){
    $config = self::findPlugin($template, $name)['config'];
    $templatePath = ROOT_DIR."../template/".$template;
    $file = $templatePath."/_plugins/$name/".$config['file'];
    $class = "template\\".$template."\\$name";
    if(file_exists($file)){
      require_once $file;
      return new $class;
    }
    return false;
  }

  public static function loadHTMLPlugin($template, $name){
    $config = self::findPlugin($template, $name)['config'];
    $templatePath = ROOT_DIR."../template/".$template;
    $file = $templatePath."/_plugins/$name/".$config['file'];
    if(file_exists($file)){
      return file_get_contents($file);
    }
    return false;
  }
}
