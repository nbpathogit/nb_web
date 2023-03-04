<?php

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {

    $billing = Billing::getByID($conn, $_GET['id']);
    // var_dump($billing);
    // exit;
    if (!$billing) {
        die("billing not found");
    }
} else {
    die("id not supplied, billing not found");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // var_dump($_POST);
    //die();

    $billing = new Billing();
    $billing->id = $_GET['id'];
    $billing->cost = $_POST['cost'];
    $billing->description = $_POST['description'];

    if ($billing->update($conn)) {
        Url::redirect("/billing.php");
    } else {
        echo '<script>alert("Add billing fail. Please verify again")</script>';
    }
}
?>

<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">
        <div class="d-flex align-items-center justify-content-between">
            <a href="/billing.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-file-invoice me-2"></i>Billing ทั้งหมด</a>
        </div>
    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <h4>แก้ไข Billing</h4>

        <form class="row g-2" method="post">
            <input type="hidden" id="id" name="custId" value="<?= $billing[0]['id'] ?>">
            <div class="col-xl-4 col-md-12">
                <label class="form-label" for="description">Description</label>
                <input class="form-control" type="text" id="description" name="description" value="<?= htmlspecialchars($billing[0]["description"]); ?>">
            </div>
            <div class="col-xl-4 col-md-12">
                <label class="form-label" for="cost">Cost</label>
                <input class="form-control" type="number" id="cost" name="cost" value="<?= htmlspecialchars($billing[0]['cost']); ?>">
            </div>

            <div class="col-xl-3 col-md-12">
                <label class="form-label" for="specimen_id" >specimen_id</label>
                <input class="form-control" type="number" id="specimen_id" name="specimen_id" value="<?= htmlspecialchars($billing[0]["specimen_id"]); ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-12">
                <label class="form-label" for="patient_id" >patient_id</label>
                <input class="form-control" type="number" id="patient_id" name="patient_id" value="<?= htmlspecialchars($billing[0]["patient_id"]); ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-12">
                <label class="form-label" for="number" >number</label>
                <input class="form-control" type="number" id="number" name="number" value="<?= htmlspecialchars($billing[0]["number"]); ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-12">
                <label class="form-label" for="name" >name</label>
                <input class="form-control" type="text" id="name" name="name" value="<?= htmlspecialchars($billing[0]["name"]); ?>" disabled>
            </div>
            <div class="col-xl-4 col-md-12">
                <label class="form-label" for="lastname" >lastname</label>
                <input class="form-control" type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($billing[0]["lastname"]); ?>" disabled>
            </div>
            <div class="col-xl-4 col-md-12">
                <label class="form-label" for="slide_type" >slide_type</label>
                <input class="form-control" type="number" id="slide_type" name="slide_type" value="<?= htmlspecialchars($billing[0]["slide_type"]); ?>" disabled>
            </div>
            <div class="col-xl-4 col-md-12">
                <label class="form-label" for="lastname" >lastname</label>
                <input class="form-control" type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($billing[0]["lastname"]); ?>" disabled>
            </div>
            <div class="col-xl-6 col-md-12">
                <label class="form-label" for="code_description" >code_description</label>
                <input class="form-control" type="text" id="code_description" name="code_description" value="<?= htmlspecialchars($billing[0]["code_description"]); ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-6">
                <label class="form-label" for="import_date" >import_date</label>
                <input class="form-control" type="text" id="import_date" name="import_date" value="<?= htmlspecialchars($billing[0]["import_date"]); ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-6">
                <label class="form-label" for="report_date" >report_date</label>
                <input class="form-control" type="text" id="report_date" name="report_date" value="<?= htmlspecialchars($billing[0]["report_date"]); ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-6">
                <label class="form-label" for="hospital" >hospital</label>
                <input class="form-control" type="text" id="hospital" name="hospital" value="<?= htmlspecialchars($billing[0]["hospital"]); ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-6">
                <label class="form-label" for="hn" >hn</label>
                <input class="form-control" type="text" id="hn" name="hn" value="<?= htmlspecialchars($billing[0]["hn"]); ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-6">
                <label class="form-label" for="send_doctor" >send_doctor</label>
                <input class="form-control" type="text" id="send_doctor" name="send_doctor" value="<?= htmlspecialchars($billing[0]["send_doctor"]); ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-6">
                <label class="form-label" for="pathologist" >pathologist</label>
                <input class="form-control" type="text" id="pathologist" name="pathologist" value="<?= htmlspecialchars($billing[0]["pathologist"]); ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-6">
                <label class="form-label" for="comment" >comment</label>
                <input class="form-control" type="text" id="comment" name="comment" value="<?= htmlspecialchars($billing[0]["comment"]); ?>" disabled>
            </div>

            <div class="d-grid gap-2 d-md-block">
                <button name="Submit" id="save" type="submit" class="btn btn-primary">แก้ไขข้อมูล</button>
            </div>

        </form>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#billingtab").addClass("active");

        // prevent from unsave
        function onNosave(e) {
            e.preventDefault();
            e.returnValue = '';
        }

        $("#billing").change(function() {
            window.addEventListener("beforeunload", onNosave);
        });

        $("#save").click(function() {
            window.removeEventListener("beforeunload", onNosave);
        })
    });
</script>