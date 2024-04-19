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





//if ($isCurUserPatho) {
    Auth::pathoAadminSimulatelogin($conn);
    Url::redirect('/login.php');
//}

?>
<?php // require 'includes/data2DOM.php';  ?>

<?php require 'includes/header.php'; ?>





<?php require 'includes/footer.php'; ?>
<script type="text/javascript">


    $(document).ready(function () {

        // set active tab
        $("#admin_tab").addClass("active");
        $("#user_sim_admin_tab").addClass("active");
    });
</script>