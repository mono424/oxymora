<?php namespace KFall\oxymora\pageBuilder;

class ErrorBuilder{

  public static function printOut($code, $message){

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
