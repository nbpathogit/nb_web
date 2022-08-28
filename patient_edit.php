<?php
//var_dump($_GET);

require 'includes/init.php';
$conn = require 'includes/db.php';

/*

  step   DOM_Section
  1000   patient_detail_section
  2000   patient_plan_section
  3000   specimen_prep_section
  6000   slide_prep_section
  10000  lab_fluid_section_section
  11000  interim_result_section
  12000  diag_result_section

 */
$debug = true;
if ($debug) {
    $hidden_data2dom = false;
    $is_vardump = true;
} else {
    $hidden_data2dom = true;
    $is_vardump = false;
}

Auth::requireLogin();

$isAddPage = false; // if add page then diable edit almost of all.
// true = Disable Edit page, false canEditPage
$isEditModePageOn = false;      //flase = view mode, true = editing mode
$isEditModePageForPatientInfoDataOn = false;  //flase = view mode, true = editing mode
$isEditModePageForPlaningDataOn = false;      //flase = view mode, true = editing mode
$isEditModePageForIniResultDataOn = false;    //flase = view mode, true = editing mode
$isEditModePageForFinResultDataOn = false;    //flase = view mode, true = editing mode
$isEditModePageForSpSlidePrepDataOn = false;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //var_dump($_POST);die();
    //    die();
    //
    

    //Request to move status
    if (isset($_POST['status'])) {
        //echo "save status";
        $isUpdateStatusError = true;
        $isUpdateReleaseTimeError = true;

//        var_dump($_POST);die();
        //uresultTypeName

        $isUpdateStatusError = Patient::updateStatusWithMoveDATE($conn, $_GET['id'], $_POST['cur_status'], $_POST['status'], $_POST['isset_date_first_report']);
        if (($_POST['cur_status'] == 12000 or $_POST['cur_status'] == 13000) && $_POST['status'] == 20000 && isset($_POST["uresultinxlist"])) {
            //if ($_POST["uresultReleaseSetlist"] == 0) {
            $isUpdateReleaseTimeError = Presultupdate::updateReleaseTime($conn, $_POST["uresultinxlist"]); //Last index
            $isUpdateTypeNameError = Patient::updateReportTypeName($conn, $_GET['id'], $_POST['uresultTypeName']);
            $isUpdateReportAsError = Patient::updateReportAs($conn, $_GET['id'], $_POST['reported_as']);
            //reported_as
            //}
        }
        if (isset($_POST['pautoscroll'])) {
            Patient::setAutoScroll($conn, $_GET['id'], $_POST['pautoscroll']);
        }

        if (isset($_POST['isautoeditmode'])) {
            Patient::setisautoeditmode($conn, $_GET['id'], $_POST['isautoeditmode']);
        }

        if ($isUpdateStatusError && $isUpdateReleaseTimeError) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
    }



    if (isset($_POST['add_u_result'])) {

        $presultupdate = new Presultupdate();
        $presultupdate->patient_id = $_GET['id'];
        $presultupdate->result_type = $_POST['result_type'];
        $presultupdate->pathologist_id = $_POST['pathologist_id'];

        Patient::setAutoScroll($conn, $_GET['id'], "diag_result_section");
        Patient::setisautoeditmode($conn, $GET['id'], "diag_result_section");

        //        var_dump($_POST);
        //        die();

        if ($presultupdate->create($conn)) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add result fail. Please verify again")</script>';
        }
    }

    if (isset($_POST['save_u_result'])) {
        //var_dump($_POST);
        Patient::setAutoScroll($conn, $_GET['id'], "uresultLastSection");
        if (Presultupdate::updateResult($conn, $_POST['id'], $_POST['pathologist_id'], $_POST['pathologist2_id'], $_POST['result_message'])) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
    }


    if (isset($_POST['discard_u_result'])) {
        //var_dump($_POST);
        Patient::setAutoScroll($conn, $_GET['id'], "uresultLastSection");
        Url::redirect("/patient_edit.php?id=" . $_GET['id']);
    }


    //Save all page
    if (isset($_POST['save'])) {

        $patient = new Patient();
        //Get Specific Row from Table
        if (isset($_GET['id'])) {
            $patient = Patient::getByID($conn, $_GET['id']);
        } else {
            $patient = null;
        }
        //var_dump($_POST);

        isset($_POST['pnum']) ? $patient->pnum = $_POST['pnum'] : null;
        isset($_POST['plabnum']) ? $patient->plabnum = $_POST['plabnum'] : null;
        isset($_POST['pname']) ? $patient->pname = $_POST['pname'] : null;
        isset($_POST['pgender']) ? $patient->pgender = $_POST['pgender'] : null;
        isset($_POST['plastname']) ? $patient->plastname = $_POST['plastname'] : null;
        isset($_POST['pedge']) ? $patient->pedge = $_POST['pedge'] : null;
        isset($_POST['date_1000']) ? $patient->date_1000 = $_POST['date_1000'] : null;
        //        isset($_POST['date_12_13_000']) ? $patient->date_12_13_000 = $_POST['date_12_13_000'] : null;
        isset($_POST['status_id']) ? $patient->status_id = $_POST['status_id'] : null;
        isset($_POST['priority_id']) ? $patient->priority_id = $_POST['priority_id'] : null;
        isset($_POST['phospital_id']) ? $patient->phospital_id = $_POST['phospital_id'] : null;
        isset($_POST['phospital_num']) ? $patient->phospital_num = $_POST['phospital_num'] : null;
        isset($_POST['ppathologist_id']) ? $patient->ppathologist_id = $_POST['ppathologist_id'] : null;
        isset($_POST['pspecimen_id']) ? $patient->pspecimen_id = $_POST['pspecimen_id'] : null;
        isset($_POST['pclinician_id']) ? $patient->pclinician_id = $_POST['pclinician_id'] : null;

        isset($_POST['p_cross_section_id']) ? $patient->p_cross_section_id = $_POST['p_cross_section_id'] : null;
        isset($_POST['p_cross_section_ass_id']) ? $patient->p_cross_section_ass_id = $_POST['p_cross_section_ass_id'] : null;
        isset($_POST['p_slide_prep_id']) ? $patient->p_slide_prep_id = $_POST['p_slide_prep_id'] : null;
        isset($_POST['p_slide_prep_sp_id']) ? $patient->p_slide_prep_sp_id = $_POST['p_slide_prep_sp_id'] : null;
        isset($_POST['pprice']) ? $patient->pprice = $_POST['pprice'] : null;
        isset($_POST['pspprice']) ? $patient->pspprice = $_POST['pspprice'] : null;
        isset($_POST['p_rs_specimen']) ? $patient->p_rs_specimen = $_POST['p_rs_specimen'] : null;
        isset($_POST['p_rs_clinical_diag']) ? $patient->p_rs_clinical_diag = $_POST['p_rs_clinical_diag'] : null;
        isset($_POST['p_rs_gross_desc']) ? $patient->p_rs_gross_desc = $_POST['p_rs_gross_desc'] : null;
        isset($_POST['p_rs_microscopic_desc']) ? $patient->p_rs_microscopic_desc = $_POST['p_rs_microscopic_desc'] : null;



        isset($_POST['p_speciment_type']) ? $patient->p_speciment_type = $_POST['p_speciment_type'] : null;
        isset($_POST['p_slide_lab_id']) ? $patient->p_slide_lab_id = $_POST['p_slide_lab_id'] : null;
        isset($_POST['p_slide_lab_price']) ? $patient->p_slide_lab_price = $_POST['p_slide_lab_price'] : null;



        if ($patient->update($conn, $_GET['id'])) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
        $patient = NULL;
    }

    //discard patient_detail section
    if (isset($_POST['discard_patient_detail'])) {
        //var_dump($_POST);
        if (Patient::setAutoScroll($conn, $_GET['id'], "patient_detail_section")) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
    }

    //discard patient_plan section
    if (isset($_POST['discard_patient_plan'])) {
        //var_dump($_POST);
        if (Patient::setAutoScroll($conn, $_GET['id'], "patient_plan_section")) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
    }

    //discard patient_plan section
    if (isset($_POST['discard_interim_result'])) {
        //var_dump($_POST);
        if (Patient::setAutoScroll($conn, $_GET['id'], "interim_result_section")) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
    }

    //Save patient_detail section
    if (isset($_POST['save_patient_detail'])) {
//        var_dump($_POST);die();
        $patient = new Patient();
        //Get Specific Row from Table
        if (isset($_GET['id'])) {
            $patient = Patient::getByID($conn, $_GET['id']);
        } else {
            $patient = null;
        }
        //var_dump($_POST);

        isset($_POST['pnum']) ? $patient->pnum = $_POST['pnum'] : null;
        isset($_POST['plabnum']) ? $patient->plabnum = $_POST['plabnum'] : null;
        isset($_POST['pname']) ? $patient->pname = $_POST['pname'] : null;
        isset($_POST['pgender']) ? $patient->pgender = $_POST['pgender'] : null;
        isset($_POST['plastname']) ? $patient->plastname = $_POST['plastname'] : null;
        isset($_POST['pedge']) ? $patient->pedge = $_POST['pedge'] : null;
        isset($_POST['date_1000']) ? $patient->date_1000 = $_POST['date_1000'] : null;
        //        isset($_POST['date_12_13_000']) ? $patient->date_12_13_000 = $_POST['date_12_13_000'] : null;
        isset($_POST['status_id']) ? $patient->status_id = $_POST['status_id'] : null;
        isset($_POST['priority_id']) ? $patient->priority_id = $_POST['priority_id'] : null;
        isset($_POST['phospital_id']) ? $patient->phospital_id = $_POST['phospital_id'] : null;
        isset($_POST['phospital_num']) ? $patient->phospital_num = $_POST['phospital_num'] : null;
        isset($_POST['ppathologist_id']) ? $patient->ppathologist_id = $_POST['ppathologist_id'] : null;
        isset($_POST['pspecimen_id']) ? $patient->pspecimen_id = $_POST['pspecimen_id'] : null;
        isset($_POST['pclinician_id']) ? $patient->pclinician_id = $_POST['pclinician_id'] : null;

        isset($_POST['p_cross_section_id']) ? $patient->p_cross_section_id = $_POST['p_cross_section_id'] : null;
        isset($_POST['p_cross_section_ass_id']) ? $patient->p_cross_section_ass_id = $_POST['p_cross_section_ass_id'] : null;
        isset($_POST['p_slide_prep_id']) ? $patient->p_slide_prep_id = $_POST['p_slide_prep_id'] : null;
        isset($_POST['p_slide_prep_sp_id']) ? $patient->p_slide_prep_sp_id = $_POST['p_slide_prep_sp_id'] : null;
        isset($_POST['pprice']) ? $patient->pprice = $_POST['pprice'] : null;
        isset($_POST['pspprice']) ? $patient->pspprice = $_POST['pspprice'] : null;
        isset($_POST['p_rs_specimen']) ? $patient->p_rs_specimen = $_POST['p_rs_specimen'] : null;
        isset($_POST['p_rs_clinical_diag']) ? $patient->p_rs_clinical_diag = $_POST['p_rs_clinical_diag'] : null;
        isset($_POST['p_rs_gross_desc']) ? $patient->p_rs_gross_desc = $_POST['p_rs_gross_desc'] : null;
        isset($_POST['p_rs_microscopic_desc']) ? $patient->p_rs_microscopic_desc = $_POST['p_rs_microscopic_desc'] : null;



        isset($_POST['p_speciment_type']) ? $patient->p_speciment_type = $_POST['p_speciment_type'] : null;
        isset($_POST['p_slide_lab_id']) ? $patient->p_slide_lab_id = $_POST['p_slide_lab_id'] : null;
        isset($_POST['p_slide_lab_price']) ? $patient->p_slide_lab_price = $_POST['p_slide_lab_price'] : null;
        $patient->isautoeditmode = "NA"; //save only
        $patient->pautoscroll = "patient_detail_section"; //set auto scroll



        if ($patient->updatePatientDetail($conn, $_GET['id'])) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
        $patient = NULL;
    }


    //Save patient_detail section and then move next to 2000
    if (isset($_POST['save_patient_detail_next'])) {

        $isUpdateStatusError = Patient::updateStatusWithMoveDATE($conn, $_GET['id'], "1000", "2000", "0");

//        var_dump($_POST);die();
        $patient = new Patient();
        //Get Specific Row from Table
        if (isset($_GET['id'])) {
            $patient = Patient::getByID($conn, $_GET['id']);
        } else {
            $patient = null;
        }
        //var_dump($_POST);

        isset($_POST['pnum']) ? $patient->pnum = $_POST['pnum'] : null;
        isset($_POST['plabnum']) ? $patient->plabnum = $_POST['plabnum'] : null;
        isset($_POST['pname']) ? $patient->pname = $_POST['pname'] : null;
        isset($_POST['pgender']) ? $patient->pgender = $_POST['pgender'] : null;
        isset($_POST['plastname']) ? $patient->plastname = $_POST['plastname'] : null;
        isset($_POST['pedge']) ? $patient->pedge = $_POST['pedge'] : null;
        isset($_POST['date_1000']) ? $patient->date_1000 = $_POST['date_1000'] : null;
        //        isset($_POST['date_12_13_000']) ? $patient->date_12_13_000 = $_POST['date_12_13_000'] : null;
        isset($_POST['status_id']) ? $patient->status_id = $_POST['status_id'] : null;
        isset($_POST['priority_id']) ? $patient->priority_id = $_POST['priority_id'] : null;
        isset($_POST['phospital_id']) ? $patient->phospital_id = $_POST['phospital_id'] : null;
        isset($_POST['phospital_num']) ? $patient->phospital_num = $_POST['phospital_num'] : null;
        isset($_POST['ppathologist_id']) ? $patient->ppathologist_id = $_POST['ppathologist_id'] : null;
        isset($_POST['pspecimen_id']) ? $patient->pspecimen_id = $_POST['pspecimen_id'] : null;
        isset($_POST['pclinician_id']) ? $patient->pclinician_id = $_POST['pclinician_id'] : null;

        isset($_POST['p_cross_section_id']) ? $patient->p_cross_section_id = $_POST['p_cross_section_id'] : null;
        isset($_POST['p_cross_section_ass_id']) ? $patient->p_cross_section_ass_id = $_POST['p_cross_section_ass_id'] : null;
        isset($_POST['p_slide_prep_id']) ? $patient->p_slide_prep_id = $_POST['p_slide_prep_id'] : null;
        isset($_POST['p_slide_prep_sp_id']) ? $patient->p_slide_prep_sp_id = $_POST['p_slide_prep_sp_id'] : null;
        isset($_POST['pprice']) ? $patient->pprice = $_POST['pprice'] : null;
        isset($_POST['pspprice']) ? $patient->pspprice = $_POST['pspprice'] : null;
        isset($_POST['p_rs_specimen']) ? $patient->p_rs_specimen = $_POST['p_rs_specimen'] : null;
        isset($_POST['p_rs_clinical_diag']) ? $patient->p_rs_clinical_diag = $_POST['p_rs_clinical_diag'] : null;
        isset($_POST['p_rs_gross_desc']) ? $patient->p_rs_gross_desc = $_POST['p_rs_gross_desc'] : null;
        isset($_POST['p_rs_microscopic_desc']) ? $patient->p_rs_microscopic_desc = $_POST['p_rs_microscopic_desc'] : null;



        isset($_POST['p_speciment_type']) ? $patient->p_speciment_type = $_POST['p_speciment_type'] : null;
        isset($_POST['p_slide_lab_id']) ? $patient->p_slide_lab_id = $_POST['p_slide_lab_id'] : null;
        isset($_POST['p_slide_lab_price']) ? $patient->p_slide_lab_price = $_POST['p_slide_lab_price'] : null;
        $patient->isautoeditmode = "patient_plan_section"; //save and auto move to edit next section
        $patient->pautoscroll = "patient_plan_section"; //set auto scroll



        if ($patient->updatePatientDetail($conn, $_GET['id'])) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
        $patient = NULL;
    }


    //Save save_patient_plan
    if (isset($_POST['save_patient_plan'])) {
        //var_dump($_POST);die();
        $patient = new Patient();
        //Get Specific Row from Table
        if (isset($_GET['id'])) {
            $patient = Patient::getByID($conn, $_GET['id']);
        } else {
            $patient = null;
        }
        //var_dump($_POST);

        isset($_POST['pnum']) ? $patient->pnum = $_POST['pnum'] : null;
        isset($_POST['plabnum']) ? $patient->plabnum = $_POST['plabnum'] : null;
        isset($_POST['pname']) ? $patient->pname = $_POST['pname'] : null;
        isset($_POST['pgender']) ? $patient->pgender = $_POST['pgender'] : null;
        isset($_POST['plastname']) ? $patient->plastname = $_POST['plastname'] : null;
        isset($_POST['pedge']) ? $patient->pedge = $_POST['pedge'] : null;
        isset($_POST['date_1000']) ? $patient->date_1000 = $_POST['date_1000'] : null;
        //        isset($_POST['date_12_13_000']) ? $patient->date_12_13_000 = $_POST['date_12_13_000'] : null;
        isset($_POST['status_id']) ? $patient->status_id = $_POST['status_id'] : null;
        isset($_POST['priority_id']) ? $patient->priority_id = $_POST['priority_id'] : null;
        isset($_POST['phospital_id']) ? $patient->phospital_id = $_POST['phospital_id'] : null;
        isset($_POST['phospital_num']) ? $patient->phospital_num = $_POST['phospital_num'] : null;
        isset($_POST['ppathologist_id']) ? $patient->ppathologist_id = $_POST['ppathologist_id'] : null;
        isset($_POST['pspecimen_id']) ? $patient->pspecimen_id = $_POST['pspecimen_id'] : null;
        isset($_POST['pclinician_id']) ? $patient->pclinician_id = $_POST['pclinician_id'] : null;

        isset($_POST['p_cross_section_id']) ? $patient->p_cross_section_id = $_POST['p_cross_section_id'] : null;
        isset($_POST['p_cross_section_ass_id']) ? $patient->p_cross_section_ass_id = $_POST['p_cross_section_ass_id'] : null;
        isset($_POST['p_slide_prep_id']) ? $patient->p_slide_prep_id = $_POST['p_slide_prep_id'] : null;
        isset($_POST['p_slide_prep_sp_id']) ? $patient->p_slide_prep_sp_id = $_POST['p_slide_prep_sp_id'] : null;
        isset($_POST['pprice']) ? $patient->pprice = $_POST['pprice'] : null;
        isset($_POST['pspprice']) ? $patient->pspprice = $_POST['pspprice'] : null;
        isset($_POST['p_rs_specimen']) ? $patient->p_rs_specimen = $_POST['p_rs_specimen'] : null;
        isset($_POST['p_rs_clinical_diag']) ? $patient->p_rs_clinical_diag = $_POST['p_rs_clinical_diag'] : null;
        isset($_POST['p_rs_gross_desc']) ? $patient->p_rs_gross_desc = $_POST['p_rs_gross_desc'] : null;
        isset($_POST['p_rs_microscopic_desc']) ? $patient->p_rs_microscopic_desc = $_POST['p_rs_microscopic_desc'] : null;



        isset($_POST['p_speciment_type']) ? $patient->p_speciment_type = $_POST['p_speciment_type'] : null;
        isset($_POST['p_slide_lab_id']) ? $patient->p_slide_lab_id = $_POST['p_slide_lab_id'] : null;
        isset($_POST['p_slide_lab_price']) ? $patient->p_slide_lab_price = $_POST['p_slide_lab_price'] : null;
        //$patient->isfirstadd = 2; //save and auto move to edit next section
        $patient->isautoeditmode = "NA"; //save only
        $patient->pautoscroll = "patient_plan_section"; //set auto scroll


        if ($patient->updatePatientPlan($conn, $_GET['id'])) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
        $patient = NULL;
    }

    //Save save_patient_plan
    if (isset($_POST['save_patient_plan_next'])) {

        //var_dump($_POST);die();
        $patient = new Patient();
        //Get Specific Row from Table
        if (isset($_GET['id'])) {
            $patient = Patient::getByID($conn, $_GET['id']);
        } else {
            $patient = null;
        }
        //var_dump($_POST);

        isset($_POST['pnum']) ? $patient->pnum = $_POST['pnum'] : null;
        isset($_POST['plabnum']) ? $patient->plabnum = $_POST['plabnum'] : null;
        isset($_POST['pname']) ? $patient->pname = $_POST['pname'] : null;
        isset($_POST['pgender']) ? $patient->pgender = $_POST['pgender'] : null;
        isset($_POST['plastname']) ? $patient->plastname = $_POST['plastname'] : null;
        isset($_POST['pedge']) ? $patient->pedge = $_POST['pedge'] : null;
        isset($_POST['date_1000']) ? $patient->date_1000 = $_POST['date_1000'] : null;
        //        isset($_POST['date_12_13_000']) ? $patient->date_12_13_000 = $_POST['date_12_13_000'] : null;
        isset($_POST['status_id']) ? $patient->status_id = $_POST['status_id'] : null;
        isset($_POST['priority_id']) ? $patient->priority_id = $_POST['priority_id'] : null;
        isset($_POST['phospital_id']) ? $patient->phospital_id = $_POST['phospital_id'] : null;
        isset($_POST['phospital_num']) ? $patient->phospital_num = $_POST['phospital_num'] : null;
        isset($_POST['ppathologist_id']) ? $patient->ppathologist_id = $_POST['ppathologist_id'] : null;
        isset($_POST['pspecimen_id']) ? $patient->pspecimen_id = $_POST['pspecimen_id'] : null;
        isset($_POST['pclinician_id']) ? $patient->pclinician_id = $_POST['pclinician_id'] : null;

        isset($_POST['p_cross_section_id']) ? $patient->p_cross_section_id = $_POST['p_cross_section_id'] : null;
        isset($_POST['p_cross_section_ass_id']) ? $patient->p_cross_section_ass_id = $_POST['p_cross_section_ass_id'] : null;
        isset($_POST['p_slide_prep_id']) ? $patient->p_slide_prep_id = $_POST['p_slide_prep_id'] : null;
        isset($_POST['p_slide_prep_sp_id']) ? $patient->p_slide_prep_sp_id = $_POST['p_slide_prep_sp_id'] : null;
        isset($_POST['pprice']) ? $patient->pprice = $_POST['pprice'] : null;
        isset($_POST['pspprice']) ? $patient->pspprice = $_POST['pspprice'] : null;
        isset($_POST['p_rs_specimen']) ? $patient->p_rs_specimen = $_POST['p_rs_specimen'] : null;
        isset($_POST['p_rs_clinical_diag']) ? $patient->p_rs_clinical_diag = $_POST['p_rs_clinical_diag'] : null;
        isset($_POST['p_rs_gross_desc']) ? $patient->p_rs_gross_desc = $_POST['p_rs_gross_desc'] : null;
        isset($_POST['p_rs_microscopic_desc']) ? $patient->p_rs_microscopic_desc = $_POST['p_rs_microscopic_desc'] : null;



        isset($_POST['p_speciment_type']) ? $patient->p_speciment_type = $_POST['p_speciment_type'] : null;
        isset($_POST['p_slide_lab_id']) ? $patient->p_slide_lab_id = $_POST['p_slide_lab_id'] : null;
        isset($_POST['p_slide_lab_price']) ? $patient->p_slide_lab_price = $_POST['p_slide_lab_price'] : null;
        if ($_POST['p_speciment_type'] == "lump") {
            $isUpdateStatusError = Patient::updateStatusWithMoveDATE($conn, $_GET['id'], "2000", "3000", "0");
            $patient->isautoeditmode = "NA"; //save only
            $patient->pautoscroll = "specimen_prep_section"; //set auto scroll
        } else {
            $isUpdateStatusError = Patient::updateStatusWithMoveDATE($conn, $_GET['id'], "2000", "10000", "0");
            $patient->isautoeditmode = "NA"; //save only
            $patient->pautoscroll = "lab_fluid_section_section"; //set auto scroll
        }

        if ($patient->updatePatientPlan($conn, $_GET['id'])) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
        $patient = NULL;
    }

    
    //Save special slide prepare
    if (isset($_POST['save_patient_plan_next'])) {

        //var_dump($_POST);die();
        $patient = new Patient();
        //Get Specific Row from Table
        if (isset($_GET['id'])) {
            $patient = Patient::getByID($conn, $_GET['id']);
        } else {
            $patient = null;
        }
        //var_dump($_POST);

        isset($_POST['pnum']) ? $patient->pnum = $_POST['pnum'] : null;
        isset($_POST['plabnum']) ? $patient->plabnum = $_POST['plabnum'] : null;
        isset($_POST['pname']) ? $patient->pname = $_POST['pname'] : null;
        isset($_POST['pgender']) ? $patient->pgender = $_POST['pgender'] : null;
        isset($_POST['plastname']) ? $patient->plastname = $_POST['plastname'] : null;
        isset($_POST['pedge']) ? $patient->pedge = $_POST['pedge'] : null;
        isset($_POST['date_1000']) ? $patient->date_1000 = $_POST['date_1000'] : null;
        //        isset($_POST['date_12_13_000']) ? $patient->date_12_13_000 = $_POST['date_12_13_000'] : null;
        isset($_POST['status_id']) ? $patient->status_id = $_POST['status_id'] : null;
        isset($_POST['priority_id']) ? $patient->priority_id = $_POST['priority_id'] : null;
        isset($_POST['phospital_id']) ? $patient->phospital_id = $_POST['phospital_id'] : null;
        isset($_POST['phospital_num']) ? $patient->phospital_num = $_POST['phospital_num'] : null;
        isset($_POST['ppathologist_id']) ? $patient->ppathologist_id = $_POST['ppathologist_id'] : null;
        isset($_POST['pspecimen_id']) ? $patient->pspecimen_id = $_POST['pspecimen_id'] : null;
        isset($_POST['pclinician_id']) ? $patient->pclinician_id = $_POST['pclinician_id'] : null;

        isset($_POST['p_cross_section_id']) ? $patient->p_cross_section_id = $_POST['p_cross_section_id'] : null;
        isset($_POST['p_cross_section_ass_id']) ? $patient->p_cross_section_ass_id = $_POST['p_cross_section_ass_id'] : null;
        isset($_POST['p_slide_prep_id']) ? $patient->p_slide_prep_id = $_POST['p_slide_prep_id'] : null;
        isset($_POST['p_slide_prep_sp_id']) ? $patient->p_slide_prep_sp_id = $_POST['p_slide_prep_sp_id'] : null;
        isset($_POST['pprice']) ? $patient->pprice = $_POST['pprice'] : null;
        isset($_POST['pspprice']) ? $patient->pspprice = $_POST['pspprice'] : null;
        isset($_POST['p_rs_specimen']) ? $patient->p_rs_specimen = $_POST['p_rs_specimen'] : null;
        isset($_POST['p_rs_clinical_diag']) ? $patient->p_rs_clinical_diag = $_POST['p_rs_clinical_diag'] : null;
        isset($_POST['p_rs_gross_desc']) ? $patient->p_rs_gross_desc = $_POST['p_rs_gross_desc'] : null;
        isset($_POST['p_rs_microscopic_desc']) ? $patient->p_rs_microscopic_desc = $_POST['p_rs_microscopic_desc'] : null;



        isset($_POST['p_speciment_type']) ? $patient->p_speciment_type = $_POST['p_speciment_type'] : null;
        isset($_POST['p_slide_lab_id']) ? $patient->p_slide_lab_id = $_POST['p_slide_lab_id'] : null;
        isset($_POST['p_slide_lab_price']) ? $patient->p_slide_lab_price = $_POST['p_slide_lab_price'] : null;
        if ($_POST['p_speciment_type'] == "lump") {
            $isUpdateStatusError = Patient::updateStatusWithMoveDATE($conn, $_GET['id'], "2000", "3000", "0");
            $patient->isautoeditmode = "NA"; //save only
            $patient->pautoscroll = "specimen_prep_section"; //set auto scroll
        } else {
            $isUpdateStatusError = Patient::updateStatusWithMoveDATE($conn, $_GET['id'], "2000", "10000", "0");
            $patient->isautoeditmode = "NA"; //save only
            $patient->pautoscroll = "lab_fluid_section_section"; //set auto scroll
        }

        if ($patient->updatePatientPlan($conn, $_GET['id'])) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
        $patient = NULL;
    }


    //Save save_interim_result
    if (isset($_POST['save_interim_result'])) {
//        var_dump($_POST);die();
        $patient = new Patient();
        //Get Specific Row from Table
        if (isset($_GET['id'])) {
            $patient = Patient::getByID($conn, $_GET['id']);
        } else {
            $patient = null;
        }
        //var_dump($_POST);

        isset($_POST['pnum']) ? $patient->pnum = $_POST['pnum'] : null;
        isset($_POST['plabnum']) ? $patient->plabnum = $_POST['plabnum'] : null;
        isset($_POST['pname']) ? $patient->pname = $_POST['pname'] : null;
        isset($_POST['pgender']) ? $patient->pgender = $_POST['pgender'] : null;
        isset($_POST['plastname']) ? $patient->plastname = $_POST['plastname'] : null;
        isset($_POST['pedge']) ? $patient->pedge = $_POST['pedge'] : null;
        isset($_POST['date_1000']) ? $patient->date_1000 = $_POST['date_1000'] : null;
        //        isset($_POST['date_12_13_000']) ? $patient->date_12_13_000 = $_POST['date_12_13_000'] : null;
        isset($_POST['status_id']) ? $patient->status_id = $_POST['status_id'] : null;
        isset($_POST['priority_id']) ? $patient->priority_id = $_POST['priority_id'] : null;
        isset($_POST['phospital_id']) ? $patient->phospital_id = $_POST['phospital_id'] : null;
        isset($_POST['phospital_num']) ? $patient->phospital_num = $_POST['phospital_num'] : null;
        isset($_POST['ppathologist_id']) ? $patient->ppathologist_id = $_POST['ppathologist_id'] : null;
        isset($_POST['pspecimen_id']) ? $patient->pspecimen_id = $_POST['pspecimen_id'] : null;
        isset($_POST['pclinician_id']) ? $patient->pclinician_id = $_POST['pclinician_id'] : null;

        isset($_POST['p_cross_section_id']) ? $patient->p_cross_section_id = $_POST['p_cross_section_id'] : null;
        isset($_POST['p_cross_section_ass_id']) ? $patient->p_cross_section_ass_id = $_POST['p_cross_section_ass_id'] : null;
        isset($_POST['p_slide_prep_id']) ? $patient->p_slide_prep_id = $_POST['p_slide_prep_id'] : null;
        isset($_POST['p_slide_prep_sp_id']) ? $patient->p_slide_prep_sp_id = $_POST['p_slide_prep_sp_id'] : null;
        isset($_POST['pprice']) ? $patient->pprice = $_POST['pprice'] : null;
        isset($_POST['pspprice']) ? $patient->pspprice = $_POST['pspprice'] : null;
        isset($_POST['p_rs_specimen']) ? $patient->p_rs_specimen = $_POST['p_rs_specimen'] : null;
        isset($_POST['p_rs_clinical_diag']) ? $patient->p_rs_clinical_diag = $_POST['p_rs_clinical_diag'] : null;
        isset($_POST['p_rs_gross_desc']) ? $patient->p_rs_gross_desc = $_POST['p_rs_gross_desc'] : null;
        isset($_POST['p_rs_microscopic_desc']) ? $patient->p_rs_microscopic_desc = $_POST['p_rs_microscopic_desc'] : null;



        isset($_POST['p_speciment_type']) ? $patient->p_speciment_type = $_POST['p_speciment_type'] : null;
        isset($_POST['p_slide_lab_id']) ? $patient->p_slide_lab_id = $_POST['p_slide_lab_id'] : null;
        isset($_POST['p_slide_lab_price']) ? $patient->p_slide_lab_price = $_POST['p_slide_lab_price'] : null;
        $patient->isautoeditmode = "NA"; //save only
        $patient->pautoscroll = "interim_result_section"; //set auto scroll


        if ($patient->updateInterimResult($conn, $_GET['id'])) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
        $patient = NULL;
    }
}



//Get Specific Row from Table
if (isset($_GET['id'])) {
    $patient = Patient::getAll($conn, $_GET['id']);
    if ($is_vardump) {
        var_dump($patient);
    }
    Patient::clearAutoScroll($conn, $_GET['id']);
    Patient::clearisautoeditmode($conn, $_GET['id']);
} else {
    $patient = null;
}

if (!$patient) {
    require 'blockopen.php';
    echo( "No Patient ID " . $_GET['id'] . ".");
    require 'blockclose.php';
    die();
}



// If isfirstadd == 1 then jump to edit mode for patient_detail
//{
//    //Move to edit mode
//    if ($patient[0]['isfirstadd'] == '1') {
//        // true = Disable Edit page, false canEditPage
//        //$isEditModePageOn = true;
//    }
//
//    if ($patient[0]['isfirstadd'] == '1') {
//        // true = Disable Edit page, false canEditPage
//        //$isEditModePageOn = true;
//        $isEditModePageForPatientInfoDataOn = true;  //flase = view mode, true = editing mode
//        $isEditModePageForPlaningDataOn = false;      //flase = view mode, true = editing mode
//        $isEditModePageForIniResultDataOn = false;    //flase = view mode, true = editing mode
//        $isEditModePageForFinResultDataOn = false;    //flase = view mode, true = editing mode
//    }
//}
// If isfirstadd == 2 then jump to edit mode for planing
//{
//    //Move to edit mode
//    if ($patient[0]['isfirstadd'] == '2') {
//        // true = Disable Edit page, false canEditPage
//        //$isEditModePageOn = true;
//    }
//
//    if ($patient[0]['isfirstadd'] == '2') {
//        // true = Disable Edit page, false canEditPage
//        //$isEditModePageOn = true;
//        $isEditModePageForPatientInfoDataOn = false;  //flase = view mode, true = editing mode
//        $isEditModePageForPlaningDataOn = true;      //flase = view mode, true = editing mode
//        $isEditModePageForIniResultDataOn = false;    //flase = view mode, true = editing mode
//        $isEditModePageForFinResultDataOn = false;    //flase = view mode, true = editing mode
//    }
//}
//var_dump($patient); die();
//$status_cur = Status::getAll($conn, $patient[0]['status_id']);s
//$patientLists = Patient::getAll($conn);
//Get List of Table
$hospitals = Hospital::getAll($conn);
$specimens = Specimen::getAll($conn);
$clinicians = User::getAllbyClinicians($conn);
$userPathos = User::getAllbyPathologis($conn);
$userTechnic = User::getAllbyTeachien($conn);
$prioritys = Priority::getAll($conn);
$statusLists = Status::getAll($conn);
$labFluids = LabFluid::getAll($conn);
$userGroupLists = Ugroup::getAll($conn);

//var_dump($userPathos);
//die();
//Get one by id
$presultupdates = Presultupdate::getAll($conn, $_GET['id']);

$clinician = User::getAll($conn, $patient[0]['pclinician_id']);

//Check whether date of fisrt report is define
$isset_date_first_report = 0;
if (isset($patient[0]['date_first_report'])) {
    $isset_date_first_report = 1;
} else {
    $isset_date_first_report = 0;
}


//Prepare Status/step
$curstatusid = $patient[0]['status_id'];
$curstatus = Status::getAll($conn, $patient[0]['status_id']);

//Prepare release status
$isReleased = ($patient[0]['reported_as'] == "ยังไม่ออกผล");

//เช็คและเตรียมตัวแปรสถานะปัจจุบัน
require 'includes/status_cur.php';


require 'user_auth.php';

//Move to edit mode
if (isset($_POST['edit_result'])) {
    // true = Disable Edit page, false canEditPage
    $isEditModePageOn = true;
}

if (isset($_POST['edit'])) {
    // true = Disable Edit page, false canEditPage
    $isEditModePageOn = true;
}

if (isset($_POST['edit_patient_detail']) || $patient[0]['isautoeditmode'] == "patient_detail_section") {
    //patient[0]['pautoscroll']
    $patient[0]['pautoscroll'] = "patient_detail_section";
    // true = Disable Edit page, false canEditPage
    $isEditModePageOn = true;
    $isEditModePageForPatientInfoDataOn = true;  //flase = view mode, true = editing mode
    $isEditModePageForPlaningDataOn = false;      //flase = view mode, true = editing mode
    $isEditModePageForIniResultDataOn = false;    //flase = view mode, true = editing mode
    $isEditModePageForFinResultDataOn = false;    //flase = view mode, true = editing mode
    $isEditModePageForSpSlidePrepDataOn = false;        //flase = view mode, true = editing mode
}
if (isset($_POST['edit_patient_plan']) || $patient[0]['isautoeditmode'] == "patient_plan_section") {
    //patient[0]['pautoscroll']
    $patient[0]['pautoscroll'] = "patient_plan_section";
    // true = Disable Edit page, false canEditPage
    $isEditModePageOn = true;
    $isEditModePageForPatientInfoDataOn = false;  //flase = view mode, true = editing mode
    $isEditModePageForPlaningDataOn = true;      //flase = view mode, true = editing mode
    $isEditModePageForIniResultDataOn = false;    //flase = view mode, true = editing mode
    $isEditModePageForFinResultDataOn = false;    //flase = view mode, true = editing mode
    $isEditModePageForSpSlidePrepDataOn = false;        //flase = view mode, true = editing mode
}
if (isset($_POST['edit_interim_result'])) {
    //patient[0]['pautoscroll']
    $patient[0]['pautoscroll'] = "interim_result_section";
    // true = Disable Edit page, false canEditPage
    $isEditModePageOn = true;
    $isEditModePageForPatientInfoDataOn = false;  //flase = view mode, true = editing mode
    $isEditModePageForPlaningDataOn = false;      //flase = view mode, true = editing mode
    $isEditModePageForIniResultDataOn = true;    //flase = view mode, true = editing mode
    $isEditModePageForFinResultDataOn = false;    //flase = view mode, true = editing mode
    $isEditModePageForSpSlidePrepDataOn = false;        //flase = view mode, true = editing mode
}
if (isset($_POST['edit_u_result']) || $patient[0]['isautoeditmode'] == "diag_result_section") {

    $patient[0]['pautoscroll'] = "uresultLastSection";
    // true = Disable Edit page, false canEditPage
    $isEditModePageOn = false;      //flase = view mode, true = editing mode
    $isEditModePageForPatientInfoDataOn = false;  //flase = view mode, true = editing mode
    $isEditModePageForPlaningDataOn = false;      //flase = view mode, true = editing mode
    $isEditModePageForIniResultDataOn = false;    //flase = view mode, true = editing mode
    $isEditModePageForFinResultDataOn = true;
    $isEditModePageForSpSlidePrepDataOn = false;        //flase = view mode, true = editing mode
}




if (isset($_POST['edit_sp_prep_slide']) || $patient[0]['isautoeditmode'] == "slide_sp_prep_section") {

    $patient[0]['pautoscroll'] = "slide_sp_prep_section";
    // true = Disable Edit page, false canEditPage
    $isEditModePageOn = true;      //flase = view mode, true = editing mode
    $isEditModePageForPatientInfoDataOn = false;  //flase = view mode, true = editing mode
    $isEditModePageForPlaningDataOn = false;      //flase = view mode, true = editing mode
    $isEditModePageForIniResultDataOn = false;    //flase = view mode, true = editing mode
    $isEditModePageForFinResultDataOn = false;
    $isEditModePageForSpSlidePrepDataOn = true;        //flase = view mode, true = editing mode
}

if($isCurrentPathoIsSecondOwneThisCaseLastest && $curstatusid == "13000"){
    $patient[0]['pautoscroll'] = "uresultLastSection";
}

//Move to View mode
if (isset($_POST['discard'])) {
    $patient[0]['pautoscroll'] = "NA";
    // true = Disable Edit page, false canEditPage
    $isEditModePageOn = false;      //flase = view mode, true = editing mode
    $isEditModePageForPatientInfoDataOn = false;  //flase = view mode, true = editing mode
    $isEditModePageForPlaningDataOn = false;      //flase = view mode, true = editing mode
    $isEditModePageForIniResultDataOn = false;    //flase = view mode, true = editing mode
    $isEditModePageForFinResultDataOn = false;    //flase = view mode, true = editing mode
    $isEditModePageForSpSlidePrepDataOn = false;        //flase = view mode, true = editing mode
}

//Move to View  mode
if (isset($_POST['discard_u_result'])) {
    // true = Disable Edit page, false canEditPage
    $isEditModePageOn = false;      //flase = view mode, true = editing mode
    $isEditModePageForPatientInfoDataOn = false;  //flase = view mode, true = editing mode
    $isEditModePageForPlaningDataOn = false;      //flase = view mode, true = editing mode
    $isEditModePageForIniResultDataOn = false;    //flase = view mode, true = editing mode
    $isEditModePageForFinResultDataOn = false;
    $isEditModePageForSpSlidePrepDataOn = false;        //flase = view mode, true = editing mode
}
if($debug){
    var_dump($isEditModePageOn);
    var_dump($isEditModePageForPatientInfoDataOn);
    var_dump($isEditModePageForPlaningDataOn);
    var_dump($isEditModePageForIniResultDataOn);
    var_dump($isEditModePageForFinResultDataOn);
    var_dump($isEditModePageForSpSlidePrepDataOn);
}


//var_dump($curstatus);
if (isset($curstatus[0]['back2'])) {
    //echo "back2 is set";
    $back2status = Status::getAll($conn, $curstatus[0]['back2']);
} else {
    //echo "back2 is Null";
    $back2status = null;
}

if (isset($curstatus[0]['back1'])) {
    //echo "back1 is set";
    $back1status = Status::getAll($conn, $curstatus[0]['back1']);
} else {
    //echo "back1 is Null";
    $back1status = null;
}

if (isset($curstatus[0]['next1'])) {
    //echo "next1 is set";
    $next1status = Status::getAll($conn, $curstatus[0]['next1']);
} else {
    //echo "next1 is Null";
    $next1status = null;
}

if (isset($curstatus[0]['next2'])) {
    //echo "next2 is set";
    $next2status = Status::getAll($conn, $curstatus[0]['next2']);
} else {
    //echo "next2 is Null";
    $next2status = null;
}

if (isset($curstatus[0]['next3'])) {
    //echo "next3 is set";
    $next3status = Status::getAll($conn, $curstatus[0]['next3']);
} else {
    //echo "next3 is Null";
    $next3status = null;
}

//$back1status = Status::getAll($conn, $curstatus[0]['back1']);
//$next1status = Status::getAll($conn, $curstatus[0]['next1']);
//$next2status = Status::getAll($conn, $curstatus[0]['next2']);
//$statusLists = Status::getAll($conn);
//var_dump($curstatus);
//var_dump($back2status);
//var_dump($back1status);
//var_dump($next1status);
//var_dump($next2status);
//var_dump($patient);
//var_dump($statusLists);
//var_dump($canEditModePage);







?>


<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()) : ?>
    <?php require 'blockopen.php'; ?>
    You are not login.
    <?php require 'blockclose.php'; ?>
<?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust) && !$isUnderCurHospital): ?>   
    <?php require 'blockopen.php'; ?>
    You have no authorize to view other hospital group. 
    <?php require 'blockclose.php'; ?>
<?php else : ?>


    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1">
            <div class="d-flex align-items-center justify-content-between">
                <a href="/patient.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-bed-pulse me-2"></i>ข้อมูลผู้รักษาทั้งหมด</a>
            </div>
        </div>
    </div>

<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1  border border-secondary">
        <!--hr noshade="noshade" width="" size="8"-->
<?php require 'includes/patient_status.php'; ?>
    </div>
</div>
    
    

<form id="patient_detail" name="" method="post">
    <?php $isEnableEditButton = ($isCurUserAdmin || (( $isCurStatus_1000 || $isCurStatus_2000 ) && ($isCurUserPatho || $isCurUserPathoAssis || $isCurUserLabOfficerNB || $isCurUserAdminStaff || $isCurrentPathoIsOwnerThisCase) ) || (( $isCurStatus_3000 || $isCurStatus_6000 || $isCurStatus_10000 || $isCurStatus_12000 || $isCurStatus_13000 || $isCurStatus_20000 ) && ($isCurrentPathoIsOwnerThisCase)) ); ?>             
    <div id="patient_detail_section" class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1  border border-secondary">
            <!--hr noshade="noshade" width="" size="8"-->
            <h4 align="center"><b>รับเข้า/ใส่ข้อมูลผู้ป่วย</b> <span style="color:orange;"><?= ($curstatusid == "1000") ? "<-ขั้นตอนปัจจุบัน" : "" ?></span>
            <?php if ($curstatusid == "1000" && !$isEditModePageOn) : ?>
                        <button name="btnmove2000" id="btnmove2000" type="submit" class="btn btn-primary"  <?= $isEnableEditButton ? "" : "disabled"; ?>   >&nbsp;&nbsp;Next step&nbsp;&nbsp;</button>
            <?php endif; ?></h4>

            <?php if ($isEditModePageOn) : ?>
                <?php if ($isEditModePageForPatientInfoDataOn) : ?>
                    <?php if ($curstatusid == "1000") : ?> <button name="save_patient_detail_next" type="submit" class="btn btn-primary">&nbsp;&nbsp;Finish/Next&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp; <?php endif; ?>
                    <button name="save_patient_detail" type="submit" class="btn btn-primary">&nbsp;&nbsp;Save&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
                    <button name="discard_patient_detail" type="submit" class="btn btn-primary">&nbsp;&nbsp;Discard&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
                <?php endif; ?>
            <?php else : ?>
                <?php if (!$isEditModePageForPatientInfoDataOn) : ?>
                    <button name="edit_patient_detail" type="submit" class="btn btn-primary"  <?= (($curstatusid >= 1000)) ? "" : "disabled"; ?>   >&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>
                <?php endif; ?>
            <?php endif; ?> 
            <?php require 'includes/patient_form_010_detail.php'; ?>



        </div>
    </div>
</form>


    <?php
    $userAuthEdit = (
            $isCurUserAdmin || $isCurUserPatho || $isCurUserPathoAssis || $isCurUserLabOfficerNB || $isCurUserAdminStaff
            //|| $isCurUserClinicianCust 
            //|| $isCurUserHospitalCust
            );
    $curStatusAuthEdit = (
            $isCurStatus_1000 || $isCurStatus_2000 || $isCurStatus_3000 || $isCurStatus_6000 || $isCurStatus_10000 || $isCurStatus_12000 || $isCurStatus_13000 || $isCurStatus_20000
            );
    ?>
    <form id="patient_plan" name="" method="post">
        <div id="patient_plan_section" class="container-fluid pt-4 px-4">
            <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
                <!--hr noshade="noshade" width="" size="8" -->
                <h4 align="center"><b>วางแผนงานวินิจฉัย โดยสถาบันเอ็นบี</b><span style="color:orange;"><?= ($curstatusid == "2000") ? "<-ขั้นตอนปัจจุบัน" : "" ?></span>
    <?php if ($curstatusid == "2000" && !$isEditModePageOn) : ?>
                            <button name="btnmove3000_10000" id="btnmove3000_10000" type="submit" class="btn btn-primary"  <?= $isEnableEditButton ? "" : "disabled"; ?>   >&nbsp;&nbsp;Next step&nbsp;&nbsp;</button>
    <?php endif; ?></h4>

    <?php if ($isEditModePageOn) : ?>
                            <?php if ($isEditModePageForPlaningDataOn) : ?>
                        <p align="left">
                                <?php if ($curstatusid == "2000") : ?><button name="save_patient_plan_next" type="submit" class="btn btn-primary">&nbsp;&nbsp;Finish/Next&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp; <?php endif; ?>
                            <button name="save_patient_plan" type="submit" class="btn btn-primary">&nbsp;&nbsp;Save&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
                            <button name="discard_patient_plan" type="submit" class="btn btn-primary">&nbsp;&nbsp;Discard&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;

                    <?php endif; ?>
                    <?php else : ?>
                        <?php $isEnableEditButton = ($isCurUserAdmin || (( $isCurStatus_1000 || $isCurStatus_2000 ) && ($isCurUserPatho || $isCurUserPathoAssis || $isCurUserLabOfficerNB || $isCurUserAdminStaff || $isCurrentPathoIsOwnerThisCase) ) || (( $isCurStatus_3000 || $isCurStatus_6000 || $isCurStatus_10000 || $isCurStatus_12000 || $isCurStatus_13000 || $isCurStatus_20000 ) && ($isCurrentPathoIsOwnerThisCase)) ); ?>
        <?php if (!$isEditModePageOn || !$isEditModePageForPlaningDataOn) : ?>
                        <p align="left"><button name="edit_patient_plan" type="submit" class="btn btn-primary"  <?= ($curstatusid >= 2000) ? "" : "disabled"; ?>   >&nbsp;&nbsp;Edit&nbsp;&nbsp;</button></p>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php require 'includes/patient_form_020_specimen_type.php'; ?>
                    <?php require 'includes/patient_form_030_prepare_specimen.php'; ?>
                <?php require 'includes/patient_form_040_prepare_slide.php'; ?>
                
                <?php require 'includes/patient_form_060_lab_cell.php'; ?>
                <?php require 'includes/patient_form_065_assigned_patho.php'; ?>

            </div>
        </div>
    </form>


    <div id="specimen_prep_section" class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
            <!--hr noshade="noshade" width="" size="8" -->
            <h4 align="center"><b>เตรียมชิ้นเนื้อ</b><span style="color:orange;"><?= ($curstatusid == "3000") ? "<-ขั้นตอนปัจจุบัน" : "" ?></span></h4>
        </div>
    </div>

    <div id="slide_prep_section" class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
            <!--hr noshade="noshade" width="" size="8" -->
            <h4 align="center"><b>เตรียมสไลด์</b><span style="color:orange;"><?= ($curstatusid == "6000") ? "<-ขั้นตอนปัจจุบัน" : "" ?></span></h4>
        </div>
    </div>

    <div id="lab_fluid_section_section" class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
            <!--hr noshade="noshade" width="" size="8" -->
            <h4 align="center"><b>แลปเซลล์วิทยา</b><span style="color:orange;"><?= ($curstatusid == "10000") ? "<-ขั้นตอนปัจจุบัน" : "" ?></span></h4>
        </div>
    </div>

    
    <div id="interim_result_section" class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1  border border-secondary">
            <!-- hr noshade="noshade" width="" size="8" -->
            <h4 align="center"><b>ข้อมูลสิ่งส่งตรวจ</b></h4> 
    <?php if ($isCurrentPathoIsOwnerThisCase): ?>
                <p align="center">คุณคือผู้ออกผลของผู้ป่วยท่านนี้</p>
    <?php else: ?> 
                <p align="center">คุณไม่ไช่ผู้ออกผลของผู้ป่วยท่านนี้ คุณสามารถดูข้อมูลได้เท่านั้น</p>
    <?php endif; ?>
        <form id="patient_interim_result" name="" method="post">
        <?php if ($isEditModePageOn) : ?>
            <?php if ($isEditModePageForIniResultDataOn) : ?>
                    <p align="left"><button name="save_interim_result" type="submit" class="btn btn-primary">&nbsp;&nbsp;Save&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
                        <button name="discard_interim_result" type="submit" class="btn btn-primary">&nbsp;&nbsp;Discard&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
                    </p>
            <?php endif; ?>
        <?php else : ?>
            
            <?php if (!$isEditModePageForIniResultDataOn) : ?>
                        <p align="left"><button name="edit_interim_result" type="submit" class="btn btn-primary"  <?= $isCurrentPathoIsOwnerThisCase && $isReleased ? "" : "disabled"; ?>   >&nbsp;&nbsp;Edit&nbsp;&nbsp;</button></p>
             <?php endif; ?>
  
        <?php endif; ?>
            <?php require 'includes/patient_form_070_interim_result.php'; ?>
            </form>
        </div>
    </div>


    <div id="diag_result_section" class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1  border border-secondary">
            <h4 align="center"><b>วินิจฉัย/ผลการตรวจ</b><span style="color:orange;"><?= ($curstatusid == "12000") ? "<-ขั้นตอนปัจจุบัน" : "" ?></span> 
    <?php if ($curstatusid != "12000" && !$isEditModePageOn) : ?>
                        <button name="btnmove12000" id="btnmove12000" type="submit" class="btn btn-primary"  <?= $isCurrentPathoIsOwnerThisCase ? "" : "disabled"; ?>   >&nbsp;&nbsp;Start Diagnostic&nbsp;&nbsp;</button>
    <?php endif; ?>

                </h4>
                    <?php if ($isUpdateResultAval) : ?>
                <!--hr noshade="noshade" width="" size="8"-->
                        <?php require 'includes/patient_form_080_result.php'; ?>
                    <?php endif; ?>
        </div>
    </div>

    <?php endif; ?>



<div id="slide_prep_section" class="container-fluid pt-4 px-4">
    <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <!--hr noshade="noshade" width="" size="8" -->
        <h4 align="center"><b>แพทย์คนที่สองรีวิว</b><span style="color:orange;"><?= ($curstatusid == "13000") ? "<-ขั้นตอนปัจจุบัน" : "" ?></span></h4>
    </div>
</div>

<div id="slide_sp_prep_section" class="container-fluid pt-4 px-4">
    <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <!--hr noshade="noshade" width="" size="8" -->
        <h4 align="center"><b>สั่งย้อมพิเศษ</b><span style="color:orange;"><?= ($curstatusid == "8000") ? "<-ขั้นตอนปัจจุบัน" : "" ?></span></h4>
        <form id="slide_prep" name="" method="post">
            

        <?php if ($isEditModePageOn) : ?>
            <?php if ($isEditModePageForSpSlidePrepDataOn) : ?>
                <button name="save_sp_prep_slide" type="submit" class="btn btn-primary">&nbsp;&nbsp;Save&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
                <button name="discard_sp_prep_slide" type="submit" class="btn btn-primary">&nbsp;&nbsp;Discard&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
            <?php endif; ?>
        <?php else : ?>
            <?php if (!$isEditModePageForSpSlidePrepDataOn) : ?>
                <button name="edit_sp_prep_slide" type="submit" class="btn btn-primary"  <?= (($curstatusid >= 8000) || $isCurrentPathoIsOwnerThisCase) ? "" : "disabled"; ?>   >&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>
            <?php endif; ?>
        <?php endif; ?> 
            
            
        <?php require 'includes/patient_form_050_prepare_sp_slide.php'; ?>
        </form>
    </div>
</div>
    
<div id="finish_section" class="container-fluid pt-4 px-4">
    <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <!--hr noshade="noshade" width="" size="8" -->
        <h4 align="center"><b>ออกผลแล้ว</b><span style="color:green;"><?= ($curstatusid == "20000") ? "<-ขั้นตอนปัจจุบัน" : "" ?></span></h4>
    </div>
</div>


<?php if (!($isEditModePageOn || $isEditModePageForFinResultDataOn)) : ?>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1  border border-secondary">
            <p align="center"><a  class="btn btn-primary" href="patient_pdf.php?id=<?= $patient[0]['id']; ?>" target="_blank">PreView PDF</a>    </p>      
        </div>
    </div>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>


<script type="text/javascript">
    $(document).ready(function () {
        //set active tab
        $("#patienttab").addClass("active");

        // prevent from unsave
        function onNosave(e) {
            e.preventDefault();
            e.returnValue = '';
        }

        $("input").change(function () {
            window.addEventListener("beforeunload", onNosave);
        });

        $(":submit").click(function () {
            window.removeEventListener("beforeunload", onNosave);
        })
    });
</script>