<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';

$result = Patient::delete2($conn,$_POST['id'],$_POST['patient_num']);
echo json_encode($result);


?>

