<?php
require '../includes/init.php';
$conn = require '../includes/db.php';
require '../user_auth.php';

header('Content-Type: application/json; charset=utf-8');

if (!Auth::isLoggedIn() || $isCurUserClinicianCust || $isCurUserHospitalCust) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

$action = isset($_POST['action']) ? trim($_POST['action']) : '';
$id = isset($_POST['id']) ? (int)$_POST['id'] : -1;
$hospital_id = isset($_POST['hospital_id']) ? (int)$_POST['hospital_id'] : -1;

if ($id < 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid ID.']);
    exit;
}

if ($action === 'delete_legacy') {
    // Delete legacy records (price_id = 0) for this hospital
    if (!$isCurUserAdmin) {
        echo json_encode(['status' => 'error', 'message' => 'สำหรับผู้ดูแลระบบ (Admin) เท่านั้น']);
        exit;
    }
    if ($hospital_id < 0) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณาระบุโรงพยาบาลที่ต้องการลบ']);
        exit;
    }
    $delSql = "DELETE FROM `service_price_list` WHERE `hospital_id` = :hospital_id AND (`price_id` = 0 OR `price_id` IS NULL)";
    $stmt = $conn->prepare($delSql);
    $stmt->bindValue(':hospital_id', $hospital_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $count = $stmt->rowCount();
        echo json_encode(['status' => 'success', 'message' => "ลบรายการราคาเดิม (Legacy price_id = 0) จำนวน {$count} รายการเรียบร้อยแล้ว"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลเดิมได้']);
    }
    exit;
}

if ($action === 'trash') {
    // Normal user or admin can move to trash
    $res = ServicePriceExcelFile::moveToTrash($conn, $id);
    if ($res) {
        echo json_encode(['status' => 'success', 'message' => 'ย้ายไฟล์ Excel และรายการราคาลงถังขยะเรียบร้อยแล้ว']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถย้ายลงถังขยะได้']);
    }
    exit;
}

if ($action === 'restore') {
    // Only Admin can restore
    if (!$isCurUserAdmin) {
        echo json_encode(['status' => 'error', 'message' => 'สำหรับผู้ดูแลระบบ (Admin) เท่านั้น']);
        exit;
    }
    $res = ServicePriceExcelFile::restore($conn, $id);
    if ($res) {
        echo json_encode(['status' => 'success', 'message' => 'กู้คืนไฟล์ Excel และรายการราคากลับมาใช้งานตามปกติเรียบร้อยแล้ว']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถกู้คืนได้']);
    }
    exit;
}

if ($action === 'delete') {
    // Only Admin can permanently delete
    if (!$isCurUserAdmin) {
        echo json_encode(['status' => 'error', 'message' => 'สำหรับผู้ดูแลระบบ (Admin) เท่านั้น']);
        exit;
    }
    $res = ServicePriceExcelFile::permanentDelete($conn, $id);
    if ($res) {
        echo json_encode(['status' => 'success', 'message' => 'ลบรายการราคาถาวรเรียบร้อยแล้ว (ไฟล์ Excel ถูกเก็บไว้เป็น Backup)']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบถาวรได้']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Unknown action.']);
