<?php

require '../includes/init.php';

Auth::requireLogin();

$conn = require '../includes/db.php';
require '../user_auth.php';

Job::delete($conn,$_POST['job_id']);



$jobs = Job::getByPatientJobRole_Unassigned($conn, (int) $_POST['patient_id'],4);
echo json_encode($jobs);
?>


