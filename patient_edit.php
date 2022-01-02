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


if (isset($_GET['id'])) {
    $patients = Patient::getAll($conn, $_GET['id']);
} else {
    $patients = null;
}


//$patients = Patient::getAll($conn);
$hospitals = Hospital::getAll($conn);
$specimens = Specimen::getAll($conn);
$clinicians = User::getAllbyClinicians($conn);
$userPathos = User::getAllbyPathologis($conn);
$prioritys = Priority::getAll($conn);


$curstatus = Status::getAll($conn, $patients[0]['status_id']);

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

//var_dump($patients);
//var_dump($statusLists);
?>

<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()): ?>
    You are not authorized.
<?php else: ?>

    <?php require 'includes/patient_status.php'; ?><hr>

    <form  id="" name="" method="post">

        <?php require 'includes/patient_form_a.php'; ?><hr>
        <?php require 'includes/patient_form_b.php'; ?><hr>
        <?php require 'includes/patient_form_c.php'; ?><hr>
        <p align="center">
            <!--<button>ตกลง</button>-->
            <button name="Submit2" type="submit" class="btn btn-primary">บันทึกทั้งหมด</button>
        </p>
    </form>

<?php endif; ?>


<?php require 'includes/footer.php'; ?>
