<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

$hospital = new Hospital();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $hospital->id = $_GET['id'];
    $hospital->hospital = $_POST['hospital_name'];
    $hospital->address = $_POST['hospital_address'];
    $hospital->hdetail = $_POST['hospital_detail'];

    if ($hospital->create($conn)) {

        Url::redirect("/hospital.php");
    } else {
        echo '<script>alert("Add hospital fail. Please verify again")</script>';
    }
}
?>


<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">


        <?php if (!Auth::isLoggedIn()) : ?>
            You are not login.
        <?php else : ?>
            <h4>เพิ่มสถานพยาบาล</h4>
            <?php require 'includes/hospital_form.php'; ?>

        <?php endif; ?>


    </div>
</div>


<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    //set active tab
    $("#hospital_main").addClass("active");
    $("#hospital_add").addClass("active");
</script>