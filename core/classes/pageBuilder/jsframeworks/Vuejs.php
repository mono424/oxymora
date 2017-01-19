<?php namespace KFall\oxymora\pageBuilder\jsframeworks;
use KFall\oxymora\database\modals\DBStatic;
use KFall\oxymora\pageBuilder\JSFrameworkBuilder;

class Vuejs implements JSFrameworkBuilder{

  public function attachScript(&$html, $config, $settings){
    $uniqid = 'vue'.substr(hash('sha256', mt_rand()), 0, 8);
    $selector = isset($config['selector']) ? $config['selector'] : false;

    if(!$selector && strpos('{vue-id}', $html) >= 0){
      $html = str_replace('{vue-id}', "data-vueid=\"$uniqid\"", $html);
      $selector = "*[data-vueid=$uniqid]";

    }

    $script = "<script>
    ".$config['var']." = new Vue({
      data: ".json_encode($settings)."
    });
    </script>";

    $attach = "<script>
      (function(){".$config['var'].".\$mount('$selector'); let x = ".$config['var']."; ".$config['var']." = null;})();
    </script>";

    $html = $script.$html.$attach;
    return $html;
  }


}
