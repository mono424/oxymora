<?php
require_once __DIR__.'../php/interface.template.php';

class Template implements invoiceTemplate{

  private $nr, $date, $to, $items, $cash, $tax;

  public function __construct($nr, $date, $to, $items, $cash = "€", $tax = 0.19){
    $this->nr = $nr;
    $this->date = $date;
    $this->to = $to;
    $this->items = $items;
    $this->cash = $cash;
    $this->tax = $tax;
  }

  public function getHtml(){
    $html = '<!DOCTYPE html>
    <html>
    <head>
    <title>HTML to API - Invoice</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,700&subset=latin,latin-ext" rel="stylesheet" type="text/css">
    <!-- <link rel="stylesheet" href="sass/main.css" media="screen" charset="utf-8"/> -->
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta http-equiv="content-type" content="text-html; charset=utf-8">
    <style type="text/css">
    html, body, div, span, applet, object, iframe,
    h1, h2, h3, h4, h5, h6, p, blockquote, pre,
    a, abbr, acronym, address, big, cite, code,
    del, dfn, em, img, ins, kbd, q, s, samp,
    small, strike, strong, sub, sup, tt, var,
    b, u, i, center,
    dl, dt, dd, ol, ul, li,
    fieldset, form, label, legend,
    table, caption, tbody, tfoot, thead, tr, th, td,
    article, aside, canvas, details, embed,
    figure, figcaption, footer, header, hgroup,
    menu, nav, output, ruby, section, summary,
    time, mark, audio, video {
      margin: 0;
      padding: 0;
      border: 0;
      font: inherit;
      font-size: 100%;
      vertical-align: baseline;
    }

    html {
      line-height: 1;
    }

    ol, ul {
      list-style: none;
    }

    table {
      border-collapse: collapse;
      border-spacing: 0;
    }

    caption, th, td {
      text-align: left;
      font-weight: normal;
      vertical-align: middle;
    }

    q, blockquote {
      quotes: none;
    }
    q:before, q:after, blockquote:before, blockquote:after {
      content: "";
      content: none;
    }

    a img {
      border: none;
    }

    article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
      display: block;
    }

    body {
      font-family: \'Source Sans Pro\', sans-serif;
      font-weight: 300;
      font-size: 12px;
      margin: 0;
      padding: 0;
      color: #777777;
    }
    body a {
      text-decoration: none;
      color: inherit;
    }
    body a:hover {
      color: inherit;
      opacity: 0.7;
    }
    body .container {
      min-width: 500px;
      margin: 0 auto;
      padding: 0 30px;
    }
    body .clearfix:after {
      content: "";
      display: table;
      clear: both;
    }
    body .left {
      float: left;
    }
    body .right {
      float: right;
    }
    body .helper {
      height: 100%;
    }

    header {
      height: 40px;
      margin-top: 20px;
      margin-bottom: 40px;
      padding: 0px 5px 0;
    }
    header figure {
      float: left;
      width: 40px;
      margin-right: 10px;
    }
    header figure img {
      height: 40px;
    }
    header .company-info {
      color: #BDB9B9;
    }
    header .company-info .title {
      margin-bottom: 5px;
      color: #2A8EAC;
      font-weight: 600;
      font-size: 2em;
    }
    header .company-info .line {
      display: inline-block;
      height: 9px;
      margin: 0 4px;
      border-left: 1px solid #2A8EAC;
    }

    section .details {
      min-width: 500px;
      margin-bottom: 40px;
      padding: 10px 35px;
      background-color: #2A8EAC;
      color: #ffffff;
    }
    section .details .client {
      width: 50%;
      line-height: 16px;
    }
    section .details .client .name {
      font-weight: 600;
    }
    section .details .data {
      width: 50%;
      text-align: right;
    }
    section .details .title {
      margin-bottom: 15px;
      font-size: 3em;
      font-weight: 400;
      text-transform: uppercase;
    }
    section .table-wrapper {
      position: relative;
      overflow: hidden;
    }
    section .table-wrapper:before {
      content: "";
      display: block;
      position: absolute;
      top: 33px;
      left: 30px;
      width: 90%;
      height: 100%;
      border-top: 2px solid #BDB9B9;
      border-left: 2px solid #BDB9B9;
      z-index: -1;
    }
    section .no-break {
      page-break-inside: avoid;
    }
    section table {
      width: 100%;
      margin-bottom: -20px;
      table-layout: fixed;
      border-collapse: separate;
      border-spacing: 5px 20px;
    }
    section table .no {
      width: 50px;
    }
    section table .desc {
      width: 55%;
    }
    section table .qty, section table .unit, section table .total {
      width: 15%;
    }
    section table tbody.head {
      vertical-align: middle;
      border-color: inherit;
    }
    section table tbody.head th {
      text-align: center;
      color: white;
      font-weight: 600;
      text-transform: uppercase;
    }
    section table tbody.head th div {
      display: inline-block;
      padding: 7px 0;
      width: 100%;
      background: #BDB9B9;
    }
    section table tbody.head th.desc div {
      width: 115px;
      margin-left: -110px;
    }
    section table tbody.body td {
      padding: 10px 5px;
      background: #F3F3F3;
      text-align: center;
    }
    section table tbody.body h3 {
      margin-bottom: 5px;
      color: #2A8EAC;
      font-weight: 600;
    }
    section table tbody.body .no {
      padding: 0px;
      background-color: #2A8EAC;
      color: #ffffff;
      font-size: 1.66666666666667em;
      font-weight: 600;
      line-height: 50px;
    }
    section table tbody.body .desc {
      padding-top: 0;
      padding-bottom: 0;
      background-color: transparent;
      color: #777787;
      text-align: left;
    }
    section table tbody.body .total {
      color: #2A8EAC;
      font-weight: 600;
    }
    section table tbody.body tr.total td {
      padding: 5px 10px;
      background-color: transparent;
      border: none;
      color: #777777;
      text-align: right;
    }
    section table tbody.body tr.total .empty {
      background: white;
    }
    section table tbody.body tr.total .total {
      font-size: 1.18181818181818em;
      font-weight: 600;
      color: #2A8EAC;
    }
    section table.grand-total {
      margin-top: 40px;
      margin-bottom: 0;
      border-collapse: collapse;
      border-spacing: 0px 0px;
      margin-bottom: 40px;
    }
    section table.grand-total tbody td {
      padding: 0 10px 10px;
      background-color: #2A8EAC;
      color: #ffffff;
      font-weight: 400;
      text-align: right;
    }
    section table.grand-total tbody td.no, section table.grand-total tbody td.desc, section table.grand-total tbody td.qty {
      background-color: transparent;
    }
    section table.grand-total tbody td.total, section table.grand-total tbody td.grand-total {
      border-right: 5px solid #ffffff;
    }
    section table.grand-total tbody td.grand-total {
      padding: 0;
      font-size: 1.16666666666667em;
      font-weight: 600;
      background-color: transparent;
    }
    section table.grand-total tbody td.grand-total div {
      float: right;
      padding: 10px 5px;
      background-color: #21BCEA;
    }
    section table.grand-total tbody td.grand-total div span {
      margin-right: 5px;
    }
    section table.grand-total tbody tr:first-child td {
      padding-top: 10px;
    }

    footer {
      margin-bottom: 20px;
      padding: 0 5px;
    }
    footer .thanks {
      margin-bottom: 40px;
      color: #2A8EAC;
      font-size: 1.16666666666667em;
      font-weight: 600;
    }
    footer .notice {
      margin-bottom: 25px;
    }
    footer .end {
      padding-top: 5px;
      border-top: 2px solid #2A8EAC;
      text-align: center;
    }

    footer .end .cont{
      width: 200px;
      height: 60px;
      display:inline-block;
      text-align: left;
      vertical-align:top;
    }
    </style>
    </head>

    <body>
    <header class="clearfix">
    <div class="container">
    <figure>
    <img class="logo" src="http://khadimfall.com/img/me.jpg" alt="">
    </figure>
    <div class="company-info">
    <h2 class="title">Khadim Fall</h2>
    <span>Spitzerstraße 1, 80939 München</span>
    <span class="line"></span>
    <a class="phone" href="tel:+49 151 57307440">+49 151 57307440</a>
    <span class="line"></span>
    <a class="email" href="mailto:khadimfall0602@gmail.com">khadimfall0602@gmail.com</a>
    </div>
    </div>
    </header>

    <section>
    <div class="details clearfix">
    <div class="client left">
    <p>Rechnungsempfänger:</p>
    <p class="name">'.$this->to['firstname'].' '.$this->to['lastname'].'</p>
    <p>
    '.$this->to['street'].'<br>
    '.$this->to['plz'].' '.$this->to['ort'].'
    </p>
    '.(empty($this->to['email']) ? '<a href="mailto:'.$this->to['email'].'">'.$this->to['email'].'</a>' : '').'
    </div>
    <div class="data right">
    <div class="title">Rechnung</div>
    <div class="date">
    Date: '.$this->date.'<br>
    Rechnungsnummer: '.$this->nr.'
    </div>
    </div>
    </div>
    <div class="container">
    <div class="table-wrapper">
    <table>
    <tbody class="head">
    <tr>
    <th class="no"></th>
    <th class="desc"><div>Beschreibung</div></th>
    <th class="qty"><div>Anzahl</div></th>
    <th class="unit"><div>Einzelpreis</div></th>
    <th class="total"><div>Gesamtpreis</div></th>
    </tr>
    </tbody>
    <tbody class="body">';

    $total=0.00;
    foreach($this->items as $key => $item){
      if(empty($item['unit-price']) || empty($item['amount']) || empty($item['description'])) continue;
      $totalPrice = $item['amount'] * $item['unit-price'];
      $html .= '<tr>
      <td class="no">'.$key.'</td>
      <td class="desc">'.$item['description'].'</td>
      <td class="qty">'.$item['amount'].'</td>
      <td class="unit">'.$this->writeCash($item['unit-price']).'</td>
      <td class="total">'.$this->writeCash($totalPrice).'</td>
      </tr>';
      $total += $totalPrice;
    }
    $taxes = $total * $this->tax;

    $html .= '</tbody>
    </table>
    </div>
    <div class="no-break">
    <table class="grand-total">
    <tbody>
    <tr>
    <td class="no"></td>
    <td class="desc"></td>
    <td class="qty"></td>
    <td class="unit">Nettobetrag:</td>
    <td class="total">'.$this->writeCash($total).'</td>
    </tr>
    <tr>
    <td class="no"></td>
    <td class="desc"></td>
    <td class="qty"></td>
    <td class="unit">MwSt 19%:</td>
    <td class="total">'.$this->writeCash($taxes).'</td>
    </tr>
    <tr>
    <td class="grand-total" colspan="5"><div><span>Rechnungsbetrag:</span>'.$this->writeCash($total + $taxes).'</div></td>
    </tr>
    </tbody>
    </table>
    </div>
    </div>
    </section>

    <footer>
    <div class="container">
    <div class="thanks">
    </div>
    <div class="notice">
    <!-- <div>Notiz:</div> -->
    <div>
    Es wird gemäß §19 Abs. 1 Umsatzsteuergesetz keine Umsatzsteuer erhoben.<br><br>
    Bitte begleichen Sie den Rechnungsbetrag innerhalb der nächsten 10 Tage.
    </div>
    </div>
    <div class="end">
    <div class="cont">
    Stadtsparkasse München<br>
    BLZ: 701 500 00<br>
    KTO: 1001725686<br>
    KTO Inh.: Khadim Fall
    </div>
    <div class="cont">
    IBAN: DE96 7015 0000 1001 7256 86<br>
    BIC: SSKMDEMMXXX<br>
    </div>
    <div class="cont">
    Steuernummer: XXX<br>
    Finanzamt München-Abt. II/III<br>
    </div>
    </div>
    </div>
    </footer>

    </body>

    </html>';

  }

  private function writeCash($price){
    $price = str_replace('.', ",", $price);
    if(str_replace(',', "", $price) == $price){
      $price = "$price,00";
    }
    return "$price €";
  }


}

?>
