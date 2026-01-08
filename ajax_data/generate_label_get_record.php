<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['cur_patient_id']);

//{"id":"195","userid":"2","sn_num":"CN2501854","hn_num":"","patho_abbreviation":"AC.","speciment_abbreviation":"B1","accept_date":"31\/12\/2025","company_name":"N.B.Pathology","create_date":null},
$datas = LabelPrint::getAllbyUserID($conn, $_POST['user_id']);
echo json_encode($datas);


?>