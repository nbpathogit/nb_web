<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

$hospitals = Hospital::getAll($conn);

//var_dump($hospitals);
?>




<?php require 'includes/header.php'; ?>

<table>
    <thead>
        <tr >
            <td><div align="center">id</div></td>
            <td><div align="center">hospital</div></td>
            <td><div align="center">address</div></td>
            <td><div align="center">detail</div></td>
            <td><div align="center">View</div></td>
            <td><div align="center">Edit</div></td>
            <td><div align="center">Delete</div></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($hospitals as $hospital): ?>
            <tr >
                <td><div align="center"><?= $hospital['id']; ?></div></td>
                <td><div align="center"><?= $hospital['hospital']; ?></div></td>
                <td><div align="center"><?= $hospital['address']; ?></div></td>
                <td><div align="center"><?= $hospital['detail']; ?></div></td>
                <td><div align="center"><a href="hospital_detail.php">Detail</a></div></td>
                <td><div align="center"><a href="hospital_edit.php">Edit</a></div></td>
                <td><div align="center"><a href="hospital_del.php">Delete</a></div></td>
            </tr>
        <?php endforeach; ?>
        </thead>
</table>

<?php require 'includes/footer.php'; ?>