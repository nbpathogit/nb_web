<?php
//กำหนด คนเตรียมสไล์ด์
$isBorder = false;
?>
<hr id="p_slide_prep_id_hr">

<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_slide_prep_id" class="form-label">พนักงานเตรียมสไลด์</label>
        <select name="p_slide_prep_id" id="p_slide_prep_id" class="form-select" <?= $canEditModePage  && $canEditPlaning_b_2_status && $canEditPlaning_b_2_group ? "" : " disabled readonly " ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userTechnic as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['id']); ?>" <?= $patient[0]['p_slide_prep_id'] == htmlspecialchars($user['id']) ? "selected" : ""; ?> > 
                    <?=
                    htmlspecialchars($user['name']);
                    echo ' ';
                    ?> <?= htmlspecialchars($user['lastname']); ?></option>
            <?php endforeach; ?>                                     
        </select> 

    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="pprice" class="form-label">ราคาค่าตรวจ(บาท)</label>
        <input name="pprice" id="pprice" type="text" class="form-control"   <?= $canEditModePage  && $canEditPlaning_b_2_status && $canEditPlaning_b_2_group ? "" : " disabled readonly " ?> value="<?= $patient[0]['pprice']; ?>"   >
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="date_6000" class="">เตรียมสไลด์แล้วเมื่อวันที่</label>
        <input name="date_6000" id="date_6000" class="form-control border" type="text"  placeholder="This Field will Auto Generate" <?= $canEditModePage && $canEditPlaning_b_2_status && $canEditPlaning_b_2_group && FALSE ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_6000']; ?>">
    </div>

</div>
