<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';
?>
<?php require 'user_auth.php'; ?>

<?php
if (isset($_GET['id'])) {

    $hospital = Hospital::getByID($conn, $_GET['id']);

    if (!$hospital) {
        die("hospital not found");
    }
} else {
    die("id not supplied, hospital not found");
}

//var_dump($hospitals);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     var_dump($_POST);
//     die();
    $hospital = Hospital::getByID($conn, $_GET['id']);
    $hospital->id = $_GET['id'];
    $hospital->hospital = $_POST['hospital_name'];
    $hospital->address = $_POST['hospital_address'];
    $hospital->hdetail = $_POST['hospital_detail'];
    $hospital->tax_id = $_POST['tax_id'];

//     var_dump($hospital);
//     exit();

    if ($hospital->update($conn)) {

        Url::redirect("/hospital_detail.php?id=".$hospital->id);
    } else {
        echo '<script>alert("Add hospital fail. Please verify again")</script>';
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

    <div class="container-fluid pt-4 px-4">
        <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">

            <?php if (!Auth::isLoggedIn()) : ?>
                You are not login.
            <?php else : ?>

                <div class="d-flex align-items-center justify-content-between">
                    <a href="/hospital.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-house-chimney-medical me-2"></i>โรงพยาบาลทั้งหมด</a>
                </div>
        </div>
    </div>
    <div class="container-fluid pt-4 px-4">
        <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">

            <h4>แก้ไขสถานพยาบาล</h4>
            <?php require 'includes/hospital_form.php'; ?>


        <?php endif; ?>


        </div>
    </div>
<?php endif; ?>


<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {
        //set active tab
        $("#hospital").addClass("active");
        $("#manage_table").addClass("active");
        $("#manage_table").addClass("show");
        $(".manage_table_dropdown").addClass("show");

        // prevent from unsave
        function onNosave(e) {
            e.preventDefault();
            e.returnValue = '';
        }

        $("#hospital_name, #hospital_address, #hospital_detail").change(function() {
            window.addEventListener("beforeunload", onNosave);
        });

        $("#save").click(function() {
            window.removeEventListener("beforeunload", onNosave);
        })
    });
</script>