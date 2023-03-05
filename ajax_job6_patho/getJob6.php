<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['cur_patient_id']);

//$jobs = Job::getByPatientJobRole($conn, (int) $_POST['cur_patient_id'],6);
$jobs = Job::getByPatientJobRoleUResult($conn, (int) $_POST['patient_id'],6,(int) $_POST['result_id']);
echo json_encode($jobs);


?>

