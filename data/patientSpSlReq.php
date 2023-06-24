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

//    if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . "/patient_monitor_8000.php")) {   // get patient statas id = 8000
//        $patientLists = Patient::getAllJoinID8000($conn, 0, $range);
//    } else if ($isCurUserClinicianCust || $isCurUserHospitalCust) {  // get patient with reported
//        $patientLists = Patient::getAllJoinWithReported($conn, 0, $range);
//    } else if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . "/patient_confirm.php")) {
//        $patientLists = Patient::getAllConfirm($conn, 0, $range);
//    } else {                                                        // get all patient
//        $patientLists = Patient::getAllJoin($conn, 0, $range);
//    }
    
    $requuestLists = ReqSpSlideID::getBillandJobTableWithStart($conn, $range);

    $data = [];
    foreach ($requuestLists as $req) {
        if ($req['rid']) {

            
//array(9) {
//  [0]=>
//  array(12) {
//    ["rid"]=>
//    string(1) "4"
//    ["bid"]=>
//    string(2) "68"
//    ["jid"]=>
//    string(3) "163"
//    ["patient_id_key"]=>
//    string(3) "176"
//    ["number"]=>
//    string(9) "CN2300001"
//    ["req_date"]=>
//    string(19) "2023-06-17 13:17:03"
//    ["finish_date"]=>
//    NULL
//    ["comment"]=>
//    string(1) "0"
//    ["jowowner"]=>
//    string(58) "พีรยุทธ สิทธิไชยากุล"
//    ["req_sp_type"]=>
//    string(15) "33000 Amyloid A"
//    ["bjob"]=>
//    string(17) "A,O,W,C1,G1,T1,Z1"
//    ["pathologist"]=>
//    string(40) "อภิชาติ ชุมทอง"
//  }

                $data[] = [$req['rid'], $req['bid'], $req['jid'], $req['patient_id_key'], $req['number'], $req['req_date'], $req['finish_date'], $req['comment'], $req['jowowner'], $req['req_sp_type'], $req['bjob'], $req['pathologist'],NULL];
       
        }
    }


    $result = ["data" => $data];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
