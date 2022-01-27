
<?php if ($modePageEditDisable): ?>
    <p align="center"><button name="edit" type="submit" class="btn btn-primary">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button></p>
<?php else: ?>
    <p align="center"><button name="save" type="submit" class="btn btn-primary">&nbsp;&nbsp;Save All&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;<button name="discard" type="submit" class="btn btn-primary">Discard</button></p>
<?php endif; ?>

<?php if ($canViewPatientInfo): ?>
    <hr noshade="noshade" width="" size="5" >
    <?php require 'includes/patient_form_a.php'; ?>
<?php endif; ?>
<?php if ($canViewNBCenter): ?>
    <hr noshade="noshade" width="" size="5" >
    <?php require 'includes/patient_form_b_1.php'; ?>
    <hr>
    <?php require 'includes/patient_form_b_2.php'; ?>
    <hr>
    <?php require 'includes/patient_form_b_3.php'; ?>
<?php endif; ?>
<?php if ($canViewResult): ?>
    <hr noshade="noshade" width="" size="5" >
    <?php require 'includes/patient_form_c.php'; ?>
    <?php if ($isUpdateResultAval): ?>
        <hr noshade="noshade" width="" size="6" >
        <?php require 'includes/patient_form_d.php'; ?>
    <?php endif; ?>
<?php endif; ?>

