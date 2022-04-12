<?php

require '../includes/init.php';

$auth = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . "/patient.php")) {

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

    if ($isCurUserClinicianCust || $isCurUserHospitalCust) {
        $patientLists = Patient::getAllJoinWithReported($conn, 0);
    } else {
        $patientLists = Patient::getAllJoin($conn, 0);
    }

    $data = [];
    foreach ($patientLists as $patient) {
        $data[] = [$patient['pid'], $patient['pnum'], $patient['pname'], $patient['plastname'], $patient['hospital'], $patient['name'], $patient['date_1000'], $patient['date_20000'], $patient['des'], $patient['reported_as'], $patient['priority'], "",];
    }



    $result = ["data" => $data];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
