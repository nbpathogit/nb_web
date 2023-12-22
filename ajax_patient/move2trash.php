<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';

$result = Patient::movetotrash($conn,$_POST['id']);
echo json_encode($result);


?>

