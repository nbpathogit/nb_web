<?php
//เตรียมสไลด์พิเศษ 12000
$isBorder = false;

?>
<hr id="p_slide_prep_sp_id_hr">
  <input type="checkbox" id="sp_slide_owner" name="sp_slide_owner" value="" <?= $isEditModePageOn && $isEditModePageForPlaningDataOn && ($userAuthEdit && $curStatusAuthEdit) ? "" : " disabled readonly " ?>  >
  
  <label for="sp_slide_owner">Select Spcial slide owner.</label><br>
<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_slide_prep_sp_id"  class="form-label" >พนักงานเตรียมไลด์พิเศษ</label>

        <select name="p_slide_prep_sp_id" id="p_slide_prep_sp_id"  class="form-select" <?= FALSE && $isEditModePageOn && $isEditModePageForPlaningDataOn && ($userAuthEdit && $curStatusAuthEdit) ? "" : " disabled readonly " ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userTechnic as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['uid']); ?>" <?= $patient[0]['p_slide_prep_sp_id'] == htmlspecialchars($user['uid']) ? "selected" : ""; ?> > 
                    <?=$user['name'] . ' ' . $user['lastname']?><?php if($user['uid']!=0 && $isCurUserAdmin):?> <?=' (' . $user['username'] . '::' . $user['ugroup'] . ')';  ?><?php endif; ?>
                </option>
            <?php endforeach; ?>
        </select> 

    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="pspprice" class="form-label">ราคาค่าตรวจพิเศษ(บาท)</label>
        <input name="pspprice" id="pspprice" type="text" class="form-control"   <?= FALSE && $isEditModePageOn && $isEditModePageForPlaningDataOn && ($userAuthEdit && $curStatusAuthEdit)  ? "" : " disabled  " ?>  value="<?= $patient[0]['pspprice']; ?>"  >
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="date_8000" class="">เตรียมสไลด์พิเศษแล้วเมื่อวันที่</label>
        <input name="date_8000" id="date_8000" class="form-control border" type="text" class=""  placeholder="This Field will Auto Generate" <?= $isEditModePageOn && $isEditModePageForPlaningDataOn && FALSE && ($userAuthEdit && $curStatusAuthEdit) ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_8000']; ?>">
    </div>

</div>

