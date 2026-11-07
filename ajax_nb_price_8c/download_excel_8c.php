<?php
require '../includes/init.php';
$conn = require '../includes/db.php';
require '../user_auth.php';

if (!Auth::isLoggedIn() || $isCurUserClinicianCust || $isCurUserHospitalCust) {
    http_response_code(403);
    echo "Unauthorized access.";
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo "Invalid file ID.";
    exit;
}

$record = ServicePriceExcelFile::getByID($conn, $id);
if (!$record) {
    http_response_code(404);
    echo "File record not found.";
    exit;
}

$fullPath = dirname(__DIR__) . '/' . ltrim($record['file_path'], '/\\');
if (!is_file($fullPath)) {
    http_response_code(404);
    echo "File not found on server.";
    exit;
}

$downloadName = !empty($record['saved_filename']) ? $record['saved_filename'] : basename($fullPath);
$downloadName = preg_replace('/[\\\\\/:*?"<>|]+/', '_', $downloadName);
$encodedName = rawurlencode($downloadName);

header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $downloadName . '"; filename*=UTF-8\'\'' . $encodedName);
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($fullPath));
readfile($fullPath);
exit;

