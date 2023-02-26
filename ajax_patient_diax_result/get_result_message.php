<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['cur_patient_id']);


$result_message = Presultupdate::getTxtResult($conn, $_POST['rs_id']);
echo json_encode($result_message);


?>

