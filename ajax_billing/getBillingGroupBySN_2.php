<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['cur_patient_id']);


/* Fordebug  *  */
$_POST['hospital_id']=9;
$_POST['startdate']='2026-01-19 00:00:00';
$_POST['enddate']='2026-01-19 00:00:00';


//p_sn	p_hn	p_admit_date	patient_name	clinicien_name	b_description_concat_nm	b_description_concat_sp	b_description_concat_all	b_cost_sum_nm	b_cost_sum_sp	b_cost_sum_all 
$billings = ServiceBilling::getBillbyHospitalbyDateRangeGroupBySN_2($conn,$_POST['hospital_id'],$_POST['startdate'],$_POST['enddate']);
/*
Output Example
p_sn    1  p_hn      p_admit_date  patient_name    clinicien_name    b_description_concat_nm    b_description_concat_sp    b_description_concat_all    b_cost_sum_nm    b_cost_sum_sp    b_cost_sum_all
CN2600002    404996    1/5/2026    นางเอ บี         Fluid cytology        Fluid cytology /    500    0    500
CN2600003    860489    1/5/2026    นางซี ดี          Fluid cytology        Fluid cytology /    500    0    500
CN2600004    757000    1/5/2026    พระอี เอฟ        Fluid cytology        Fluid cytology /    500    0    500
CN2600005    80158     1/5/2026    นางจี เฮช         Fluid cytology        Fluid cytology /    500    0    500
CN2600006    64190     1/5/2026    นางไอ เจ         Fluid cytology / cell block    CK7 / CK20 / HepPar1 / Glypican-3 / CK19    Fluid cytology / cell block / CK7 / CK20 / HepPar1...    1000    4000    5000
CN2600008    286232    1/6/2026    นายเค แอล        Fluid cytology        Fluid cytology /    500    0    500
 *          */

foreach ($billings as $key => $billing) {
    $aa=ServiceBilling::getBillbyHospitalbyDateRangeGroupBySN_2_subarray_nm($conn,$_POST['hospital_id'],$_POST['startdate'],$_POST['enddate'],$billings[$key]['p_sn']);
    $bb=ServiceBilling::getBillbyHospitalbyDateRangeGroupBySN_2_subarray_sp($conn,$_POST['hospital_id'],$_POST['startdate'],$_POST['enddate'],$billings[$key]['p_sn']);
//    $billing[]= $b;
    $aabb = array_merge($aa, $bb);
    $billings[$key]['subarray']= $aabb;
//    var_dump($billing);
//    var_dump($billings[$key]);
    
}

var_dump($billings);


//echo json_encode($billings);


?>

