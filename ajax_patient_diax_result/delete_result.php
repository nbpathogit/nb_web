<?php

require '../includes/init.php';
$conn = require '../includes/db.php';

$result = Presultupdate::deleteById($conn,$_POST['rs_id']);

echo json_encode($result);

