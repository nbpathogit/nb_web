<?php
require '../includes/init.php';
$conn = require '../includes/db.php';
require '../user_auth.php';

header('Content-Type: application/json; charset=utf-8');

if (!Auth::isLoggedIn() || $isCurUserClinicianCust || $isCurUserHospitalCust) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

$hospital_id = isset($_REQUEST['hospital_id']) ? (int)$_REQUEST['hospital_id'] : -1;
$history = ServicePriceExcelFile::getAll($conn, $hospital_id);

echo json_encode($history, JSON_UNESCAPED_UNICODE);

