<?php namespace KFall\oxymora\logs;

class Logger{

  public static $skeleton = "[time] >> [type] >> [message]";

  public static function log($message, $type = "notice", $logfile = "main.log"){
    if(!preg_match("/[a-z0-9\_\-]*\.log/Ui",$logfile)){return false;}
    $text = str_replace("[time]", date("Y-m-d H:i:s"), self::$skeleton);
    $text = str_replace("[type]", str_pad($type, 16, " ", STR_PAD_BOTH), $text);
    $text = str_replace("[message]", $message, $text);
    file_put_contents(LOGS_DIR."/$logfile", $text."\n\n", FILE_APPEND);
    return true;
  }

}

?>
