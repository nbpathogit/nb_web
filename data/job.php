<?php

require '../includes/init.php';

$auth = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . Url::getSubFolder1() . "/job.php")) {

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

    $range = "0";
    if (isset($_REQUEST['range'])) {

        date_default_timezone_set('Asia/Bangkok');

        if ($_REQUEST['range'] == '1m')
            $dateTime = new DateTime("-1 Months");
        else if ($_REQUEST['range'] == '3m')
            $dateTime = new DateTime("-3 Months");
        else if ($_REQUEST['range'] == '6m')
            $dateTime = new DateTime("-6 Months");
        else if ($_REQUEST['range'] == '1y')
            $dateTime = new DateTime("-1 Years");
        else if ($_REQUEST['range'] == '2y')
            $dateTime = new DateTime("-2 Years");

        $range = $dateTime->format('Y-m-d');
    }


    $jobs = Job::getAll($conn, 0, 0, $range);

    $data = [];
    foreach ($jobs as $job) {
        if ($job['id'])
            $data[] = [$job['id'], $job['job_role_id'], $job['patient_id'], $job['patient_number'], $job['user_id'], $job['pre_name'], $job['name'], $job['lastname'], $job['jobname'], $job['pay'], $job['cost_count_per_day'], $job['comment'], $job['finish_date'], $job['insert_time'], $job['qty'], $job['req_date']];
//----------------------------0------------------1-----------------2---------------------3--------------------4---------------5----------------6---------------7----------------8--------------9-------------------10---------------------11----------------12--------------------13---------------14-------------15----------
    }
    $result = ["data" => $data];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
