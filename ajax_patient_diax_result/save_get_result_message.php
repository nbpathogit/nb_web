<?php

require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//var_dump($_POST);die();

//$specimens = Specimen::getAll($conn, $_POST['specimen_id']);

$a =  Presultupdate::setTxtResult($conn, $_POST['rs_id'], $_POST['result_message']);
if($a){
    
}else{
    echo $a;
    die();
}

// if pathologist_admin edit, then record edit date
if( $_POST['usergroup'] == '1900'){
    Patient::set_update_edit_date($conn, $_POST['patient_id']);
}

$result_message = Presultupdate::getTxtResult($conn, $_POST['rs_id']);
echo json_encode($result_message);