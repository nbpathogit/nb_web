<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

 require 'user_auth.php'; 

$hospital = new Hospital();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $hospital->id = $_GET['id'];
    $hospital->hospital = $_POST['hospital_name'];
    $hospital->address = $_POST['hospital_address'];
    $hospital->hdetail = $_POST['hospital_detail'];
    
    $hospital->tax_id = $_POST['tax_id'];
    $hospital->create_by = $_POST['create_by'];

    if ($hospital->create($conn)) {

        Url::redirect("/hospital.php");
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
        <?php require 'blockclose.php';?>
    <?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)): //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้ ?> 
        <?php require 'blockopen.php'; ?>
        You have no authorize to view this content. <br>
        คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้
        <?php require 'blockclose.php';?>
    <?php else : ?>
<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">

            <div class="d-flex align-items-center justify-content-between">
                <a href="/hospital.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-house-chimney-medical me-2"></i>โรงพยาบาลทั้งหมด</a>
            </div>
    </div>
</div>
<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">

        <h4>เพิ่มสถานพยาบาล</h4>
        <?php require 'includes/hospital_form.php'; ?>

    </div>
</div>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {
        //set active tab
        $("#hospital").addClass("active");

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