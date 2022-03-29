<?php

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {

    $patient = Patient::getByID($conn, $_GET['id']);

    if (!$patient) {
        die("patient not found");
    }
} else {
    die("id not supplied, patient not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($patient->delete($conn)) {

        Url::redirect("/patient.php");
    }
}

?>
<?php require 'includes/header.php'; ?>

<h2>Delete patient</h2>

<form method="post">

    <p>Are you sure?</p>

    <button>Delete</button>
    <a href="patient.php?id=<?= $patient->id; ?>">Cancel</a>

</form>

<?php require 'includes/footer.php'; ?>
