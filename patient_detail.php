<?php
//var_dump($_GET);

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {
    $patient = Patient::getAll($conn,$_GET['id']);
} else {
    $patient = null;
}

//var_dump($patient);
//die();



//$patientLists = Patient::getAll($conn);

//Get Specific Row from Table
if (isset($_GET['id'])) {
    $patient = Patient::getAll($conn, $_GET['id']);
} else {
    $patient = null;
}
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


$canViewPatientInfo = Auth::canViewPatientInfo();
$isDisableEditPatientInfo = true;


$canViewNBCenter = Auth::canViewNBCenter();
$isDisableEditNBCenter = true;

$canViewResult = Auth::canViewPatientResult();
$isDisableEditResult = true;


//disable by field
$isHideResult = true;
$isStatusDisableEdit = true;

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

    </form>

<?php endif; ?>

<?php require 'includes/footer.php'; ?>
