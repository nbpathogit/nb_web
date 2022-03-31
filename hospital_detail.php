<?php
//var_dump($_GET);

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {
    $hospital = Hospital::getByID($conn, $_GET['id']);
} else {
    $hospital = null;
}

//var_dump($hospital);

?>

<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

<div class="d-flex align-items-center justify-content-between">
                <a href="/hospital.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-house-chimney-medical me-2"></i>โรงพยาบาลทั้งหมด</a>
            </div></div></div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <?php if ($hospital) : ?>

            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th scope="col">Key</th>
                        <th scope="col">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">ชื่อสถานพยาบาล</th>
                        <td><?= htmlspecialchars($hospital->hospital); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">ที่อยู่</th>
                        <td><?= htmlspecialchars($hospital->address); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">รายละเอียด</th>
                        <td><?= htmlspecialchars($hospital->hdetail); ?></td>
                    </tr>

                </tbody>
            </table>

        <?php else : ?>
            <p>Hospital not found.</p>
        <?php endif; ?>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    //set active tab
    $("#hospital_main").addClass("active");
    $("#hospital_add").addClass("active");
</script>