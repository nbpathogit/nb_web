<?php

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {

    $fluid = LabFluid::getByID($conn, $_GET['id']);

    if (!$fluid) {
        die("fluid not found");
    }
} else {
    die("id not supplied, fluid not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($fluid->delete($conn)) {

        Url::redirect("/labfluid.php");
    }
}

?>
<?php require 'includes/header.php'; ?>

<h2>Delete fluid</h2>

<form method="post">

    <p>Are you sure?</p>

    <button>Delete</button>
    <a href="labfluid.php">Cancel</a>

</form>

<?php require 'includes/footer.php'; ?>
