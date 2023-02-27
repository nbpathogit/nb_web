<?php

require '../includes/init.php';
$conn = require '../includes/db.php';

date_default_timezone_set('Asia/Bangkok');
$release_time = date('Y-m-d H:i:s');

echo  Presultupdate::setReleaseTimeIfNull($conn, $_POST['patient_id'], $release_time);
