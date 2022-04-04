<?php

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';
require 'user_auth.php';

if (!$isCurUserAdmin){
    Auth::block();
}

if (isset($_GET['id'])) {

    $hospital = Hospital::getByID($conn, $_GET['id']);

    if (!$hospital) {
        die("hospital not found");
    }
} else {
    die("id not supplied, hospital not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($hospital->delete($conn)) {

        Url::redirect("/hospital.php");
    }
}

?>
<?php require 'includes/header.php'; ?>

<h2>Delete hospital</h2>

<form method="post">

    <p>Are you sure?</p>

    <button>Delete</button>
    <a href="hospital.php?id=<?= $hospital->id; ?>">Cancel</a>

</form>

<?php require 'includes/footer.php'; ?>