<?php if ($canViewPatientInfo_a_group) : ?>
    <!-- <hr noshade="noshade" width="" size="5"> -->
    <?php require 'includes/patient_form_a.php'; ?>
<?php endif; ?>

</div>
    </div>

<?php if ($canViewPlaning_b_1_group) : ?>

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1">

            <?php require 'includes/patient_form_b_0.php'; ?>
            <?php require 'includes/patient_form_b_1.php'; ?>

            <?php require 'includes/patient_form_b_2.php'; ?>

            <?php require 'includes/patient_form_b_3.php'; ?>

            <?php require 'includes/patient_form_b_4.php'; ?>

        </div>
    </div>

<?php endif; ?>
<?php if ($canViewResult_c_group) : ?>
    <!-- <hr noshade="noshade" width="" size="5"> -->

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1">

            <?php require 'includes/patient_form_c.php'; ?>

            </div>
    </div>

<?php endif; ?>