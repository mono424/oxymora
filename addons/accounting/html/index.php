<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once 'php/functions.php';
use KFall\oxymora\database\DB;

define('TABLE',"accounting_invoices");
define('TABLE_CUSTOMER',"accounting_customer");

define('STATUS_OPEN',0);
define('STATUS_SENT',1);
define('STATUS_PAID',2);


$pdo = DB::pdo();

// MAYBE DO SOMETHING
if(isset($_POST['createInvoice'])){
  if($permissionManager->checkPermission('manage_invoices')){
    createInvoice('rechnung01', $_POST['customer'], $_POST['items']);
  }else{
    echo "No Access!";
  }
}

if(isset($_POST['addCustomer'])){
  if($permissionManager->checkPermission('manage_customer')){
    addCustomer($_POST['data']);
  }else{
    echo "No Access!";
  }
}

if(isset($_POST['deleteCustomer'])){
  if($permissionManager->checkPermission('manage_customer')){
    deleteCustomer($_POST['id']);
  }else{
    echo "No Access!";
  }
}

if(isset($_POST['rollBack'])){
  if($permissionManager->checkPermission('manage_invoices')){
    rollBack();
  }else{
    echo "No Access!";
  }
}

if(isset($_POST['setId'])){
  if($permissionManager->checkPermission('manage_invoices')){
    setNextId($_POST['id']);
  }else{
    echo "No Access!";
  }
}

if(isset($_POST['ajax']) && $_POST['ajax'] == "setInvoiceStatus"){
  if($permissionManager->checkPermission('manage_invoices')){
    setInvoiceStatus($_POST['id'],$_POST['status']);
  }else{
    echo "No Access!";
  }
  die();
}



// GET INVOICES
$prep = $pdo->prepare("SELECT `".TABLE."`.*, `".TABLE_CUSTOMER."`.`firstname`, `".TABLE_CUSTOMER."`.`lastname`
FROM `".TABLE."` LEFT JOIN `".TABLE_CUSTOMER."` ON `".TABLE."`.`customer`=`".TABLE_CUSTOMER."`.`id` ORDER BY `id` desc");
$success = $prep->execute();
if(!$success){die('something went wrong!');}
$invoices = $prep->fetchAll(PDO::FETCH_ASSOC);

// GET CUSTOMER
$customer = getCustomer();
?>
<div class="tabContainer light">
  <ul>
    <li><a data-tab="rechnungen">Ausgestellte Rechnungen</a></li>
    <li><a data-tab="kunden">Kunden</a></li>
    <li><a data-tab="newCustomer">Neuer Kunde</a></li>
    <li><a data-tab="newInvoice">Neue Rechnung</a></li>
    <li><a data-tab="extra">Extra</a></li>
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
              customer
            </th>
            <th>
              total
            </th>
            <th>
              status
            </th>
            <th>
              PDF
            </th>
          </thead>
          <tbody>
            <?php

            foreach($invoices as $invoice){
              $items = json_decode($invoice['items'], true);
              $total = 0;
              foreach($items as $item){
                $total += ($item['amount'] * $item['price']);
              }
              echo "<tr>";
              echo "<td>".$invoice['id']."</td>";
              echo "<td>".date("d.m.Y", strtotime($invoice['created']))."</td>";
              echo "<td>".$invoice['firstname']." ".$invoice['lastname']." (".$invoice['customer'].")</td>";
              echo "<td>".writeCash($total)."</td>";
              echo "<td><a href=\"#\" onclick=\"changeStatus('".$invoice['id']."')\">".statusToText($invoice['status'])."</a></td>";
              echo '<td><form action="download.php" method="POST"><input type="hidden" name="invoice" value="'.$invoice['id'].'"><a href="#" onclick="parentNode.submit();">'.$invoice['file']."</a></form></td>";
              echo "</tr>";
            }

            ?>
          </tbody>
        </table>
      </div>
    </div>




    <div class="tab" data-tab="kunden">
      <div class="dataContainer">
        <table>
          <thead>
            <th>
              ID
            </th>
            <th>
              Vorname
            </th>
            <th>
              Nachname
            </th>
            <th>
              Strasse
            </th>
            <th>
              PLZ
            </th>
            <th>
              Ort
            </th>
            <th>
              Email
            </th>
            <th>
              Erstellt
            </th>
            <th>
              Actions
            </th>
          </thead>
          <tbody>
            <?php

            foreach($customer as $c){
              echo "<tr>";
              echo "<td>".$c->id."</td>";
              echo "<td>".$c->firstname."</td>";
              echo "<td>".$c->lastname."</td>";
              echo "<td>".$c->street."</td>";
              echo "<td>".$c->plz."</td>";
              echo "<td>".$c->ort."</td>";
              echo "<td>".$c->email."</td>";
              echo "<td>".date("d.m.Y", strtotime($c->created))."</td>";
              echo "<td style=\"text-align:right;\"><form method=\"post\"><input type=\"hidden\" name=\"id\" value=\"".$c->id."\"><!--<button type=\"submit\" name=\"editCustomer\">Editieren</button>--><button type=\"submit\" name=\"deleteCustomer\">Löschen</button></form></td>";
              echo "</tr>";
            }

            ?>
          </tbody>
        </table>
      </div>
    </div>




    <div class="tab" data-tab="newCustomer">
      <div class="dataContainer">
        <form action="index.php" method="post">
          <h3>Kundeninformationen</h3>
          <input class="oxinput" type="input" name="data[firstname]" value="" placeholder="Vorname*" required>
          <input class="oxinput" type="input" name="data[lastname]" value="" placeholder="Nachname*" required>
          <input class="oxinput" type="input" name="data[street]" value="" placeholder="Straße*" required>
          <input class="oxinput" type="input" name="data[plz]" value="" placeholder="PLZ*" required>
          <input class="oxinput" type="input" name="data[ort]" value="" placeholder="Ort*" required>
          <input class="oxinput" type="email" name="data[email]" value="" placeholder="Email">
          <br>
          <button class="oxbutton" type="submit" name="addCustomer">Kunde Hinzufügen</button>
        </form>
      </div>
    </div>




    <div class="tab" data-tab="newInvoice">
      <div class="dataContainer">
        <form action="index.php" method="post">
          <h3>Empfänger</h3>
          <select style="width: 97%;" class="oxinput" name="customer" required>
            <option disabled selected>Empfänger auswählen</option>
            <?php
            foreach($customer as $c){
              echo '<option value="'.$c->id.'">'.$c->firstname." ".$c->lastname." [".$c->id."]".'</option>';
            }
            ?>
          </select>
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

    <div class="tab" data-tab="extra">
      <div class="dataContainer">
        <form action="index.php" method="post">
          <input type="text" name="id" value="<?php echo getNextId(); ?>">
          <button class="oxbutton" type="submit" name="setId">Set Next Invoice Id</button>
        </form>
        <br><br>
        <form action="index.php" method="post">
          <button class="oxbutton" type="submit" name="rollBack">Take last Invoice back!</button>
        </form>
      </div>
    </div>

  </div>
</div>


<?php


// FUNCTIONS
function writeCash($price){
  $price = number_format(floatval($price), 2, ',', '');
  $price = str_replace('.', ",", $price);
  return "$price €";
}

function statusToText($status){
  switch ($status) {
    case STATUS_OPEN:
    return "Eröffnet";
    break;
    case STATUS_SENT:
    return "Gestellt";
    break;
    case STATUS_PAID:
    return "Gezahlt";
    break;
  }


}



?>
