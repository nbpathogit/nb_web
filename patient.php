<?php
require 'includes/init.php';

$conn = require 'includes/db.php';
Auth::requireLogin();

require 'user_auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['trash_id'])) {
        if (Patient::movetotrash($conn, $_POST['trash_id'])) {
            Url::redirect('/patient.php');
        } else {
            Url::redirect('/patient.php');
        }
    }
    if (isset($_POST['delete_id'])) {
        if (Patient::delete2($conn, $_POST['delete_id'])) {
            Url::redirect('/patient.php');
        } else {
            Url::redirect('/patient.php');
        }
    }
}
?>

<?php if (isset($_GET['msg'])): ?>
    <script type="text/javascript">
        var carry_msg = "<?= $_GET['msg'] ?>";
    </script>
<?php endif; ?>

<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 addsolidborder">

        <?php if (!Auth::isLoggedIn()) : ?>
            You are not authorized.
        <?php else : ?>

            <div class="d-flex align-items-center justify-content-between">
                <a href="patient_add.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-bed-pulse me-2"></i>เพิ่มข้อมูลผู้รักษา</a>
            </div>


    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 addsolidborder">

                    
        <h1 align="center"><span id="patient_title_message">กำลังแสดงจากข้อมูลย้อนหลัง 2 เดือน</span></h1>
            

        <table class="table table-hover" id="patient_table" style="width:100%">
            <!--<table border="1" align="center">-->
            <thead>
                <tr>
                    <th>#</th>                    <!-- 0 -->
                    
                    <th>type</th>                   <!-- 1 -->

                    <th>เลขที่ผู้ป่วย</th>                 <!-- 2 --> 
                    <th>HN</th>                   <!-- 3 --> 
                    <th>ชื่อผู้ป่วย</th>                  <!-- 4 --> 
                    <th>นามสกุลผู้ป่วย</th>             <!-- 5 -->
                    <th>โรงพยาบาล</th>               <!-- 6 -->
                    <th>พยาธิแพทย์</th>                <!-- 7 -->
                    <th>วันที่รับ</th>                   <!-- 8 --> 
                    <th>วันที่รายงาน</th>                 <!-- 9 -->
                    <th>ใช้เวลา<br>ออกผล(วัน)</th>           <!-- 10 --> 
                    <th>สถานะอื่นๆ</th>                 <!-- 11 -->  
                    <th>การออกผล</th>                 <!-- 12 --> 
                    <th>ความสำคัญ</th>                  <!-- 13 -->
                    <th>PDF</th>                    <!-- 14 -->
                    <th>จัดการ</th>                    <!-- 15 -->
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
    var domain = "<?= Url::currentURL() ?>";
    <?php if ($isCurUserAdmin) : ?>
        var isCurUserAdmin = 1;
    <?php else : ?>
        var isCurUserAdmin = 0;
    <?php endif; ?>
        
    <?php if ($isCurUserCust) : ?>
        var isCurUserCust = 1;
    <?php else : ?>
        var isCurUserCust = 0;
    <?php endif; ?>
</script>
<script type="text/javascript" src="<?= Url::getSubFolder1() ?>/js/patient.js?v=3xxxxxx"></script>

