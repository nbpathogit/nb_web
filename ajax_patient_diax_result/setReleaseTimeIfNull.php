<?php

require '../includes/init.php';
$conn = require '../includes/db.php';

date_default_timezone_set('Asia/Bangkok');
$release_time = date('Y-m-d H:i:s');

//echo 'release_time = '.$release_time.' importdate = '.$_POST['importdate'];
//die();

Patient::set_update_released_time($conn,$_POST['patient_id'], $release_time);


Presultupdate::setReleaseTimeIfNull($conn, $_POST['patient_id'], $release_time);
echo  Patient::set_update_first_released_time_if_null($conn, $_POST['patient_id'], $release_time, $_POST['importdate'] );
