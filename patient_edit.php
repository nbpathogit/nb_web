<?php
//var_dump($_GET);

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

// true = Disable Edit page, false canEditPage
$canEditModePage = false;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //    var_dump($_POST);
    //    die();
    //

    //Request to move status
    if (isset($_POST['status'])) {
        //echo "save status";
        $isUpdateStatusError = true;
        $isUpdateReleaseTimeError = true;
        $isUpdateStatusError = Patient::updateStatusWithMoveDATE($conn, $_GET['id'], $_POST['cur_status'], $_POST['status'], $_POST['isset_date_first_report']);
        if ($_POST['cur_status'] == 14000 && $_POST['status'] == 20000 && isset($_POST["uresultinxlist"])) {
            if ($_POST["uresultReleaseSetlist"] == 0) {
                $isUpdateReleaseTimeError = Presultupdate::updateReleaseTime($conn, $_POST["uresultinxlist"]);
            }
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


        if ($presultupdate->create($conn)) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add result fail. Please verify again")</script>';
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

        isset($_POST['pnum']) ? $patient->pnum = $_POST['pnum'] : null;
        isset($_POST['plabnum']) ? $patient->plabnum = $_POST['plabnum'] : null;
        isset($_POST['pname']) ? $patient->pname = $_POST['pname'] : null;
        isset($_POST['pgender']) ? $patient->pgender = $_POST['pgender'] : null;
        isset($_POST['plastname']) ? $patient->plastname = $_POST['plastname'] : null;
        isset($_POST['pedge']) ? $patient->pedge = $_POST['pedge'] : null;
        isset($_POST['date_1000']) ? $patient->date_1000 = $_POST['date_1000'] : null;
        isset($_POST['date_12_13_000']) ? $patient->date_12_13_000 = $_POST['date_12_13_000'] : null;
        isset($_POST['status_id']) ? $patient->status_id = $_POST['status_id'] : null;
        isset($_POST['priority_id']) ? $patient->priority_id = $_POST['priority_id'] : null;
        isset($_POST['phospital_id']) ? $patient->phospital_id = $_POST['phospital_id'] : null;
        isset($_POST['phospital_num']) ? $patient->phospital_num = $_POST['phospital_num'] : null;
        isset($_POST['ppathologist_id']) ? $patient->ppathologist_id = $_POST['ppathologist_id'] : null;
        isset($_POST['pspecimen_id']) ? $patient->pspecimen_id = $_POST['pspecimen_id'] : null;
        isset($_POST['pclinician_id']) ? $patient->pclinician_id = $_POST['pclinician_id'] : null;
        isset($_POST['ppathologist2_id']) ? $patient->ppathologist2_id = $_POST['ppathologist2_id'] : null;
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
        isset($_POST['p_rs_diagnosis']) ? $patient->p_rs_diagnosis = $_POST['p_rs_diagnosis'] : null;
        isset($_POST['p_uresult_id']) ? $patient->p_uresult_id = $_POST['p_uresult_id'] : null;

        isset($_POST['p_speciment_type']) ? $patient->p_uresult_id = $_POST['p_speciment_type'] : null;
        isset($_POST['p_slide_lab_id']) ? $patient->p_uresult_id = $_POST['p_slide_lab_id'] : null;
        isset($_POST['p_slide_lab_price']) ? $patient->p_uresult_id = $_POST['p_slide_lab_price'] : null;



        if ($patient->update($conn, $_GET['id'])) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
        $patient = NULL;
    }

    //Move to edit mode
    if (isset($_POST['edit'])) {
        // true = Disable Edit page, false canEditPage
        $canEditModePage = true;
    }
    //Move to View only mode
    if (isset($_POST['discard'])) {
        // true = Disable Edit page, false canEditPage
        $canEditModePage = false;
    }



    if (isset($_POST['save_u_result'])) {
        var_dump($_POST);
        if (Presultupdate::updateResult($conn, $_POST['id'], $_POST['pathologist_id'], $_POST['result_message'])) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
    }
    //Move to edit mode
    if (isset($_POST['edit_u_result'])) {
        // true = Disable Edit page, false canEditPage
        $canEditModePage = true;
    }
    //Move to View only mode
    if (isset($_POST['discard_u_result'])) {
        // true = Disable Edit page, false canEditPage
        $canEditModePage = false;
    }
}



//Get Specific Row from Table
if (isset($_GET['id'])) {
    $patient = Patient::getAll($conn, $_GET['id']);
} else {
    $patient = null;
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

//var_dump($userPathos);
//die();

//Get one by id
$presultupdates = Presultupdate::getAll($conn, $_GET['id']);

$clinician = User::getAll($conn,$patient[0]['pclinician_id']);

$isset_date_first_report = 0;
if (isset($patient[0]['date_first_report'])) {
    $isset_date_first_report = 1;
} else {
    $isset_date_first_report = 0;
}


//Prepare Status
$curstatus = Status::getAll($conn, $patient[0]['status_id']);
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



require 'patient_edit_auth.php';
//var_dump($canEditModePage);
?>

<?php require 'includes/header.php'; ?>


<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1">


        <?php if (!Auth::isLoggedIn()) : ?>
            You are not login.
        <?php else : ?>

            <hr noshade="noshade" width="" size="8">
            <?php require 'includes/patient_status.php'; ?>

            <hr noshade="noshade" width="" size="8">


            <form id="formEditPatient" name="" method="post">
                <?php if ($patient[0]['date_13000'] == NULL) : ?>
                    <?php if ($canEditModePage) : ?>
                        <p align="center"><button name="save" type="submit" class="btn btn-primary">&nbsp;&nbsp;Save All&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;<button name="discard" type="submit" class="btn btn-primary">Discard</button></p>
                    <?php else : ?>
                        <p align="center"><button name="edit" type="submit" class="btn btn-primary">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button></p>
                    <?php endif; ?>
                <?php endif; ?>


<?php require 'includes/patient_form.php'; ?>

<br>
<?php if ($patient[0]['date_13000'] == NULL) : ?>
    <?php if ($canEditModePage) : ?>
        <p align="center"><button name="save" type="submit" class="btn btn-primary">&nbsp;&nbsp;Save All&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;<button name="discard" type="submit" class="btn btn-primary">Discard</button></p>
    <?php else : ?>
        <p align="center"><button name="edit" type="submit" class="btn btn-primary">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button></p>
    <?php endif; ?>
<?php endif; ?>
</form>


<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <?php if ($isUpdateResultAval) : ?>
            <hr noshade="noshade" width="" size="6">
            <?php require 'includes/patient_form_d.php'; ?>
        <?php endif; ?>

    <?php endif; ?>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    // set active tab
    $("#patient_main").addClass("active");
    $("#patient").addClass("active");
</script>