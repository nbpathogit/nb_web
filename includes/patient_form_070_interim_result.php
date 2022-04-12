<?php
$isBorder = false;


$userAuthEdit = (
        $isCurUserAdmin 
    || $isCurUserPatho 
    //|| $isCurUserPathoAssis 
    //|| $isCurUserLabOfficerNB 
    //|| $isCurUserAdminStaff 
    //|| $isCurUserClinicianCust 
    //|| $isCurUserHospitalCust
        );

$curStatusAuthEdit = (
    //    $isCurStatus_1000 
    //     $isCurStatus_2000 
     $isCurStatus_3000 
    || $isCurStatus_6000 
    || $isCurStatus_10000
    || $isCurStatus_12000
    //|| $isCurStatus_13000
    //|| $isCurStatus_20000
        );

//  && ($userAuthEdit && $curStatusAuthEdit)


?>
<hr noshade="noshade" width="" size="5" >
<h4 align="center"><b>ข้อมูลสิ่งส่งตรวจ</b></h4> 


<?php if ($isHideResult): ?>
<?php else: ?>


    <?php if ( $isPathoOwneThisrCase): ?>
        <p align="center">คุณคือออกผลของผู้ป่วยท่านนี้</p>
    <?php else: ?> 
        <p align="center">คุณไม่ไช่ผู้ออกผลของผู้ป่วยท่านนี้ คุณสามารถดูข้อมูลได้เท่านั้น</p>
    <?php endif; ?>

    <div align=""  class="mb-3">
        <label for="p_rs_specimen">SPECIMEN</label><br>

        <textarea name="p_rs_specimen" cols="100" rows="5" class="form-control" id="p_rs_specimen" <?= $isEditModePageOn && ($isCurUserAdmin || $isPathoOwneThisrCase || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> ><?= htmlspecialchars($patient[0]['p_rs_specimen']); ?></textarea>
    </div>

    <div align=""  class="mb-3">
        <label for="p_rs_clinical_diag">CLINICAL DIAGNOSIS</label><br>
        <textarea name="p_rs_clinical_diag" cols="100" rows="5" class="form-control" id="p_rs_clinical_diag"  <?= $isEditModePageOn && ($isCurUserAdmin || $isPathoOwneThisrCase|| ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?>  ><?= htmlspecialchars($patient[0]['p_rs_clinical_diag']); ?></textarea>
    </div>

    <div align=""  class="mb-3">
        <label for="p_rs_gross_desc">GROSS DESCRIPTION</label><br>
        <textarea name="p_rs_gross_desc" cols="100" rows="5" class="form-control" id="p_rs_gross_desc" <?= $isEditModePageOn && ($isCurUserAdmin || $isPathoOwneThisrCase || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?>  ><?= htmlspecialchars($patient[0]['p_rs_gross_desc']); ?></textarea>
    </div>

    <div align=""  class="mb-3">
        <label for="p_rs_microscopic_desc">MICROSCOPIC DESCRIPTION </label><br>
        <textarea name="p_rs_microscopic_desc" cols="100"  rows="5" class="form-control" id="p_rs_microscopic_desc" <?= $isEditModePageOn && ($isCurUserAdmin || $isPathoOwneThisrCase || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?>  ><?= htmlspecialchars($patient[0]['p_rs_microscopic_desc']); ?></textarea>
    </div>

<?php endif; ?>

