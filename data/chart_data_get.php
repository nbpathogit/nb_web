<?php

require '../includes/init.php';

$auth = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (
        !empty($_SERVER['HTTP_REFERER'])
        && (strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . Url::getSubFolder1() . "/chart_data.php") !== false ||
            strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . Url::getSubFolder1() . "/chart_data") !== false)
    ) {

        // >>>> Security check
        if (empty($_SESSION['skey']) || empty($_REQUEST['skey']) || ($_SESSION['skey'] != $_REQUEST['skey'])) {
            // Return JSON error instead of blocking
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Authentication failed']);
            exit;
        } else {
            // echo "AJAX request";
            $auth = true;
        }
    } else {
        // Return JSON error instead of blocking
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }
} else {
    // Return JSON error instead of blocking
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

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
        // Default date range if no parameters provided - set to last 30 days
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime('-30 days'));
        $dateFilter = "AND DATE(p.date_1000) >= '$startDate' AND DATE(p.date_1000) <= '$endDate'";
    }

    $sql = "SELECT
        DATE(p.date_1000) as date_in,
        COUNT(p.date_1000) as number_of,
        GROUP_CONCAT(concat(p.ppre_name,' ',p.pname)) as names,
        GROUP_CONCAT(p.plastname) as surnames,
        GROUP_CONCAT(p.pnum) as pns,
        GROUP_CONCAT(p.`pedge`) as ages,
        GROUP_CONCAT(p.phospital_num) as hns,
        GROUP_CONCAT(h.hospital) as hospitals,
        GROUP_CONCAT(CONCAT(jPatho.name, ' ', jPatho.lastname)) as pathologists,
        GROUP_CONCAT(qcn.score_specimen) as score_specimens,
        GROUP_CONCAT(qcn.score_staining) as score_stainings,
        GROUP_CONCAT(qcn.score_mounting) as score_mountings,
        GROUP_CONCAT(qcn.score_labeling) as score_labelings,
        GROUP_CONCAT(qcn.note) as notes

    FROM patient as p
    JOIN hospital as h on p.phospital_id = h.id

    LEFT JOIN questionnaire_quality_cn as qcn ON qcn.patient_id = p.id
    LEFT JOIN job as jPatho ON jPatho.patient_id = p.id and jPatho.job_role_id = 5

    WHERE p.sn_type = 'CN'
      and p.movetotrash = 0
      $dateFilter
      GROUP BY date_in
      ORDER BY date_in ASC";

    try {
        $assoc_datas = Patient::getBySQL($conn, $sql);
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database query failed: ' . $e->getMessage()]);
        exit;
    }

    // Check if this is a chart data request
    $isChartRequest = isset($_REQUEST['chart']) && $_REQUEST['chart'] == '1';
    
    if ($isChartRequest) {
        // Prepare chart data for line chart with time series
        $chartData = [];
        
        // Get date range from the query or use defaults
        $dateRangeStart = $startDate ?: date('Y-m-d', strtotime('-30 days'));
        $dateRangeEnd = $endDate ?: date('Y-m-d');
        
        // Create a complete date range array
        $dateRange = [];
        $currentDate = new DateTime($dateRangeStart);
        $endDateObj = new DateTime($dateRangeEnd);
        
        while ($currentDate <= $endDateObj) {
            $dateRange[$currentDate->format('Y-m-d')] = 0; // Initialize with 0
            $currentDate->modify('+1 day');
        }
        
        // Fill in actual data from database
        foreach ($assoc_datas as $row) {
            $dateRange[$row['date_in']] = (int)$row['number_of'];
        }
        
        // Prepare data points for line chart (including zeros)
        $dataPoints = [];
        foreach ($dateRange as $date => $count) {
            $dataPoints[] = [
                'x' => $date, // Date for x-axis
                'y' => $count // Number for y-axis (includes zeros)
            ];
        }
        
        $chartData = [
            'datasets' => [
                [
                    'label' => 'Number of Patients',
                    'data' => $dataPoints,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'pointRadius' => 5,
                    'pointHoverRadius' => 7,
                    'pointBackgroundColor' => 'rgba(54, 162, 235, 1)',
                    'pointBorderColor' => 'rgba(54, 162, 235, 1)'
                ]
            ]
        ];
        
        header('Content-Type: application/json');
        echo json_encode(['chartData' => $chartData], JSON_UNESCAPED_UNICODE);
    } else {
        // Table data response
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
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
