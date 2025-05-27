<?php

require '../includes/init.php';

$auth = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!empty($_SERVER['HTTP_REFERER']) && ((strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . Url::getSubFolder1() . "/patient.php"))  || (strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . Url::getSubFolder1() . "/patient_monitor_8000.php"))   || (strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . Url::getSubFolder1() . "/patient_confirm.php")))) {

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
        if (!is_numeric($_REQUEST['range'])) {
            date_default_timezone_set('Asia/Bangkok');

            if ($_REQUEST['range'] == '1m')
                $dateTime = new DateTime("-1 Months");
            else if ($_REQUEST['range'] == '2m')
                $dateTime = new DateTime("-2 Months");
            else if ($_REQUEST['range'] == '3m')
                $dateTime = new DateTime("-3 Months");
            else if ($_REQUEST['range'] == '6m')
                $dateTime = new DateTime("-6 Months");
            else if ($_REQUEST['range'] == '1y')
                $dateTime = new DateTime("-1 Years");
            else if ($_REQUEST['range'] == '2y')
                $dateTime = new DateTime("-2 Years");

            $range = $dateTime->format('Y-m-d');
        } else {
            $range = $_REQUEST['range'];
        }
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

    if (strpos($_SERVER['HTTP_REFERER'],  "patient_confirm.php")) {
        $patientLists = Patient::getAllConfirm($conn, 0, $range);
    } else {                                                        // get all patient
        //$patientLists = Patient::getAllJoin($conn, 0, $range);
        $patientLists = Patient::getAllJoin_v2($conn, 0, $range);
    }


    $data = [];
    foreach ($patientLists as $patient) {
        if ($patient['pid']) {
            //phospital_num
            if ($_SESSION['user']->ugroup_id == '5000' || $_SESSION['user']->ugroup_id == '5100') {
                if ($_SESSION['user']->uhospital_id == $patient['phospital_id']) { // Select only Owner Hospital
                    $data[] =
                        [
                            $patient['pid']                 //0
                            ,
                            $patient['p_sn_type']          //1
                            ,
                            $patient['p_pnum']            //2
                            ,
                            $patient['p_phospital_num']   //3
                            ,
                            $patient['p_pname']           //4
                            ,
                            $patient['p_plastname']       //5
                            ,
                            $patient['h_hospitial']       //6
                            ,
                            $patient['name_patho']        //7
                            ,
                            $patient['p_date_1000']       //8
                            ,
                            $patient['p_date_first_report'] //9
                            ,
                            $patient['s_des']             //10
                            ,
                            $patient['p_reported_as']     //11
                            ,
                            $patient['pri_priority']      //12
                            ,
                            $patient['p_second_patho_review'] //13
                            ,
                            $patient['p_request_sp_slide'] //14
                            ,
                            $patient['p_tr_time']         //15
                            ,
                            $patient['p_create_by']        //16
                            ,
                            $patient['u_clinician']        //17
                            ,
                            $patient['pid']                //18
                            ,
                            $patient['pid']                //19
                        ];
                }
            } else {
                $data[] =
                    [
                        $patient['pid']                        //0
                        ,
                        $patient['p_sn_type']                 //1
                        ,
                        $patient['p_pnum']                   //2
                        ,
                        $patient['p_phospital_num']          //3
                        ,
                        $patient['p_pname']                  //4
                        ,
                        $patient['p_plastname']              //5
                        ,
                        $patient['h_hospitial']              //6
                        ,
                        $patient['name_patho']               //7
                        ,
                        $patient['p_date_1000']              //8
                        ,
                        $patient['p_date_first_report']      //9
                        ,
                        $patient['s_des']                    //10
                        ,
                        $patient['p_reported_as']            //11
                        ,
                        $patient['pri_priority']             //12
                        ,
                        $patient['p_second_patho_review']    //13
                        ,
                        $patient['p_request_sp_slide']       //14
                        ,
                        $patient['p_tr_time']                 //15
                        ,
                        $patient['p_create_by']               //16
                        ,
                        $patient['u_clinician']               //17
                        ,
                        $patient['pid']                       //18
                        ,
                        $patient['edit_date']                 //19
                    ];
            }
        }
    }


    $result = ["data" => $data];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
