<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['cur_patient_id']);

$jobs = Job::getByPatientJobRole($conn, (int) $_POST['cur_patient_id'],6);
echo json_encode($jobs);


?>

