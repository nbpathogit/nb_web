<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['cur_patient_id']);

$specimens = Specimen::getSpecimenByHospitalID($conn, $_POST['hospital_id'] , $_POST['type_id']);

echo json_encode($specimens);
