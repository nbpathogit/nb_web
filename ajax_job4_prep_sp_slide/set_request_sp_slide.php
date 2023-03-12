<?php

require '../includes/init.php';
$conn = require '../includes/db.php';

// set request special slide 0/1/2
//Job::set_request_sp_slide($conn, $id, $request_sp_slide)($conn,  $_POST['job4_id'], $_POST['request_sp_slide']);
echo Patient::set_request_sp_slide($conn, $_POST['patient_id'], $_POST['request_sp_slide']);


