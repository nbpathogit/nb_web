<?php

//can view by user group

$u_group = Auth::getUserGroup();
//user group id
// 1000 ผู้ดูแลระบบ
// 2000 พยาธิ์แพทย์
// 2100 ผู้ช่วยพยาธิแพทย์
// 2200 เจ้าหน้าที่แลป
// 2500 เจ้าหน้าที่ธุรการ
// 2600 เจ้าหน้าที่การตลาด
// 2700 เจ้าหน้าที่งานคุณภาพ
// 5000 แพทย์ผู้ส่งตรวจ
// 5100 เจ้าหน้าที่แลป(ลูกค้า)
//                                       ผู้ดูแลระบบ                 พยาธิ์แพทย์                ผู้ช่วยพยาธิแพทย์            เจ้าหน้าที่แลป               เจ้าหน้าที่ธุรการ             แพทย์ผู้ส่งตรวจ              เจ้าหน้าที่แลป(ลูกค้า)
$canViewPatientInfo_a_group = false   || $u_group->id==1000 || $u_group->id==2000 || $u_group->id==2100 || $u_group->id==2200 || $u_group->id==2500 || $u_group->id==5000 || $u_group->id==5100;
$canViewPlaning_b_1_group   = false   || $u_group->id==1000 || $u_group->id==2000 || $u_group->id==2100 || $u_group->id==2200 || $u_group->id==2500 || $u_group->id==5000 || $u_group->id==5100;
$canViewPlaning_b_2_group   = false   || $u_group->id==1000 || $u_group->id==2000 || $u_group->id==2100 || $u_group->id==2200 || $u_group->id==2500 || $u_group->id==5000 || $u_group->id==5100;
$canViewPlaning_b_3_group   = false   || $u_group->id==1000 || $u_group->id==2000 || $u_group->id==2100 || $u_group->id==2200 || $u_group->id==2500 || $u_group->id==5000 || $u_group->id==5100;
$canViewResult_c_group      = false   || $u_group->id==1000 || $u_group->id==2000 || $u_group->id==2100 || $u_group->id==2200 || $u_group->id==2500 || $u_group->id==5000 || $u_group->id==5100;
$canViewResult_d_group      = false   || $u_group->id==1000 || $u_group->id==2000 || $u_group->id==2100 || $u_group->id==2200 || $u_group->id==2500 || $u_group->id==5000 || $u_group->id==5100;

// Editable by user group
//                                       ผู้ดูแลระบบ                 พยาธิ์แพทย์                ผู้ช่วยพยาธิแพทย์            เจ้าหน้าที่แลป               เจ้าหน้าที่ธุรการ             แพทย์ผู้ส่งตรวจ              เจ้าหน้าที่แลป(ลูกค้า)

$canEditPatientInfo_a_group = false   || $u_group->id==1000 || $u_group->id==2000 || $u_group->id==2100 || $u_group->id==2200 || $u_group->id==2500 || $u_group->id==""   || $u_group->id=="";
$canEditPlaning_b_1_group   = false   || $u_group->id==1000 || $u_group->id==2000 || $u_group->id==2100 || $u_group->id==2200 || $u_group->id==2500 || $u_group->id==""   || $u_group->id=="";
$canEditPlaning_b_2_group   = false   || $u_group->id==1000 || $u_group->id==2000 || $u_group->id==2100 || $u_group->id==2200 || $u_group->id==2500 || $u_group->id==""   || $u_group->id=="";
$canEditPlaning_b_3_group   = false   || $u_group->id==1000 || $u_group->id==2000 || $u_group->id==2100 || $u_group->id==2200 || $u_group->id==2500 || $u_group->id==""   || $u_group->id=="";
$canEditResult_c_group      = false   || $u_group->id==1000 || $u_group->id==2000 || $u_group->id==""   || $u_group->id==""   || $u_group->id==""   || $u_group->id==""   || $u_group->id=="";
$canEditResult_d_group      = false   || $u_group->id==1000 || $u_group->id==2000 || $u_group->id==""   || $u_group->id==""   || $u_group->id==""   || $u_group->id==""   || $u_group->id=="";



//Editable by status
//$canEditPatientInfo_a_status = !Status::is_disable_patient_detail($patient[0]['status_id']) ;
$canEditPatientInfo_a_status = false || $u_group->id==1000   || ($patient[0]['status_id'] == 1000);
$canEditPlaning_b_1_status   = false || $u_group->id==1000   || ($patient[0]['status_id'] == 2000);
$canEditPlaning_b_2_status   = false || $u_group->id==1000   || ($patient[0]['status_id'] == 2000);
$canEditPlaning_b_3_status   = false || $u_group->id==1000   || ($patient[0]['status_id'] == 2000   || ($patient[0]['status_id'] == 12000));
$canEditResult_c_status      = false || $u_group->id==1000   || ($patient[0]['status_id'] == 12000) || ($patient[0]['status_id'] == 13000);
$canEditResult_d_status      = false || $u_group->id==1000   || ($patient[0]['status_id'] == 12000);


//disable by field
$isHideResult = false;
$isStatusDisableEdit = true;
$isDisableSpecialSlide = false;

//Other
$isUpdateResultAval = true;  //Get from database