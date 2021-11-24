<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

$patients = Patient::getAll($conn);
$hospitals = Hospital::getAll($conn);
$specimens = Specimen::getAll($conn);
$clinicians = Clinician::getAll($conn);

//var_dump($patients);
//var_dump($Specimens);
//var_dump($clinicians);
?>


 

<?php require 'includes/header.php'; ?>

<?php require 'includes/patient_form.php'; ?>

<?php require 'includes/patient_search.php'; ?>


<table class="table table-hover table-striped">
    <thead>
        <tr>
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
            <td><div align="center"><?= $patient['pnum']; ?></div></td>
            <td><div align="center"><?= $patient['pname']; ?></div></td>
            <td><div align="center"><?= $patient['plastname']; ?></div></td>
            <td><div align="center"><?= $patient['hospital']; ?></div></td>
            <td><div align="center"><?= $patient['name']; ?></div></td>
            <td><div align="center"><?= $patient['import_date']; ?></div></td>
            <td><div align="center"><?= $patient['report_date']; ?></div></td>
            <td><div align="center"><?= $patient['status']; ?></div></td>
            <td><div align="center"><?= $patient['priority']; ?></div></td>
            <td><div align="center"><a href="patient_detail.php">Detail</a></div></td>
            <td><div align="center"><a href="patient_edit.php">Edit</a></div></td>
            <td><div align="center"><a href="patient_del.php">Delete</a></div></td>
        </tr>
<?php endforeach; ?>

    </tbody>
</table>



<?php require 'includes/footer.php'; ?>