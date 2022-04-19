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

    $url = "";

    // flag var
    $user_updated = false;  //update user success
    $passchg = false;       // password has change
    $is_signature_file = false;  // have signature file

    $user_edit->id = $_GET['id'];
    $user_edit->name = $_POST['name'];
    $user_edit->lastname = $_POST['lastname'];
    $user_edit->umobile = $_POST['umobile'];
    $user_edit->uemail = $_POST['uemail'];
    $user_edit->username = $_POST['username'];


    // if have old password field correct and new pass correct -> save hash new password
    if (
        !empty($_POST['old_password'])
        &&  (User::authenticate($conn, $user[0]['username'], $_POST['old_password']) || $isCurUserAdmin)  // admin can change password without old password
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
            $user_updated = true;
            $url = "/user_detail.php?id=$user_edit->id&result=1";
            if ($passchg) $url = $url . "&psch=1";
        } else {
            echo '<script>alert("Edit user fail. Please verify again")</script>';
        }
    } catch (Exception $e) {
        $user_edit->errors[] = $e->getMessage();
    }


    // var_dump($_FILES);
    // exit;
    if ($user_updated) {
        try {

            switch ($_FILES['signature']['error']) {
                case UPLOAD_ERR_OK:
                    break;

                case UPLOAD_ERR_NO_FILE:
                    Url::redirect($url);
                    break;

                case UPLOAD_ERR_INI_SIZE:
                    throw new Exception('File is too large');
                    break;

                default:
                    throw new Exception('An error occurred');
            }

            if (empty($_FILES)) {
                // do nothing
            } else if ($_FILES['signature']['size'] < 5000000) {
                $mime_types = ['image/gif', 'image/png', 'image/jpeg', 'image/jpg'];
                if (in_array($_FILES['signature']['type'], $mime_types)) {


                    // validate filename
                    $pathinfo = pathinfo($_FILES['signature']['name']); //split file extension

                    //use username as filename
                    $base = $_POST['username'];
                    $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $base);
                    $filename = $base . "." . $pathinfo['extension'];

                    // save destination
                    $destination = $_SERVER['DOCUMENT_ROOT'] . "/signature/" . $filename;

                    // chech exitsting filename
                    $i = 1;
                    while (file_exists($destination)) {
                        $filename = $base . "-$i." . $pathinfo['extension'];
                        $destination = $_SERVER['DOCUMENT_ROOT'] . "/signature/" . $filename;
                        $i++;
                    }

                    // pre signature file to delete
                    $pre_signature = $_SERVER['DOCUMENT_ROOT'] . $user[0]['signature_file'];

                    if (move_uploaded_file($_FILES['signature']['tmp_name'], $destination)) {
                        // echo "upload success";
                        $user_edit->signature_file = "/signature/" . $filename;
                        if ($user_edit->setSignatureFile($conn)) {

                            //delete old file
                            if (!$pre_signature == "" && !is_null($pre_signature)) {
                                unlink($pre_signature);
                            }

                            $url .= "&signature=1";
                            Url::redirect($url);
                        }
                    } else {

                        throw new Exception('Unable to move uploaded file');
                    }
                } else {
                    throw new Exception('Invalid file type');
                }
            } else {
                throw new Exception('File is too large');
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            $url .= "&signature=" . urlencode($error);
            Url::redirect($url);
        }
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


            <form id="edituser" method="post" enctype="multipart/form-data">
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
        });

        $("#signature-delete").on("click",function(e) {

            e.preventDefault();
            if (confirm("Are you sure?")) {
                var frm = $("<form>");
                frm.attr('method', 'post');
                frm.attr('action', $(this).attr('href'));
                frm.appendTo("body");
                frm.submit();
            }

        });

    });
</script>