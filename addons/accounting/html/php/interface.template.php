<?php

interface invoiceTemplate(){

  public function __construct($nr, $date, $to, $items);
  public function getHtml();

}
