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

$canCurUserChangeUGroup = TRUE;

if($isCurUserAdmin){
   $ugroups = Ugroup::getAll($conn); 
}else{
   $ugroups = Ugroup::getCust($conn);
}

$hospitals = Hospital::getAll($conn);
//var_dump($hospitals);
// $user = [];
// $user[0] = [];

$user = User::getInit();

$url = "";

// flag var
$user_updated = false;  //update user success
$is_signature_file = false;  // have signature file


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //array(8) { ["ugroup_id_user_add"]=> string(4) "5000" ["uhospital_id_user_add"]=> string(2) "18" ["pre_name"]=> string(9) "นาง" ["name"]=> string(4) "ssss" ["lastname"]=> string(3) "sss" ["username"]=> string(5) "aaaaa" ["password"]=> string(5) "aaaaa" ["add"]=> string(0) "" }
//     var_dump($_POST);
//     die();

    $user_new = User::getInitObj();
    $user_new->pre_name = $_POST['pre_name'];
    $user_new->name = $_POST['name'];
    $user_new->lastname = $_POST['lastname'];

    $user_new->ugroup_id = $_POST['ugroup_id_user_add'];
    $user_new->uhospital_id = $_POST['uhospital_id_user_add'];
    
    $user_new->username = $_POST['username'];
    $user_new->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_new->create_by = $_POST['create_by'];

    try {
        if ($user_new->create($conn)) {
            if($_POST['ugroup_id_user_add'] == "5100"){
                Hospital::setUserID($conn,$_POST['uhospital_id_user_add'],$user_new->id);
            }
            $user_updated = true;
            Url::redirect('/user_edit.php?id='.$user_new->id.'&result=1');
        } else {
            
            throw new Exception('Add user fail. Please verify again.');
        }
    } catch (Exception $e) {
        $user_new->errors[] = $e->getMessage();
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

           

            <?php if (!empty($user_new->errors)) : ?>
                <div class="alert alert-warning" role="alert">
                    <?php foreach ($user_new->errors as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>



                <?php require 'includes_user/user_form_add.php'; ?>
               

        </div>
    </div>
<?php endif; ?>
<?php require 'includes/footer.php'; ?>
    
    



<script type="text/javascript">
    $(document).ready(function () {
        //set active tab
        $("#user").addClass("active");

        // prevent from unsave
        function onNosave(e) {
            e.preventDefault();
            e.returnValue = '';
        }

        $("input").change(function () {
            window.addEventListener("beforeunload", onNosave);
        });
        
        //add
        $("#add").click(function () {
            window.removeEventListener("beforeunload", onNosave);
        });
        
        $("#save").click(function () {
            window.removeEventListener("beforeunload", onNosave);
        });
    });
</script>