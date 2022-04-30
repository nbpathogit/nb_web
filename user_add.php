<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';
require 'user_auth.php';

    $ugroups = Ugroup::getAll($conn);

    $hospitals = Hospital::getAll($conn);
    //var_dump($hospitals);
    // $user = [];
    // $user[0] = [];


    $url = "";

    // flag var
    $user_updated = false;  //update user success
    $is_signature_file = false;  // have signature file


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // var_dump($_POST);
        // die();

        $user_new = new User();
        $user_new->name = $_POST['name'];
        $user_new->lastname = $_POST['lastname'];
        $user_new->umobile = $_POST['umobile'];
        $user_new->uemail = $_POST['uemail'];
        $user_new->username = $_POST['username'];
        $user_new->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user_new->ugroup_id = $_POST['ugroup_id'];
        $user_new->uhospital_id = $_POST['uhospital_id'];
        $user_new->udetail = $_POST['udetail'];

        try {
            if ($user_new->create($conn)) {
                $user_updated = true;
                $url = "/user_detail.php?id=$user_new->id&result=1";
            } else {
                echo '<script>alert("Add user fail. Please verify again")</script>';
            }
        } catch (Exception $e) {
            $user_new->errors[] = $e->getMessage();
        }


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
    
    
                        if (move_uploaded_file($_FILES['signature']['tmp_name'], $destination)) {
                            // echo "upload success";
                            $user_new->signature_file = "/signature/" . $filename;
                            if ($user_new->setSignatureFile($conn)) {
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
<?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)) : //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้ 
?>
    <?php require 'blockopen.php'; ?>
    You have no authorize to view this content. <br>
    คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้
    <?php require 'blockclose.php'; ?>
<?php else : ?>


    


    <div class="container-fluid pt-4 px-4">
        <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

            <div><b>เพิ่มผู้ใช้งานระบบ</b></div>

            <?php if (!empty($user_new->errors)) : ?>
                <div class="alert alert-warning" role="alert">
                    <?php foreach ($user_new->errors as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>


            <form id="adduser" method="post" enctype="multipart/form-data">
                <?php require 'includes/user_form.php'; ?>
                <div><button id="save" class="btn btn-primary">Add</button></div>
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