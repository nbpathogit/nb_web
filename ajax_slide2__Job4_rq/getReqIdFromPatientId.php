<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['id']);
$req_id = ReqSpSlideID::getReqIdFromPatientId($conn, $_POST['patient_id']); 

echo json_encode($req_id);


?>

