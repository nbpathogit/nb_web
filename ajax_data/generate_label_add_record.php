<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require '../includes/init.php';
$conn = require '../includes/db.php';

return LabelPrint::createByLoopNum($conn,$_POST['userid'],$_POST['sn_num'],$_POST['hn_num'],$_POST['patho_abbrev'],$_POST['accept_date'],$_POST['company_name'],$_POST['letter'],$_POST['start_num'],$_POST['end_num']);