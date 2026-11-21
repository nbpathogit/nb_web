<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['id']);


//            'hospital_id': hospital_id,
//            'service_id': service_id
$hospital_id = isset($_POST['hospital_id']) ? (int)$_POST['hospital_id'] : 0;
$service_id = isset($_POST['service_id']) ? (int)$_POST['service_id'] : 0;
$effective_date = !empty($_POST['effective_date']) ? substr(trim($_POST['effective_date']), 0, 10) : null;
$billings = ServicePriceList::getSpecimenByHospitalIDJoptypeSpecialStainging($conn, $hospital_id, $service_id, $effective_date);

echo json_encode($billings);


?>

