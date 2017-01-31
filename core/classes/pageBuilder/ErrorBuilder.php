<?php namespace KFall\oxymora\pageBuilder;
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\pageBuilder\errors\PageError;

class ErrorBuilder{

  public static function throwError($code, $message, $page){
    // Error Object
    $error = new PageError($message, $code, $page);

    // run error handler addons
    AddonManager::triggerEvent(ADDON_EVENT_PAGEERROR, $error);

    // check if the error still should be displayed
    if(!$error->isIgnored()){
      // Load Error Template
      $html = file_get_contents(ROOT_DIR."html/error.html");
      $html = str_replace("{errorcode}",$code,$html);
      $html = str_replace("{errormessage}",$message,$html);

      // Send Error Code and Body to Browser :)
      http_response_code($code);
      echo $html;
      return true;
    }
  }

}
