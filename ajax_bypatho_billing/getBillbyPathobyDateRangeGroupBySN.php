<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['cur_patient_id']);

//p_sn	p_hn	p_admit_date	patient_name	clinicien_name	b_description_concat_nm	b_description_concat_sp	b_description_concat_all	b_cost_sum_nm	b_cost_sum_sp	b_cost_sum_all 
$billings = ServiceBilling::getBillbyPathobyDateRangeGroupBySN($conn,$_POST['patho_id'],$_POST['startdate'],$_POST['enddate']);
echo json_encode($billings);


?>

