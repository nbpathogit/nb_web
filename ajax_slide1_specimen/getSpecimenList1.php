<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['id']);
$hospital_id = isset($_POST['hospital_id']) ? (int)$_POST['hospital_id'] : 0;
$effective_date = !empty($_POST['effective_date']) ? substr(trim($_POST['effective_date']), 0, 10) : null;
$billings = ServicePriceList::getSpecimenByHospitalIDJoptypeSpecimen($conn, $hospital_id, $effective_date);

echo json_encode($billings);


?>

