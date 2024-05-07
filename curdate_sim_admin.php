<?php
require 'includes/init.php';
// var_dump($_SESSION['user']->id);exit;
// Auth::requireLogin();
Auth::requireLogin("user_sim_admin.php");

$conn = require 'includes/db.php';
require 'user_auth.php';





$ugroups = Ugroup::getAll($conn);

$hospitals = Hospital::getAll($conn);
//var_dump($hospitals);




if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    var_dump($_POST);
    $_SESSION['cur_date'] = $_POST['cur_date'];
}
?>


<?php require 'includes/header.php'; ?>

<?php 
if (!$isCurUserAdmin) {
    echo 'No authorize this page.';
    echo "<?php require 'includes/footer.php'; ?>";
    die();
}
?>


<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
        <h1>Current Date is :: <?= Util::get_curreint_thai_date_time(); ?></h1>
    </div>
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
        <form method="post">
            <div class="col-xl-4 col-md-6 ">
                <label for="" class=""><b>Simulate Current Date:</b></label>
                <input type="text" name="cur_date" id="cur_date" class="form-control " value="<?= Util::get_curreint_thai_date(); ?>">
            </div>
            <div class="col-xl-4 col-md-6 ">
                <button name="set_cur_date" id="set_cur_date" type="submit" class="btn btn-primary">&nbsp;&nbsp;Set Current Date&nbsp;&nbsp;</button>
            </div>
        </form>
    </div>
</div>




<?php require 'includes/footer.php'; ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<!--<script src="https://code.jquery.com/jquery-3.6.0.js"></script>-->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>


    
    $(function() {
        $("#cur_date").datepicker({
            dateFormat: 'yy-mm-dd'
        });

    });
</script>
