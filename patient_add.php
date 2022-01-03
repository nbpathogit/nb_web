<?php
require 'includes/init.php';

$conn = require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST);
//die();

    $patient = new Patient();
    isset($_POST['pnum']) ? $patient->pnum = $_POST['pnum'] : "";
    isset($_POST['plabnum']) ? $patient->plabnum = $_POST['plabnum'] : "";
    isset($_POST['pname']) ? $patient->pname = $_POST['pname'] : "";
    isset($_POST['pgender']) ? $patient->pgender = $_POST['pgender'] : "";
    isset($_POST['plastname']) ? $patient->plastname = $_POST['plastname'] : "";
    isset($_POST['pedge']) ? $patient->pedge = $_POST['pedge'] : "";
    isset($_POST['import_date']) ? $patient->import_date = $_POST['import_date'] : "";
    isset($_POST['report_date']) ? $patient->report_date = $_POST['report_date'] : "";
    isset($_POST['status']) ? $patient->status = $_POST['status'] : "";
    isset($_POST['priority']) ? $patient->priority = $_POST['priority'] : "";
    isset($_POST['phospital_id']) ? $patient->phospital_id = $_POST['phospital_id'] : "";
    isset($_POST['phospital_num']) ? $patient->phospital_num = $_POST['phospital_num'] : "";
    isset($_POST['ppathologist_id']) ? $patient->ppathologist_id = $_POST['ppathologist_id'] : "";
    isset($_POST['pspecimen_id']) ? $patient->pspecimen_id = $_POST['pspecimen_id'] : "";
    isset($_POST['pclinician_id']) ? $patient->pclinician_id = $_POST["pclinician_id"] : "";
    isset($_POST['pprice']) ? $patient->pprice = $_POST['pprice'] : "";
    isset($_POST['pspprice']) ? $patient->pspprice = $_POST['pspprice'] : "";
    isset($_POST['p_rs_specimen']) ? $patient->p_rs_specimen = $_POST['p_rs_specimen'] : "";
    isset($_POST['p_rs_clinical_diag']) ? $patient->p_rs_clinical_diag = $_POST['p_rs_clinical_diag'] : "";
    isset($_POST['p_rs_gross_desc']) ? $patient->p_rs_gross_desc = $_POST['p_rs_gross_desc'] : "";
    isset($_POST['p_rs_microscopic_desc']) ? $patient->p_rs_microscopic_desc = $_POST['p_rs_microscopic_desc'] : "";
    isset($_POST['p_rs_diagnosis']) ? $patient->p_rs_diagnosis = $_POST['p_rs_diagnosis'] : "";



    if ($patient->create($conn)) {
        Url::redirect("/patient_detail.php?id=$patient->id");
    } else {
        echo '<script>alert("Add user fail. Please verify again")</script>';
    }
}

$patients = Patient::getInit();

//$patientLists = Patient::getAll($conn);

$hospitals = Hospital::getAll($conn);
$specimens = Specimen::getAll($conn);
$clinicians = User::getAllbyClinicians($conn);
$userPathos = User::getAllbyPathologis($conn);
$userTechnic = User::getAllbyTeachien($conn);
$prioritys = Priority::getAll($conn);
$status = Status::getAll($conn, $patients[0]['status_id']);
$statusLists = Status::getAll($conn);



//$ug = Auth::getUserGroup();


//var_dump($patients);
//
//var_dump($patientLists);
//var_dump($Specimens);
//var_dump($clinicians);
//var_dump($users);
//var_dump($userPathos);
//var_dump($userTechnic);
//var_dump($status);


$canViewPatientInfo = Auth::canViewPatientInfo();
$isDisableEditPatientInfo = Auth::isDisableEditPatientInfo();


$canViewNBCenter = Auth::canViewNBCenter();
$isDisableEditNBCenter = Auth::isDisableEditNBCenter();

$canViewResult = Auth::canViewPatientResult();
$isDisableEditResult = Auth::isDisableEditPatientResult();
?>

<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()): ?>
    You are not authorized.
<?php else: ?>

    <?php //require 'includes/patient_status.php';  ?><hr>

    <form  id="" name="" method="post">
        <?php if ($canViewPatientInfo): ?>
            <?php require 'includes/patient_form_a.php'; ?><hr>
        <?php endif; ?>
        <?php if ($canViewNBCenter): ?>
            <?php require 'includes/patient_form_b.php'; ?><hr>
        <?php endif; ?>
        <?php if ($canViewResult): ?>
            <?php require 'includes/patient_form_c.php'; ?><hr>
        <?php endif; ?>

        <p align="center">
            <!--<button>ตกลง</button>-->
            <button name="Submit2" type="submit" class="btn btn-primary">เพิ่มข้อมูล</button>
        </p>
    </form>

<?php endif; ?>

<?php require 'includes/footer.php'; ?>
