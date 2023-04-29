<?php
require 'includes/init.php';
// var_dump($_SESSION['user']->id);exit;
// Auth::requireLogin();
Auth::requireLogin("user_edit.php", $_GET['id']);

$conn = require 'includes/db.php';
require 'user_auth.php';


$isCurrentuserOwner = $_GET['id'] == $_SESSION['userid'];

$canCurUserChangeUGroup = $isCurUserAdmin || $isCurUserPatho || $isCurUserPathoAssis || $isCurUserLabOfficerNB || $isCurUserAdminStaff;

// Ownder and Group can change password
$canChangePassword = $isCurrentuserOwner || $isCurUserAdmin || $isCurUserPatho || $isCurUserPathoAssis || $isCurUserLabOfficerNB || $isCurUserAdminStaff;

// Who can change password
$canResetPassword = $isCurUserAdmin || $isCurUserPatho || $isCurUserPathoAssis || $isCurUserLabOfficerNB || $isCurUserAdminStaff;


$ugroups = Ugroup::getAll($conn);

$hospitals = Hospital::getAll($conn);
//var_dump($hospitals);

if (isset($_GET['id'])) {
    $user = User::getAll($conn, $_GET['id']);
    if (!$user) {
        die("user not found");
    }
    $is_targetUserNB = $user[0]['ugroup_id'] == 2000 || $user[0]['ugroup_id'] == 2100 || $user[0]['ugroup_id'] == 2200 || $user[0]['ugroup_id'] == 2500;
    $is_targetUserCus = $user[0]['ugroup_id'] == 5000 || $user[0]['ugroup_id'] == 5100;
} else {
    die("id not supplied, user not found");
}

$user_edit = new User();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //     var_dump($_POST);
    //     die();
    //     
    // flag var
    $user_updated = false;  //update user success
    $passchg = false;       // password has change
    $is_signature_file = false;  // have signature file
    //Save user detail btn pressed
    if (isset($_POST['save'])) {
        $url = "";
//        var_dump($_POST);
//        die();
//        


        $user_edit->id = $_GET['id'];
        $user_edit->pre_name = $_POST['pre_name'];
        $user_edit->name = $_POST['name'];
        $user_edit->lastname = $_POST['lastname'];
        $user_edit->pre_name_e = $_POST['pre_name_e'];
        $user_edit->name_e = $_POST['name_e'];
        $user_edit->lastname_e = $_POST['lastname_e'];
        $user_edit->short_name = $_POST['short_name'];
        $user_edit->educational_bf = $_POST['educational_bf'];
        $user_edit->role = $_POST['role'];
        $user_edit->ugroup_id = $_POST['ugroup_id'];
        $user_edit->uhospital_id = $_POST['uhospital_id'];
        if (isset($_POST["umobile_enable"])) { //IF checkbox umobile_enable checked
            $user_edit->umobile = $_POST['umobile'];
        } else {
            $user_edit->umobile = $user[0]['umobile'];
            if (!isset($user_edit->umobile)) {
                $user_edit->umobile = "";
            } //replace blank instead of NULL
        }
        if (isset($_POST["uemail_enable"])) { //IF checkbox uemail_enable checked
            $user_edit->uemail = $_POST['uemail'];
        } else {
            $user_edit->uemail = $user[0]['uemail'];
            if (!isset($user_edit->uemail)) {
                $user_edit->uemail = "";
            } //replace blank instead of NULL
        }


        try {
            if ($user_edit->updateUserProfile($conn)) {
                $user_updated = true;
                $url = "/user_detail.php?id=$user_edit->id&result=1";
                if ($passchg === true)
                    $url = $url . "&psch=1";
                Url::redirect("/user_edit.php?id=" . $_GET['id'] . "&psch=1");
            } else {
                throw new Exception('Edit user fail. Please verify again.');
            }
        } catch (Exception $e) {
            throw $e;
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
                        $pre_signature = $user[0]['signature_file'];

                        if (move_uploaded_file($_FILES['signature']['tmp_name'], $destination)) {
                            // echo "upload success";
                            $user_edit->signature_file = "/signature/" . $filename;
                            if ($user_edit->setSignatureFile($conn)) {

                                //delete old file
                                if (!$pre_signature == "" && !is_null($pre_signature)) {
                                    unlink($_SERVER['DOCUMENT_ROOT'] . $pre_signature);
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



    //Save user password btn
//    if (isset($_POST['save_userpass'])) {
//        try {
//            $isAllowToSetPassWord = false;
//            if (isset($_POST['old_password'])) {
//                $isAllowToSetPassWord = User::authenticate($conn, $_POST['username'], $_POST['old_password']);
//                if (!$isAllowToSetPassWord) {
//                    throw new Exception('Your fill in old password in-correct.');
//                }
//            } else {
//                //first time set password
//                $isAllowToSetPassWord = TRUE;
//            }
//            if ($isAllowToSetPassWord) {
//
//                $user_edit->id = $_GET['id'];
//                $user_edit->username = $_POST['username'];
//                // if have old password field correct and new pass correct -> save hash new password
//                if ($canChangePassword) {
//                    $user_edit->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
//                    try {
//                        if ($user_edit->updateUserPass($conn)) {
//                            $user_updated = true;
//                            $url = "/user_detail.php?id=$user_edit->id&result=1";
//                            if ($passchg === true)
//                                $url = $url . "&psch=1";
//                            Url::redirect("/user_edit.php?id=" . $_GET['id'] . "&psch=1");
//                        } else {
//                            throw new Exception('Edit user fail. Please verify again.');
//                        }
//                    } catch (Exception $e) {
//                        throw $e;
//                    }
//                    $passchg = true;
//                }
//                // use old pass with no rehash
//                else {
//                    $passchg = false;
//                    throw new Exception('You dont have authorize to change password.');
//                }
//            } else {
//                $passchg = false;
//                throw new Exception('Your fill in old password in-correct.');
//            }
//        } catch (Exception $e) {
//            $user_edit->errors[] = $e->getMessage();
//        }
//        //        $user_edit->ugroup_id = $_POST['ugroup_id'];
//        //        $user_edit->uhospital_id = $_POST['uhospital_id'];
//        //        $user_edit->udetail = $_POST['udetail'];
//
//    } // End of  isset($_POST['save_userpass'])
}
?>

<?php require 'includes/header.php'; ?>

<?php require 'includes/data2DOM.php'; ?>
<?php //Page authorize   
?>
<?php
if (($isCurUserClinicianCust || $isCurUserHospitalCust) && $_SESSION['user']->id != $_GET['id']) { //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้ + ดูได้เฉพาะของตัวเอง
    require 'blockopen.php';
    echo "You have no authorize to view this content. <br>";
    echo "คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้";
    require 'blockclose.php';
    require 'includes/footer.php';
    die();
}
?>

<?php if (!empty($user_edit->errors)) : ?>
    <div id="slide_prep_section" class="container-fluid pt-4 px-4">
        <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
            <div class="alert alert-warning" role="alert">
                <?php foreach ($user_edit->errors as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>




<?php
if (isset($_GET['msg'])) {
    ?>
    <div class="alert alert-success" role="alert">
        <?= $_GET['msg']; ?>
    </div>
    <?php
}?>

<?php
if (isset($_GET['signature'])) {
    if ($_GET['signature'] == 1) :
    ?>
        <div class="alert alert-success" role="alert">
            Signature update Successful
        </div>
    <?php else : ?>
        <div class="alert alert-warning" role="alert">
            <?= $_GET['signature']; ?>
        </div>
<?php
    endif;
}
?>














<?php require 'includes_user/user_form_detail.php'; ?>

<?php require 'includes_user/user_form_ajax.php'; ?>




<?php // require 'includes_user/user_form_userpassword.php'; ?>


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

        $(":submit").click(function() {
            window.removeEventListener("beforeunload", onNosave);
        });

        $("#signature-delete").on("click", function(e) {

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