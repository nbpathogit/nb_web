<?php
require 'includes/init.php';

$conn = require 'includes/db.php';
require 'user_auth.php';
?>

<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()) : ?>
    You are not authorized.
<?php else : ?>

<?php endif; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">


            <div class="d-flex align-items-center justify-content-between">
                <a href="patient_monitor_8000_print_job.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-bed-pulse me-2"></i>พิมพ์งานร้องขอสไลด์พิเศษ</a>
            </div>

    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">


        <table class="table table-hover" id="request_sp_sl_table" style="width:100%">
            <!--<table border="1" align="center">-->
            <thead>
                <tr>

                    <th>rid              </th><!--0-->
                    <th>bid              </th><!--1-->
                    <th>jid              </th><!--2-->
                    <th>patient_id_key   </th><!--3-->
                    <th>number           </th><!--4-->
                    <th>req_date         </th><!--5-->
                    <th>finish_date      </th><!--6-->
                    <th>comment          </th><!--7-->
                    <th>jowowner         </th><!--8-->
                    <th>req_sp_type      </th><!--9-->
                    <th>bjob             </th><!--10-->
                    <th>pathologist      </th><!--11-->
                    <th>จัดการ             </th><!--12-->
                    
                </tr>
            </thead>
        </table>



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
<script type="text/javascript" src="<?= Url::getSubFolder1() ?>/js/patient_8000.js?v=1xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"></script>