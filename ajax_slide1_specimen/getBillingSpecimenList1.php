<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['id']);
$billings = ServiceBilling::getAll145($conn, $_GET['id'],1);

echo json_encode($billings);


?>

