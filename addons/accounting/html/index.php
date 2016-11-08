<?php
require_once 'php/functions.php';
use KFall\oxymora\database\DB;

define('TABLE',"accounting_invoices");


$pdo = DB::pdo();

// ADD MAYBE !?=
if(isset($_POST['createInvoice'])){
  createInvoice('rechnung01', $_POST['to'], $_POST['items']);
}



// GET INVOICES
$prep = $pdo->prepare("SELECT * FROM `".TABLE."`");
$success = $prep->execute();
if(!$success){die('something went wrong!');}
$invoices = $prep->fetchAll(PDO::FETCH_ASSOC);

?>
  <div class="tabContainer light">
    <ul>
      <li><a data-tab="rechnungen">Ausgestellte Rechnungen</a></li>
      <li><a data-tab="new">Neue Rechnung</a></li>
    </ul>
    <div class="tabContent">



      <div class="tab" data-tab="rechnungen">
        <div class="dataContainer">
          <table>
            <thead>
              <th>
                ID
              </th>
              <th>
                Date
              </th>
              <th>
                PDF
              </th>
            </thead>
            <tbody>
              <?php

              foreach($invoices as $invoice){
                echo "<tr>";
                echo "<td>".$invoice['id']."</td>";
                echo "<td>".date("d.m.Y", strtotime($invoice['created']))."</td>";
                echo '<td><form action="download.php" method="POST"><input type="hidden" name="invoice" value="'.$invoice['id'].'"><a href="#" onclick="parentNode.submit();">'.$invoice['file']."</a></form></td>";
                echo "</tr>";
              }

              ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="tab" data-tab="new">
        <div class="dataContainer">
          <form action="index.php" method="post">
            <h3>Empfänger</h3>
            <input class="oxinput" type="input" name="to[firstname]" value="" placeholder="Vorname*" required>
            <input class="oxinput" type="input" name="to[lastname]" value="" placeholder="Nachname*" required>
            <input class="oxinput" type="input" name="to[street]" value="" placeholder="Straße*" required>
            <input class="oxinput" type="input" name="to[plz]" value="" placeholder="PLZ*" required>
            <input class="oxinput" type="input" name="to[ort]" value="" placeholder="Ort*" required>
            <input class="oxinput" type="email" name="to[email]" value="" placeholder="Email">
            <h3>Items</h3>
            <div id="items">
              <div class="item">
                <input style="display:inline-block; width: 50%;" class="oxinput" type="text" name="items[0][description]" value="" placeholder="Beschreibung*" required>
                <input style="display:inline-block; width: 15%;" class="oxinput" type="text" pattern="[0-9]{1,}" name="items[0][amount]" value="" placeholder="Anzahl*" required>
                <input style="display:inline-block; width: 10%;" class="oxinput" type="text" name="items[0][amount-type]" value="" placeholder="Stück*" required>
                <input style="display:inline-block; width: 20%;" class="oxinput" type="text" pattern="[0-9]{1,}\.[0-9]{2}" name="items[0][price]" value="" placeholder="Preis(13.50)*" required>
              </div>
            </div>
            <br>
            <button class="oxbutton" onclick="addItem()" type="button" name="button">Add Item</button>
            <button class="oxbutton" type="submit" name="createInvoice">Create Invoice</button>
          </form>
        </div>
      </div>

    </div>
  </div>
