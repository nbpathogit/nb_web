<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['cur_patient_id']);

$hirelists = HireList::getAll($conn, (int) $_POST['patient_id']);

echo json_encode($hirelists);

?>

