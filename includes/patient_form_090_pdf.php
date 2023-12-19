<?php
$isBorder = false;
$isSetShowaddResultButton = true;
?>



<?php if (!($isEditModePageOn || $isEditModePageForFinResultDataOn)) : ?>
<?php if(!$isCurUserCust):  ?>
            <p align="center"><a class="btn btn-primary" href="patient_pdf.php?id=<?= $patient[0]['id']; ?>&preview" target="_blank"  >PDF PreView Before release</a> </p>
<?php endif; ?>
            
<?php endif; ?>
            <p align="center"><a class="btn btn-primary" href="patient_pdf.php?id=<?= $patient[0]['id']; ?>" target="_blank"  >PDF Lastest released </a> </p>
</p>

<?php //require 'patient_form_080_job6__select_rs_modal.php'; ?>
<?php //require 'patient_from_080_job6_template_select_modal.php';      ?>







    