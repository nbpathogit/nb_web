<?php
$isBorder = false;
?>

<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_slide_prep_sp_id" class="form-label" >พนักงานเตรียมไลด์พิเศษ</label>

        <select name="p_slide_prep_sp_id" class="form-select" <?= $modePageEditDisable || $isDisableEditNBCenter || $isDisableSpecialSlide ? " disabled readonly " : "" ?> >
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
        <label for="pspprice" class="form-label">ราคาค่าตรวจพิเศษ(บาท)</label>
        <input name="pspprice" type="text" class="form-control" id="pspprice"  <?= $modePageEditDisable || $isDisableEditNBCenter || $isDisableSpecialSlide  ? " disabled readonly " : "" ?>  value="<?= $patient[0]['pspprice']; ?>"  >
    </div>

</div>

<hr>