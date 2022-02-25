<?php
require 'includes/init.php';

$conn = require 'includes/db.php';

if(!Auth::isLoggedIn()){
    Url::redirect("/login.php");
}

require "includes/header.php"; ?>

<!-- Blank Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
        <div class="col-md-6 text-center">
            <h3>หน้าแรก</h3>
        </div>
    </div>
</div>
<!-- Blank End -->

<?php require "includes/footer.php"; ?>