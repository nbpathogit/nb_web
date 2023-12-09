<?php
//คนเช่วยตัด

require '../includes/init.php';

$auth = false;


//if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
//    if (!empty($_SERVER['HTTP_REFERER']) && ((strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . Url::getSubFolder1()."/patient.php"))  || (strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . Url::getSubFolder1(). "/job2_finish.php"))   || (strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . Url::getSubFolder1(). "/patient_confirm.php")))) {
//
//        // >>>> Security check
//        if (empty($_SESSION['skey']) || empty($_REQUEST['skey']) || ($_SESSION['skey'] != $_REQUEST['skey'])) {
//            Auth::block("HTTP/1.1 403 Forbidden A");
//        } else {
//            // echo "AJAX request";
//            $auth = true;
//        }
//    } else {
//         Auth::block("HTTP/1.1 403 Forbidden B");
//    }
//} else {
//     Auth::block("HTTP/1.1 403 Forbidden C");
//}



$auth = true;










if ($auth) {
    $conn = require '../includes/db.php';
    require '../user_auth.php';

    $range = "0";
    $end = "0";
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

//        $myfile = fopen("job2_finish_test.txt", "w") or die("Unable to open file!");
//        fwrite($myfile,  "Test");
//        fclose($myfile);

    $patientLists = Patient::getAllJob2FinishPhp($conn, 0, $range,$end);
    //console.log($patientLists);
    //var_dump($patientLists);

//        $myfile = fopen("job2_finish.txt", "w") or die("Unable to open file!");
//        fwrite($myfile,  $patientLists);
//        fclose($myfile);

    
//["pid"]=> string(3) "200" ["p_pnum"]=> string(9) "SN2323116" ["p_hn"]=> string(0) "" ["p_accept"]=> string(19) "2023-11-04 23:04:27" 
//---0---------------------------1--------------------------------2------------------------3-----------------------------------------------
//
//["p_patient"]=> string(24) "Mrs.เอบี wcdcwdc" ["j_id"]=> string(3) "225" ["j_patient_id"]=> string(3) "200" ["j_user_id"]=> string(2) "40" 
//-------4--------------------------------------5-------------------------------6--------------------------------7-----------------------
//
//["j_job_role_id"]=> string(1) "2" ["j_jobname"]=> string(39) "คนช่วยตัดนื้อ" ["j_owners"]=> string(93) "นายทวี ธรรมสรณกุล,นางพนมพร ภู่อ่ำ" ["finish_date"]=> NULL
//-------8-------------------------------9---------------------------------10---------------------------------------------11---------------
//
    
    $data = [];
    foreach ($patientLists as $patient) {
        if ($patient['pid']) {// exclude id zero
           
            $data[] = [$patient['pid'], $patient['p_pnum'], $patient['p_hn'], $patient['p_accept'], 
                $patient['p_patient'], $patient['j_id'], $patient['j_patient_id'], $patient['j_user_id'], 
                $patient['j_job_role_id'], $patient['j_jobname'], $patient['j_owners'],$patient['j_qty'], $patient['finish_date'],null];
                
            
        }
    }

    $result = ["data" => $data];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    //echo var_dump($patientLists);
}
