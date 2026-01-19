<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['cur_patient_id']);

$patientLists = Patient::getAllJoin_forlableprint_by_acceptdate($conn, $_POST['accept_date']);
echo json_encode($patientLists);


?>