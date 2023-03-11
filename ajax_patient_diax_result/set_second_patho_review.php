<?php

require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//var_dump($_POST);die();

//$specimens = Specimen::getAll($conn, $_POST['specimen_id']);



// set review status 0/1/2
Job::setSecondPathoReview($conn,  $_POST['job6_id'], $_POST['second_patho_review']);
Presultupdate::setSecondPathoReview($conn, $_POST['result_id'], $_POST['second_patho_review']);
echo Patient::setSecondPathoReview($conn, $_POST['patient_id'], $_POST['second_patho_review']);
