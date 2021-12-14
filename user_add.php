<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

$ugroups = Ugroup::getAll($conn);

$hospitals = Hospital::getAll($conn);
//var_dump($hospitals);




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST);
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

<?php require 'includes/header.php'; ?>

<div align=""><b>เพิ่มผู้ใช้งานระบบ</b></div>

    <form id="" method="post" >
<?php require 'includes/user_form.php'; ?>
    <div align=""><button>Add</button></div>
    </form>

<?php require 'includes/footer.php'; ?>