<?php
$isBorder = false;

?>



<?php if ($isHideResult): ?>
<?php else: ?>




    <div align=""  class="mb-3">
        <label for="p_rs_specimen">SPECIMEN</label><br>

        <textarea name="p_rs_specimen" cols="100" rows="5" class="form-control" id="p_rs_specimen" <?= $isEditModePageOn && $isEditModePageForIniResultDataOn && ($isCurUserAdmin || $isCurrentPathoIsOwnerThisCase || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> ><?= htmlspecialchars($patient[0]['p_rs_specimen']); ?></textarea>
    </div>

    <div align=""  class="mb-3">
        <label for="p_rs_clinical_diag">CLINICAL DIAGNOSIS</label><br>
        <textarea name="p_rs_clinical_diag" cols="100" rows="5" class="form-control" id="p_rs_clinical_diag"  <?= $isEditModePageOn && $isEditModePageForIniResultDataOn && ($isCurUserAdmin || $isCurrentPathoIsOwnerThisCase|| ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?>  ><?= htmlspecialchars($patient[0]['p_rs_clinical_diag']); ?></textarea>
    </div>

    <div align=""  class="mb-3">
        <label for="p_rs_gross_desc">GROSS DESCRIPTION</label><br>
        <textarea name="p_rs_gross_desc" cols="100" rows="5" class="form-control" id="p_rs_gross_desc" <?= $isEditModePageOn && $isEditModePageForIniResultDataOn && ($isCurUserAdmin || $isCurrentPathoIsOwnerThisCase || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?>  ><?= htmlspecialchars($patient[0]['p_rs_gross_desc']); ?></textarea>
    </div>

    <div align=""  class="mb-3">
        <label for="p_rs_microscopic_desc">MICROSCOPIC DESCRIPTION </label><br>
        <textarea name="p_rs_microscopic_desc" cols="100"  rows="5" class="form-control" id="p_rs_microscopic_desc" <?= $isEditModePageOn && $isEditModePageForIniResultDataOn && ($isCurUserAdmin || $isCurrentPathoIsOwnerThisCase || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?>  ><?= htmlspecialchars($patient[0]['p_rs_microscopic_desc']); ?></textarea>
    </div>

<?php endif; ?>

