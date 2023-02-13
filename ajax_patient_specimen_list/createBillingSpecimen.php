<?php
require '../includes/init.php';
$conn = require '../includes/db.php';
Auth::requireLogin("../patient_edit.php", $_POST['id']);


$billing = Billing::getInitObj();
$billing->patient_id=(int)$_POST['id'];
$billing->number=$_POST['cur_pnum'];
$billing->description=$_POST['specimen_text'];
$billing->comment=$_POST['comment_for_specimen'];
$billing->cost=$_POST['price_for_specimen'];

$billing->import_date=$_POST['date_1000'];

$billing->create($conn);
//echo $billing->import_date;