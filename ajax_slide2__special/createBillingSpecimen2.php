<?php

require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//Auth::requireLogin("../patient_edit.php", $_POST['id']);


    $specimens = Specimen::getAll($conn, $_POST['specimen_id']);


    $billing = Billing::getInitObj();
    $billing->patient_id = (int) $_POST['patient_id'];
    $billing->number = $_POST['cur_pnum']; //surgical number
    $billing->lastname = ""; //patient surname
    $billing->slide_type = 2; //patient surname
    $billing->report_date = NULL;
    $billing->pathologist = "";


    $billing->name = ""; //patient name
    $billing->specimen_id = (int) $_POST['specimen_id'];
    $billing->code_description = $_POST['specimen_num'];
    $billing->description = $_POST['specimen_text'];
    $billing->import_date = $_POST['date_1000'];
    $billing->hospital = $_POST['phospital_text'];
    $billing->hn = $_POST['cur_phospital_num'];
    
    $billing->send_doctor = $_POST['pclinician_text'];
    $billing->cost = (int) $_POST['price_for_specimen'];
    $billing->comment = $_POST['comment_for_specimen'];


    $billing->create($conn);
    
    $billings = Billing::getAll($conn, $_POST['patient_id'],2);

    echo json_encode($billings);