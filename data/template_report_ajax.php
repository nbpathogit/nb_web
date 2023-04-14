<?php

require '../includes/init.php';

$auth = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . "/templateReport.php")) {

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



    $tps = TemplateReport::getTemplateByUser($conn);

    $data = [];
    foreach ($tps as $tp) {
        //              tid         rid         uid         uname         ulastname         rname         tname         tdescription	
        $data[] = [$tp['tid'], $tp['rid'], $tp['uid'], $tp['uname'], $tp['ulastname'], $tp['rname'], $tp['tname'], $tp['tdescription']];
    }
    $result = ["data" => $data];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
