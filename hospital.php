<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

$paginator = new Paginator(  isset($_GET['page'])?$_GET['page']:1 ,20,Hospital::getTotal($conn));
$hospitals = Hospital::getPage($conn,$paginator->limit,$paginator->offset);
// $hospitals = Hospital::getAll($conn);

// var_dump($hospitals);
?>

<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()) : ?>
    You are not login.
<?php else : ?>


    <table class="table table-hover table-striped text-center">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">hospital</th>
                <th scope="col">address</th>
                <th scope="col">detail</th>
                <th scope="col">View</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hospitals as $hospital) : ?>
                <tr>
                    <th scope="row"><?= $hospital['id']; ?></th>
                    <td><?= $hospital['hospital']; ?></td>
                    <td><?= $hospital['address']; ?></td>
                    <td><?= $hospital['hdetail']; ?></td>
                    <td><a href="hospital_detail.php?id=<?= $hospital['id']; ?>">Detail</a></td>
                    <td><a href="hospital_edit.php?id=<?= $hospital['id']; ?>">Edit</a></td>
                    <td><a class="delete" href="hospital_del.php?id=<?= $hospital['id']; ?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
            </thead>
    </table>
    <?php require 'includes/pagination.php';?>
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