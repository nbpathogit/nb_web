<div class="row mb-3 g-3">

    <h4 class="mb-1">
        <song class="">ผลการตรวจ</song>
    </h4>

    <div class="col-auto">
        <label class="form-label" for="p_rs_specimen">SPECIMEN</label>
        <textarea class="form-control" name="p_rs_specimen" cols="100" rows="5" id="p_rs_specimen"></textarea>
    </div>
</div>

    <div class="col-auto">
        <label class="form-label" for="p_rs_clinical_diag">CLINICAL DIAGNOSIS</label>
        <textarea name="p_rs_clinical_diag" cols="100" rows="5" class="form-control" id="p_rs_clinical_diag"></textarea>

    </div>
</div>

    <div class="col-auto">
        <label class="form-label" for="p_rs_gross_desc">GROSS DESCRIPTION</label>
        <textarea name="p_rs_gross_desc" cols="100" rows="5" class="form-control" id="p_rs_gross_desc"></textarea>
    </div>
</div>


<div class="row mb-3">
    <label for="p_slide_prep_sp_id" class="col-sm-2 col-form-label">พนักงานเตรียมไลด์พิเศษ</label>
    <div class="col-sm-10">
        <select name="p_slide_prep_sp_id" class="form-select" <?= $isDisableEditNBCenter ? " disabled readonly " : ""?> >
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


    <div class="col-auto">
        <label class="form-label" for="p_rs_microscopic_desc">MICROSCOPIC DESCRIPTION </label>
        <textarea name="p_rs_microscopic_desc" cols="100" rows="5" class="form-control" id="p_rs_microscopic_desc"></textarea>
    </div>

    <div class="col-auto">
        <label class="form-label" for="p_rs_diagnosis">DIAGNOSIS</label>
        <textarea name="p_rs_diagnosis" cols="100" rows="5" class="form-control" id="p_rs_diagnosis"></textarea>
    </div>

</div>
