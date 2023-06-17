<?php
require 'includes/init.php';

$conn = require 'includes/db.php';
Auth::requireLogin();
require 'user_auth.php';

require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">


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

                    <th scope="col">#</th> <!--0-->
                    <th scope="col">specimen id</th> <!--1-->
                    <th scope="col">patient id</th><!--2-->
                    <th scope="col">เลขที่โรงพยาบาล</th> <!--3-->
                    <th scope="col">ชื่อ</th> <!--4-->
                    <th scope="col">lastname</th> <!--5-->
                    <th scope="col">ชนิดค่าบริการ</th> <!--6-->
                    <th scope="col">code</th> <!--7-->
                    <th scope="col">description</th> <!--8-->
                    <th scope="col">วันที่รับ</th> <!--9-->
                    <th scope="col">วันที่เสร็จ</th> <!--10-->
                    <th scope="col">โรงพยาบาล</th> <!--11-->
                    <th scope="col">เลขที่โรงพยาบาล</th> <!--12-->
                    <th scope="col">แพทย์ผู้ส่งตรวจ</th> <!--13-->
                    <th scope="col">pathologist</th> <!--14-->
                    <th scope="col">ค่าตรวจ</th> <!--15-->
                    <th scope="col">Others</th> <!--16-->
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="16" style="text-align:right">Total:</th>
                    <th style="text-align:right"></th>
                </tr>
            </tfoot>
        </table>

    <?php endif; ?>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    var skey = "<?= $_SESSION["skey"] ?>";
    $("#manage_bill").addClass("active");
</script>
<script src="<?= Url::getSubFolder1() ?>/js/billing.js?v2"></script>