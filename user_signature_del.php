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

    if ($isCurUserAdmin || $_SESSION['user']->id == $_GET['id']) {
        $pre_signature = $user->signature_file;

        $user->signature_file = null;

        if ($user->setSignatureFile($conn)) {

            //delete old file
            if (!$pre_signature == "" && !is_null($pre_signature)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . $pre_signature);
            }

            Url::redirect("/user_edit.php?id=" . $_GET['id']);
        }
    } else {
        Auth::block();
    }
}

?>
<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()) : ?>
    <?php require 'blockopen.php'; ?>
    You are not login.<br>
    คุณไม่ได้ล็อกอิน กรุณาล็อกอินก่อนเข้าใช้งาน
    <?php require 'blockclose.php'; ?>
<?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)) : //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้ 
?>
    <?php require 'blockopen.php'; ?>
    You have no authorize to view this content. <br>
    คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้
    <?php require 'blockclose.php'; ?>
<?php else : ?>



    <h2>Delete Signature</h2>

    <form method="post">

        <p>Are you sure?</p>

        <button>Delete</button>
        <a href="user_edit.php?id=<?= $user->id; ?>">Cancel</a>

    </form>
<?php endif; ?>
<?php require 'includes/footer.php'; ?>