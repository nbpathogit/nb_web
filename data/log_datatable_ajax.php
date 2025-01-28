<?php

require '../includes/init.php';

$auth = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']  . Url::getSubFolder1(). "/log_login_logout_datatable.php")) {

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

    
    $assoc_datas = Log::getAllLogInLogOut($conn);
//. " `id`,`username`,`name`,`event`,`detail`,`event_date` "
    $data = [];
    foreach ($assoc_datas as $adata) {

        $data[] = [$adata['id'], $adata['username'], $adata['name'], $adata['event'], $adata['detail'], $adata['event_date'] ];
    }
    $result = ["data" => $data];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
