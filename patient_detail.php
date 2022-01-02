<?php
//var_dump($_GET);

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {
    $patients = Patient::getAll($conn,$_GET['id']);
} else {
    $patients = null;
}

//var_dump($patients);
//die();



//$patientLists = Patient::getAll($conn);

$hospitals = Hospital::getAll($conn);
$specimens = Specimen::getAll($conn);
$clinicians = User::getAllbyClinicians($conn);
$userPathos = User::getAllbyPathologis($conn);
$userTechnic = User::getAllbyTeachien($conn);
$prioritys = Priority::getAll($conn);
$status = Status::getAll($conn, $patients[0]['status_id']);
//$statusLists = Status::getAll($conn);



//$ug = Auth::getUserGroup();


//var_dump($patients);
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
