<?php
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//Auth::requireLogin("../patient_edit.php", $_POST['id']);

try{
$specimens = Specimen::getAll($conn,$_POST['specimen_id']);


$billing = Billing::getInitObj();
$billing->patient_id=(int)$_POST['id'];
$billing->number=$_POST['cur_pnum'];//surgical number
$billing->name="";//patient name
$billing->lastname="";//patient surname
$billing->slide_type=1;//patient surname
$billing->code_description=$_POST['specimen_id'];
$billing->description=$_POST['specimen_text'];
$billing->import_date=$_POST['date_1000'];
$billing->report_date=NULL;
$billing->hospital=$_POST['phospital_text'];
$billing->hn=$_POST['cur_phospital_num'];;
$billing->send_doctor=$_POST['pclinician_text'];
$billing->pathologist="";
$billing->cost=(int)$_POST['price_for_specimen'];
$billing->comment=$specimens[0]['comment'];


$billing->create($conn);

  
} catch(Exception $e) {
  throw $e;
}