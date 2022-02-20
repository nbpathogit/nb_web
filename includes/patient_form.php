


<?php if ($canViewPatientInfo_a_group): ?>
    <hr noshade="noshade" width="" size="5" >
    <?php require 'includes/patient_form_a.php'; ?>
<?php endif; ?>
<?php if ($canViewPlaning_b_1_group): ?>
    <?php require 'includes/patient_form_b_0.php'; ?>
    <?php require 'includes/patient_form_b_1.php'; ?>
    
    <?php require 'includes/patient_form_b_2.php'; ?>
    
    <?php require 'includes/patient_form_b_3.php'; ?>
    
    <?php require 'includes/patient_form_b_4.php'; ?>
<?php endif; ?>
<?php if ($canViewResult_c_group): ?>
    <hr noshade="noshade" width="" size="5" >
    <?php require 'includes/patient_form_c.php'; ?>
   
<?php endif; ?>

