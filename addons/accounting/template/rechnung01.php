<?php
require_once __DIR__.'/../html/php/interface.template.php';

class Template implements invoiceTemplate{

  private $nr, $date, $to, $items, $cash, $tax;

  public function __construct($nr, $date, $to, $items, $cash = "€", $tax = 0){
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
      <meta charset="utf-8">
      <title></title>
      <style media="screen">
      body{
        width: 100%;
        font-family: helvetica, sans-serif;
        margin:0;
        padding:0;
      }

      .container{
        width: 100%;
        position:relative;
        margin-bottom: 30px;
        box-sizing: border-box;
      }

      .container.header{

      }

      .container.overview{
        height: 100px;
        background: #2A8EAC;
        padding: 10px;
      }

      .container.overview a{
        color: white;
        text-decoration: none;
      }

      .container.overview p{
        color:white;
        font-size: 12px;
        line-height: 16px;
        margin:0;
        padding:0;
      }

      .container.overview .date{
        margin-top: 10px;
        color:white;
        font-size: 14px;
      }

      .container.overview h3{
        margin:0;
        color:white;
        font-size: 32px;
        font-weight: normal;
      }

      .container .left{
        position:absolute;
        left:10px;
      }

      .container .right{
        position:absolute;
        right:10px;
        width: 220px;
      }

      h2{
        color: #2A8EAC;
        margin: 0 0 4px 0;
        font-size: 24px;
      }

      .seperator{
        color: #2A8EAC;
      }

      span, a{
        font-size: 14px;
        color: #BDB9B9;
        text-decoration: none;
      }

      .container.listing{
      }

      .container.listing table{
        width:100%;
      }

      .container.listing table thead{
        height: 60px;
      }

      .container.listing table thead .no{
        width: 50px;
      }

      .container.listing table thead .desc{
        width: 55%;
      }

      .container.listing table thead th div{
        color:white;
        display:inline-block;
        background: #BDB9B9;
        padding: 7px 10px;
        font-size: 14px;
      }

      .container.listing table tbody td{
        background: #F3F3F3;
        color:#777777;
        text-align:center;
        padding: 10px 5px;
        font-size: 14px;
      }

      .container.listing table tbody td.no{
        color: white;
        background: #2A8EAC;
      }

      .container.listing table tbody td.x{
        background:transparent;
      }

      .container.listing table tbody td.blue{
        background: #2A8EAC;
        color:white;
      }

      .container.listing table tbody td.total{
        background: #21BCEA;
        color:white;
        font-weight: bold;
        font-size: 16px;
      }

      .container.listing table tbody td.desc{
        width: 55%;
      }

      .container.listing table tbody td.sepL{
        height: 30px;
        background:transparent;
      }

      .container.listing table tbody td.sepS{
        padding: 0;
        height: 2px;
        background:transparent;
      }

      footer {
        color:#777777;
        margin-bottom: 20px;
        padding: 0 5px;
        font-size: 12px;
        position:absolute;
        bottom: 0;
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
        width: 240px;
        height: 60px;
        display:inline-block;
        text-align: left;
        vertical-align:top;
      }

      </style>
    </head>
    <body>
      <div class="container header">
        <h2>Khadim Fall</h2>
        <span>Spitzerstraße 1, 80939 München</span>
        <span class="seperator">|</span>
        <a class="phone" href="tel:+49 151 57307440">+49 151 57307440</a>
        <span class="seperator">|</span>
        <a class="email" href="mailto:khadimfall0602@gmail.com">khadimfall0602@gmail.com</a>
      </div>

      <div class="container overview">
        <div class="left">
          <p>Rechnungsempfänger:</p>
          <p class="name">'.$this->to['firstname'].' '.$this->to['lastname'].'</p>
          <p>
            '.$this->to['street'].'<br>
            '.$this->to['plz'].' '.$this->to['ort'].'
          </p>
          <p>'.(empty($this->to['email']) ? '<a href="mailto:'.$this->to['email'].'">'.$this->to['email'].'</a>' : '').'</p>
        </div>
        <div class="right">
          <h3>RECHNUNG</h3>
          <div class="date">
            Date: '.$this->date.'<br>
            Rechnungsnummer: '.$this->nr.'
          </div>
        </div>
      </div>

      <div class="container listing">
        <table>
          <thead>
            <tr>
              <th class="no"><div>#</div></th>
              <th class="desc"><div>Beschreibung</div></th>
              <th><div>Anzahl</div></th>
              <th><div>Preis</div></th>
              <th><div>Gesamt</div></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="sepS">&nbsp;</td>
              <td class="sepS">&nbsp;</td>
              <td class="sepS">&nbsp;</td>
              <td class="sepS">&nbsp;</td>
              <td class="sepS">&nbsp;</td>
            </tr>';
            $total=0.00;
            foreach($this->items as $key => $item){
              if(empty($item['price']) || empty($item['amount']) || empty($item['amount-type']) || empty($item['description'])) continue;
              $totalPrice = $item['amount'] * $item['price'];
              $html .= '<tr>
                <td class="no">'.++$key.'</td>
                <td class="desc">'.$item['description'].'</td>
                <td>'.$item['amount'].' '.$item['amount-type'].'</td>
                <td>'.$this->writeCash($item['price']).'</td>
                <td>'.$this->writeCash($totalPrice).'</td>
              </tr>';
              $total += $totalPrice;
            }
            $taxes = $total * $this->tax;

            $html .= '
            <tr>
              <td class="sepL">&nbsp;</td>
              <td class="sepL">&nbsp;</td>
              <td class="sepL">&nbsp;</td>
              <td class="sepL">&nbsp;</td>
              <td class="sepL">&nbsp;</td>
            </tr>
            <tr>
              <td class="x"></td>
              <td class="x"></td>
              <td class="x"></td>
              <td class="blue">Nettobetrag:</td>
              <td class="blue">'.$this->writeCash($total).'</td>
            </tr>
            <tr>
              <td class="x"></td>
              <td class="x"></td>
              <td class="x"></td>
              <td class="blue">MwSt('.($this->tax * 100).'%):</td>
              <td class="blue">'.$this->writeCash($taxes).'</td>
            </tr>
            <tr>
              <td class="x"></td>
              <td class="x"></td>
              <td class="x"></td>
              <td class="total">Rechnungsbetrag:</td>
              <td class="total">'.$this->writeCash($total + $taxes).'</td>
            </tr>
          </tbody>
        </table>
      </div>


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
              Steuernummer: 147/101/40082<br>
              Finanzamt München-Abt. II/III<br>
            </div>
          </div>
        </div>
      </footer>
    </body>
    </html>

';


    return $html;

  }

  private function writeCash($price){
    $price = number_format(floatval($price), 2, ',', '');
    $price = str_replace('.', ",", $price);
    return "$price €";
  }


}

?>
