<?php
//var_dump($_GET);

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {
    $hospital = Hospital::getByID($conn,$_GET['id']);
} else {
    $hospital = null;
}

//var_dump($hospital);

?>

<?php require 'includes/header.php'; ?>

<?php if ($hospital) : ?>

<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th scope="col">Key</th>
            <th scope="col">Detail</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">ชื่อสถานพยาบาล</th>
            <td><?= htmlspecialchars($hospital->hospital); ?></td>
        </tr>
        <tr>
            <th scope="row">ที่อยู่</th>
            <td><?= htmlspecialchars($hospital->address); ?></td>
        </tr>
        <tr>
            <th scope="row">รายละเอียด</th>
            <td><?= htmlspecialchars($hospital->hdetail); ?></td>
        </tr>

    </tbody>
</table>

<?php else : ?>
    <p>Hospital not found.</p>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>