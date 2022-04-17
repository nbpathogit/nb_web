<?php
//var_dump($_GET);

require 'includes/init.php';
$conn = require 'includes/db.php';


Auth::requireLogin();





// true = Disable Edit page, false canEditPage
$isEditModePageOn = false;  //For initial data page
$canEditModePage2 = false; //For Result added page


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //    var_dump($_POST);
    //    die();
    //

    //Request to move status
    if (isset($_POST['status'])) {
        //echo "save status";
        $isUpdateStatusError = true;
        $isUpdateReleaseTimeError = true;

//        var_dump($_POST);die();
        //uresultTypeName

        $isUpdateStatusError = Patient::updateStatusWithMoveDATE($conn, $_GET['id'], $_POST['cur_status'], $_POST['status'], $_POST['isset_date_first_report']);
        if (($_POST['cur_status'] == 12000 or $_POST['cur_status'] == 13000) && $_POST['status'] == 20000 && isset($_POST["uresultinxlist"])) {
            //if ($_POST["uresultReleaseSetlist"] == 0) {
            $isUpdateReleaseTimeError = Presultupdate::updateReleaseTime($conn, $_POST["uresultinxlist"]); //Last index
            $isUpdateTypeNameError = Patient::updateReportTypeName($conn, $_GET['id'], $_POST['uresultTypeName']);
            $isUpdateReportAsError = Patient::updateReportAs($conn, $_GET['id'], $_POST['reported_as']);
            //reported_as
            //}
        }

        if ($isUpdateStatusError && $isUpdateReleaseTimeError) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
    }



    if (isset($_POST['add_u_result'])) {

        $presultupdate = new Presultupdate();
        $presultupdate->patient_id = $_GET['id'];
        $presultupdate->result_type = $_POST['result_type'];
        $presultupdate->pathologist_id = $_POST['pathologist_id'];

        //        var_dump($_POST);
        //        die();

        if ($presultupdate->create($conn)) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add result fail. Please verify again")</script>';
        }
    }

    if (isset($_POST['save_u_result'])) {
        //var_dump($_POST);
        if (Presultupdate::updateResult($conn, $_POST['id'], $_POST['pathologist_id'], $_POST['pathologist2_id'], $_POST['result_message'])) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
    }



    //Save all page
    if (isset($_POST['save'])) {

        $patient = new Patient();
        //Get Specific Row from Table
        if (isset($_GET['id'])) {
            $patient = Patient::getByID($conn, $_GET['id']);
        } else {
            $patient = null;
        }
        //var_dump($_POST);

        isset($_POST['pnum']) ? $patient->pnum = $_POST['pnum'] : null;
        isset($_POST['plabnum']) ? $patient->plabnum = $_POST['plabnum'] : null;
        isset($_POST['pname']) ? $patient->pname = $_POST['pname'] : null;
        isset($_POST['pgender']) ? $patient->pgender = $_POST['pgender'] : null;
        isset($_POST['plastname']) ? $patient->plastname = $_POST['plastname'] : null;
        isset($_POST['pedge']) ? $patient->pedge = $_POST['pedge'] : null;
        isset($_POST['date_1000']) ? $patient->date_1000 = $_POST['date_1000'] : null;
        //        isset($_POST['date_12_13_000']) ? $patient->date_12_13_000 = $_POST['date_12_13_000'] : null;
        isset($_POST['status_id']) ? $patient->status_id = $_POST['status_id'] : null;
        isset($_POST['priority_id']) ? $patient->priority_id = $_POST['priority_id'] : null;
        isset($_POST['phospital_id']) ? $patient->phospital_id = $_POST['phospital_id'] : null;
        isset($_POST['phospital_num']) ? $patient->phospital_num = $_POST['phospital_num'] : null;
        isset($_POST['ppathologist_id']) ? $patient->ppathologist_id = $_POST['ppathologist_id'] : null;
        isset($_POST['pspecimen_id']) ? $patient->pspecimen_id = $_POST['pspecimen_id'] : null;
        isset($_POST['pclinician_id']) ? $patient->pclinician_id = $_POST['pclinician_id'] : null;
        
        isset($_POST['p_cross_section_id']) ? $patient->p_cross_section_id = $_POST['p_cross_section_id'] : null;
        isset($_POST['p_cross_section_ass_id']) ? $patient->p_cross_section_ass_id = $_POST['p_cross_section_ass_id'] : null;
        isset($_POST['p_slide_prep_id']) ? $patient->p_slide_prep_id = $_POST['p_slide_prep_id'] : null;
        isset($_POST['p_slide_prep_sp_id']) ? $patient->p_slide_prep_sp_id = $_POST['p_slide_prep_sp_id'] : null;
        isset($_POST['pprice']) ? $patient->pprice = $_POST['pprice'] : null;
        isset($_POST['pspprice']) ? $patient->pspprice = $_POST['pspprice'] : null;
        isset($_POST['p_rs_specimen']) ? $patient->p_rs_specimen = $_POST['p_rs_specimen'] : null;
        isset($_POST['p_rs_clinical_diag']) ? $patient->p_rs_clinical_diag = $_POST['p_rs_clinical_diag'] : null;
        isset($_POST['p_rs_gross_desc']) ? $patient->p_rs_gross_desc = $_POST['p_rs_gross_desc'] : null;
        isset($_POST['p_rs_microscopic_desc']) ? $patient->p_rs_microscopic_desc = $_POST['p_rs_microscopic_desc'] : null;



        isset($_POST['p_speciment_type']) ? $patient->p_speciment_type = $_POST['p_speciment_type'] : null;
        isset($_POST['p_slide_lab_id']) ? $patient->p_slide_lab_id = $_POST['p_slide_lab_id'] : null;
        isset($_POST['p_slide_lab_price']) ? $patient->p_slide_lab_price = $_POST['p_slide_lab_price'] : null;



        if ($patient->update($conn, $_GET['id'])) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
        $patient = NULL;
    }

    //Move to edit mode

    if (isset($_POST['edit_result'])) {
        // true = Disable Edit page, false canEditPage
        $isEditModePageOn = true;
    }

    if (isset($_POST['edit'])) {
        // true = Disable Edit page, false canEditPage
        $isEditModePageOn = true;
    }
    //Move to View only mode
    if (isset($_POST['discard'])) {
        // true = Disable Edit page, false canEditPage
        $isEditModePageOn = false;
    }




    //Move to View only mode
    if (isset($_POST['discard_u_result'])) {
        // true = Disable Edit page, false canEditPage
        $canEditModePage2 = false;
    }
    if (isset($_POST['edit_u_result'])) {
        // true = Disable Edit page, false canEditPage
        $canEditModePage2 = true;
    }
}



//Get Specific Row from Table
if (isset($_GET['id'])) {
    $patient = Patient::getAll($conn, $_GET['id']);
} else {
    $patient = null;
}

if (!$patient) {
    require 'blockopen.php';
    echo( "No Patient ID " . $_GET['id'] . ".");
    require 'blockclose.php';
    die();
}
//var_dump($patient);
//$status_cur = Status::getAll($conn, $patient[0]['status_id']);s
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
$userGroupLists = Ugroup::getAll($conn);

//var_dump($userPathos);
//die();
//Get one by id
$presultupdates = Presultupdate::getAll($conn, $_GET['id']);

$clinician = User::getAll($conn, $patient[0]['pclinician_id']);

//Check whether date of fisrt report is define
$isset_date_first_report = 0;
if (isset($patient[0]['date_first_report'])) {
    $isset_date_first_report = 1;
} else {
    $isset_date_first_report = 0;
}


//Prepare Status
$curstatus = Status::getAll($conn, $patient[0]['status_id']);

//เช็คและเตรียมตัวแปรสถานะปัจจุบัน
require 'includes/status_cur.php';


//var_dump($curstatus);
if (isset($curstatus[0]['back2'])) {
    //echo "back2 is set";
    $back2status = Status::getAll($conn, $curstatus[0]['back2']);
} else {
    //echo "back2 is Null";
    $back2status = null;
}

if (isset($curstatus[0]['back1'])) {
    //echo "back1 is set";
    $back1status = Status::getAll($conn, $curstatus[0]['back1']);
} else {
    //echo "back1 is Null";
    $back1status = null;
}

if (isset($curstatus[0]['next1'])) {
    //echo "next1 is set";
    $next1status = Status::getAll($conn, $curstatus[0]['next1']);
} else {
    //echo "next1 is Null";
    $next1status = null;
}

if (isset($curstatus[0]['next2'])) {
    //echo "next2 is set";
    $next2status = Status::getAll($conn, $curstatus[0]['next2']);
} else {
    //echo "next2 is Null";
    $next2status = null;
}

if (isset($curstatus[0]['next3'])) {
    //echo "next3 is set";
    $next3status = Status::getAll($conn, $curstatus[0]['next3']);
} else {
    //echo "next3 is Null";
    $next3status = null;
}

//$back1status = Status::getAll($conn, $curstatus[0]['back1']);
//$next1status = Status::getAll($conn, $curstatus[0]['next1']);
//$next2status = Status::getAll($conn, $curstatus[0]['next2']);
//$statusLists = Status::getAll($conn);
//var_dump($curstatus);
//var_dump($back2status);
//var_dump($back1status);
//var_dump($next1status);
//var_dump($next2status);
//var_dump($patient);
//var_dump($statusLists);
//var_dump($canEditModePage);
require 'user_auth.php';
?>


<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()) : ?>
    <?php require 'blockopen.php'; ?>
    You are not login.
    <?php require 'blockclose.php'; ?>
<?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust) && !$isUnderCurHospital): ?>   
    <?php require 'blockopen.php'; ?>
    You have no authorize to view other hospital group. 
    <?php require 'blockclose.php'; ?>
<?php else : ?>


    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1">

            <div class="d-flex align-items-center justify-content-between">
                <a href="/patient.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-bed-pulse me-2"></i>ข้อมูลผู้รักษาทั้งหมด</a>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1">
            <hr noshade="noshade" width="" size="8">
            <?php require 'includes/patient_status.php'; ?>
        </div>
    </div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1">



            <hr noshade="noshade" width="" size="8">


            <form id="formEditPatient" name="" method="post">

                <?php if ($isEditModePageOn) : ?>
                    <p align="center"><button name="save" type="submit" class="btn btn-primary">&nbsp;&nbsp;Save All&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
                        <a  class="btn btn-primary" href="patient_edit.php?id=<?= $patient[0]['id']; ?>" >Discard</a></p>
                <?php else : ?>
                    <?php
                    $isEnableEditButton = ($isCurUserAdmin 
                            || (( $isCurStatus_1000 || $isCurStatus_2000 ) && ($isCurUserPatho || $isCurUserPathoAssis || $isCurUserLabOfficerNB || $isCurUserAdminStaff || $isCurrentPathoIsOwnerThisCase) ) 
                            || (( $isCurStatus_3000 || $isCurStatus_6000 || $isCurStatus_10000 || $isCurStatus_12000 || $isCurStatus_13000 || $isCurStatus_20000  ) && ($isCurrentPathoIsOwnerThisCase))
                            );
                    ?>
                    <?php if (!$canEditModePage2) : ?>
                        <p align="center"><button name="edit" type="submit" class="btn btn-primary"  <?= $isEnableEditButton ? "" : "disabled"; ?>  
                                                  >&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>

                <?php require 'includes/patient_form.php'; ?>

                <br>

                <?php if ($isEditModePageOn) : ?>
                    <p align="center"><button name="save" type="submit" class="btn btn-primary">&nbsp;&nbsp;Save All&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
                        <a  class="btn btn-primary" href="patient_edit.php?id=<?= $patient[0]['id']; ?>" >Discard</a></p>
                <?php else : ?>
                    <?php if (!$canEditModePage2) : ?>
                        <p align="center"><button name="edit" type="submit" class="btn btn-primary"  <?=($isEnableEditButton ) ? "" : "disabled"; ?>   
                                                  >&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>
                        </p>  
                    <?php endif; ?>
                <?php endif; ?>

            </form>
        </div>
    </div>

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1">

            <?php if ($isUpdateResultAval) : ?>
                <hr noshade="noshade" width="" size="6">
                <?php require 'includes/patient_form_080_result.php'; ?>
            <?php endif; ?>

        <?php endif; ?>

    </div>
</div>

<?php if (!($isEditModePageOn || $canEditModePage2)) : ?>
    <p align="center"><a  class="btn btn-primary" href="patient_pdf.php?id=<?= $patient[0]['id']; ?>" target="_blank">View PDF</a>    </p>               
<?php endif; ?>

<?php require 'includes/footer.php'; ?>


<script type="text/javascript">
    $(document).ready(function () {
        //set active tab
        $("#patienttab").addClass("active");

        // prevent from unsave
        function onNosave(e) {
            e.preventDefault();
            e.returnValue = '';
        }

        $("input").change(function () {
            window.addEventListener("beforeunload", onNosave);
        });

        $(":submit").click(function () {
            window.removeEventListener("beforeunload", onNosave);
        })
    });
</script>