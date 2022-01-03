<h4 align="center"><b>ผลการตรวจ</b></h4> 

<div align=""  class="mb-3">
    <label for="p_rs_specimen">SPECIMEN</label><br>
    <textarea name="p_rs_specimen" cols="100" rows="5" class="form-control" id="p_rs_specimen" <?= $isDisableEditResult ? " disabled readonly " : ""?> ><?= $patients[0]['p_rs_specimen']; ?></textarea>
</div>

<div align=""  class="mb-3">
    <label for="p_rs_clinical_diag">CLINICAL DIAGNOSIS</label><br>
    <textarea name="p_rs_clinical_diag" cols="100" rows="5" class="form-control" id="p_rs_clinical_diag"  <?= $isDisableEditResult ? " disabled readonly " : ""?>><?= $patients[0]['p_rs_clinical_diag']; ?></textarea>
</div>

<div align=""  class="mb-3">
    <label for="p_rs_gross_desc">GROSS DESCRIPTION</label><br>
    <textarea name="p_rs_gross_desc" cols="100" rows="5" class="form-control" id="p_rs_gross_desc" <?= $isDisableEditResult ? " disabled readonly " : ""?>><?= $patients[0]['p_rs_gross_desc']; ?></textarea>
</div>

<div align=""  class="mb-3">
    <label for="p_rs_microscopic_desc">MICROSCOPIC DESCRIPTION </label><br>
    <textarea name="p_rs_microscopic_desc" cols="100"  rows="5" class="form-control" id="p_rs_microscopic_desc" <?= $isDisableEditResult ? " disabled readonly " : ""?>><?= $patients[0]['p_rs_microscopic_desc']; ?></textarea>
</div>

<div align=""  class="mb-3">
    <label for="p_rs_diagnosis">DIAGNOSIS</label><br>
    <textarea name="p_rs_diagnosis" cols="100" rows="5" class="form-control" id="p_rs_diagnosis" <?= $isDisableEditResult ? " disabled readonly " : ""?> ><?= $patients[0]['p_rs_diagnosis']; ?></textarea>
</div>



