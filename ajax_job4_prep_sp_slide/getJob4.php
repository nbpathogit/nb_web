<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['cur_patient_id']);


$jobs = Job::getByPatientJobRole_Unassigned($conn, (int) $_POST['cur_patient_id'],4);
echo json_encode($jobs);


?>

