<?php

require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//var_dump($_POST);die();

//$specimens = Specimen::getAll($conn, $_POST['specimen_id']);

Patient::setJob2Qty($conn, $_POST['patient_id'], $_POST['job2qty']);

Job::setJob2Qty($conn, $_POST['patient_id'], $_POST['job2qty']);

//echo json_encode($jobs);
