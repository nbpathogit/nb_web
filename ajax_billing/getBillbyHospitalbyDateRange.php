<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['cur_patient_id']);

//$jobs = Job::getByPatientJobRole($conn, (int) $_POST['cur_patient_id'],6);
$billings = ServiceBilling::getBillbyHospitalbyDateRange($conn,$_GET['hospital_id'],$_GET['startdate'],$_GET['enddate']);


//0     1        2       3           4        
//hid   bid      pid     job_id   p_sn
//21    157      410     771      CN2400001

//  5                  6               7        
//  patient_name       admit_date  hospital_num 
//  นางเอเอ บีบี          1/1/2024    117865                         

// 8               9                10               11
// clinicien_name  h_hospital       cytologist_name  pathologist_name
// เอเอ บีบี          โรงพยาบาลหล่มสัก   ชื่อนักเซลล์          อภิชาติ ชุมทอง                                 

//12        13                     14
//b_code    b_description          b_cost
//  38301   Non-Gynecological ผ     500
//
//15
//s_service_type
//ตรวจพิเศา

$data = [];
foreach ($billings as $b) {

    $data[] = 
        [
        $b['hid']
        ,$b['bid']
        ,$b['pid']
        ,$b['job_id']
        ,$b['p_sn']
        ,$b['patient_name']
        ,$b['admit_date']
        ,$b['b_service_date']
        ,$b['hospital_num']
        ,$b['clinicien_name']
        ,$b['h_hospital']
        ,$b['cytologist_name']
        ,$b['pathologist_name']
        ,$b['b_code']
        ,$b['b_code2']
        ,$b['b_description']
        ,$b['b_cost']
        ,$b['s_service_type']
        ,$b['s_service_typea_bill']
        ];

}


$result = ["data" => $data];
echo json_encode($result, JSON_UNESCAPED_UNICODE);


?>

