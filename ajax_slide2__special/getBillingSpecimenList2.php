<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['id']);
$billings = ServiceBilling::getAllUnRequest2367($conn, $_GET['id'],2);

echo json_encode($billings);


?>

