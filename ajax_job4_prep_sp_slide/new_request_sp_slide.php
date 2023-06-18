<?php

require '../includes/init.php';
$conn = require '../includes/db.php';

//Create Request ID, Add patient_id to Request ID
$cur_thai_time = Util::get_curreint_thai_date_time();
$req = ReqSpSlideID::getInitObj();
$req->job_id = 0;
$req->req_date = $cur_thai_time;
$req->create_user_id = $_POST['cur_user_id'];
$req->comment = $_POST['comment'];
$req->patient_id = $_POST['patient_id'];

//echo var_dump($_POST);die();

$req->create($conn);
$req_id = $req->id;



//Add request id to billing
Billing::setRequestIDifNotSet_SlideType2($conn, $_POST['patient_id'], $req_id,$cur_thai_time);

//Add request id to
Job::setRequestIDifNotSetJobRole4($conn, $_POST['patient_id'], $req_id,$cur_thai_time);


echo $req_id;


