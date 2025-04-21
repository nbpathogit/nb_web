<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';

$iniN = InternalNote::getInitObj();
        $iniN->patient_id=$_POST['patient_id'];
        $iniN->note=$_POST['note'];
        $iniN->creater=$_POST['creater'];
        $iniN->editer=$_POST['editer'];
        $iniN->edit_date=$_POST['edit_date'];
        $iniN->created_date=$_POST['created_date'];
        
        $result = $iniN->create($conn);
        

echo json_encode($result);


?>
