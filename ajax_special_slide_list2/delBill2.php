<?php

require '../includes/init.php';

Auth::requireLogin();

$conn = require '../includes/db.php';
require '../user_auth.php';

Billing::delete($conn,$_POST['bill_id']);
$billings = Billing::getAll($conn, $_POST['patient_id'],2);

echo json_encode($billings);

?>


