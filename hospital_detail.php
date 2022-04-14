<?php
//var_dump($_GET);

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';
?>
<?php require 'user_auth.php'; ?>

<?php
if (isset($_GET['id'])) {
    $hospital = Hospital::getByID($conn, $_GET['id']);
} else {
    $hospital = null;
}

//var_dump($hospital);

?>

<?php require 'includes/header.php'; ?>
<?php if (!Auth::isLoggedIn()) : ?>
    <?php require 'blockopen.php'; ?>
    You are not login.<br>
    คุณไม่ได้ล็อกอิน กรุณาล็อกอินก่อนเข้าใช้งาน    
    <?php require 'blockclose.php';?>
<?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)): //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้ ?> 
    <?php require 'blockopen.php'; ?>
    You have no authorize to view this content. <br>
    คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้
    <?php require 'blockclose.php';?>
<?php else : ?>
<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <div class="d-flex align-items-center justify-content-start">
            <a href="/hospital.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-hospital-user me-2"></i>โรงพยาบาลทั้งหมด</a>
            <a href="/hospital_add.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-house-chimney-medical me-2"></i>เพิ่มโรงพยาบาล</a>
            <a href="/hospital_edit.php?id=<?= $_GET['id'] ?>" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-marker me-2"></i>แก้ไข</a>
        </div>
    </div>
</div>

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
<?php endif; ?>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    //set active tab
    $("#hospital").addClass("active");
</script>