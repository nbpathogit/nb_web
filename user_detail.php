<?php
//var_dump($_GET);

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';
require 'user_auth.php';

if (isset($_GET['id'])) {
    $user = User::getAll($conn, $_GET['id']);
} else {
    $user = null;
}

// var_dump($user);



?>

<?php require 'includes/header.php'; ?>
<?php if (!Auth::isLoggedIn()) : ?>
    <?php require 'blockopen.php'; ?>
    You are not login.<br>
    คุณไม่ได้ล็อกอิน กรุณาล็อกอินก่อนเข้าใช้งาน
    <?php require 'blockclose.php'; ?>
<?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust) && $_SESSION['user']->id != $_GET['id']) : //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้  + ดูได้เฉพาะของตัวเอง 
?>
    <?php require 'blockopen.php'; ?>
    You have no authorize to view this content. <br>
    คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้
    <?php require 'blockclose.php'; ?>
<?php else : ?>
    <div class="container-fluid pt-4 px-4">
        <div class="row bg-light rounded align-items-center justify-content-start p-3 mx-1">
            <div class="d-flex align-items-center justify-content-start">
                <a href="/user.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-user-doctor me-2"></i>ผู้ใช้งานทั้งหมด</a>
                <a href="/user_add.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-user-plus me-2"></i>เพิ่มผู้ใช้งาน</a>
                <a href="/user_edit.php?id=<?= $_GET['id'] ?>" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-marker me-2"></i>แก้ไข</a>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-4 px-4">
        <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

            <div>
                <p>
                    <?php
                    if (isset($_GET['result'])) {
                        if ($_GET['result'] == 1) : ?>
                <div class="alert alert-success" role="alert">
                    USER Added/Edit Successful
                </div>
            <?php endif;
                    }
                    if (isset($_GET['psch'])) {
                        if ($_GET['psch'] == 1) : ?>
                <div class="alert alert-success" role="alert">
                    Edit new password Successful
                </div>
            <?php endif;
                    }

                    if (isset($_GET['signature'])) {
                        if ($_GET['signature'] == 1) : ?>
                <div class="alert alert-success" role="alert">
                    Signature update Successful
                </div>
            <?php else : ?>
                <div class="alert alert-warning" role="alert">
                    <?= $_GET['signature']; ?>
                </div>
        <?php endif;
                    }
        ?>
        <p>
            </div> <br>

            <table class="table table-hover table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col" style="width:20%">Key</th>
                        <th scope="col">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>id</td>
                        <td>
                            <?= $user[0]['uid']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>ชื่อนามสกุล</td>
                        <td>
                            <?= $user[0]['pre_name'] . " " . $user[0]['name'] .  " " . $user[0]['lastname']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Fullname</td>
                        <td>
                        <?= $user[0]['pre_name_e'] . " " . $user[0]['name_e'] .  " " . $user[0]['lastname_e']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>ชื่อย่อ</td>
                        <td>
                        <?= $user[0]['short_name']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>วุฒิการศึกษา</td>
                        <td>
                            <?= $user[0]['educational_bf']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>ตำแหน่ง</td>
                        <td>
                            <?= $user[0]['role']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>เบอร์โทรศัพท์</td>
                        <td>
                            <?= $user[0]['umobile']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>e-mail</td>
                        <td>
                            <?= $user[0]['uemail']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>รายละเอียดเพิ่มเติม</td>
                        <td>
                            <?= $user[0]['udetail']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>username</td>
                        <td>
                            <?= $user[0]['username']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>กลุ่มผู้ใช้งาน</td>
                        <td>
                            <?= $user[0]['ugroup']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>รายละเอียด</td>
                        <td>
                            <?= $user[0]['udetail']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>ลายเซ็น</td>
                        <td>
                            <?php if ($user[0]['signature_file'] == "" || is_null($user[0]['signature_file'])) : ?>
                                no file
                            <?php else : ?>
                                <img src="<?= $user[0]['signature_file'] ?>" class="img-thumbnail" style="max-height: 150px;">
                            <?php endif; ?>

                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
<?php endif; ?>
<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    // set active tab
    $("#user").addClass("active");
</script>