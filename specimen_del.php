<?php

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';
require 'user_auth.php';

if (!$isCurUserAdmin){
    Auth::block();
}

if (isset($_GET['id'])) {

    $specimen = ServicePriceList::getByID($conn, $_GET['id']);

    if (!$specimen) {
        die("specimen not found");
    }
} else {
    die("id not supplied, specimen not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($specimen->delete($conn)) {

        Url::redirect("/specimen.php");
    }
}

?>
<?php require 'includes/header.php'; ?>

<h2>Delete specimen</h2>

<form method="post">

    <p>Are you sure?</p>

    <button>Delete</button>
    <a href="specimen.php?id=<?= $specimen->id; ?>">Cancel</a>

</form>

<?php require 'includes/footer.php'; ?>
