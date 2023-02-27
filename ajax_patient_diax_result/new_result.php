<?php

require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//var_dump($_POST);die();

//$specimens = Specimen::getAll($conn, $_POST['specimen_id']);

$resultreport = Presultupdate::getInitObj();
$resultreport->group_type = $_POST['group_type'];
$resultreport->patient_id = $_POST['cur_patient_id'];
$resultreport->result_type = $_POST['result_type'];
$resultreport->result_type_id = $_POST['result_type_id'];
$resultreport->release_type = $_POST['release_type'];
$a = $resultreport->create($conn);
if($a){
    //success
//    echo "success";
//    echo $a;
}else{
    echo $a;
    die();
}


$result = $resultreport->getByID($conn, $resultreport->id);
echo json_encode($result);

