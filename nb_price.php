<?php
require 'includes/init.php';
$conn = require 'includes/db.php';
!Auth::requireLogin();
require 'user_auth.php'; 
if (!Auth::isLoggedIn()) {
    Util::alert(" You are not login.");
    die();
} elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)) {
    Util::alert("You have no authorize to access this page.");
} else {
    //Allow to do next 
}

?>
<?php require 'includes/header.php'; ?>

<?php require 'includes/footer.php'; ?>