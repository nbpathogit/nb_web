<?php
//คนตัดเนื้อ

require 'includes/init.php';

$conn = require 'includes/db.php';
?>
<?php require 'user_auth.php'; ?>
<?php require 'includes/header.php'; ?>
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

        </div>
    </div>

    <div class="container-fluid pt-4 px-4">
        <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
            
           <?php echo "Under construction"; ?>

<!--//            $data[] = [$job['id'], $job['job_role_id'], $job['patient_id'], $job['patient_number'], $job['user_id'], $job['pre_name'], $job['name'], $job['lastname'], $job['jobname'], $job['pay'], $job['cost_count_per_day'], $job['comment'], $job['finish_date'], $job['insert_time'], $job['qty'], $job['req_date']];
//-----     -----------------------0------------------1-----------------2---------------------3--------------------4---------------5----------------6---------------7----------------8--------------9-------------------10---------------------11----------------12--------------------13---------------14-------------15------------>


<!--<table class="table table-hover table-striped" id="job_table" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">#</th>                       0 
                        <th scope="col">job role id</th>             1 
                        <th scope="col">patient id</th>              2 
                        <th scope="col">Patient</th>                 3 
                        <th scope="col">user id</th>                 4 
                        <th scope="col">prename</th>                 5 
                        <th scope="col">name</th>                    6 
                        <th scope="col">lastname</th>                7 
                        <th scope="col">jobname</th>                 8 
                        <th scope="col">pay</th>                     9 
                        <th scope="col">pay/day</th>                 10 
                        <th scope="col">comment</th>                 11 
                        <th scope="col">Request_date</th>            12 
                        <th scope="col">finish_date</th>             13 
                        <th scope="col">Qty.</th>                    14 
                        <th scope="col">insert_time</th>             15 
                    </tr>
                </thead>
            </table>-->

        <?php endif; ?>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    var skey = "<?= $_SESSION["skey"] ?>";
    var domain = "<?= Url::currentURL() ?>";
</script>
<script src="<?= Url::getSubFolder1() ?>/js/job1_finish.js?v0"></script>