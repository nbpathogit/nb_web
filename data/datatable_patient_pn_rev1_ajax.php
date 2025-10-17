<?php

require '../includes/init.php';

$auth = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . Url::getSubFolder1() . "/datatable_draft.php")) {

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

    $sql = "SELECTconcat(p.ppre_name,' ',p.pname) as name ,
    p.plastname as surname ,
    p.pnum as pn,
    p.`pedge` as age, 
    p.phospital_num as hn,
    h.hospital as Hospital,
    rsu_21.result_message as Specimen,
    rsu_22.result_message as ssource,
    rsu_23.result_message  as specimen_adequacy,
    rsu_24.result_message as interpretation,
    rsu_25.result_message as EDUCATIONAL_NOTES_AND_SUGGESTION,
    p.date_1000 as Date_Received,
    p.date_20000 as Date_Report,
    concat(jPatho.name,' ',jPatho.lastname) as Pathologist,
    concat(jCyto.name,' ',jCyto.lastname) as Cytologist

    FROM patient as p
    JOIN hospital as h on p.phospital_id = h.id

    LEFT JOIN presultupdate as rsu_21 ON rsu_21.patient_id = p.id and rsu_21.result_type_id = 21
    LEFT JOIN presultupdate as rsu_22 ON rsu_22.patient_id = p.id and rsu_22.result_type_id = 22
    LEFT JOIN presultupdate as rsu_23 ON rsu_23.patient_id = p.id and rsu_23.result_type_id = 23
    LEFT JOIN presultupdate as rsu_24 ON rsu_24.patient_id = p.id and rsu_24.result_type_id = 24
    LEFT JOIN presultupdate as rsu_25 ON rsu_25.patient_id = p.id and rsu_25.result_type_id = 25

    LEFT JOIN job as jPatho ON jPatho.patient_id = p.id and jPatho.job_role_id = 5
    LEFT JOIN job as jCyto ON jCyto.patient_id = p.id and jCyto.job_role_id = 7

    WHERE p.sn_type = 'PN' 

  and p.movetotrash = 0
  AND DATE(p.date_1000) >= '2025-01-01' AND DATE(p.date_1000) <= '2025-05-13' 
  ORDER BY p.pnum ASC";
    
    $assoc_datas = Patient::getBySQL($conn, $sql);

    $data = [];
    foreach ($assoc_datas as $adata) {
        if ($adata['uid'])
            $data[] = [$adata['uid'], $adata['username'], $adata['pre_name'], $adata['name'], $adata['lastname'], $adata['pre_name_e'], $adata['name_e'], $adata['lastname_e'], $adata['short_name'], $adata['hospital'], $adata['ugroup'], "action"];
    }
    $result = ["data" => $data];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
