<?php

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';
require 'user_auth.php';



if (isset($_GET['id'])) {

    $user = User::getByID($conn, $_GET['id']);

    if (!$user) {
        die("user not found");
    }
} else {
    die("id not supplied, user not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($user->delete($conn)) {

        Url::redirect("/user.php");
    }
}

?>
<?php require 'includes/header.php'; ?>

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
    
if (!$isCurUserAdmin){
    Auth::block();
}

<h2>Delete user</h2>

<form method="post">

    <p>Are you sure?</p>

    <button>Delete</button>
    <a href="user_detail.php?id=<?= $user->id; ?>">Cancel</a>

</form>
<?php endif; ?>
<?php require 'includes/footer.php'; ?>
