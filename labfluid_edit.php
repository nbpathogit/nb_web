<?php
require 'includes/init.php';
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
if (($isCurUserClinicianCust || $isCurUserHospitalCust)) { //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้ .
    require 'includes/header.php';
    require 'blockopen.php';
    echo 'You have no authorize to view this content. <br>';
    echo 'คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้';
    require 'blockclose.php';
}

if (isset($_GET['id'])) {

    $fluid = LabFluid::getByID($conn, $_GET['id']);
    // var_dump($fluid);exit;
    if (!$fluid) {
        die("fluid not found");
    }
} else {
    die("id not supplied, fluid not found");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // var_dump($_POST);
    //die();

    $fluid = new LabFluid();
    $fluid->id = $_GET['id'];
    $fluid->labname = $_POST['labname'];
    $fluid->lab_des = $_POST['lab_des'];

    if ($fluid->update($conn)) {

        Url::redirect("/labfluid.php");
    } else {
        echo '<script>alert("Add fluid fail. Please verify again")</script>';
    }
}
?>


<?php require 'includes/header.php'; ?>
<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">
        <div class="d-flex align-items-center justify-content-between">
            <a href="/labfluid.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-water me-2"></i>Fluid ทั้งหมด</a>
        </div>
    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <h4>แก้ไข Fluid</h4>

        <form class="row g-2" method="post">

            <div class="mb-3">
                <label for="labname" class="form-label">Fluid</label>
                <input name="labname" type="text" class="form-control" id="labname" value="<?= htmlspecialchars($fluid->labname); ?>">
            </div>

            <div class="mb-3">
                <label for="lab_des" class="form-label">Fluid detail</label>
                <textarea name="lab_des" class="form-control" id="lab_des" rows="3"><?= htmlspecialchars($fluid->lab_des); ?></textarea>
            </div>

            <div class="d-grid gap-2 d-md-block">
                <button name="Submit" id="submit" type="submit" class="btn btn-primary">แก้ไขข้อมูล</button>
                <button name="Reset" type="reset" class="btn btn-secondary" id="Reset">ยกเลิก</button>
            </div>

        </form>

    </div>
</div>


<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function () {

        $("#fluid").addClass("active");

        // prevent from unsave
        function onNosave(e) {
            e.preventDefault();
            e.returnValue = '';
        }

        $("#labname, #lab_des").change(function () {
            window.addEventListener("beforeunload", onNosave);
        });

        $("#submit").click(function () {
            window.removeEventListener("beforeunload", onNosave);
        })

    });
</script>