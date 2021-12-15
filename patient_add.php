<?php
require 'includes/init.php';

$conn = require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST);


    $patient = new Patient();
    $patient->pnum = $_POST['pnum'];
    $patient->plabnum = $_POST['plabnum'];
    $patient->pname = $_POST['pname'];
    $patient->pgender = $_POST['pgender'];
    $patient->plastname = $_POST['plastname'];
    $patient->pedge = $_POST['pedge'];
    $patient->import_date = $_POST['import_date'];
    $patient->report_date = $_POST['report_date'];
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

$patients = Patient::getInit();

$patientLists = Patient::getAll($conn);

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
?>

<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()): ?>
    You are not authorized.
<?php else: ?>
    
<hr>
  
            <form id="" name="" method="post" >
                <?php require 'includes/patient_form_a.php'; ?>
                <?php require 'includes/patient_form_b.php'; ?>
                <p align="center">
                    <button class="btn btn-primary">ตกลง</button>
                    <!--<input name="Submit" type="submit" class="" id="Submit" value="เพิ่ม">-->
                    <!--<input name="Submit2" type="reset" class="" id="Submit2" value="ยกเลิก">-->
                </p>
            </form>

<?php endif; ?>

<?php require 'includes/footer.php'; ?>