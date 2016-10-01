<?php

function html_navItem($display, $id, $title, $url){
  return '<div data-display="'.$display.'" data-id="'.$id.'" class="navitem">
    <div class="title">'.$title.'</div>
    <div class="url">'.$url.'</div>
    <div class="buttonbar">
      <button data-action="displayUp" type="button"><i class="fa fa-arrow-up" aria-hidden="true"></i></button>
      <button data-action="displayDown" type="button"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
      <button data-action="edit" type="button"><i class="fa fa-pencil" aria-hidden="true"></i></button>
      <button data-action="remove" class="red" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
    </div>
  </div>';
}

function html_pageItem($url){
  return '<div data-page="'.$url.'" class="pageitem">
    <button class="deletePageButton" type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
    <div class="icon"><i class="fa fa-chrome" aria-hidden="true"></i></div>
    <div class="title">'.$url.'</div>
  </div>
  ';
}
