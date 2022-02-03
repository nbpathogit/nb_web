<?php
require 'includes/init.php';


$conn = require 'includes/db.php';

//$users = User::getAll($conn);

$ugroups = Ugroup::getAll($conn);

$hospitals = Hospital::getAll($conn);

//Ternary Operator
$paginator = new Paginator(isset($_GET['page']) ? $_GET['page'] : 1, 10, User::getTotal($conn));

$users = User::getPage($conn, $paginator->limit, $paginator->offset);

//var_dump($users);
//var_dump($ugroups);
//var_dump($hospitals);
?>

<?php require 'includes/header.php'; ?>

<hr>

<?php if (!Auth::isLoggedIn()) : ?>
    You are not authorized.
<?php else : ?>

    <!--<table class="table table-hover table-striped"  border="1" >-->

    <table class="table table-hover table-striped text-center">
        <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">name</th>
                <th scope="col">lastname</th>
                <th scope="col">username</th>
                <th scope="col">password</th>
                <th scope="col">hospital</th>
                <th scope="col">group</th>
                <th scope="col">Detail</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <th scope="col"><?= $user['uid']; ?></th>
                    <td><?= $user['name']; ?></td>
                    <td><?= $user['lastname']; ?></td>
                    <td><?= $user['username']; ?></td>
                    <td>****</td>
                    <td><?= $user['hospital']; ?></td>
                    <td><?= $user['ugroup']; ?></td>
                    <td><a href="user_detail.php?id=<?= $user['uid']; ?>">Detail</a></td>
                    <td><a href="user_edit.php?id=<?= $user['uid']; ?>">Edit</a></td>
                    <td><a class="delete" href="user_del.php?id=<?= $user['uid']; ?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
            </thead>
    </table>

    <?php require 'includes/pagination.php'; ?>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $("a.delete").on("click", function(e) {

        e.preventDefault();

        if (confirm("Are you sure?")) {

            var frm = $("<form>");
            frm.attr('method', 'post');
            frm.attr('action', $(this).attr('href'));
            frm.appendTo("body");
            frm.submit();
        }
    });
</script>