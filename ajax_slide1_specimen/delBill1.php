<?php

require '../includes/init.php';

//Auth::requireLogin();

$conn = require '../includes/db.php';
//require '../user_auth.php';

ServiceBilling::delete($conn,$_POST['bill_id']);
$billings = ServiceBilling::getAll145($conn, $_POST['patient_id'],1);

echo json_encode($billings);

?>


