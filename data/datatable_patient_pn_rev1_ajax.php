<?php

require '../includes/init.php';

$auth = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (
        !empty($_SERVER['HTTP_REFERER'])
        && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . Url::getSubFolder1() . "/datatable_patient_pn_rev1.php")
    ) {

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



$auth = true;






if ($auth) {
    $conn = require '../includes/db.php';

    // Handle date range parameters
    $startDate = null;
    $endDate = null;
    
    if (isset($_REQUEST['startDate']) && isset($_REQUEST['endDate'])) {
        $startDate = $_REQUEST['startDate'];
        $endDate = $_REQUEST['endDate'];
    }

    // Build WHERE clause for date filtering
    $dateFilter = "";
    if ($startDate && $endDate) {
        $dateFilter = "AND DATE(p.date_1000) >= '$startDate' AND DATE(p.date_1000) <= '$endDate'";
    } else {
        // Default date range if no parameters provided - set to today
        $today = date('Y-m-d');
        $dateFilter = "AND DATE(p.date_1000) >= '$today' AND DATE(p.date_1000) <= '$today'";
    }

    $sql = "SELECT
        concat(p.ppre_name,' ',p.pname) as name ,
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
  $dateFilter
  ORDER BY p.pnum ASC";

    $assoc_datas = Patient::getBySQL($conn, $sql);

    $columns = [];
    $data = [];

    if (!empty($assoc_datas)) {
        $firstRow = $assoc_datas[0];
        foreach (array_keys($firstRow) as $columnName) {
            $columns[] = ['title' => $columnName, 'data' => $columnName];
        }

        foreach ($assoc_datas as $adata) {
            $data[] = $adata;
        }
    }

    $result = ["data" => $data, "columns" => $columns];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
