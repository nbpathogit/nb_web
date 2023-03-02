<?php

require '../includes/init.php';

$auth = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!empty($_SERVER['HTTP_REFERER']) && ((strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . "/patient.php"))  || (strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . "/patient_monitor_8000.php")))) {

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

    if (isset($_REQUEST['psid']) && $_REQUEST['psid'] == 8000) {   // get patient statas id = 8000
        $patientLists = Patient::getAllJoinID8000($conn, 0, $range);
    } else if ($isCurUserClinicianCust || $isCurUserHospitalCust) {  // get patient with reported
        $patientLists = Patient::getAllJoinWithReported($conn, 0, $range);
    } else {                                                        // get all patient
        $patientLists = Patient::getAllJoin($conn, 0, $range);
    }

    $data = [];
    foreach ($patientLists as $patient) {
        if ($patient['pid']) {

            if ($_SESSION['user']->ugroup_id == '5000' || $_SESSION['user']->ugroup_id == '5100') {
                if ($_SESSION['user']->uhospital_id == $patient['phospital_id']) {
                    $data[] = [$patient['pid'], $patient['pnum'], $patient['pname'], $patient['plastname'], $patient['hospital'], $patient['name'], $patient['date_1000'], $patient['date_20000'], $patient['des'], $patient['reported_as'], $patient['priority'], "", ""];
                }
            } else {
                $data[] = [$patient['pid'], $patient['pnum'], $patient['pname'], $patient['plastname'], $patient['hospital'], $patient['name'], $patient['date_1000'], $patient['date_20000'], $patient['des'], $patient['reported_as'], $patient['priority'], "", ""];
            }
        }

        // if ($_REQUEST['range'] == "1m")
        //         $data[] = [$patient['pid'], $patient['pnum'], $patient['pname'], $patient['plastname'], $patient['hospital'], $patient['name'], "30", $patient['date_20000'], $patient['des'], $patient['reported_as'], $patient['priority'], "",];
        //     else if ($_REQUEST['range'] == "3m")
        //         $data[] = [$patient['pid'], $patient['pnum'], $patient['pname'], $patient['plastname'], $patient['hospital'], $patient['name'], "90", $patient['date_20000'], $patient['des'], $patient['reported_as'], $patient['priority'], "",];
        //     else if ($_REQUEST['range'] == "1y")
        //         $data[] = [$patient['pid'], $patient['pnum'], $patient['pname'], $patient['plastname'], $patient['hospital'], $patient['name'], "360", $patient['date_20000'], $patient['des'], $patient['reported_as'], $patient['priority'], "",];

    }


    $result = ["data" => $data];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
