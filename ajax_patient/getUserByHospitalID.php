<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';

$users = User::getAllbyCliniciansbyHospitalID($conn, $_POST['hospital_id']);
echo json_encode($users);


?>

