<?php
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


            <div class="d-flex align-items-center justify-content-between">
                <a href="#" id="csvdownload" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-download me-2"></i>Export</a>
            </div>

        </div>
    </div>

    <div class="container-fluid pt-4 px-4">
        <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">

<!-- [$job['id'], $job['job_role_id'], $job['patient_id'], $job['patient_number'], $job['user_id'], $job['pre_name'], $job['name'], $job['lastname'], $job['jobname'], $job['pay'], $job['cost_count_per_day'], $job['comment'], $job['finish_date'], $job['insert_time']]; -->
            <table class="table table-hover table-striped" id="job_table" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">job role id</th>
                        <th scope="col">patient id</th>
                        <th scope="col">Patient</th>
                        <th scope="col">user id</th>
                        <th scope="col">prename</th>
                        <th scope="col">name</th>
                        <th scope="col">lastname</th>
                        <th scope="col">jobname</th>
                        <th scope="col">pay</th>
                        <th scope="col">cost count per day</th>
                        <th scope="col">comment</th>
                        <th scope="col">finish_date</th>
                        <th scope="col">insert_time</th>
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
<script src="<?= Url::getSubFolder1() ?>/js/job.js?v0xxxx"></script>