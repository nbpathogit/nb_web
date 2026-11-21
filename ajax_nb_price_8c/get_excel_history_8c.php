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

// Check if there are legacy price records with price_id = 0 for the specified hospital (or across all if hospital_id == -1)
$legacySql = "SELECT COUNT(*) AS cnt, MIN(effective_date) AS min_eff, MAX(effective_date) AS max_eff 
              FROM `service_price_list` 
              WHERE (price_id = 0 OR price_id IS NULL)";
if ($hospital_id !== -1) {
    $legacySql .= " AND hospital_id = :hospital_id";
}

$lStmt = $conn->prepare($legacySql);
if ($hospital_id !== -1) {
    $lStmt->bindValue(':hospital_id', $hospital_id, PDO::PARAM_INT);
}
$lStmt->execute();
$legacyStats = $lStmt->fetch(PDO::FETCH_ASSOC);

$legacyData = null;
if ($legacyStats && (int)$legacyStats['cnt'] > 0) {
    $effDisplay = '-';
    if (!empty($legacyStats['min_eff'])) {
        $effDisplay = ($legacyStats['min_eff'] === $legacyStats['max_eff'])
            ? $legacyStats['min_eff']
            : ($legacyStats['min_eff'] . ' ถึง ' . $legacyStats['max_eff']);
    }
    $legacyData = [
        'id' => 0,
        'hospital_id' => $hospital_id,
        'row_count' => (int)$legacyStats['cnt'],
        'effective_date' => $effDisplay,
        'is_legacy' => true
    ];
}

echo json_encode([
    'history' => $history,
    'legacy' => $legacyData
], JSON_UNESCAPED_UNICODE);

