<?php

require 'includes/init.php';

// Auth::requireLogin();

$conn = require 'includes/db.php';

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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // var_dump($_POST);
    // die();

    $user_edit = new User();
    $user_edit->id = $_GET['id'];
    $user_edit->name = $_POST['name'];
    $user_edit->lastname = $_POST['lastname'];
    $user_edit->umobile = $_POST['umobile'];
    $user_edit->uemail = $_POST['uemail'];
    $user_edit->username = $_POST['username'];

    // if no change password -> don't hash
    if ($_POST['password'] == $user[0]['password']) $user_edit->password = $_POST['password'];
    // if new password -> hash
    else  $user_edit->password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $user_edit->ugroup_id = $_POST['ugroup_id'];
    $user_edit->uhospital_id = $_POST['uhospital_id'];
    $user_edit->udetail = $_POST['udetail'];

    if ($user_edit->update($conn)) {
        Url::redirect("/user_detail.php?id=$user_edit->id&result=1");
    } else {
        echo '<script>alert("Edit user fail. Please verify again")</script>';
    }
}
?>

<?php require 'includes/header.php'; ?>

<div align=""><b>แก้ไขผู้ใช้งานระบบ</b></div>

<form id="adduser" method="post">
    <?php require 'includes/user_form.php'; ?>
    <div align=""><button class="btn btn-primary">Edit</button></div>
</form>

<?php require 'includes/footer.php'; ?>