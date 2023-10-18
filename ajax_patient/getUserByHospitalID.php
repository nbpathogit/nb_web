<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';

$users = User::getAllbyCliniciansbyHospitalID($conn, $_GET['hospital_id']);
echo json_encode($users);


?>

