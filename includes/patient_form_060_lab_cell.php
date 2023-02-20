<?php
//ใส่ค่าแลปเซลวิทยา แลปน้ำ 
$isBorder = false;

?>
<hr id="p_slide_lab_id_hr">

<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_slide_lab_id" class="form-label" >แลปเซลด์วิทยา(To be remove)</label>
        <select name="p_slide_lab_id" id="p_slide_lab_id" class="form-select" <?= $isEditModePageOn && $isEditModePageForPlaningDataOn && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($labFluids as $labFluid): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($labFluid['id']); ?>" <?= $patient[0]['p_slide_lab_id'] == $labFluid['id'] ? "selected" : ""; ?> > 
                    <?=    $labFluid['labname'] ;?> </option>
            <?php endforeach; ?>                                     
        </select> 

    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_slide_lab_price" class="form-label">ราคาค่าตรวจ(บาท)(To be remove)</label>
        <input name="p_slide_lab_price" id="p_slide_lab_price" type="text" class="form-control"   disabled readonly  value="<?= $patient[0]['p_slide_lab_price']; ?>"  >
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="date_10000" class="">เตรียมสไลด์น้ำแล้วเมื่อวันที่(To be remove)</label>
        <input name="date_10000"  id="date_10000"  class="form-control border" type="text" placeholder="This Field will Auto Generate" <?= $isEditModePageOn && $isEditModePageForPlaningDataOn && FALSE ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_10000']; ?>">
    </div>

</div>

