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

$result_message = Presultupdate::getTxtResult($conn, $_POST['rs_id']);
echo json_encode($result_message);