<?php

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {

    $specimen = Specimen::getByID($conn, $_GET['id']);

    if (!$specimen) {
        die("specimen not found");
    }
} else {
    die("id not supplied, specimen not found");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // var_dump($_POST);
    //die();

    $specimen = new Specimen();
    $specimen->id = $_GET['id'];
    $specimen->specimen = $_POST['specimen'];

    if ($specimen->update($conn)) {

        Url::redirect("/specimen.php");
    } else {
        echo '<script>alert("Add specimen fail. Please verify again")</script>';
    }
}
?>

<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <h4>แก้ไขสิ่งส่งตรวจ</h4>


        <form class="row g-2" method="post">

            <div class="mb-3">
                <label for="specimen" class="form-label">สิ่งส่งตรวจ</label>
                <textarea name="specimen" class="form-control" id="specimen" rows="3"><?= htmlspecialchars($specimen->specimen); ?></textarea>
            </div>

            <div class="d-grid gap-2 d-md-block">
                <button name="Submit" type="submit" class="btn btn-primary">แก้ไขข้อมูล</button>
                <button name="Reset" type="reset" class="btn btn-secondary" id="Reset">ยกเลิก</button>
            </div>

        </form>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $("#specimen_main").addClass("active");
    $("#specimen_add").addClass("active");
</script>