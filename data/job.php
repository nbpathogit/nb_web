<?php

require '../includes/init.php';

$auth = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . "/job.php")) {

        // >>>> Security check
        if (empty($_SESSION['skey']) || empty($_REQUEST['skey']) || ($_SESSION['skey'] != $_REQUEST['skey'])) {
            Auth::block();
        } else {
            // echo "AJAX request";
            $auth = true;
        }
    } else {
        Auth::block();
    }
} else {
    Auth::block();
}

if ($auth) {
    $conn = require '../includes/db.php';
    $jobs = Job::getAll($conn);

    $data = [];
    foreach ($jobs as $job) {
        if ($job['id'])
            $data[] = [$job['id'], $job['job_role_id'], $job['patient_id'], $job['patient_number'], $job['user_id'], $job['pre_name'], $job['name'], $job['lastname'], $job['jobname'], $job['pay'], $job['cost_count_per_day'], $job['comment'], $job['finish_date'], $job['insert_time']];
    }
    $result = ["data" => $data];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
