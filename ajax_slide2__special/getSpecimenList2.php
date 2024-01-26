<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['id']);


//            'hospital_id': hospital_id,
//            'service_id': service_id
$billings = ServicePriceList::getSpecimenByHospitalIDJoptypeSpecialStainging($conn, $_POST['hospital_id'], $_POST['service_id']);

echo json_encode($billings);


?>

