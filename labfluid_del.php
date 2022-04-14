<?php

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

?>
<?php require 'includes/header.php'; ?>
<?php require 'user_auth.php'; ?>
    <?php if (!Auth::isLoggedIn()) : ?>
        <?php require 'blockopen.php'; ?>
        You are not login.<br>
        คุณไม่ได้ล็อกอิน กรุณาล็อกอินก่อนเข้าใช้งาน    
        <?php require 'blockclose.php';?>
    <?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)): //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้ ?> 
        <?php require 'blockopen.php'; ?>
        You have no authorize to view this content. <br>
        คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้
        <?php require 'blockclose.php';?>
    <?php else : ?>
<?php
if (!$isCurUserAdmin){
    Auth::block();
}

if (isset($_GET['id'])) {

    $fluid = LabFluid::getByID($conn, $_GET['id']);

    if (!$fluid) {
        die("fluid not found");
    }
} else {
    die("id not supplied, fluid not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($fluid->delete($conn)) {

        Url::redirect("/labfluid.php");
    }
}

?>


<h2>Delete fluid</h2>

<form method="post">

    <p>Are you sure?</p>

    <button>Delete</button>
    <a href="labfluid.php">Cancel</a>

</form>
<?php endif; ?>
<?php require 'includes/footer.php'; ?>
