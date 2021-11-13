<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

$patients = Patient::getAll($conn, $_GET['id']);

//var_dump($patients);
?>




<?php require 'includes/header.php'; ?>

<table>
    <thead>
        <tr >
            <td><div align="center">ลำดับที่</div></td>
            <td><div align="center">เลขที่ผู้ป่วย</div></td>
            <td><div align="center">ชื่อผู้ป่วย</div></td>
            <td><div align="center">นามสกุลผู้ป่วย</div></td>
            <td><div align="center">โรงพยาบาล</div></td>
            <td><div align="center">พยาธิแพทย์</div></td>
            <td><div align="center">วันที่รับ</div></td>
            <td><div align="center">วันที่รายงาน</div></td>
            <td><div align="center">สถานะ</div></td>
            <td><div align="center"><p>ความสำคัญ</p></div></td>
            <td><div align="center">ราย<br>ละเอียด</div></td>
            <td><div align="center">แก้ไข</div></td>
            <td><div align="center">ลบ</div></td>
        </tr>
    </thead>
    <tbody>

<?php foreach ($patients as $patient): ?>
        <tr >
            <td><div align="center"><?= $patient['id']; ?></div></td>
            <td><div align="center"><?= $patient['number']; ?></div></td>
            <td><div align="center"><?= $patient['number']; ?></div></td>
            <td><div align="center"><?= $patient['number']; ?></div></td>
            <td><div align="center"><?= $patient['number']; ?></div></td>
            <td><div align="center"><?= $patient['number']; ?></div></td>
            <td><div align="center"><?= $patient['number']; ?></div></td>
            <td><div align="center"><?= $patient['number']; ?></div></td>
            <td><div align="center"><?= $patient['number']; ?></div></td>
            <td><div align="center"><?= $patient['number']; ?></div></td>
            <td><div align="center"><a href="patient_detail.php">Detail</a></div></td>
            <td><div align="center"><a href="patient_edit.php">Edit</a></div></td>
            <td><div align="center"><a href="patient_del.php">Delete</a></div></td>
        </tr>
<?php endforeach; ?>

    </tbody>
</table>



<?php require 'includes/footer.php'; ?>