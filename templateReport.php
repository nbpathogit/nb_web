<?php
require 'includes/init.php';

$conn = require 'includes/db.php';

require 'includes/header.php'; ?>
<?php require 'user_auth.php'; ?>
<?php if (!Auth::isLoggedIn()) : ?>
    You are not login.<br>
    คุณไม่ได้ล็อกอิน กรุณาล็อกอินก่อนเข้าใช้งาน
    <?php require 'includes/footer.php'; die();?>
<?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)) : //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้  
?>
    You have no authorize to view this content. <br>
    คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้
    <?php require 'includes/footer.php'; die();?>
<?php else : ?>
<?php endif; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
        
        <table class="table table-hover table-striped" id="template_report_table" style="width:100%">

            <thead>
                <tr>
                    <!--id     |                |      ชื่อ          |  ชนิด ออกผล    |  ชือเท็มเพลต  | เท็มเพล็ต-->
                    <th scope="col">id</th>
                    <th scope="col">ชื่อ</th>
                    <th scope="col">ชนิด ออกผล</th>
                    <th scope="col">ชือเท็มเพลต</th>
                    <th scope="col">เท็มเพล็ต</th>
                    <th scope="col">จัดการ</th>
        

                </tr>
            </thead>
        </table>
        
        
    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
       var skey = "<?= $_SESSION["skey"] ?>";
       var user_id = <?= Auth::getUserId()?>;
</script>
<script src="js/template_report.js?v0xxxxxxxปxxปxxx"></script>