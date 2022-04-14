<?php
require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';
require 'user_auth.php';
?>
<?php require 'user_auth.php'; ?>

<?php
if (isset($_GET['id'])) {

    $hospital = Hospital::getByID($conn, $_GET['id']);

    if (!$hospital) {
        die("hospital not found for id ::"+ $_GET['id']);
    }
} else {
    die("id not supplied, hospital not found, for id ::"+ $_GET['id']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($hospital->delete($conn)) {

        Url::redirect("/hospital.php");
    }
}
?>
<?php require 'includes/header.php'; ?>
<?php if (!Auth::isLoggedIn()) : ?>
    <?php require 'blockopen.php'; ?>
    You are not login.<br>
    คุณไม่ได้ล็อกอิน กรุณาล็อกอินก่อนเข้าใช้งาน    
    <?php require 'blockclose.php'; ?>
<?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)): //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้  ?> 
    <?php require 'blockopen.php'; ?>
    You have no authorize to view this content. <br>
    คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้
    <?php require 'blockclose.php'; ?>
<?php else : ?>
    <?php
    if (!$isCurUserAdmin) {
        Auth::block();
    }
    ?>
    <h2>Delete hospital</h2>

    <form method="post">

        <p>Are you sure?</p>

        <button>Delete</button>
        <a href="hospital.php?id=<?= $hospital->id; ?>">Cancel</a>

    </form>
<?PHP endif; ?>

<?php require 'includes/footer.php'; ?>