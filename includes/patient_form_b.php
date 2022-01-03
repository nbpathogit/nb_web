<h4 align="center"><b>ข้อมูลโดยสถายัน เอ็น แอน บี</b></h4>

<div class="row mb-3"> 

    <label for="plabnum" class="col-sm-2 col-form-label">LAB Number</label>
    <div class="col-sm-10">
        <input name="plabnum" type="text" class="form-control" id="plabnum" <?= $isDisableEditNBCenter ? " disabled readonly " : ""?> value="<?= $patients[0]['plabnum']; ?>">
    </div>
</div>

<div class="row mb-3">
    <label for="" class="col-sm-2 col-form-label">สถานะ</label>
    <div class="col-sm-10">
         <select name="status_id" class="form-select" <?= $isDisableEditNBCenter ? " disabled readonly " : ""?>>
            <?php foreach ($statusLists as $status): ?>
                <option value="<?= $status['id']; ?>" <?= $patients[0]['status_id'] == $status['id'] ? "selected" : ""; ?>   ><?= htmlspecialchars($status['des']); ?></option>
            <?php endforeach; ?>
        </select>   
    </div>
</div>

<div class="row mb-3">
    <label for="ppathologist_id" class="col-sm-2 col-form-label">พยาธิแพทย์</label>
    <div class="col-sm-10">
        <select name="ppathologist_id" class="form-select" <?= $isDisableEditNBCenter ? " disabled readonly " : ""?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userPathos as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['id']); ?>" <?= $patients[0]['ppathologist_id'] == htmlspecialchars($user['id']) ? "selected" : ""; ?> > 
                    <?=
                    htmlspecialchars($user['name']);
                    echo ' ';
                    ?> <?= htmlspecialchars($user['lastname']); ?></option>
            <?php endforeach; ?>                                     
        </select> 
    </div>
</div>

<div class="row mb-3">
    <label for="ppathologist_id" class="col-sm-2 col-form-label">พนักงานตัดเนื้อ</label>
    <div class="col-sm-10">
        <select name="ppathologist_id" class="form-select" <?= $isDisableEditNBCenter ? " disabled readonly " : ""?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userTechnic as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['id']); ?>" <?= $patients[0]['p_cross_section_id'] == htmlspecialchars($user['id']) ? "selected" : ""; ?> > 
                    <?=
                    htmlspecialchars($user['name']);
                    echo ' ';
                    ?> <?= htmlspecialchars($user['lastname']); ?></option>
            <?php endforeach; ?>                                     
        </select> 
    </div>
</div>

<div class="row mb-3">
    <label for="ppathologist_id" class="col-sm-2 col-form-label">พนักงานผู้ช่วยตัดเนื้อ</label>
    <div class="col-sm-10">
        <select name="ppathologist_id" class="form-select" <?= $isDisableEditNBCenter ? " disabled readonly " : ""?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userTechnic as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['id']); ?>" <?= $patients[0]['p_cross_section_ass_id'] == htmlspecialchars($user['id']) ? "selected" : ""; ?> > 
                    <?=
                    htmlspecialchars($user['name']);
                    echo ' ';
                    ?> <?= htmlspecialchars($user['lastname']); ?></option>
            <?php endforeach; ?>                                     
        </select> 
    </div>
</div>

<div class="row mb-3">
    <label for="ppathologist_id" class="col-sm-2 col-form-label">พนักงานเตรียมสไลด์</label>
    <div class="col-sm-10">
        <select name="ppathologist_id" class="form-select" <?= $isDisableEditNBCenter ? " disabled readonly " : ""?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userTechnic as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['id']); ?>" <?= $patients[0]['p_slide_prep_id'] == htmlspecialchars($user['id']) ? "selected" : ""; ?> > 
                    <?=
                    htmlspecialchars($user['name']);
                    echo ' ';
                    ?> <?= htmlspecialchars($user['lastname']); ?></option>
            <?php endforeach; ?>                                     
        </select> 
    </div>
</div>


<div class="row mb-3">
    <label for="ppathologist_id" class="col-sm-2 col-form-label">พนักงานเตรียมไลด์พิเศษ</label>
    <div class="col-sm-10">
        <select name="ppathologist_id" class="form-select" <?= $isDisableEditNBCenter ? " disabled readonly " : ""?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userTechnic as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['id']); ?>" <?= $patients[0]['p_slide_prep_sp_id'] == htmlspecialchars($user['id']) ? "selected" : ""; ?> > 
                    <?=
                    htmlspecialchars($user['name']);
                    echo ' ';
                    ?> <?= htmlspecialchars($user['lastname']); ?></option>
            <?php endforeach; ?>                                     
        </select> 
    </div>
</div>



<div align="" class="row mb-3">

    <label for="" class="col-sm-2 col-form-label">ราคาค่าตรวจ(บาท)</label>
    <div class="col-sm-10">
        <input name="pprice" type="text" class="form-control" id="pprice"  <?= $isDisableEditNBCenter ? " disabled readonly " : ""?> value="<?= $patients[0]['pprice']; ?>"   >
    </div>

    </div>


<div class="row mb-3">
    <label for="pspprice" class="col-sm-2 col-form-label">ราคาค่าตรวจพิเศษ(บาท)</label>
    <div class="col-sm-10">
        <input name="pspprice" type="text" class="form-control" id="pspprice"  <?= $isDisableEditNBCenter ? " disabled readonly " : ""?>  value="<?= $patients[0]['pspprice']; ?>"  >

</div>
</div>

<div class="row mb-3">

    <label for="report_date" class="col-sm-2 col-form-label">วันที่รายงานผล</label>
    <div class="col-sm-10">
        <input name="report_date" type="text" class="form-control" id="report_date" <?= $isDisableEditNBCenter ? " disabled readonly " : ""?> value="<?= $patients[0]['report_date']; ?>">
    </div>
</div>
