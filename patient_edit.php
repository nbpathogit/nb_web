<?php
//var_dump($_GET);

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //var_dump($_POST);
    //die();
    if (isset($_POST['status'])) {
        if (Patient::updateStatus($conn, $_GET['id'], $_POST['status'])) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
    }
}



//Get Specific Row from Table
if (isset($_GET['id'])) {
    $patient = Patient::getAll($conn, $_GET['id']);
} else {
    $patient = null;
}
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





$curstatus = Status::getAll($conn, $patient[0]['status_id']);

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

//disable by group
$canViewPatientInfo = Auth::canViewPatientInfo();
$isDisableEditPatientInfo = Auth::isDisableEditPatientInfo();


$canViewNBCenter = Auth::canViewNBCenter();
$isDisableEditNBCenter = Auth::isDisableEditNBCenter();

$canViewResult = Auth::canViewPatientResult();
$isUpdateResultAval = true;
$isDisableEditResult = Auth::isDisableEditPatientResult();


//disable by field
$isHideResult = false;
$isStatusDisableEdit = true;

?>

<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()): ?>
    You are not login.
<?php else: ?>

    <?php require 'includes/patient_status.php'; ?><hr>

    <form  id="" name="" method="post">

        <?php if ($canViewPatientInfo): ?>
            <?php require 'includes/patient_form_a.php'; ?><hr>
        <?php endif; ?>
        <?php if ($canViewNBCenter): ?>
            <?php require 'includes/patient_form_b.php'; ?><hr>
        <?php endif; ?>
        <?php if ($canViewResult): ?>
            <?php require 'includes/patient_form_c.php'; ?><hr>
            <?php if ($isUpdateResultAval): ?>
                <?php require 'includes/patient_form_d.php'; ?><hr>
            <?php endif; ?>
        <?php endif; ?>
            
        <p align="center"><button name="Submit2" type="submit" class="btn btn-primary">ตกลง</button></p>
    </form>

<?php endif; ?>


<?php require 'includes/footer.php'; ?>
