<?php
require 'includes/init.php';

$conn = require 'includes/db.php';

if(!Auth::isLoggedIn()){
        echo "time out plese login again";
    Url::redirect("/login.php");
}else{

}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST);
//    die();
    
    $patientini = Patient::getInit();

    $patient = new Patient();
    isset($_POST['pnum'])            ? $patient->pnum = $_POST['pnum']                      : $patient->pnum = $patientini['pnum'];
    isset($_POST['plabnum'])         ? $patient->plabnum = $_POST['plabnum']                : $patient->plabnum = $patientini['plabnum'] ;
    isset($_POST['pname'])           ? $patient->pname = $_POST['pname']                    : $patient->pname = $patientini['pname'];
    isset($_POST['pgender'])         ? $patient->pgender = $_POST['pgender']                : $patient->pgender = $patientini['pgender'];
    isset($_POST['plastname'])       ? $patient->plastname = $_POST['plastname']            : $patient->plastname = $patientini['plastname'];
    isset($_POST['pedge'])           ? $patient->pedge = $_POST['pedge']                    : $patient->pedge = $patientini['pedge'];
    isset($_POST['import_date'])     ? $patient->import_date = $_POST['import_date']        : $patient->import_date = $patientini['import_date'];
    isset($_POST['report_date'])     ? $patient->report_date = $_POST['report_date']        : $patient->report_date = $patientini['report_date'];

    
    isset($_POST['status_id'])      ? $patient->status_id = $_POST['status_id']             : $patient->status_id = $patientini['status_id'];
    
    
    isset($_POST['priority'])       ? $patient->priority = $_POST['priority']               : $patient->priority = $patientini['priority'] ;
    isset($_POST['phospital_id'])   ? $patient->phospital_id = $_POST['phospital_id']       : $patient->phospital_id = $patientini['phospital_id'];
    isset($_POST['phospital_num'])  ? $patient->phospital_num = $_POST['phospital_num']     : $patient->phospital_num = $patientini['phospital_num'];
    isset($_POST['ppathologist_id'])? $patient->ppathologist_id = $_POST['ppathologist_id'] : $patient->ppathologist_id = $patientini['ppathologist_id'];
    isset($_POST['pspecimen_id'])   ? $patient->pspecimen_id = $_POST['pspecimen_id']       : $patient->pspecimen_id = $patientini['pspecimen_id'];
    isset($_POST['pclinician_id'])  ? $patient->pclinician_id = $_POST["pclinician_id"]     : $patient->pclinician_id = $patientini["pclinician_id"];

    
    isset($_POST['ppathologist2_id'])       ? $patient->ppathologist2_id = $_POST["ppathologist2_id"]               : $patient->ppathologist2_id = $patientini["ppathologist2_id"];
    isset($_POST['p_cross_section_id'])     ? $patient->p_cross_section_id = $_POST["p_cross_section_id"]           : $patient->p_cross_section_id = $patientini["p_cross_section_id"];
    isset($_POST['p_cross_section_ass_id']) ? $patient->p_cross_section_ass_id = $_POST["p_cross_section_ass_id"]   : $patient->p_cross_section_ass_id = $patientini["p_cross_section_ass_id"];
    isset($_POST['p_slide_prep_id'])        ? $patient->p_slide_prep_id = $_POST["p_slide_prep_id"]                 : $patient->p_slide_prep_id = $patientini["p_slide_prep_id"];
    isset($_POST['p_slide_prep_sp_id'])     ? $patient->p_slide_prep_sp_id = $_POST["p_slide_prep_sp_id"]           : $patient->p_slide_prep_sp_id = $patientini["p_slide_prep_sp_id"];
            

    isset($_POST['pprice'])                 ? $patient->pprice = $_POST['pprice']                                   : $patient->pprice = $patientini['pprice'];
    isset($_POST['pspprice'])               ? $patient->pspprice = $_POST['pspprice']                               : $patient->pspprice = $patientini['pspprice'];
    isset($_POST['p_rs_specimen'])          ? $patient->p_rs_specimen = $_POST['p_rs_specimen']                     : $patient->p_rs_specimen = $patientini['p_rs_specimen'];
    isset($_POST['p_rs_clinical_diag'])     ? $patient->p_rs_clinical_diag = $_POST['p_rs_clinical_diag']           : $patient->p_rs_clinical_diag = $patientini['p_rs_clinical_diag'];
    isset($_POST['p_rs_gross_desc'])        ? $patient->p_rs_gross_desc = $_POST['p_rs_gross_desc']                 : $patient->p_rs_gross_desc = $patientini['p_rs_gross_desc'];
    isset($_POST['p_rs_microscopic_desc'])  ? $patient->p_rs_microscopic_desc = $_POST['p_rs_microscopic_desc']     : $patient->p_rs_microscopic_desc = $patientini['p_rs_microscopic_desc'];
    isset($_POST['p_rs_diagnosis'])         ? $patient->p_rs_diagnosis = $_POST['p_rs_diagnosis']                   : $patient->p_rs_diagnosis = $patientini['p_rs_diagnosis'];
    isset($_POST['p_uresult_id'])           ? $patient->p_uresult_id = $_POST['p_uresult_id']                       : $patient->p_uresult_id = $patientini['p_uresult_id'];



    if ($patient->create($conn)) {
        Url::redirect("/patient_detail.php?id=$patient->id");
    } else {
        echo '<script>alert("Add user fail. Please verify again")</script>';
    }
}
//Get Specific Row from Table
$patient = Patient::getInit();
//$status_cur = Status::getAll($conn, $patient[0]['status_id']);

//$patientLists = Patient::getAll($conn);

//Get List of Table
$hospitals = Hospital::getAll($conn);
$specimens = Specimen::getAll($conn);
$clinicians = User::getAllbyClinicians($conn);
$userPathos = User::getAllbyPathologis($conn);
$userTechnic = User::getAllbyTeachien($conn);
$prioritys = Priority::getAll($conn);
$statusLists = Status::getAll($conn);



//$ug = Auth::getUserGroup();


//var_dump($patient);
//
//var_dump($patientLists);
//var_dump($Specimens);
//var_dump($clinicians);
//var_dump($users);
//var_dump($userPathos);
//var_dump($userTechnic);
//var_dump($status);

//disable by group
$canViewPatientInfo = Auth::canViewPatientInfo();
$isDisableEditPatientInfo = Auth::isDisableEditPatientInfo();


$canViewNBCenter = Auth::canViewNBCenter();
$isDisableEditNBCenter = Auth::isDisableEditNBCenter();

$canViewResult = Auth::canViewPatientResult();
$isDisableEditResult = Auth::isDisableEditPatientResult();


//disable by field
$isHideResult = true;
$isStatusDisableEdit = true;

?>

<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()): ?>
    You are not authorized.
<?php else: ?>

    <?php //require 'includes/patient_status.php';  ?><hr>

    <form  id="formAddPatient" class="" name="" method="post">
        <?php if ($canViewPatientInfo): ?>
            <?php require 'includes/patient_form_a.php'; ?><hr>
        <?php endif; ?>
        <?php if ($canViewNBCenter): ?>
            <?php require 'includes/patient_form_b.php'; ?><hr>
        <?php endif; ?>
        <?php if ($canViewResult): ?>
            <?php require 'includes/patient_form_c.php'; ?><hr>
            <?php require 'includes/patient_form_d.php'; ?><hr>
        <?php endif; ?>

        <p align="center">
            <!--<button>ตกลง</button>-->
            <button name="Submit2" type="submit" class="btn btn-primary">เพิ่มข้อมูล</button>
        </p>
    </form>

<?php endif; ?>

<?php require 'includes/footer.php'; ?>
