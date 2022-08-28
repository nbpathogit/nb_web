<?php
$isBorder = false;


$userAuthEdit = (
        $isCurUserAdmin 
    || $isCurUserPatho 
    || $isCurUserPathoAssis 
    || $isCurUserLabOfficerNB 
    || $isCurUserAdminStaff 
    //|| $isCurUserClinicianCust 
    //|| $isCurUserHospitalCust
        );

$curStatusAuthEdit = (
        $isCurStatus_1000 
    || $isCurStatus_2000 
    || $isCurStatus_3000 
    || $isCurStatus_6000 
    || $isCurStatus_8000
    || $isCurStatus_10000
    || $isCurStatus_12000
    || $isCurStatus_13000
    || $isCurStatus_20000
        );

if($debug){
    echo "$userAuthEdit and $curStatusAuthEdit";
    var_dump($userAuthEdit);
    var_dump($curStatusAuthEdit);
}
?>

<!-- Content here -->

<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="pnum" align="right" class="">เลขที่ผู้ป่วย</label>
        <input name="pnum" type="text" id="pnum" class="form-control" placeholder=""  <?= $isEditModePageOn && $isEditModePageForPatientInfoDataOn && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?>  value="<?= $patient[0]['pnum']; ?>">
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="pname" class="">ชื่อผู้ป่วย</label>
        <input name="pname" type="text" class="form-control border" id="pname" placeholder="" <?= $isEditModePageOn && $isEditModePageForPatientInfoDataOn && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> value="<?= $patient[0]['pname']; ?>">
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="plastname" class="">นามสกุล</label>
        <div class="col">
            <input name="plastname" type="text" class="form-control" id="plastname" placeholder="" <?= $isEditModePageOn && $isEditModePageForPatientInfoDataOn  && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> value="<?= $patient[0]['plastname']; ?>">
        </div>
    </div>
  

</div>





