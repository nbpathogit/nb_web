<?php
require 'includes/init.php';

$lab_no = "B2400003";
$nb_no = "SN0033";
$pdf_path = 'C:/pdf.pdf';
$txt_path = 'C:/txt.txt';

$winmed = new WinmedAPI();
$res=json_decode($winmed->sentAPI($lab_no, $nb_no, $pdf_path, $txt_path));

// var_dump($res->resCode);
if($res->resCode) { // fail
  echo $res->resCode;
} elseif($res->result){ // success
  echo $res->result;
} else{
  var_dump($res);
}