<?php

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';
require 'user_auth.php';

if (isset($_GET['id'])) {

    $job = Job::getByID($conn, $_GET['id']);
    // var_dump($job);
    // exit;
    if (!$job) {
        die("job not found");
    }
} else {
    die("id not supplied, job not found");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // var_dump($_POST);
    //die();

    $job = new Job();
    $job->id = $_GET['id'];
    $job->jobname = $_POST['jobname'];
    $job->pay = $_POST['pay'];
    $job->cost_count_per_day = $_POST['cost_count_per_day'];

    if ($job->update($conn)) {
        Url::redirect("/job.php");
    } else {
        echo '<script>alert("Add job fail. Please verify again")</script>';
    }
}
?>

<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
        <div class="d-flex align-items-center justify-content-between">
            <a href="/job.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-table-list me-2"></i>Job ทั้งหมด</a>
        </div>
    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">

        <h4>แก้ไข Job</h4>

        <form class="row g-2" method="post">
            <input type="hidden" id="id" name="custId" value="<?= $job[0]['id'] ?>">
            <div class="col-xl-4 col-md-12">
                <label class="form-label" for="jobname">Job name</label>
                <input class="form-control" type="text" id="jobname" name="jobname" value="<?= htmlspecialchars($job[0]["jobname"]); ?>">
            </div>
            <div class="col-xl-4 col-md-12">
                <label class="form-label" for="pay">Pay</label>
                <input class="form-control" type="number" id="pay" name="pay" value="<?= htmlspecialchars($job[0]['pay']); ?>">
            </div>
            <div class="col-xl-4 col-md-12">
                <label class="form-label" for="cost_count_per_day">cost_count_per_day</label>
                <input class="form-control" type="number" id="cost_count_per_day" name="cost_count_per_day" value="<?= htmlspecialchars($job[0]["cost_count_per_day"]); ?>">
            </div>

            <div class="col-xl-3 col-md-12">
                <label class="form-label" for="job_role_id" >job_role_id</label>
                <input class="form-control" type="number" id="job_role_id" name="job_role_id" value="<?= htmlspecialchars($job[0]["job_role_id"]); ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-12">
                <label class="form-label" for="patient_id" >patient_id</label>
                <input class="form-control" type="number" id="patient_id" name="patient_id" value="<?= htmlspecialchars($job[0]["patient_id"]); ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-12">
                <label class="form-label" for="patient_number" >patient_number</label>
                <input class="form-control" type="text" id="patient_number" name="patient_number" value="<?= htmlspecialchars($job[0]["patient_number"]); ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-12">
                <label class="form-label" for="user_id" >user_id</label>
                <input class="form-control" type="number" id="user_id" name="user_id" value="<?= htmlspecialchars($job[0]["user_id"]); ?>" disabled>
            </div>
            <div class="col-xl-4 col-md-12">
                <label class="form-label" for="pre_name" >pre_name</label>
                <input class="form-control" type="text" id="pre_name" name="pre_name" value="<?= htmlspecialchars($job[0]["pre_name"]); ?>" disabled>
            </div>
            <div class="col-xl-4 col-md-12">
                <label class="form-label" for="name" >name</label>
                <input class="form-control" type="text" id="name" name="name" value="<?= htmlspecialchars($job[0]["name"]); ?>" disabled>
            </div>
            <div class="col-xl-4 col-md-12">
                <label class="form-label" for="lastname" >lastname</label>
                <input class="form-control" type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($job[0]["lastname"]); ?>" disabled>
            </div>
            <div class="col-xl-6 col-md-12">
                <label class="form-label" for="comment" >comment</label>
                <input class="form-control" type="text" id="comment" name="comment" value="<?= htmlspecialchars($job[0]["comment"]); ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-6">
                <label class="form-label" for="finish_date" >finish_date</label>
                <input class="form-control" type="text" id="finish_date" name="finish_date" value="<?= htmlspecialchars($job[0]["finish_date"]); ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-6">
                <label class="form-label" for="insert_time" >insert_time</label>
                <input class="form-control" type="text" id="insert_time" name="insert_time" value="<?= htmlspecialchars($job[0]["insert_time"]); ?>" disabled>
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
        $("#jobtab").addClass("active");

        // prevent from unsave
        function onNosave(e) {
            e.preventDefault();
            e.returnValue = '';
        }

        $("#job").change(function() {
            window.addEventListener("beforeunload", onNosave);
        });

        $("#save").click(function() {
            window.removeEventListener("beforeunload", onNosave);
        })
    });
</script>