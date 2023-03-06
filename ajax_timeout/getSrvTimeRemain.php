<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['cur_patient_id']);

$srvTimeRemain = Auth::getSrvTimeRemain();
echo $srvTimeRemain;


?>