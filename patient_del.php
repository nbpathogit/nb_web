<?php

require 'includes/init.php';

$conn = require 'includes/db.php';

?>
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

    $patient = Patient::getByID($conn, $_GET['id']);

    if (!$patient) {
        die("patient not found");
    }
} else {
    die("id not supplied, patient not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($patient->delete($conn)) {

        Url::redirect("/patient.php");
    }
}

?>
    
    
<?php require 'includes/header.php'; ?>

<h2>Delete patient</h2>

<form method="post">

    <p>Are you sure?</p>

    <button>Delete</button>
    <a href="patient.php?id=<?= $patient->id; ?>">Cancel</a>

</form>

<?php require 'includes/footer.php'; ?>
<?php endif; ?>
