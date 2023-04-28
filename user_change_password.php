<?php
//var_dump($_GET);

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';
require 'user_auth.php';



// var_dump($user);
//Save user password btn
if (isset($_POST['save_userpass'])) {
    try {

        if ($_POST['password'] != $_POST['set_password_confirm']) {
            ?>
            <script type="text/javascript">
                alert("Password missmatch");
            </script>
            <?php
            throw new Exception('Password Missmatch.');
        }

        if (isset($_POST['old_password'])) {
            if (User::authenticate($conn, $_POST['username'], $_POST['old_password'])) {
                // Current password
            } else {
                ?>
                <script type="text/javascript">
                    alert('ใส่รหัสผ่านปัจจุบันผิด กรุณาลองอีกครั้ง');
                </script>
                <?php
                throw new Exception('ใส่รหัสผ่านปัจจุบันผิด กรุณาลองอีกครั้ง');
            }
        }

        $user_edit = new User();
        $user_edit->id = Auth::getUserId();
        $user_edit->username = $_POST['username'];
        $user_edit->password = password_hash($_POST['password'], PASSWORD_DEFAULT);

//        throw new Exception('Break.');


        if ($user_edit->updateUserPass($conn)) {
            ?>

            <?php
            Url::redirect("/patient.php?msg=Update user/password success");
        } else {
            ?>
            <script type="text/javascript">
                alert("Update user/password Fail");
            </script>
            <?php
            throw new Exception('Edit user fail. Please verify again.');
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}




$isCurrentPassChangeMe = User::authenticate($conn, Auth::getUsername(), "changeme");
$user = User::getAll($conn, Auth::getUserId());
?>



<!DOCTYPE html>
<html lang="th">

    <head>
        <meta charset="utf-8">
        <title>NB Phatho - change password</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Favicon -->
        <link href="img/favicon.ico" rel="icon">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
    </head>

    <body>
        <div class="container-xxl position-relative bg-white d-flex p-0">
            <!-- Spinner Start -->
            <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <!-- Spinner End -->




            <!-- Sign In Start -->
            <div class="container-fluid">
                <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                    <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                        <div class="bg-nb bg-blue-a rounded p-4 p-sm-5 my-4 mx-3">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <a href="/" class="">
                                    <h3 class="text-primary"><i class="fa-solid fa-microscope"></i> NB Phatho</h3>
                                </a>
                            </div>

                            <?php if (!empty($error)) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <p><?= $error ?></p>
                                </div>
                            <?php endif; ?>

                            <form id="change_user_pass" method="post" >


                                <div class="row mb-3 g-3 align-items-center">
                                    <div class="col-auto align-items-center">
                                        <!--<div class="col-auto">-->
                                        <label for="username">ชื่อเข้าใช้</label>
                                        <input class="form-control" name="username" type="text"  size="30" maxlength="10" readonly value="<?= (isset($user[0]['username']) ? $user[0]['username'] : ''); ?>">

                                        <!--</div>-->
                                    </div>

                                    <?php if (!$isCurrentPassChangeMe) : ?>
                                        <div class="col-auto">
                                            <label for="old_password">รหัสผ่านเปัจจุบัน</label>
                                            <input class="form-control" name="old_password" type="password" id="old_password" size="30" maxlength="10">
                                            <!--<span class="form-text"></span>-->
                                        </div>
                                        <hr>
                                    <?php else: ?>
                                        <div class="col-auto">
                                            <span style="color:red;"><b>กรุณาตั้งรหัสผ่านใหม่</b></span>
                                        </div>
                                    <?php endif; ?>

                                    <div class="col-auto">
                                        <label for="password"><?= (isset($user[0]['password']) ? "ตั้งรหัสผ่านใหม่" : "รหัสผ่าน"); ?></label><?= (isset($user[0]['password']) ? "" : "<span> *</span>"); ?>
                                        <input class="form-control" name="password" type="password" id="password" size="30" maxlength="10">
                                        <span class="form-text">6-10 ตัวอักษร</span>
                                    </div>
                                    <div class="col-auto">
                                        <label for="set_password_confirm"><?= (isset($user[0]['password']) ? "ยืนยันรหัสผ่านผ่านใหม่" : "ยืนยันรหัสผ่าน"); ?></label><?= (isset($user[0]['password']) ? "" : "<span> *</span>"); ?>
                                        <input class="form-control" name="set_password_confirm" type="password" id="set_password_confirm" size="30" maxlength="10">
                                        <span class="form-text">6-10 ตัวอักษร</span>
                                    </div>

                                </div>

                                <div><button id="save_userpass" type="submit" name="save_userpass" class="btn btn-primary">Save</button></div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Sign In End -->

        </div>

        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>
    </body>

</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js">
</script>
<script type="text/javascript">

    $("#change_user_pass").validate({
        rules: {

            username: {
                required: true
            },

            old_password: {
                minlength: 5,
                required: true
            },

            password: {
                minlength: 5,
                required: true
            },
            set_password_confirm: {
                minlength: 5,
                required: true,
                equalTo: "#password"
            }

        }

    });
</script>