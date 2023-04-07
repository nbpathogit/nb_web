<?php
//var_dump($_GET);

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {
    $patient = Patient::getAll($conn, $_GET['id']);
} else {
    $patient = null;
    require 'blockopen.php';
    echo( "No Patient ID assigned");
    require 'blockclose.php';
    die();
    
}
if (!$patient) {
    require 'blockopen.php';
    echo( "No Patient ID " . $_GET['id'] . ".");
    require 'blockclose.php';
    die();
}
//var_dump($patient);
//die();



//$patientLists = Patient::getAll($conn);

//Get Specific Row from Table

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
//var_dump($statusLists);
//var_dump($status);


// true = Disable Edit page, false canEditPage
$isEditModePageOn = false;


$canViewPatientInfo = Auth::canViewPatientInfo();
$canEditPatientInfo = true;


$canViewNBCenter = Auth::canViewNBCenter();
$canEditNBCenter = true;

$canViewResult = Auth::canViewPatientResult();
$canEditResult = true;


//disable by field
$isHideResult = true;
$isStatusDisableEdit = true;

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
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">




            <?php //require 'includes/patient_status.php';  
            ?>
            <hr>

            <form id="" name="" method="post">
                <?php if ($canViewPatientInfo) : ?>
                    <?php require 'includes/patient_form_detail.php'; ?>
                    <hr>
                <?php endif; ?>
                <?php if ($canViewNBCenter) : ?>
                    <?php require 'includes/patient_form_b.php'; ?>
                    <hr>
                <?php endif; ?>
                <?php if ($canViewResult) : ?>
                    <?php require 'includes/patient_form_1result.php'; ?>
                    <hr>
                <?php endif; ?>

            </form>

        

    </div>
</div>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    // set active tab
    $("#patientab").addClass("active");
</script>