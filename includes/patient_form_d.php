<?php
$isBorder = false;
?>

<h4 align="center"><b>ผลการตรวจ(เพิ่มเติม)</b></h4> 


<div align=""  class="mb-3">
    <label for="p_rsu_diagnosis">ADDENDUM / REVISED</label><br>
    <textarea name="p_rsu_diagnosis" cols="100" rows="5" class="form-control" id="p_rs_diagnosis" <?= $modePageEditDisable || $isDisableEditResult ? " disabled readonly " : "" ?> >TBD</textarea>
</div>

<div class="row <?= $isBorder ? "border" : "" ?>">
    <div class="col-xl-6 col-md-6 <?= $isBorder ? "border" : "" ?> ">

        <label for="ppathologist_u_id" class="col-form-label">พยาธิแพทย์ผู้ออกผลเพิ่มเติม</label>

        <select name="ppathologist_u_id" class="form-select" <?= $modePageEditDisable || $isDisableEditResult ? " disabled readonly " : "" ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userPathos as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['id']); ?>" <?= $patient[0]['ppathologist_id'] == htmlspecialchars($user['id']) ? "selected" : ""; ?> > 
                    <?=
                    htmlspecialchars($user['name']);
                    echo ' ';
                    ?> <?= htmlspecialchars($user['lastname']); ?></option>
            <?php endforeach; ?>                                     
        </select> 
    </div>

    <div class="col-xl-6 col-md-6 <?= $isBorder ? "border" : "" ?> ">

        <label for="date_14000" class="form-label">รายงานผลเพิ่มเติมแล้วเมื่อวันที่</label>
        <input name="date_14000"  type="date" class="form-control" id="date_14000" <?= $modePageEditDisable || $isDisableEditResult ? " disabled readonly " : "" ?> value="<?= $patient[0]['date_14000']; ?>">

    </div>
</div>

