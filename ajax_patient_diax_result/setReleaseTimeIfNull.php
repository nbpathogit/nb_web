<?php

require '../includes/init.php';
$conn = require '../includes/db.php';

date_default_timezone_set('Asia/Bangkok');
$release_time = date('Y-m-d H:i:s');

Patient::set_update_released_time($conn,$_POST['patient_id'], $release_time);
Patient::set_update_first_released_time_if_null($conn, $$_POST['patient_id'], $release_time);

echo  Presultupdate::setReleaseTimeIfNull($conn, $_POST['patient_id'], $release_time);
