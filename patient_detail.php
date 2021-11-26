<?php
//var_dump($_GET);

require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {
    $patients = Patient::getAll($conn,$_GET['id']);
} else {
    $patients = null;
}


//$patients = Patient::getAll($conn);
$hospitals = Hospital::getAll($conn);
$specimens = Specimen::getAll($conn);
$clinicians = Clinician::getAll($conn);
$userPathos = User::getAllbyPathologis($conn);
$prioritys = Priority::getAll($conn);

//var_dump($patients);
//var_dump($prioritys);

?>

<?php require 'includes/header.php'; ?>

USER Detail <br>
<?php require 'includes/patient_form.php'; ?>

<?php require 'includes/footer.php'; ?>
