<?php

require '../includes/init.php';

Auth::requireLogin();

$conn = require '../includes/db.php';
require '../user_auth.php';

Job::delete($conn,$_POST['job_id']);

//$jobs = Job::getByPatientJobRole($conn, (int) $_POST['patient_id'],6);
$jobs = Job::getByPatientJobRoleUResult($conn, (int) $_POST['patient_id'],6,(int) $_POST['result_id']);

Presultupdate::updateSecondPatho($conn, (int) $_POST['result_id'], (int) 0);

echo json_encode($jobs);
?>


