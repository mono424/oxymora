<?php namespace KFall\oxymora\addons;

class AddonManager{

  public static function listAll($showHidden = true){
    $mainpath = ROOT_DIR."..\\addons";
    $all = scandir($mainpath);
    $dirs = [];
    foreach($all as $item){
      $path = $mainpath."\\".$item;
      if(strlen(trim($item, ".")) > 0 && is_dir($path)){
        $assoc['name'] = $item;
        $assoc['config'] = json_decode(file_get_contents($path."\\config.json"), true);
        $assoc['icon'] = (file_exists($path."\\icon.png"));
        $assoc['iconUrl'] = ($assoc['icon']) ? "addons/".$item."/icon.png" : null ;
        if($showHidden || $assoc['config']['visible']){
          $dirs[] = $assoc;
        }
      }
    }
    return $dirs;
  }

  public static function find($name){
    $templatePath = ROOT_DIR."..\\addons";
    $all = scandir($templatePath);
    foreach($all as $item){
      $path = $templatePath."\\".$item;
      if(strlen(trim($item, ".")) > 0 && is_dir($path)){
        if($item == $name){
          $assoc['name'] = $item;
          $assoc['config'] = json_decode(file_get_contents($path."\\config.json"), true);
          $assoc['icon'] = (file_exists($path."\\icon.png"));
          $assoc['iconUrl'] = ($assoc['icon']) ? "addons/".$item."/icon.png" : null;
          return $assoc;
        }
      }
    }
    return false;
  }


}
