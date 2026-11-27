<?php
require '../includes/init.php';
$conn = require '../includes/db.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$subfolder = isset($_POST['subfolder']) ? trim($_POST['subfolder']) : '';
$skey = isset($_POST['skey']) ? trim($_POST['skey']) : '';
$adminusername = isset($_POST['adminusername']) ? trim($_POST['adminusername']) : '';
$ugroup_id = isset($_POST['ugroup_id']) ? trim($_POST['ugroup_id']) : '';

if ($username === '' || $password === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'กรุณากรอกรหัสผ่าน'
    ]);
    exit;
}

if (!User::isUserNameActive($conn, $username)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ไม่พบบัญชีผู้ใช้ หรือบัญชีถูกระงับการใช้งาน'
    ]);
    exit;
}

$isAuthenticated = User::authenticate($conn, $username, $password);

// กรณี Admin จำลองผู้ใช้ (Simulate Login) อนุญาตให้ใช้รหัสผ่านของ Admin ได้ด้วย
if (!$isAuthenticated && $adminusername !== '') {
    if (User::isUserNameActive($conn, $adminusername) && User::authenticate($conn, $adminusername, $password)) {
        $isAuthenticated = true;
    }
}

if (!$isAuthenticated) {
    echo json_encode([
        'status' => 'error',
        'message' => 'รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง'
    ]);
    exit;
}

// Re-initialize session via Auth::login
Auth::login($conn, $username, $subfolder);

// คงค่า skey เดิมของหน้าเว็บไว้ เพื่อให้ AJAX / DataTables ในหน้าที่เปิดค้างอยู่ทำงานต่อได้ทันที
if ($skey !== '') {
    $_SESSION['skey'] = $skey;
}

// คงสถานะ Admin Simulate (ถ้ามี)
if ($adminusername !== '') {
    $_SESSION['adminusername'] = $adminusername;
    if ($ugroup_id === '1900') {
        $_SESSION['usergroup'] = Ugroup::getByID($conn, '1900');
    }
}

echo json_encode([
    'status' => 'success',
    'timeRemain' => Auth::$sesstion_timelimit_int_sec,
    'skey' => $_SESSION['skey']
]);
