<?php

require '../includes/init.php';

$auth = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!empty($_SERVER['HTTP_REFERER']) && ((strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . Url::getSubFolder1()."/patient.php"))  || (strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . Url::getSubFolder1(). "/patient_monitor_8000.php"))   || (strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . Url::getSubFolder1(). "/patient_confirm.php")))) {

        // >>>> Security check
        if (empty($_SESSION['skey']) || empty($_REQUEST['skey']) || ($_SESSION['skey'] != $_REQUEST['skey'])) {
            Auth::block("HTTP/1.1 403 Forbidden A");
        } else {
            // echo "AJAX request";
            $auth = true;
        }
    } else {
         Auth::block("HTTP/1.1 403 Forbidden B");
    }
} else {
     Auth::block("HTTP/1.1 403 Forbidden C");
}

if ($auth) {
    $conn = require '../includes/db.php';
    require '../user_auth.php';

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

    if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . "/patient_monitor_8000.php")) {   // get patient statas id = 8000
        $patientLists = Patient::getAllJoinID8000($conn, 0, $range);
    } else if ($isCurUserClinicianCust || $isCurUserHospitalCust) {  // get patient with reported
        $patientLists = Patient::getAllJoinWithReported($conn, 0, $range);
    } else if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . "/patient_confirm.php")) {
        $patientLists = Patient::getAllConfirm($conn, 0, $range);
    } else {                                                        // get all patient
        $patientLists = Patient::getAllJoin($conn, 0, $range);
    }

    $data = [];
    foreach ($patientLists as $patient) {
        if ($patient['pid']) {

            if ($_SESSION['user']->ugroup_id == '5000' || $_SESSION['user']->ugroup_id == '5100') {
                if ($_SESSION['user']->uhospital_id == $patient['phospital_id']) {
                    $data[] = [$patient['pid'], $patient['pnum'], $patient['pname'], $patient['plastname'], $patient['hospital'], $patient['name'], $patient['date_1000'], $patient['date_20000'], $patient['des'], $patient['reported_as'], $patient['priority'], $patient['second_patho_review'], $patient['request_sp_slide'],$patient['tr_time']];
                }
            } else {
                $data[] = [$patient['pid'], $patient['pnum'], $patient['pname'], $patient['plastname'], $patient['hospital'], $patient['name'], $patient['date_1000'], $patient['date_20000'], $patient['des'], $patient['reported_as'], $patient['priority'], $patient['second_patho_review'], $patient['request_sp_slide'],$patient['tr_time']];
            }
        }
    }


    $result = ["data" => $data];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
