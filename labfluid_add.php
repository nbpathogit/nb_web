<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fluid = new LabFluid();
    $fluid->id = $_GET['id'];
    $fluid->labname = $_POST['labname'];
    $fluid->lab_des = $_POST['lab_des'];

    if ($fluid->create($conn)) {

        Url::redirect("/labfluid.php");
    } else {
        echo '<script>alert("Add fluid fail. Please verify again")</script>';
    }
}
?>


<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">


        <?php if (!Auth::isLoggedIn()) : ?>
            You are not login.
        <?php else : ?>

            <div class="d-flex align-items-center justify-content-between">
                <a href="/labfluid.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-water me-2"></i>Fluid ทั้งหมด</a>
            </div>
    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">
        <h4>เพิ่ม Fluid</h4>

        <form class="row g-2" method="post">

            <div class="mb-3">
                <label for="labname" class="form-label">Fluid</label>
                <input name="labname" type="text" class="form-control" id="labname">
            </div>

            <div class="mb-3">
                <label for="lab_des" class="form-label">Fluid detail</label>
                <textarea name="lab_des" class="form-control" id="lab_des" rows="3"></textarea>
            </div>

            <div class="d-grid gap-2 d-md-block">
                <button name="Submit" type="submit" class="btn btn-primary">แก้ไขข้อมูล</button>
                <button name="Reset" type="reset" class="btn btn-secondary" id="Reset">ยกเลิก</button>
            </div>

        </form>

    <?php endif; ?>


    </div>
</div>


<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    //set active tab
    $("#fluid").addClass("active");
</script>