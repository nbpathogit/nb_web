<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';

$result = ReqSpSlideID::movetotrash($conn,$_POST['id'],$_POST['patient_num']);
$result = Job::movetotrash($conn,$_POST['id'],$_POST['patient_num']);
$result = Patient::movetotrash($conn,$_POST['id'],$_POST['patient_num']);
echo json_encode($result);


?>

