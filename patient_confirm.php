<?php
require 'includes/init.php';

$conn = require 'includes/db.php';
Auth::requireLogin();

require 'user_auth.php';

?>

<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 addsolidborder">

        <?php if (!Auth::isLoggedIn()) : ?>
            You are not login.<br>
            คุณไม่ได้ล็อกอิน กรุณาล็อกอินก่อนเข้าใช้งาน
        <?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)) : //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้   
        ?>
            You have no authorize to view this content. <br>
            คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้
        <?php else : ?>

            <div class="d-flex align-items-center justify-content-between">
                <a href="patient_add.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-bed-pulse me-2"></i>เพิ่มข้อมูลผู้รักษา</a>
            </div>

    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 addsolidborder">


        <table class="table table-hover" id="patient_table" style="width:100%">
            <!--<table border="1" align="center">-->
            <thead>
                <tr>
                    <th>#</th>
                    <th>เลขที่ผู้ป่วย</th>
                    <th>ชื่อผู้ป่วย</th>
                    <th>นามสกุลผู้ป่วย</th>
                    <th>โรงพยาบาล</th>
                    <th>พยาธิแพทย์</th>
                    <th>วันที่รับ</th>
                    <th>วันที่รายงาน</th>
                    <th>คอนเฟิร์มผล</th>
                    <th>การออกผล</th>
                    <th>ความสำคัญ</th>
                    <th>PDF</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
        </table>

    <?php endif; ?>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    var skey = "<?= $_SESSION['skey']; ?>";
    var ugroup_id = <?= $_SESSION['user']->ugroup_id ?>;
    <?php if (isset($isCurUserAdmin)) : ?>
        var isCurUserAdmin = 1;
    <?php else : ?>
        var isCurUserAdmin = 0;
    <?php endif; ?>
</script>
<script type="text/javascript" src="<?= Url::getSubFolder1() ?>/js/patient_confirm.js?v=2"></script>