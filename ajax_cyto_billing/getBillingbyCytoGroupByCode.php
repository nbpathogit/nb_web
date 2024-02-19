<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['cur_patient_id']);

//$jobs = Job::getByPatientJobRole($conn, (int) $_POST['cur_patient_id'],6);
$billings = ServiceBilling::getBillbyCytobyDateRangeGroupByCode($conn,$_POST['cytologist_id'],$_POST['startdate'],$_POST['enddate']);
echo json_encode($billings);


?>

