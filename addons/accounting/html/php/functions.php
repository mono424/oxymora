<?php
use Dompdf\Dompdf;
use KFall\oxymora\database\DB;
// ========================================
//  FUNCTIONS
// ========================================

// Create Invoice
function createInvoice($template, $to, $items){
  require_once __DIR__.'/dompdf/autoload.inc.php';
  // create db reference
  $id = createDBReference();
  if($id === false){return false;}

  // html invoice
  $html = createHTMLInvoice($id, $template, $to, $items);
  $filename = "invoice-$id.pdf";
  $filepath = __DIR__."/../../invoices/$filename";

  // save to reference
  addFileToDBReference($id, $filename);

  // instantiate and use the dompdf class
  $dompdf = new Dompdf();
  $dompdf->loadHtml($html);

  // Setup the paper size and orientation
  $dompdf->setPaper('A4', 'portrait');

  // Render the HTML as PDF
  $dompdf->render();

  // Save it
  file_put_contents($filepath, $dompdf->output());
  file_put_contents($filepath.".html", $html);
}

// create html invoice
function createHTMLInvoice($id, $template, $to, $items){
  require_once __DIR__."/../../template/$template.php";
  $invoice = new Template($id, date("Y-m-d H:i:s"), $to, $items);
  return $invoice->getHtml();
}

// put in db
function createDBReference(){
  $pdo = DB::pdo();
  $prep = $pdo->prepare("INSERT INTO `".TABLE."`() VALUES ()");
  if($prep->execute()){
    return $pdo->lastInsertId();
  }else{
    return false;
  }
}

// put file in reference
function addFileToDBReference($id, $file){
  $pdo = DB::pdo();
  $prep = $pdo->prepare("UPDATE `".TABLE."` SET `file`=:file WHERE `id`=:id");
  $prep->bindValue(':id',$id);
  $prep->bindValue(':file',$file);
  return $prep->execute();
}
