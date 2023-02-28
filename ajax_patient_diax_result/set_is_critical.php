<?php

require '../includes/init.php';
$conn = require '../includes/db.php';


echo Patient::addCriticalReport($conn, $_POST['patient_id'], $_POST['critical_report']);