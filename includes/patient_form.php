<?php if ($canViewPatientInfo_a_group) : ?>
    <!-- <hr noshade="noshade" width="" size="5"> -->
    <?php require 'includes/patient_form_detail.php'; ?>
<?php endif; ?>

</div>
    </div>

<?php if ($canViewPlaning_b_1_group) : ?>

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1">

            <?php require 'includes/patient_form_specimen.php'; ?>
            <?php require 'includes/patient_form_prepare_specimen.php'; ?>

            <?php require 'includes/patient_form_prepare_slide.php'; ?>

            <?php require 'includes/patient_form_prepare_sp_slide.php'; ?>

            <?php require 'includes/patient_form_lab_cell.php'; ?>

        </div>
    </div>

<?php endif; ?>
<?php if ($canViewResult_c_group) : ?>
    <!-- <hr noshade="noshade" width="" size="5"> -->

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1">

            <?php require 'includes/patient_form_1result.php'; ?>

            </div>
    </div>

<?php endif; ?>