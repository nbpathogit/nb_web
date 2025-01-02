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
    
    
    $range = "0";
    if (isset($_REQUEST['range'])) {

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
    }


//    $assoc_datas = QuesSN::getAll($conn);
    $assoc_datas = QuesCN::getAllbyDaterange($conn,$range);

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
