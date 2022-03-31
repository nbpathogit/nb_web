<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {

    $hospital = Hospital::getByID($conn, $_GET['id']);

    if (!$hospital) {
        die("hospital not found");
    }
} else {
    die("id not supplied, hospital not found");
}

//var_dump($hospitals);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // var_dump($_POST);
    // die();

    // $hospital = new Hospital();
    $hospital->id = $_GET['id'];
    $hospital->hospital = $_POST['hospital_name'];
    $hospital->address = $_POST['hospital_address'];
    $hospital->hdetail = $_POST['hospital_detail'];

    // var_dump($hospital);
    // exit();

    if ($hospital->update($conn)) {

        Url::redirect("/hospital.php");
    } else {
        echo '<script>alert("Add hospital fail. Please verify again")</script>';
    }
}

?>




<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <?php if (!Auth::isLoggedIn()) : ?>
            You are not login.
        <?php else : ?>

            <div class="d-flex align-items-center justify-content-between">
                <a href="/hospital.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-house-chimney-medical me-2"></i>โรงพยาบาลทั้งหมด</a>
            </div>
    </div>
</div>
<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <h4>แก้ไขสถานพยาบาล</h4>
        <?php require 'includes/hospital_form.php'; ?>


    <?php endif; ?>


    </div>
</div>


<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {
        //set active tab
        $("#hospital").addClass("active");

        // prevent from unsave
        function onNosave(e) {
            e.preventDefault();
            e.returnValue = '';
        }

        $("#hospital_name, #hospital_address, #hospital_detail").change(function() {
            window.addEventListener("beforeunload", onNosave);
        });

        $("#save").click(function() {
            window.removeEventListener("beforeunload", onNosave);
        })
    });
</script>