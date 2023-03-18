<?php

require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//var_dump($_POST);die();

//$specimens = Specimen::getAll($conn, $_POST['specimen_id']);


$result = Patient::setSpComment($conn, $_POST['patient_id'], $_POST['p_sp_patho_comment']);
$patients = Patient::getSpComment($conn, $_POST['patient_id']);

echo json_encode($patients);


