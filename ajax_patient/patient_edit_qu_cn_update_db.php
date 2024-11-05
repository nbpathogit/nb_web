<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';

$result = QuesCn::update($conn,$_POST['id'],$_POST['patient_id'],$_POST['a1'],$_POST['a2'],$_POST['a3'],$_POST['a4'],$_POST['qcomment']);
//
echo json_encode($result);


?>

