<?php namespace KFall\oxymora\pageBuilder\errors;

class PageError extends Error{
  public $page;

  public function __construct($message, $nr, $page){
    $this->nr = $nr;
    $this->message = $message;
    $this->page = $page;
  }

}
