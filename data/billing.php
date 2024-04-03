<?php

require '../includes/init.php';

$auth = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . Url::getSubFolder1() . "/billing")) {

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
    if (isset($_REQUEST['end'])) {

        $bills = ServiceBilling::getAllDateforBillPage($conn, $_REQUEST['start'], $_REQUEST['end']);

    } else if (isset($_REQUEST['range'])) {

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

        $bills = ServiceBilling::getAllDateforBillPage($conn, $range);
    }


    //var_dump($bills);
    
//    SELECT p.id as pid 
//        , b.id as b_id 
//        , p.sn_type as p_sntype
//        , p.pnum as p_sn_num
//        , p.phospital_num as p_hn
//        , CONCAT(p.pname,' ',p.plastname) as p_pname
//        , CONCAT(user_cli.name,' ',user_cli.lastname) as user_clinicient
//        , hp.hospital as hp_hospital
//        , CONCAT(j5.name,' ',j5.lastname) as j5_pathologist
//        , DATE(p.date_1000)	as p_accept_date
//        , st.service_type as st_type
//        , b.code_description as b_code
//        , b.description as b_description
//        , b.sp_slide_block as b_sp_slide_block
//        , b.cost as b_cost
//    FROM  patient as p  
//     JOIN service_billing as b  ON  p.id = b.patient_id
//     LEFT JOIN service_type as st ON st.id = b.slide_type
//     LEFT JOIN job as j5 ON j5.patient_id = p.id and j5.job_role_id = 5
//     LEFT JOIN user as user_cli ON user_cli.id = p.pclinician_id 
//     LEFT JOIN hospital as hp ON hp.id = p.phospital_id
//    WHERE  p.movetotrash = 0  and date(b.import_date) >= '2024-01-01'  and date(b.import_date) <= '2024-01-31'  ORDER by p.pnum ASC
    $data = [];
    foreach ($bills as $bill) {
        if ($bill['pid']) {
           $data[] = [$bill['pid']                //0 
                , $bill['b_id']                   //1 
                , $bill['p_sntype']               //2 
                , $bill['p_sn_num']               //3 
                , $bill['p_hn']                   //4 
                , $bill['p_pname']                //5 
                , $bill['user_clinicient']        //6 
                , $bill['hp_hospital']            //7 
                , $bill['j5_pathologist']         //8 
                , $bill['p_accept_date']          //9 
                , $bill['b_billing_date']          //10 
                , $bill['st_service_typea_bill']    //11 
                , $bill['st_type']                //12
                , $bill['b_code']                 //13
                , $bill['b_description']          //14
                , $bill['b_sp_slide_block']       //15
                , $bill['b_cost']];               //16
        }              
    }
    $result = ["data" => $data];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
