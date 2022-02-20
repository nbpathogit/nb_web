<?php
$isBorder = false;
?>
<hr id="p_slide_lab_id_hr">



<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_slide_lab_id" class="form-label" >แลปเตรียมสไลด์จากของเหลว</label>
        <select name="p_slide_lab_id" id="p_slide_lab_id" class="form-select" <?= $canEditModePage && $canEditPlaning_b_3_group  && $canEditPlaning_b_3_status ? "" : " disabled readonly " ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userTechnic as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['id']); ?>" <?= $patient[0]['p_slide_prep_sp_id'] == htmlspecialchars($user['id']) ? "selected" : ""; ?> > 
                    <?=
                    htmlspecialchars($user['name']);
                    echo ' ';
                    ?> <?= htmlspecialchars($user['lastname']); ?></option>
            <?php endforeach; ?>                                     
        </select> 

    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="plabprice" class="form-label">ราคาค่าตรวจ(บาท)</label>
        <input name="plabprice" id="plabprice" type="text" class="form-control"   <?= $canEditModePage && $canEditPlaning_b_3_group && $canEditPlaning_b_3_status  ? "" : " disabled readonly " ?>  value="<?= $patient[0]['pspprice']; ?>"  >
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="date_10000" class="">เตรียมสไลด์แล้วเมื่อวันที่</label>
        <input name="date_10000"  id="date_10000"  class="form-control border" type="text" placeholder="This Field will Auto Generate" <?= $canEditModePage && $canEditPlaning_b_3_status && FALSE ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_10000']; ?>">
    </div>

</div>

