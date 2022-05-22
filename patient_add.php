<?php
require 'includes/init.php';

$conn = require 'includes/db.php';

if (!Auth::isLoggedIn()) {
    echo "time out plese login again";
    Url::redirect("/login.php");
} else {
}


$isAddPage = true;

$patientini = Patient::getInit();
//var_dump($patientini);
//die();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //    var_dump($_POST);
    //    die();

    $patientini = Patient::getInit();

    $patient = new Patient();
    isset($_POST['pnum'])            ? $patient->pnum = $_POST['pnum']                      : $patient->pnum = $patientini[0]['pnum'];
    isset($_POST['plabnum'])         ? $patient->plabnum = $_POST['plabnum']                : $patient->plabnum = $patientini[0]['plabnum'];
    isset($_POST['pname'])           ? $patient->pname = $_POST['pname']                    : $patient->pname = $patientini[0]['pname'];
    isset($_POST['pgender'])         ? $patient->pgender = $_POST['pgender']                : $patient->pgender = $patientini[0]['pgender'];
    isset($_POST['plastname'])       ? $patient->plastname = $_POST['plastname']            : $patient->plastname = $patientini[0]['plastname'];
    isset($_POST['pedge'])           ? $patient->pedge = $_POST['pedge']                    : $patient->pedge = $patientini[0]['pedge'];
    isset($_POST['date_1000'])     ? $patient->date_1000 = $_POST['date_1000']        : $patient->date_1000 = $patientini[0]['date_1000'];





    isset($_POST['date_2000'])     ? $patient->date_2000 = $_POST['date_2000']        : $patient->date_2000 = $patientini[0]['date_2000'];
    isset($_POST['date_3000'])     ? $patient->date_3000 = $_POST['date_3000']        : $patient->date_3000 = $patientini[0]['date_3000'];
    isset($_POST['date_6000'])     ? $patient->date_6000 = $_POST['date_6000']        : $patient->date_6000 = $patientini[0]['date_6000'];
    isset($_POST['date_8000'])     ? $patient->date_8000 = $_POST['date_8000']        : $patient->date_8000 = $patientini[0]['date_8000'];
    isset($_POST['date_10000'])     ? $patient->date_10000 = $_POST['date_10000']        : $patient->date_10000 = $patientini[0]['date_10000'];
    isset($_POST['date_12000'])     ? $patient->date_12000 = $_POST['date_12000']        : $patient->date_12000 = $patientini[0]['date_12000'];
    isset($_POST['date_13000'])     ? $patient->date_13000 = $_POST['date_13000']        : $patient->date_13000 = $patientini[0]['date_13000'];
    isset($_POST['date_14000'])     ? $patient->date_14000 = $_POST['date_14000']        : $patient->date_14000 = $patientini[0]['date_14000'];
    isset($_POST['date_20000'])     ? $patient->date_20000 = $_POST['date_20000']        : $patient->date_20000 = $patientini[0]['date_20000'];
    isset($_POST['date_first_report'])     ? $patient->date_first_report = $_POST['date_first_report']        : $patient->date_first_report = $patientini[0]['date_first_report'];



    isset($_POST['status_id'])      ? $patient->status_id = $_POST['status_id']             : $patient->status_id = $patientini[0]['status_id'];
    isset($_POST['priority_id'])    ? $patient->priority_id = $_POST['priority_id']               : $patient->priority_id = $patientini[0]['priority_id'];
    isset($_POST['phospital_id'])   ? $patient->phospital_id = $_POST['phospital_id']       : $patient->phospital_id = $patientini[0]['phospital_id'];
    isset($_POST['phospital_num'])  ? $patient->phospital_num = $_POST['phospital_num']     : $patient->phospital_num = $patientini[0]['phospital_num'];
    isset($_POST['ppathologist_id']) ? $patient->ppathologist_id = $_POST['ppathologist_id'] : $patient->ppathologist_id = $patientini[0]['ppathologist_id'];
    isset($_POST['pspecimen_id'])   ? $patient->pspecimen_id = $_POST['pspecimen_id']       : $patient->pspecimen_id = $patientini[0]['pspecimen_id'];
    isset($_POST['pclinician_id'])  ? $patient->pclinician_id = $_POST["pclinician_id"]     : $patient->pclinician_id = $patientini[0]["pclinician_id"];
    
    isset($_POST['p_cross_section_id'])     ? $patient->p_cross_section_id = $_POST["p_cross_section_id"]           : $patient->p_cross_section_id = $patientini[0]["p_cross_section_id"];
    isset($_POST['p_cross_section_ass_id']) ? $patient->p_cross_section_ass_id = $_POST["p_cross_section_ass_id"]   : $patient->p_cross_section_ass_id = $patientini[0]["p_cross_section_ass_id"];
    isset($_POST['p_slide_prep_id'])        ? $patient->p_slide_prep_id = $_POST["p_slide_prep_id"]                 : $patient->p_slide_prep_id = $patientini[0]["p_slide_prep_id"];
    isset($_POST['p_slide_prep_sp_id'])     ? $patient->p_slide_prep_sp_id = $_POST["p_slide_prep_sp_id"]           : $patient->p_slide_prep_sp_id = $patientini[0]["p_slide_prep_sp_id"];
    isset($_POST['pprice'])                 ? $patient->pprice = $_POST['pprice']                                   : $patient->pprice = $patientini[0]['pprice'];
    isset($_POST['pspprice'])               ? $patient->pspprice = $_POST['pspprice']                               : $patient->pspprice = $patientini[0]['pspprice'];
    isset($_POST['p_rs_specimen'])          ? $patient->p_rs_specimen = $_POST['p_rs_specimen']                     : $patient->p_rs_specimen = $patientini[0]['p_rs_specimen'];
    isset($_POST['p_rs_clinical_diag'])     ? $patient->p_rs_clinical_diag = $_POST['p_rs_clinical_diag']           : $patient->p_rs_clinical_diag = $patientini[0]['p_rs_clinical_diag'];
    isset($_POST['p_rs_gross_desc'])        ? $patient->p_rs_gross_desc = $_POST['p_rs_gross_desc']                 : $patient->p_rs_gross_desc = $patientini[0]['p_rs_gross_desc'];
    isset($_POST['p_rs_microscopic_desc'])  ? $patient->p_rs_microscopic_desc = $_POST['p_rs_microscopic_desc']     : $patient->p_rs_microscopic_desc = $patientini[0]['p_rs_microscopic_desc'];
   
  


    isset($_POST['p_speciment_type'])         ? $patient->p_speciment_type = $_POST['p_speciment_type']             : $patient->p_speciment_type = $patientini[0]['p_speciment_type'];
    isset($_POST['p_slide_lab_id'])           ? $patient->p_slide_lab_id = $_POST['p_slide_lab_id']                 : $patient->p_slide_lab_id = $patientini[0]['p_slide_lab_id'];
    isset($_POST['p_slide_lab_price'])        ? $patient->p_slide_lab_price = $_POST['p_slide_lab_price']           : $patient->p_slide_lab_price = $patientini[0]['p_slide_lab_price'];



    if ($patient->create($conn)) {

        Url::redirect("/patient_edit.php?id=$patient->id");
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
$labFluids = LabFluid::getAll($conn);



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

// true = Disable Edit page, false canEditPage
$isEditModePageOn = true;


require 'user_auth.php';

//Prepare Status
$curstatus[0]['id'] = 1000;
//เช็คและเตรียมตัวแปรสถานะปัจจุบัน
require 'includes/status_cur.php';

?>


<?php require 'includes/header.php'; ?>
<?php require 'user_auth.php';?>


    
    

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">


<?php if (!Auth::isLoggedIn()) : ?>
    You are not login.
<?php elseif( ($isCurUserClinicianCust || $isCurUserHospitalCust) ): ?>   
    You have no authorize to view this content.
    คุณไม่มีสิทธิ์ในการเข้าถึงส่วนนี้
<?php else : ?>

            <div class="d-flex align-items-center justify-content-between">
                <a href="/patient.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-bed-pulse me-2"></i>ข้อมูลผู้รักษาทั้งหมด</a>
            </div>
    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <?php //require 'includes/patient_status.php';  
        ?>
        <hr>

        <form id="formAddPatient" class="" name="" method="post">
            <?php require 'includes/patient_form.php'; ?>

            <p align="center">
                <!--<button>ตกลง</button>-->
                <button name="Submit2" type="submit" class="btn btn-primary">เพิ่มข้อมูล</button>
            </p>
        </form>

    <?php endif; ?>


    </div>
</div>


<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    // set active tab
    $("#patienttab").addClass("active");
</script>