<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['id']);
$billings = ServicePriceList::getAll($conn, $_POST['id']);

echo json_encode($billings);


?>

