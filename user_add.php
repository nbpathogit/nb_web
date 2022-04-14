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


<?php
$ugroups = Ugroup::getAll($conn);

$hospitals = Hospital::getAll($conn);
//var_dump($hospitals);
$user = [];
$user[0] = [];



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // var_dump($_POST);
    //die();

    $user = new User();
    $user->name = $_POST['name'];
    $user->lastname = $_POST['lastname'];
    $user->umobile = $_POST['umobile'];
    $user->uemail = $_POST['uemail'];
    $user->username = $_POST['username'];
    $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user->ugroup_id = $_POST['ugroup_id'];
    $user->uhospital_id = $_POST['uhospital_id'];
    $user->udetail = $_POST['udetail'];


    if ($user->create($conn)) {

        Url::redirect("/user_detail.php?id=$user->id&result=1");
    } else {
        echo '<script>alert("Add user fail. Please verify again")</script>';
    }
}
?>


<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <div><b>เพิ่มผู้ใช้งานระบบ</b></div>

        <form id="adduser" method="post">
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