<?php
//กำหนด คน ตัดเนื้อ
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
<hr id="p_cross_section_id_hr">

<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_cross_section_id"  class="form-label">พนักงานตัดเนื้อ</label>

        <select name="p_cross_section_id" id="p_cross_section_id" class="form-select" <?= $isEditModePageOn && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userTechnic as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['uid']); ?>" <?= $patient[0]['p_cross_section_id'] == htmlspecialchars($user['uid']) ? "selected" : ""; ?> > 
                    <?=$user['name'] . ' ' . $user['lastname']?><?php if($user['uid']!=0 && $isCurUserAdmin):?> <?=' (' . $user['username'] . '::' . $user['ugroup'] . ')';  ?><?php endif; ?>
                </option>
            <?php endforeach; ?>                                     
        </select> 

    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_cross_section_ass_id"  class="form-label">พนักงานผู้ช่วยตัดเนื้อ</label>
        <select name="p_cross_section_ass_id" id="p_cross_section_ass_id" class="form-select" <?= $isEditModePageOn && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userTechnic as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['uid']); ?>" <?= $patient[0]['p_cross_section_ass_id'] == htmlspecialchars($user['uid']) ? "selected" : ""; ?> > 
                    <?=$user['name'] . ' ' . $user['lastname']?><?php if($user['uid']!=0 && $isCurUserAdmin):?> <?=' (' . $user['username'] . '::' . $user['ugroup'] . ')';  ?><?php endif; ?>
                </option>
            <?php endforeach; ?>                                     
        </select> 

    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="date_3000" class="">เตรียมชิ้นเนื้อแล้วแล้วเมื่อวันที่</label>
        <input name="date_3000" id="date_3000" class="form-control border" type="text"  placeholder="This Field will Auto Generate" <?= $isEditModePageOn && FALSE && ($userAuthEdit && $curStatusAuthEdit) ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_3000']; ?>">
    </div>

</div>

