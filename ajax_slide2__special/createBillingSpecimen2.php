<?php

require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
// $specimens = Specimen::getAll($conn, $_POST['specimen_id']);


try {


        $billing = ServiceBilling::getInitObj();
        $billing->patient_id = (int) $_POST['patient_id'];
        $billing->number = $_POST['cur_pnum']; //surgical number
        $billing->lastname = ""; //patient surname
        $billing->slide_type = $_POST['job_type']; //
        $billing->report_date = NULL;
        $billing->pathologist = "";

        $billing->name = ""; //patient name
        $billing->specimen_id = (int) $_POST['specimen_id'];
        $billing->code_description = $_POST['specimen_num'];
        $billing->description = $_POST['specimen_text'];
        $billing->import_date = $_POST['date_1000'];
        $billing->hospital = $_POST['phospital_text'];
        $billing->hospital_id = $_POST['hospital_id'];
        $billing->hn = $_POST['cur_phospital_num'];
        
        $billing->nm_slide_count=0; //text	
        $billing->sp_slide_block=$_POST['blox_name']; //text
        $billing->sp_slide_count=0; //text

        $billing->send_doctor = $_POST['pclinician_text'];
        $billing->cost = (int) $_POST['price_for_specimen'];
        $billing->comment = $_POST['comment_for_specimen'];

//        echo var_dump($_POST);
//        die();

        $billing->create($conn);
    
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}





//$billings = ServiceBilling::getAll($conn, $_POST['patient_id'], 2);
$billings = ServiceBilling::getAllUnRequest2367($conn, $_POST['patient_id'], 0);

echo json_encode($billings);
