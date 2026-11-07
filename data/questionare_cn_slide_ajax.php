<?php

/*

 SELECT 
#*,
a.id as id, 
a.patient_id AS patient_id, 
b.pnum as patient_num ,
a.score_thickness AS score_thickness,
a.score_staining AS score_staining,
a.score_mounting AS score_mounting,
a.score_labeling AS score_labeling,
a.score_contaminate AS score_contaminate,
a.note AS note
FROM `questionnaire_quality_sn` AS a
JOIN patient as b ON b.id = a.patient_id and b.movetotrash = 0;
 
 *  */


require '../includes/init.php';

$auth = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']  . Url::getSubFolder1(). "/questionare_cn_slide.php")) {

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



//$auth = true;
if ($auth) {
    $conn = require '../includes/db.php';
    date_default_timezone_set('Asia/Bangkok');

    $range = "0";
    $start_date = "";
    $end_date = "";

    if (!empty($_REQUEST['start_month']) && !empty($_REQUEST['end_month'])) {
        $sm = preg_replace('/[^0-9\-]/', '', $_REQUEST['start_month']);
        $em = preg_replace('/[^0-9\-]/', '', $_REQUEST['end_month']);
        if ($sm > $em) {
            $tmp = $sm;
            $sm = $em;
            $em = $tmp;
        }
        $start_date = $sm . "-01 00:00:00";
        $end_ts = strtotime($em . "-01");
        $end_date = ($end_ts !== false) ? date('Y-m-t 23:59:59', $end_ts) : ($em . "-31 23:59:59");
    } elseif (!empty($_REQUEST['start_date']) && !empty($_REQUEST['end_date'])) {
        $sd = preg_replace('/[^0-9\-]/', '', $_REQUEST['start_date']);
        $ed = preg_replace('/[^0-9\-]/', '', $_REQUEST['end_date']);
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
    } else {
        $req_range = isset($_REQUEST['range']) ? trim($_REQUEST['range']) : '2m';
        if ($req_range === 'all' || $req_range === '0' || $req_range === '') {
            $range = "0";
        } else {
            if ($req_range == '1m')
                $dateTime = new DateTime("-1 Months");
            else if ($req_range == '2m')
                $dateTime = new DateTime("-2 Months");
            else if ($req_range == '3m')
                $dateTime = new DateTime("-3 Months");
            else if ($req_range == '6m')
                $dateTime = new DateTime("-6 Months");
            else if ($req_range == '1y')
                $dateTime = new DateTime("-1 Years");
            else if ($req_range == '2y')
                $dateTime = new DateTime("-2 Years");
            else
                $dateTime = new DateTime("-2 Months");

            $range = $dateTime->format('Y-m-d');
        }
    }

//    $assoc_datas = QuesSN::getAll($conn);
    $assoc_datas = QuesCN::getAllbyDaterange($conn, $range, $start_date, $end_date);

    $data = [];
    foreach ($assoc_datas as $adata) {
        $data[] = [
            $adata['id'], 
            $adata['patient_id'], 
            $adata['patient_num'], 
            $adata['accept_date'], 
            $adata['score_thickness'], 
            $adata['score_staining'], 
            $adata['score_mounting'], 
            $adata['score_labeling'], 
            $adata['note'], 
        ];
        
    }
    $result = ["data" => $data];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
