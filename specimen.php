<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

// $specimens = Specimen::getAll($conn);

$paginator = new Paginator(  isset($_GET['page'])?$_GET['page']:1 ,30,Specimen::getTotal($conn));
// var_dump($paginator);
$specimens = Specimen::getPage($conn,$paginator->limit,$paginator->offset);
?>




<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()): ?>
    You are not login.
<?php else: ?>

<table class="table table-hover table-striped text-center">
    <thead>
        <tr >
            <th scope="col">#</th>
            <th scope="col">Specimen</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($specimens as $specimen): ?>
            <tr >
                <th scope="row"><?= $specimen['id']; ?></td>
                <td><?= $specimen['specimen']; ?></td>
                <td><a href="specimen_edit.php?id=<?= $specimen['id']; ?>">Edit</a></td>
                <td><a class="delete" href="specimen_del.php?id=<?= $specimen['id']; ?>">Delete</a></td>
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