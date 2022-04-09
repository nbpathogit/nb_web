<?php
//กำหนด คนเตรียมสไล์ด์
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
<hr id="p_slide_prep_id_hr">

<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_slide_prep_id" class="form-label">พนักงานเตรียมสไลด์</label>
        <select name="p_slide_prep_id" id="p_slide_prep_id" class="form-select" <?= $isEditModePageOn && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userTechnic as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['uid']); ?>" <?= $patient[0]['p_slide_prep_id'] == htmlspecialchars($user['uid']) ? "selected" : ""; ?> > 
                    <?=$user['name'] . ' ' . $user['lastname']?><?php if($user['uid']!=0):?> <?=' (' . $user['username'] . '::' . $user['ugroup'] . ')';  ?><?php endif; ?>
                </option>
            <?php endforeach; ?>                                     
        </select> 

    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="pprice" class="form-label">ราคาค่าตรวจ(บาท)</label>
        <input name="pprice" id="pprice" type="text" class="form-control"   <?= $isEditModePageOn && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> value="<?= $patient[0]['pprice']; ?>"   >
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="date_6000" class="">เตรียมสไลด์แล้วเมื่อวันที่</label>
        <input name="date_6000" id="date_6000" class="form-control border" type="text"  placeholder="This Field will Auto Generate" <?= $isEditModePageOn && FALSE ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_6000']; ?>">
    </div>

</div>
