<?php
require 'includes/init.php';

$conn = require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //var_dump($_POST);


    $patient = new Patient();
    $patient->pnum = $_POST['pnum'];
    $patient->plabnum = $_POST['plabnum'];
    $patient->pname = $_POST['pname'];
    $patient->pgender = $_POST['pgender'];
    $patient->plastname = $_POST['plastname'];
    $patient->pedge = $_POST['pedge'];
    $patient->date_1000 = $_POST['date_1000'];
    $patient->date_12_13_000 = $_POST['date_12_13_000'];
    $patient->status = $_POST['status'];
    $patient->priority = $_POST['priority'];
    $patient->phospital_id = $_POST['phospital_id'];
    $patient->phospital_num = $_POST['phospital_num'];
    $patient->ppathologist_id = $_POST['ppathologist_id'];
    $patient->pspecimen_id = $_POST['pspecimen_id'];
    $patient->pclinician_id = $_POST["pclinician_id"];
    $patient->pprice = $_POST['pprice'];
    $patient->pspprice = $_POST['pspprice'];
    $patient->p_rs_specimen = $_POST['p_rs_specimen'];
    $patient->p_rs_clinical_diag = $_POST['p_rs_clinical_diag'];
    $patient->p_rs_gross_desc = $_POST['p_rs_gross_desc'];
    $patient->p_rs_microscopic_desc = $_POST['p_rs_microscopic_desc'];
    $patient->p_rs_diagnosis = $_POST['p_rs_diagnosis'];



    if ($patient->create($conn)) {
        Url::redirect("/patient_detail.php?id=$patient->id");
    } else {
        echo '<script>alert("Add user fail. Please verify again")</script>';
    }
}

//$patients = Patient::getInit();

//$patientLists = Patient::getAllJoin($conn);

//Ternary Operator
$paginator = new Paginator(  isset($_GET['page'])?$_GET['page']:1  ,10,Patient::getTotal($conn));

$patientLists = Patient::getPage($conn,$paginator->limit,$paginator->offset);

$hospitals = Hospital::getAll($conn);
$specimens = Specimen::getAll($conn);
$clinicians = User::getAllbyClinicians($conn);
$userPathos = User::getAllbyPathologis($conn);
$prioritys = Priority::getAll($conn);

//var_dump($patients);

//var_dump($patientLists);
//var_dump($Specimens);
//var_dump($clinicians);
//var_dump($users);
//var_dump($userPathos);
//var_dump($userPathos);
?>

<?php require 'includes/header.php'; ?>


<?php if (!Auth::isLoggedIn()): ?>
    You are not authorized.
<?php else: ?>

 
<p>&nbsp;</p>
<hr>

<?php require 'includes/patient_search.php'; ?>
<p>&nbsp;</p>
<hr>

<table class="table table-hover table-striped">
<!--<table border="1" align="center">-->
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
            <!--<td><div align="center">ราย<br>ละเอียด</div></td>-->
            <td><div align="center">แก้ไข/ราย<br>ละเอียด</div></td>
            <td><div align="center">รายงาน<br>(pdf)</div></td>
            <td><div align="center">ลบ</div></td>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($patientLists as $patient): ?>
            <tr >
                <td><div align="center"><?= $patient['pid']; ?></div></td>
                <td><div align="center"><?= $patient['pnum']; ?></div></td>
                <td><div align="center"><?= $patient['pname']; ?></div></td>
                <td><div align="center"><?= $patient['plastname']; ?></div></td>
                <td><div align="center"><?= $patient['hospital']; ?></div></td>
                <td><div align="center"><?= $patient['name']; ?></div></td>
                <td><div align="center"><?= $patient['date_1000']; ?></div></td>
                <td><div align="center"><?= $patient['date_20000']; ?></div></td>
                <td><div align="center"><?= $patient['des']; ?></div></td>
                <td><div align="center"><?= $patient['priority']; ?></div></td>
                <!--<td><div align="center"><a href="patient_detail.php?id=<?= $patient['pid']; ?>">Detail</a></div></td>-->
                <td><div align="center"><a href="patient_edit.php?id=<?= $patient['pid']; ?>">Detail/Edit</a></div></td>
                <td><div align="center"><a target ="_blank" href="patient_pdf.php?id=<?= $patient['pid']; ?>">view</a></div></td>
                <td><div align="center"><a href="patient_del.php?id=<?= $patient['pid']; ?>">Delete</a></div></td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>
<?php require 'includes/pagination.php';?>

<?php endif; ?>

<?php require 'includes/footer.php'; ?>