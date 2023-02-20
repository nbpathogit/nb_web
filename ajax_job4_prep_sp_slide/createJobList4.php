<?php

require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//var_dump($_POST);die();

//$specimens = Specimen::getAll($conn, $_POST['specimen_id']);


$job = Job::getInitObj();
//$job->id = null;
$job->job_role_id = (int) $_POST['job_role_id'];
$job->patient_id = (int) $_POST['patient_id'];
$job->patient_number = $_POST['patient_number'];
$job->user_id = (int) $_POST['user_id'];
$job->pre_name = $_POST['pre_name'];
$job->name = $_POST['name'];
$job->lastname = $_POST['lastname'];
$job->jobname = $_POST['jobname'];
$job->pay = (float)$_POST['pay'];
$job->cost_count_per_day = (int) $_POST['cost_count_per_day'];
$job->comment = $_POST['comment'];
$job->finish_date = null;


$job->create($conn);

$jobs = Job::getByPatientJobRole($conn, (int) $_POST['patient_id'],4);

echo json_encode($jobs);


