<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

// $specimens = Specimen::getAll($conn);

$paginator = new Paginator(  isset($_GET['page'])?$_GET['page']:1  ,30,Specimen::getTotal($conn));
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
        </tr>
    </thead>
    <tbody>
        <?php foreach ($specimens as $specimen): ?>
            <tr >
                <th scope="row"><?= $specimen['id']; ?></td>
                <td><?= $specimen['specimen']; ?></td>
            </tr>
        <?php endforeach; ?>
        </thead>
</table>
<?php require 'includes/pagination.php';?>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>