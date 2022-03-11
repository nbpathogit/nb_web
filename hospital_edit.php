<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {

    $hospital = Hospital::getByID($conn, $_GET['id']);

    if (!$hospital) {
        die("hospital not found");
    }
} else {
    die("id not supplied, hospital not found");
}

//var_dump($hospitals);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // var_dump($_POST);
    // die();

    // $hospital = new Hospital();
    $hospital->id = $_GET['id'];
    $hospital->hospital = $_POST['hospital_name'];
    $hospital->address = $_POST['hospital_address'];
    $hospital->hdetail = $_POST['hospital_detail'];

    // var_dump($hospital);
    // exit();

    if ($hospital->update($conn)) {

        Url::redirect("/hospital.php");
    } else {
        echo '<script>alert("Add hospital fail. Please verify again")</script>';
    }
}

?>




<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()) : ?>
    You are not login.
<?php else : ?>
    <h4>แก้ไขสถานพยาบาล</h4>
    <?php require 'includes/hospital_form.php'; ?>

<?php endif; ?>

<?php require 'includes/footer.php'; ?>