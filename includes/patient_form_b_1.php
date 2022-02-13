<?php
//กำหนด คน ตัดเนื้อ
$isBorder = false;
?>

<h4 align="center"><b>กำหนดทีมวินิจฉัย โดยสถายัน เอ็น แอน บี</b></h4>


<div class="row <?= $isBorder ? "border" : "" ?>">
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="" class="">กำหนดทีมตรวจวินิจฉัยแล้วเมื่อวันที่</label>
        <input name="date_2000" class="form-control border" type="text" class="" id="date_3000" placeholder="This Field will Auto Generate" <?= $canEditModePage && $canEditPlaning_b_1_status && FALSE ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_2000']; ?>">
    </div>
</div>

<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_cross_section_id"  class="form-label">พนักงานตัดเนื้อ</label>

        <select name="p_cross_section_id" class="form-select" <?= $canEditModePage && $canEditPlaning_b_1_status ? "" : " disabled readonly " ?> >
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
        <select name="p_cross_section_ass_id" class="form-select" <?= $canEditModePage && $canEditPlaning_b_1_status ? "" : " disabled readonly " ?> >
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

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="" class="">เตรียมชิ้นเนื้อแล้วแล้วเมื่อวันที่</label>
        <input name="date_3000" class="form-control border" type="text" class="" id="date_3000" placeholder="This Field will Auto Generate" <?= $canEditModePage && $canEditPlaning_b_1_status && FALSE ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_3000']; ?>">
    </div>

</div>

