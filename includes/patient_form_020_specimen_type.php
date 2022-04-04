<?php
//เลือกชนิดชิ้นเนื้อ
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
    //    $isCurStatus_1000 
     $isCurStatus_2000 
    //|| $isCurStatus_3000 
    //|| $isCurStatus_6000 
    //|| $isCurStatus_10000
    //|| $isCurStatus_12000
    //|| $isCurStatus_13000
    //|| $isCurStatus_20000
        );
?>

<hr noshade="noshade" width="" size="5" >
<h4 align="center"><b>วางแผนงานวินิจฉัย โดยสถายันเอ็นแอนบี</b></h4>

<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">

        <fieldset>
            <legend>เลือกชนิดสิ่งส่งตรวจ</legend>
            <input type="radio" name="p_speciment_type" id="lumptype" value="lump"   <?= $patient[0]['p_speciment_type'] == "lump"  ? "checked" : ""; ?> <?= $isEditModePageOn && ($userAuthEdit) && ($curStatusAuthEdit) ? "" : " disabled readonly " ?>  >ชิ้นเนื้อ&nbsp;
            <input type="radio" name="p_speciment_type" id="fluidtype" value="fluid" <?= $patient[0]['p_speciment_type'] == "fluid" ? "checked" : ""; ?> <?= $isEditModePageOn && ($userAuthEdit) && ($curStatusAuthEdit) ? "" : " disabled readonly " ?>  >เซลวิทยา
        </fieldset>

    </div>  

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="date_2000" class="">กำหนดทีมตรวจวินิจฉัยแล้วเมื่อวันที่</label>
        <input name="date_2000" id="date_2000" class="form-control border" type="text" placeholder="This Field will Auto Generate" <?= $isEditModePageOn && ($userAuthEdit) && ($curStatusAuthEdit) && FALSE ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_2000']; ?>">
    </div>


</div>

