<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['id']);
$job_bill = ReqSpSlideID::getJobFromReqId($conn, $_POST['req_id']); 

echo json_encode($job_bill);


?>

