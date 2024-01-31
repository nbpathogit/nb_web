<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['cur_patient_id']);
           

            
//            'startdate': startdate,
//            'enddate': enddate,
//            'user_job_daily': user_job_daily,
//            'role_job_daily': role_job_daily,

$jobs = Job::getJobCountByDaily($conn,  $_POST['startdate'],$_POST['enddate'],$_POST['user_job_daily'],$_POST['role_job_daily']);

echo json_encode($jobs);


?>

