<?php
require 'includes/init.php';

$conn = require 'includes/db.php';

require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">

        <?php require 'user_auth.php'; ?>
        <?php if (!Auth::isLoggedIn()) : ?>
            You are not login.<br>
            คุณไม่ได้ล็อกอิน กรุณาล็อกอินก่อนเข้าใช้งาน
        <?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)) : //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้  
        ?>
            You have no authorize to view this content. <br>
            คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้
        <?php else : ?>


            <div class="d-flex align-items-center justify-content-between">
                <a href="#" id="csvdownload" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-download me-2"></i>Export</a>
            </div>

    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">


        <table class="table table-hover table-striped" id="billing_table" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">specimen id</th>
                    <th scope="col">patient id</th>
                    <th scope="col">Number</th>
                    <th scope="col">name</th>
                    <th scope="col">lastname</th>
                    <th scope="col">slide type</th>
                    <th scope="col">code description</th>
                    <th scope="col">description</th>
                    <th scope="col">import date</th>
                    <th scope="col">Date</th>
                    <th scope="col">Hospital</th>
                    <th scope="col">hn</th>
                    <th scope="col">Doctor</th>
                    <th scope="col">pathologist</th>
                    <th scope="col">cost</th>
                    <th scope="col">Others</th>
                </tr>
            </thead>
        </table>

    <?php endif; ?>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
       var skey = "<?= $_SESSION["skey"] ?>";
</script>
<script src="<?= Url::getSubFolder1() ?>/js/billing_2.js"></script>