<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

$users = User::getAll($conn);

$ugroups = Ugroup::getAll($conn);

$hospitals = Hospital::getAll($conn);

//var_dump($users);
//var_dump($ugroups);
//var_dump($hospitals);


if($_SERVER["REQUEST_METHOD"] == "POST"){
    var_dump($_POST);
    //die();
    
    $user = new User();
    $user->name = $_POST['name'];
    $user->lastname = $_POST['lastname'];
    $user->umobile = $_POST['umobile'];
    $user->uemail = $_POST['uemail'];
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];
    $user->ugroup_id = $_POST['ugroup_id'];
    $user->uhospital_id = $_POST['uhospital_id'];
    $user->udetail = $_POST['udetail'];

  

    if($user->create($conn)){

        Url::redirect("/user_detail.php?id=$user->id");
    }else{
        echo '<script>alert("Add user fail. Please verify again")</script>';
    }
}
?>




<?php require 'includes/header.php'; ?>

<?php require 'includes/user_form.php'; ?>

<table class="table table-hover table-striped" >
    <thead>
        <tr >
            <td><div align="center">id</div></td>
            <td><div align="center">name</div></td>
            <td><div align="center">lastname</div></td>
            <td><div align="center">username</div></td>
            <td><div align="center">password</div></td>
            <td><div align="center">hospital</div></td>
            <td><div align="center">group</div></td>
            <td><div align="center">Detail</a</div></td>
            <td><div align="center">Edit</div></td>
            <td><div align="center">Delete</div></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr >
                <td><div align="center"><?= $user['uid']; ?></div></td>
                <td><div align="center"><?= $user['name']; ?></div></td>
                <td><div align="center"><?= $user['lastname']; ?></div></td>
                <td><div align="center"><?= $user['username']; ?></div></td>
                <td><div align="center"><?= $user['password']; ?></div></td>
                <td><div align="center"><?= $user['hospital']; ?></div></td>
                <td><div align="center"><?= $user['ugroup']; ?></div></td>
                <td><div align="center"><a href="\user_detail.php?id=<?= $user['uid']; ?>">Detail</a></div></td>
                <td><div align="center"><a href="hospital_edit.php">Edit</a></div></td>
                <td><div align="center"><a href="hospital_del.php">Delete</a></div></td>
            </tr>
        <?php endforeach; ?>
        </thead>
</table>

<?php require 'includes/footer.php'; ?>