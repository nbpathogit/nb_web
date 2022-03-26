<?php
$isBorder = false;
?>

<h4 align="center"><b>ข้อมูลสิ่งส่งตรวจ</b></h4> 


<?php if ($isHideResult): ?>
<?php else: ?>

    <div align=""  class="mb-3">
        <label for="p_rs_specimen">SPECIMEN</label><br>
        <textarea name="p_rs_specimen" cols="100" rows="5" class="form-control" id="p_rs_specimen" <?= $canEditModePage && $canEditResult_c_group && $canEditResult_c_status ? "" : " disabled readonly " ?> ><?= $patient[0]['p_rs_specimen']; ?></textarea>
    </div>

    <div align=""  class="mb-3">
        <label for="p_rs_clinical_diag">CLINICAL DIAGNOSIS</label><br>
        <textarea name="p_rs_clinical_diag" cols="100" rows="5" class="form-control" id="p_rs_clinical_diag"  <?= $canEditModePage && $canEditResult_c_group && $canEditResult_c_status ? "" : " disabled readonly " ?>><?= $patient[0]['p_rs_clinical_diag']; ?></textarea>
    </div>

    <div align=""  class="mb-3">
        <label for="p_rs_gross_desc">GROSS DESCRIPTION</label><br>
        <textarea name="p_rs_gross_desc" cols="100" rows="5" class="form-control" id="p_rs_gross_desc" <?= $canEditModePage && $canEditResult_c_group && $canEditResult_c_status ? "" : " disabled readonly " ?>><?= $patient[0]['p_rs_gross_desc']; ?></textarea>
    </div>

    <div align=""  class="mb-3">
        <label for="p_rs_microscopic_desc">MICROSCOPIC DESCRIPTION </label><br>
        <textarea name="p_rs_microscopic_desc" cols="100"  rows="5" class="form-control" id="p_rs_microscopic_desc" <?= $canEditModePage && $canEditResult_c_group && $canEditResult_c_status ? "" : " disabled readonly " ?>><?= $patient[0]['p_rs_microscopic_desc']; ?></textarea>
    </div>

    <div align=""  class="mb-3">
        <label for="p_rs_diagnosis">DIAGNOSIS</label><br>
        <textarea name="p_rs_diagnosis" cols="100" rows="5" class="form-control" id="p_rs_diagnosis" <?= $canEditModePage && $canEditResult_c_group && $canEditResult_c_status ? "" : " disabled readonly " ?> ><?= $patient[0]['p_rs_diagnosis']; ?></textarea>
    </div>
<?php endif; ?>




<div class="row <?= $isBorder ? "border" : "" ?>">
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="ppathologist_id" class="col-form-label">พยาธิแพทย์ผู้ออกผล</label>
        <select name="ppathologist_id" class="form-select" <?= $canEditModePage && $canEditResult_c_group&& $canEditResult_c_status ? "" : " disabled readonly " ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userPathos as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['uid']); ?>" <?= $patient[0]['ppathologist_id'] == htmlspecialchars($user['uid']) ? "selected" : ""; ?> > 
                    <?=
                    htmlspecialchars($user['name']);
                    echo ' ';
                    ?> <?= htmlspecialchars($user['lastname']); ?></option>
            <?php endforeach; ?>                                     
        </select> 
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="ppathologist2_id" class="col-form-label">พยาธิแพทย์คอนเฟิร์มผล</label>
        <select name="ppathologist2_id" class="form-select" <?= $canEditModePage && $canEditResult_c_group&& $canEditResult_c_status ? "" : " disabled readonly " ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userPathos as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['uid']); ?>" <?= $patient[0]['ppathologist_id'] == htmlspecialchars($user['uid']) ? "selected" : ""; ?> > 
                    <?=
                    htmlspecialchars($user['name']);
                    echo ' ';
                    ?> <?= htmlspecialchars($user['lastname']); ?></option>
            <?php endforeach; ?>                                     
        </select> 
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">

        <label for="date_13000" class="form-label">รายงานผลแล้วเมื่อวันที่</label>
        <input name="date_13000"  type="text" class="form-control border" id="date_13000"  placeholder="This Field will Auto Generate"  <?= $canEditModePage&& $canEditResult_c_status && FALSE ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_13000']; ?>">

    </div>
</div>

