<?php

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

 
require 'user_auth.php'; 
if (!Auth::isLoggedIn()) {
    require 'includes/header.php';
        require 'blockopen.php';
        echo 'You are not login.<br>';
        echo 'คุณไม่ได้ล็อกอิน กรุณาล็อกอินก่อนเข้าใช้งาน';  
        require 'blockclose.php';
                die();
 }       
if (($isCurUserClinicianCust || $isCurUserHospitalCust)){ //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้ 
    require 'includes/header.php';
        require 'blockopen.php'; 
        echo 'You have no authorize to view this content. <br>';
        echo 'คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้';
        require 'blockclose.php';
}

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

<?php require 'includes/header.php';?>
<h2>Delete fluid</h2>

<form method="post">

    <p>Are you sure?</p>

    <button>Delete</button>
    <a href="labfluid.php">Cancel</a>

</form>

<?php require 'includes/footer.php'; ?>
