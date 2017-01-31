<?php namespace KFall\oxymora\pageBuilder\errors;

class Error{
  public $nr;
  public $message;
  private $ignore = false;

  public function __construct($message, $nr){
    $this->nr = $nr;
    $this->message = $message;
  }

  public function ignore(){
    $this->ignore = true;
  }

  public function isIgnored(){
    return $this->ignore;
  }

}
