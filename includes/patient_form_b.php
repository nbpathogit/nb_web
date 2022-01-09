<?php
$isBorder = false;
?>

<h4 align="center"><b>ข้อมูลโดยสถายัน เอ็น แอน บี</b></h4>


<div class="row <?= $isBorder ? "border" : "" ?>">
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for=""  class="form-label">สถานะ</label>
        <select name="status_id" class="form-select" <?= $isDisableEditNBCenter || $isStatusDisableEdit ? " disabled readonly " : "" ?>>
            <?php foreach ($statusLists as $status): ?>
                <option value="<?= $status['id']; ?>" <?= $patient[0]['status_id'] == $status['id'] ? "selected" : ""; ?>   ><?= htmlspecialchars($status['des']); ?></option>
            <?php endforeach; ?>
        </select>   
    </div>



    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_cross_section_id"  class="form-label">พนักงานตัดเนื้อ</label>

        <select name="p_cross_section_id" class="form-select" <?= $isDisableEditNBCenter ? " disabled readonly " : "" ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userTechnic as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['id']); ?>" <?= $patient[0]['p_cross_section_id'] == htmlspecialchars($user['id']) ? "selected" : ""; ?> > 
                    <?=
                    htmlspecialchars($user['name']);
                    echo ' ';
                    ?> <?= htmlspecialchars($user['lastname']); ?></option>
            <?php endforeach; ?>                                     
        </select> 

    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_cross_section_ass_id"  class="form-label">พนักงานผู้ช่วยตัดเนื้อ</label>
        <select name="p_cross_section_ass_id" class="form-select" <?= $isDisableEditNBCenter ? " disabled readonly " : "" ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userTechnic as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['id']); ?>" <?= $patient[0]['p_cross_section_ass_id'] == htmlspecialchars($user['id']) ? "selected" : ""; ?> > 
                    <?=
                    htmlspecialchars($user['name']);
                    echo ' ';
                    ?> <?= htmlspecialchars($user['lastname']); ?></option>
            <?php endforeach; ?>                                     
        </select> 

    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_slide_prep_id" class="form-label">พนักงานเตรียมสไลด์</label>
        <select name="p_slide_prep_id" class="form-select" <?= $isDisableEditNBCenter ? " disabled readonly " : "" ?> >
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
        <label for="" class="form-label">ราคาค่าตรวจ(บาท)</label>
        <input name="pprice" type="text" class="form-control" id="pprice"  <?= $isDisableEditNBCenter ? " disabled readonly " : "" ?> value="<?= $patient[0]['pprice']; ?>"   >
    </div>
</div>

<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_slide_prep_sp_id" class="form-label" >พนักงานเตรียมไลด์พิเศษ</label>

        <select name="p_slide_prep_sp_id" class="form-select" <?= $isDisableEditNBCenter ? " disabled readonly " : "" ?> >
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
        <input name="pspprice" type="text" class="form-control" id="pspprice"  <?= $isDisableEditNBCenter ? " disabled readonly " : "" ?>  value="<?= $patient[0]['pspprice']; ?>"  >
    </div>

</div>
