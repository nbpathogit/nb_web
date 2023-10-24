<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['id']);
$billings = ServicePriceList::getSpecimenByHospitalID($conn, $_POST['hospital_id'],1);

echo json_encode($billings);


?>

