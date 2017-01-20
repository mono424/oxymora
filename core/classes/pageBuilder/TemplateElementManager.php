<?php namespace KFall\oxymora\pageBuilder;

class TemplateElementManager{

  public static function listElements($template, $showHidden = true){
    $templatePath = ROOT_DIR."../template/".$template."/_elements";
    $all = scandir($templatePath);
    $dirs = [];
    foreach($all as $item){
      $path = $templatePath."/".$item;
      if(strlen(trim($item, ".")) > 0 && is_dir($path)){
        $assoc['name'] = $item;
        $assoc['config'] = json_decode(file_get_contents($path."/config.json"), true);
        $assoc['thumb'] = (file_exists($path."/thumb.jpg"));
        $assoc['thumbUrl'] = ($assoc['thumb']) ? "template/".$template."/_elements/".$item."/thumb.jpg" : null ;
        if($showHidden || $assoc['config']['visible']){
          $dirs[] = $assoc;
        }
      }
    }
    return $dirs;
  }

  public static function findElement($template, $name){
    $templatePath = ROOT_DIR."../template/".$template."/_elements";
    $all = scandir($templatePath);
    foreach($all as $item){
      $path = $templatePath."/".$item;
      if(strlen(trim($item, ".")) > 0 && is_dir($path)){
        if($item == $name){
          $assoc['name'] = $item;
          $assoc['config'] = json_decode(file_get_contents($path."/config.json"), true);
          $assoc['thumb'] = (file_exists($path."/thumb.jpg"));
          $assoc['thumbUrl'] = ($assoc['thumb']) ? "template/".$template."/_elements/".$item."/thumb.jpg" : null ;
          return $assoc;
        }
      }
    }
    return false;
  }

  public static function loadElement($template, $name){
    $config = self::findElement($template, $name)['config'];
    $templatePath = ROOT_DIR."../template/".$template;
    $file = $templatePath."/_elements/$name/".$config['file'];
    $class = "template\\".$template."\\$name";
    if(file_exists($file)){
      require_once $file;
      return new $class;
    }
    return false;
  }

  public static function loadHTMLElement($template, $name){
    $config = self::findElement($template, $name)['config'];
    $templatePath = ROOT_DIR."../template/".$template;
    $file = $templatePath."/_elements/$name/".$config['file'];
    if(file_exists($file)){
      return file_get_contents($file);
    }
    return false;
  }
}
