<?php
require 'includes/init.php';

$conn = require 'includes/db.php';

if (!Auth::isLoggedIn()) {
    Url::redirect("/login.php");
}

$stats = Stat::getTotal($conn);

require "includes/header.php"; ?>



<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-start p-4">
                <i class="fa-solid fa-database fa-lg me-2"></i>
                <h4 class="text-start">ข้อมูลจากฐานระบบ</h4>
            </div>
        </div>
        <?php foreach ($stats as $stat) :  ?>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3 text-end">
                        <p class="mb-2"><?= $stat["table_name"] ?></p>
                        <h6 class="mb-0"><?= $stat["table_rows"] ?></h6>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>


<?php require "includes/footer.php"; ?>

<script type="text/javascript">
    $(document).ready(function() {

        //set active tab
        $("#home").addClass("active");

    });
</script>