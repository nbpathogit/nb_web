<?php
require 'includes/init.php';


$conn = require 'includes/db.php';

//$users = User::getAll($conn);

$ugroups = Ugroup::getAll($conn);

$hospitals = Hospital::getAll($conn);

//Ternary Operator
$paginator = new Paginator(  isset($_GET['page'])?$_GET['page']:1  ,10,User::getTotal($conn));

$users = User::getPage($conn,$paginator->limit,$paginator->offset);

//var_dump($users);
//var_dump($ugroups);
//var_dump($hospitals);
?>

<?php require 'includes/header.php'; ?>

<hr>

<?php if (!Auth::isLoggedIn()): ?>
    You are not authorized.
<?php else: ?>

    <!--<table class="table table-hover table-striped"  border="1" >-->

    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <td>
                    <div align="center">id</div>
                </td>
                <td>
                    <div align="center">name</div>
                </td>
                <td>
                    <div align="center">lastname</div>
                </td>
                <td>
                    <div align="center">username</div>
                </td>
                <td>
                    <div align="center">password</div>
                </td>
                <td>
                    <div align="center">hospital</div>
                </td>
                <td>
                    <div align="center">group</div>
                </td>
                <td>
                    <div align="center">Detail</a< /div>
                </td>
                <td>
                    <div align="center">Edit</div>
                </td>
                <td>
                    <div align="center">Delete</div>
                </td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr >
                    <td><div align="center"><?= $user['uid']; ?></div></td>
                    <td><div align="center"><?= $user['name']; ?></div></td>
                    <td><div align="center"><?= $user['lastname']; ?></div></td>
                    <td><div align="center"><?= $user['username']; ?></div></td>
                    <td><div align="center">****</div></td>
                    <td><div align="center"><?= $user['hospital']; ?></div></td>
                    <td><div align="center"><?= $user['ugroup']; ?></div></td>
                    <td><div align="center"><a href="\user_detail.php?id=<?= $user['uid']; ?>">Detail</a></div></td>
                    <td><div align="center"><a href="hospital_edit.php">Edit</a></div></td>
                    <td><div align="center"><a href="hospital_del.php">Delete</a></div></td>
                </tr>
            <?php endforeach; ?>
            </thead>
    </table>
    
    <?php require 'includes/pagination.php';?>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>