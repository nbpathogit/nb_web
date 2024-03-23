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


<!--            <div class="d-flex align-items-center justify-content-between">
                <a href="#" id="csvdownload" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-download me-2"></i>Export</a>
            </div>-->

    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
        <h4>ดรายการใบแจ้งหนี้ในแต่ละผู้รักษา</h4>
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" >
            <i class="fa fa-calendar"></i>(ดึงตามวันรับเข้า)&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
        <br><br>

        <table class="table table-hover table-striped" id="billing_table" style="width:100%">
            <thead>
                <tr>

                    <th scope="col">#</th>            <!--0-->
                    <th scope="col">b_id</th>         <!--1-->
                    <th scope="col">type</th>         <!--2-->
                    <th scope="col">SN</th>           <!--3-->
                    <th scope="col">HN</th>           <!--4-->
                    <th scope="col">Patient</th>      <!--5-->
                    <th scope="col">Clinicient</th>   <!--6-->
                    <th scope="col">Hospital</th>     <!--7-->
                    <th scope="col">pathologist</th>  <!--8-->
                    <th scope="col">Accept Date</th>  <!--9-->
                    <th scope="col">Billing Date</th> <!--10-->
                    <th scope="col">Service Type</th> <!--11-->
                    <th scope="col">Code</th>         <!--12-->
                    <th scope="col">Description</th>  <!--13-->
                    <th scope="col">Block</th>        <!--14-->
                    <th scope="col">Cost</th>         <!--15-->

                </tr>
            </thead>
<!--            <tfoot>
                <tr>
                    <th colspan="16" style="text-align:right">Total:</th>
                    <th style="text-align:right"></th>
                </tr>
            </tfoot>-->
        </table>

    <?php endif; ?>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript">
    var skey = "<?= $_SESSION["skey"] ?>";
    $("#manage_bill").addClass("active");
    $("#manage_bill_dropdown").addClass("show");
    $("#billing_tab").addClass("active");
</script>
<script src="<?= Url::getSubFolder1() ?>/js/billing_a.js?v1"></script>