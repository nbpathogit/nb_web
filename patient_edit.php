<?php
//var_dump($_GET);

require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {
    $patients = Patient::getAll($conn,$_GET['id']);
} else {
    $patients = null;
}


//$patients = Patient::getAll($conn);
$hospitals = Hospital::getAll($conn);
$specimens = Specimen::getAll($conn);
$clinicians = User::getAllbyClinicians($conn);
$userPathos = User::getAllbyPathologis($conn);
$prioritys = Priority::getAll($conn);

//var_dump($patients);
//var_dump($prioritys);

?>

<?php require 'includes/header.php'; ?>

<div align="center"> Patient Edit </div><br>
<table border="1" align="center" width="1000">
    <tr>
        <td>       
            <form id="" name="" method="post" >
                <?php require 'includes/patient_form_a.php'; ?>
                <?php require 'includes/patient_form_b.php'; ?>
                <p align="center">
                    <button>แก้ไข</button>
                    <!--<input name="Submit" type="submit" class="" id="Submit" value="เพิ่ม">-->
                    <!--<input name="Submit2" type="reset" class="" id="Submit2" value="ยกเลิก">-->
                </p>
            </form>
        </td>
    </tr>
</table>

<?php require 'includes/footer.php'; ?>
