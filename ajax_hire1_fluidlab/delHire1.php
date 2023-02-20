<?php

require '../includes/init.php';

Auth::requireLogin();

$conn = require '../includes/db.php';
require '../user_auth.php';

HireList::delete($conn,$_POST['job_id']);

$hirelists = HireList::getAll($conn, (int) $_POST['patient_id'],5);

echo json_encode($hirelists);
?>


