<?php

require 'includes/init.php';
// var_dump($_SESSION['user']->id);exit;
// Auth::requireLogin();

$conn = require 'includes/db.php';
require 'user_auth.php';
$ugroups = Ugroup::getAll($conn);

$hospitals = Hospital::getAll($conn);
//var_dump($hospitals);

if (isset($_GET['id'])) {
    $user = User::getAll($conn, $_GET['id']);
    if (!$user) {
        die("user not found");
    }
} else {
    die("id not supplied, user not found");
}

$user_edit = new User();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // var_dump($_POST);
    // die();


    $user_edit->id = $_GET['id'];
    $user_edit->name = $_POST['name'];
    $user_edit->lastname = $_POST['lastname'];
    $user_edit->umobile = $_POST['umobile'];
    $user_edit->uemail = $_POST['uemail'];
    $user_edit->username = $_POST['username'];


    // if have old password field correct and new pass correct -> save hash new password
    if (
        !empty($_POST['old_password'])
        &&  User::authenticate($conn, $user[0]['username'], $_POST['old_password'])
        && ($_POST['password'] == $_POST['set_password_confirm'])
    ) {
        $user_edit->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $passchg = true;
    }
    // use old pass with no rehash
    else {
        $user_edit->password = $user[0]['password'];
        $passchg = false;
    }

    $user_edit->ugroup_id = $_POST['ugroup_id'];
    $user_edit->uhospital_id = $_POST['uhospital_id'];
    $user_edit->udetail = $_POST['udetail'];


    try {
        if ($user_edit->update($conn)) {
            $url = "/user_detail.php?id=$user_edit->id&result=1";
            if ($passchg) $url = $url . "&psch=1";
            Url::redirect($url);
        } else {
            echo '<script>alert("Edit user fail. Please verify again")</script>';
        }
    } catch (Exception $e) {
        $user->errors[] = $e->getMessage();
    }
}
?>

<?php require 'includes/header.php'; ?>
<?php if (!Auth::isLoggedIn()) : ?>
    <?php require 'blockopen.php'; ?>
    You are not login.<br>
    คุณไม่ได้ล็อกอิน กรุณาล็อกอินก่อนเข้าใช้งาน
    <?php require 'blockclose.php'; ?>
<?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust) && $_SESSION['user']->id != $_GET['id']) : //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้ + ดูได้เฉพาะของตัวเอง
?>
    <?php require 'blockopen.php'; ?>
    You have no authorize to view this content. <br>
    คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้
    <?php require 'blockclose.php'; ?>
<?php else : ?>

    <div class="container-fluid pt-4 px-4">
        <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

            <div><b>แก้ไขผู้ใช้งานระบบ</b></div>

            <?php if (!empty($user_edit->errors)) : ?>
                <div class="alert alert-warning" role="alert">
                    <?php foreach ($user_edit->errors as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>


            <form id="edituser" method="post">
                <?php require 'includes/user_form.php'; ?>
                <div><button id="save" class="btn btn-primary">Edit</button></div>
            </form>

        </div>
    </div>

<?php endif; ?>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {
        //set active tab
        $("#user").addClass("active");

        // prevent from unsave
        function onNosave(e) {
            e.preventDefault();
            e.returnValue = '';
        }

        $("input").change(function() {
            window.addEventListener("beforeunload", onNosave);
        });

        $("#save").click(function() {
            window.removeEventListener("beforeunload", onNosave);
        })
    });
</script>