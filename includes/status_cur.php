<?php

//Prepare Status
//$curstatus = Status::getAll($conn, $patient[0]['status_id']);

//เช็คสถานะปัจจุบัน
//
$isCurStatus_1000 = $curstatus[0]['id'] == 1000;  //รับเข้า
$isCurStatus_2000 = $curstatus[0]['id'] == 2000;  //วางแผนงาน
$isCurStatus_3000 = $curstatus[0]['id'] == 3000; //เตรียมชิ้นเนื้อ
$isCurStatus_6000 = $curstatus[0]['id'] == 6000; //เตรียมสไลด์
$isCurStatus_10000 = $curstatus[0]['id'] == 10000; //แลปเซลวิทยา
$isCurStatus_12000 = $curstatus[0]['id'] == 12000; //วินจฉัย
$isCurStatus_13000 = $curstatus[0]['id'] == 13000; //วินจฉัยคอนเฟิร์ม
$isCurStatus_20000 = $curstatus[0]['id'] == 20000; //ออกผล