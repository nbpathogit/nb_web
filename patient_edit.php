<?php
//var_dump($_GET);

require 'includes/init.php';
$inited = TRUE;
$conn = require 'includes/db.php';
Auth::requireLogin("patient_edit.php", $_GET['id']);

/*

  step   DOM_Section
  1000   patient_detail_section
  2000   patient_plan_section
  3000   specimen_prep_section
  6000   slide_prep_section
  10000  lab_fluid_section_section
  11000  interim_result_section
  12000  diag_result_section
  13000  confirm_result_section

 */

$debug = false;
if ($debug) {
    $hidden_data2dom = false;
    $is_vardump = true;
} else {
    $hidden_data2dom = true;
    $is_vardump = false;
}


$isAddPage = false; // if add page then diable edit almost of all.
// true = Disable Edit page, false canEditPage
$isEditModePageOn = false;      //flase = view mode, true = editing mode
$isEditModePageForPatientInfoDataOn = false;  //flase = view mode, true = editing mode
$isEditModePageForPlaningDataOn = false;      //flase = view mode, true = editing mode
$isEditModePageForIniResultDataOn = false;    //flase = view mode, true = editing mode
$isEditModePageForFinResultDataOn = false;    //flase = view mode, true = editing mode
$isEditModePageForSpSlidePrepDataOn = false;

$pautoscroll_post = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //var_dump($_POST);die();
    //    die();
    //
    if (isset($_POST['refreshpage'])) {
        $pautoscroll_post = $_POST['pautoscroll'];
        
    }

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
//            $isUpdateReleaseTimeError = Presultupdate::updateReleaseTime($conn, $_POST["uresultinxlist"]); //Last index
//            $isUpdateTypeNameError = Patient::updateReportTypeName($conn, $_GET['id'], $_POST['uresultTypeName']);
            
            //================= Need to move to JS ajax later==================================
            $isUpdateReportAsError = Patient::updateReportAs($conn, $_GET['id'], $_POST['reported_as']);


            //=============Generate PDF When released=================
            //$patient_id = 178;
            $pdfOutputOption = 'F';
            $hideTable = true;
            $requestFrom = 'patient_edit_php';
            require 'patient_pdf.php';
            //================================
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

        //Patient::setAutoScroll($conn, $_GET['id'], "uresultLastSection");
        //Patient::setisautoeditmode($conn, $_GET['id'], "uresultLastSection");
        //                var_dump($_POST);
        //                die();

        if ($presultupdate->create($conn)) {
            Url::redirect("/patient_edit.php?id=" . $_GET['id']);
        } else {
            echo '<script>alert("Add result fail. Please verify again")</script>';
        }
    }

    if (isset($_POST['save_u_result'])) {
        // var_dump($_POST);
        // exit;
        //Patient::setAutoScroll($conn, $_GET['id'], "uresultLastSection");
        if (Presultupdate::updateResult($conn, $_POST['id'], $_POST['pathologist_id'], $_POST['pathologist2_id'], $_POST['result_message'])) {
            if (isset($_POST['critical_report'])) { // check critical report
                if (Patient::addCriticalReport($conn, $_GET['id'], 1)) {
                    Url::redirect("/patient_edit.php?id=" . $_GET['id']);
                } else {
                    echo '<script>alert("Add critical report fail. Please verify again")</script>';
                }
            } else { // not check critical report
                if (Patient::addCriticalReport($conn, $_GET['id'], 0)) {
                    Url::redirect("/patient_edit.php?id=" . $_GET['id']);
                } else {
                    echo '<script>alert("Add critical report fail. Please verify again")</script>';
                }
            }
        } else {
            echo '<script>alert("Add result fail. Please verify again")</script>';
        }
    }


    if (isset($_POST['discard_u_result'])) {
        //var_dump($_POST);
        //Patient::setAutoScroll($conn, $_GET['id'], "uresultLastSection");
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
        isset($_POST['ppre_name']) ? $patient->ppre_name = $_POST['ppre_name'] : $patient->ppre_name = $patientini[0]['ppre_name'];
        isset($_POST['pname']) ? $patient->pname = $_POST['pname'] : null;
        isset($_POST['plastname']) ? $patient->plastname = $_POST['plastname'] : null;
        isset($_POST['pgender']) ? $patient->pgender = $_POST['pgender'] : null;
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
//        var_dump($_POST); die();

        isset($_POST['pnum']) ? $patient->pnum = $_POST['pnum'] : null;
        isset($_POST['plabnum']) ? $patient->plabnum = $_POST['plabnum'] : null;
        isset($_POST['ppre_name']) ? $patient->ppre_name = $_POST['ppre_name'] : null;
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
        isset($_POST['ppre_name']) ? $patient->ppre_name = $_POST['ppre_name'] : null;
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
    if (isset($_POST['save_sp_prep_slide'])) {

        //var_dump($_POST);die();
        $patient = new Patient();
        //Get Specific Row from Table
        if (isset($_GET['id'])) {
            $patient = Patient::getByID($conn, $_GET['id']);
        } else {
            $patient = null;
        }
        //var_dump($_POST);


        isset($_POST['p_slide_prep_sp_id']) ? $patient->p_slide_prep_sp_id = $_POST['p_slide_prep_sp_id'] : null;
        isset($_POST['pspprice']) ? $patient->pspprice = $_POST['pspprice'] : null;
        isset($_POST['p_sp_patho_comment']) ? $patient->p_sp_patho_comment = $_POST['p_sp_patho_comment'] : null;

        $patient->isautoeditmode = "NA"; //save only
        $patient->pautoscroll = "slide_sp_prep_section"; //set auto scroll

        if ($patient->updateSpcialSlide($conn, $_GET['id'])) {
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
$patient_id = 0;
if (isset($_GET['id'])) {
    $patient = Patient::getAll($conn, $_GET['id']);
    $patient_id = $_GET['id'];
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
    echo ("No Patient ID " . $_GET['id'] . ".");
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
$specimens = ServicePriceList::getSpecimen($conn);
$clinicians = User::getAllbyClinicians($conn);
$userPathos = User::getAllbyPathologis($conn);
$userTechnic = User::getAllbyTeachien($conn);   //2000 2100 2200
$userCytologist = User::getAllbyCytologist($conn);
$prioritys = Priority::getAll($conn);
$statusLists = Status::getAll($conn);
$labFluids = LabFluid::getAll($conn);
$userGroupLists = Ugroup::getAll($conn);
$jobRoles = JobRole::getAll($conn);
$rsResultType2s = ReportType::getAllbyGroup2($conn);

//var_dump($userPathos);
//die();
//Get one by id
$presultupdates = Presultupdate::getAll($conn, $_GET['id']);
$presultupdate1s = Presultupdate::getAllofGroup1($conn, $_GET['id']);
$presultupdate2s = Presultupdate::getAllofGroup2($conn, $_GET['id']);
$presultupdate3s = Presultupdate::getAllofGroup3($conn, $_GET['id']);




$clinician = User::getAll($conn, $patient[0]['pclinician_id']);

$pathoOwnerNameObj = User::getByID($conn, $patient[0]['ppathologist_id']);

$billings = ServiceBilling::getAll($conn, $_GET['id'], 1);
$billing2s = ServiceBilling::getAll($conn, $_GET['id'], 2);

$job_crosss = Job::getCrossSection($conn, $patient[0]['id']);
$job_assis_crosss = Job::getAssisCrossSection($conn, $patient[0]['id']);
$job3s = Job::getByPatientJobRole($conn, $patient[0]['id'], 3);
//$job4s = Job::getByPatientJobRole($conn, $patient[0]['id'], 4);
$job4s = Job::getByPatientJobRole_Unassigned($conn, $patient[0]['id'], 4);
$job5s = Job::getByPatientJobRole($conn, $patient[0]['id'], 5); // Patho1
$job6s = Job::getByPatientJobRole($conn, $patient[0]['id'], 6); // Second Patho1
$job7s = Job::getByPatientJobRole($conn, $patient[0]['id'], 7); // Cycologist

$outsideContracts = OutsideContract::getAll($conn);
$hires = HireList::getAll($conn, $patient[0]['id']);

//var_dump($billings);die();
//$patho2OwnerName = User::getName($conn, $patient[0]['ppathologist_id']);
//Prepare Status/step
$curstatusid = $patient[0]['status_id'];
$curstatus = Status::getAll($conn, $patient[0]['status_id']);

//Prepare release status
$isReleased = ($patient[0]['reported_as'] == "ยังไม่ออกผล");


//Check whether date of fisrt report is define
$isset_date_first_report = 0;
if (isset($patient[0]['date_first_report'])) {
    $isset_date_first_report = 1;
} else {
    $isset_date_first_report = 0;
}

$isLastReleaseGroup2SecondPathoAval = FALSE;
foreach ($presultupdate2s as $prsu) {
    $result_id = $prsu['id'];
    $job = Job::getByPatientJobRoleUResult($conn, $patient_id, 6, $result_id);
    $isLastReleaseGroup2SecondPathoAval = isset($job[0]['name']) ? TRUE : FALSE;


//    echo "result_id=";    var_dump($result_id); echo "<br>";
//    echo "patient_id=";   var_dump($patient_id); echo "<br>";
//    echo "job=";          var_dump($job); echo "<br>";
//    echo "isLastReleaseGroup2SecondPathoAval=";          var_dump($isLastReleaseGroup2SecondPathoAval); echo "<br>";echo "<br>";
}

$isLastReleaseGroup3SecondPathoAval = FALSE;
foreach ($presultupdate3s as $prsu) {
    $result_id = $prsu['id'];
    $job = Job::getByPatientJobRoleUResult($conn, $patient_id, 6, $result_id);
    $isLastReleaseGroup3SecondPathoAval = isset($job[0]['name']) ? TRUE : FALSE;


//    echo "result_id=";    var_dump($result_id); echo "<br>";
//    echo "patient_id=";   var_dump($patient_id); echo "<br>";
//    echo "job=";          var_dump($job); echo "<br>";
//    echo "isLastReleaseGroup2SecondPathoAval=";          var_dump($isLastReleaseGroup2SecondPathoAval); echo "<br>";echo "<br>";
}
//die();
//เช็คและเตรียมตัวแปรสถานะปัจจุบัน
require 'includes/status_cur.php';


require 'user_auth.php';

//Move to edit mode
if (isset($_GET['pautoscroll'])) {
    $patient[0]['pautoscroll'] = $_GET['pautoscroll'];
}

if($pautoscroll_post!=''){
    $patient[0]['pautoscroll'] = $pautoscroll_post;
}

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
if (isset($_POST['edit_u_result']) || $patient[0]['isautoeditmode'] == "uresultLastSection") {

    //$patient[0]['pautoscroll'] = "uresultLastSection";
    // true = Disable Edit page, false canEditPage
    $isEditModePageOn = true;      //flase = view mode, true = editing mode
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

//if ($isCurrentPathoIsSecondOwneThisCase && $curstatusid == "13000") {
//    //$patient[0]['pautoscroll'] = "uresultLastSection";
//}

if (isset($_GET['focus'])) {
    $patient[0]['pautoscroll'] = $_GET['focus'];
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
if ($debug) {
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
    <?php require 'includes/footer.php'; ?>
    <?php die(); ?>
<?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust) && !$isUnderCurHospital) : ?>
    <?php require 'blockopen.php'; ?>
    You have no authorize to view other hospital group.
    <?php require 'blockclose.php'; ?>
    <?php require 'includes/footer.php'; ?>
    <?php die(); ?>
<?php else : ?>
<?php endif; ?>

<?php if (!empty($errors)) : ?>
    <br>
    <div class="alert alert-warning" role="alert">
        <?php foreach ($errors as $error) : ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
        <div class="d-flex align-items-center justify-content-between">
            <a href="/patient.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-bed-pulse me-2"></i>ข้อมูลผู้รักษาทั้งหมด</a>
        </div>
    </div>
</div>
<?php require 'includes/data2DOM.php'; ?>
<div class="container-fluid pt-4 px-4">
    <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1  border border-secondary">
        <!--hr noshade="noshade" width="" size="8"-->
        <?php require 'includes/patient_status.php'; ?>
    </div>
</div>

<?php //========================================รับเข้า/ใส่ข้อมูลผู้ป่วย========================================================================================================  ?>
<?php $isEnableEditButton = ($isCurUserAdmin || (($isCurStatus_1000 || $isCurStatus_2000) && ($isCurUserPatho || $isCurUserPathoAssis || $isCurUserLabOfficerNB || $isCurUserAdminStaff || $isCurrentPathoIsOwnerThisCase)) || (($isCurStatus_3000 || $isCurStatus_6000 || $isCurStatus_10000 || $isCurStatus_12000 || $isCurStatus_13000 || $isCurStatus_20000) && ($isCurrentPathoIsOwnerThisCase))); ?>
<div id="patient_detail_section" class="container-fluid pt-4 px-4">
    <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1  border border-secondary">

        <!--hr noshade="noshade" width="" size="8"-->
        <h4 align="center"><b>รับเข้า/ใส่ข้อมูลผู้ป่วย</b> <span style="color:orange;"><?= ($curstatusid == "1000") ? "<b> <-ขั้นตอนปัจจุบัน</b>" : "" ?></span>

            <?php if ($curstatusid == "1000" && !$isEditModePageOn) : ?>
                <?php if (!$isCurUserCust): ?>
                <button name="btnmove2000" id="btnmove2000" type="submit" class="btn btn-primary" <?= $isEnableEditButton ? "" : "disabled"; ?>>&nbsp;&nbsp;Next step&nbsp;&nbsp;</button>
                <?php endif; ?>
            <?php endif; ?>
        </h4>

        <form id="patient_detail" name="" method="post">
            <?php if ($isEditModePageOn) : ?>
                <?php if ($isEditModePageForPatientInfoDataOn) : ?>
                    <?php if ($curstatusid == "1000") : ?> <button name="save_patient_detail_next" type="submit" class="btn btn-primary">&nbsp;&nbsp;Finish/Next&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp; <?php endif; ?>
                    <button name="save_patient_detail" type="submit" class="btn btn-primary">&nbsp;&nbsp;Save&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
                    <a class="btn btn-primary" href="patient_edit.php?id=<?= $patient[0]['id']; ?>&focus=patient_detail_section">Discard</a>
                <?php endif; ?>
            <?php else : ?>
                <?php if (!$isEditModePageForPatientInfoDataOn) : ?>
                    <?php if (!$isCurUserCust): ?>
                    <button name="edit_patient_detail" type="submit" class="btn btn-primary" <?= (($curstatusid >= 1000)) ? "" : "disabled"; ?>>&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php require 'includes/patient_form_010_detail.php'; ?>
        </form>

    </div>
</div>
    

    
<?php
    $userAuthEdit = ($isCurUserAdmin || $isCurUserPatho || $isCurUserPathoAssis || $isCurUserLabOfficerNB || $isCurUserAdminStaff
        //|| $isCurUserClinicianCust 
        //|| $isCurUserHospitalCust
        );
$curStatusAuthEdit = ($isCurStatus_1000 || $isCurStatus_2000 || $isCurStatus_3000 || $isCurStatus_6000 || $isCurStatus_10000 || $isCurStatus_12000 || $isCurStatus_13000 || $isCurStatus_20000
        );
?>

<?php //START=================สิ่งส่งตรวจ และ แพทย์ผู้ตรวจ=====================================================================================================?>
<div id="patient_plan_section" class="container-fluid pt-4 px-4">
    <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">

        <!--hr noshade="noshade" width="" size="8" -->
        <h4 align="center"><b>สิ่งส่งตรวจ และ แพทย์ผู้ตรวจ</b><span style="color:orange;"> <?= ""; // ($curstatusid == "2000") ? "<b> <-ขั้นตอนปัจจุบัน</b>" : ""   ?></span>
            <?php if ($hide) : ?>
                <?php if ($curstatusid == "2000" && !$isEditModePageOn) : ?>
                    <button name="btnmove3000_10000" id="btnmove3000_10000" type="submit" class="btn btn-primary" <?= $isEnableEditButton ? "" : "disabled"; ?>>&nbsp;&nbsp;Next step&nbsp;&nbsp;</button>
                <?php endif; ?>
            <?php endif; ?>

        </h4>

        <?php if (false) : ?>
            <form id="patient_plan" name="" method="post">
                <?php if ($isEditModePageOn) : ?>
                    <?php if ($isEditModePageForPlaningDataOn) : ?>
                        <p align="left">
                            <?php if ($curstatusid == "2000") : ?><button name="save_patient_plan_next" type="submit" class="btn btn-primary">&nbsp;&nbsp;Finish/Next&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp; <?php endif; ?>
                            <button name="save_patient_plan" type="submit" class="btn btn-primary">&nbsp;&nbsp;Save&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
                            <a class="btn btn-primary" href="patient_edit.php?id=<?= $patient[0]['id']; ?>&focus=patient_plan_section">Discard</a>
                        </p>
                    <?php endif; ?>
                <?php else : ?>
                    <?php $isEnableEditButton = ($isCurUserAdmin || (($isCurStatus_1000 || $isCurStatus_2000) && ($isCurUserPatho || $isCurUserPathoAssis || $isCurUserLabOfficerNB || $isCurUserAdminStaff || $isCurrentPathoIsOwnerThisCase)) || (($isCurStatus_3000 || $isCurStatus_6000 || $isCurStatus_10000 || $isCurStatus_12000 || $isCurStatus_13000 || $isCurStatus_20000) && ($isCurrentPathoIsOwnerThisCase))); ?>
                    <?php if (!$isEditModePageOn || !$isEditModePageForPlaningDataOn) : ?>
                        <p align="left"><button name="edit_patient_plan" type="submit" class="btn btn-primary" disabled <?= ($curstatusid >= 2000) ? "" : "disabled"; ?>>&nbsp;&nbsp;Edit&nbsp;&nbsp;</button></p>
                    <?php endif; ?>
                <?php endif; ?>

                <?php //require 'includes/patient_form_020_specimen_type.php'; ?>

                <?php //require 'includes/patient_form_065_assigned_patho.php'; ?>
            </form>
        <?php endif; ?>
        <?php  if($patient[0]['sn_type']=='PN' || $patient[0]['sn_type']=='LN'): ?>
            <?php require 'includes/patient_form_067_job7_assigned_cytologist.php'; ?>
            <?php require 'includes/patient_form_065_job5_assigned_patho.php'; ?> 
        <?php else: ?>
            <?php require 'includes/patient_form_065_job5_assigned_patho.php'; ?> 
        <?php endif; ?>
        <?php require 'includes/patient_form_015_slide1_add_specimen.php'; ?>

    </div>
</div>
<?php //END=================สิ่งส่งตรวจ และ แพทย์ผู้ตรวจ=====================================================================================================?>


<?php //START === PN/LN ================================================================================================================================ ?>
<?php  if($patient[0]['sn_type']=='PN'): ?>

<div id="diag_result_section" class="container-fluid pt-4 px-4">
    <div class="bg-nb bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1  border border-secondary">
        <h4 align="center"><b><span style="color:red"></span>วินิจฉัย/ผลการตรวจ</b><span style="color:orange;"><?= ($curstatusid == "12000") ? "<b> <-ขั้นตอนปัจจุบัน</b>" : "" ?></span>

        </h4>



        <?php if ($isUpdateResultAval) : ?>
            <!--hr noshade="noshade" width="" size="8"-->
            <?php require 'includes/patient_form_080_job7_result.php'; ?>
        <?php endif; ?>
    </div>
</div>
    
<?php //END === PN/LN ================================================================================================================================ ?>
    
<?php //START == NON PN ============================================================================================================================?>
<?php else: ?>
    
    
    
    
    
    
    
    
    
    
    
    
    
<?php //เตรียมชิ้นเนื้อ  ?>
<?php if (!$isCurUserCust): ?>
    <div id="specimen_prep_section" class="container-fluid pt-4 px-4">
        <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
            <!--hr noshade="noshade" width="" size="8" -->
            <h4 align="center"><b>เตรียมชิ้นเนื้อ</b><span style="color:orange;"><?= ""; // ($curstatusid == "3000") ? "<b> <-ขั้นตอนปัจจุบัน</b>" : ""    ?></span></h4>
            <?php require 'includes/patient_form_030_job1_prepare_specimen.php'; ?>
            <?php require 'includes/patient_form_035_job2_prepare_specimen.php'; ?>
        </div>
    </div>
<?php endif; ?>
    


<?php if (!$isCurUserCust): ?>
    <div id="slide_prep_section" class="container-fluid pt-4 px-4">
        <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
            <!--hr noshade="noshade" width="" size="8" -->
            <h4 align="center"><b>เตรียมสไลด์(จุลพยาธิวิทยา)</b><span style="color:orange;"><?= ""; // ($curstatusid == "6000") ? "<b> <-ขั้นตอนปัจจุบัน</b>" : ""    ?></span></h4>
            <?php require 'includes/patient_form_040_job3_prepare_slide.php'; ?>
        </div>
    </div>
<?php endif; ?>
    


<?php if (!$isCurUserCust && FALSE): ?>
    <div id="lab_fluid_section_section" class="container-fluid pt-4 px-4">
        <div class="bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
            <!--hr noshade="noshade" width="" size="8" -->
            <h4 align="center"><b>เตรียมสไลด์เซลล์วิทยา</b><span style="color:orange;"><?= ""; // ($curstatusid == "10000") ? "<b> <-ขั้นตอนปัจจุบัน</b>" : ""    ?></span></h4>
            <?php require 'includes/patient_form_060_hire1_lab_cell.php'; ?>
        </div>
    </div>
<?php endif; ?>



    

<div id="slide_sp_prep_section" class="container-fluid pt-4 px-4">
            
    <!--<h4 align="center"><a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample"><b>ตรวจพิเศษ</b><span style="color:orange;"><?= ""; // ($curstatusid == "8000") ? "<b> <-ขั้นตอนปัจจุบัน</b>" : ""    ?></span></a></h4>-->
    <div class="bg-nb  bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary"   id="collapseExample">
        <!--hr noshade="noshade" width="" size="8" --> 
        <h4 align="center"><b>ตรวจพิเศษ</b><span style="color:orange;"><?= ""; // ($curstatusid == "8000") ? "<b> <-ขั้นตอนปัจจุบัน</b>" : ""    ?></span>

            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#slide_sp_prep_section_collapse" aria-expanded="false" aria-controls="slide_sp_prep_section_collapse">
            ซ่อน/แสดง
            </button>
        </h4>
        <div id="slide_sp_prep_section_collapse" class="collapse">
        <span id="sp_status_message">
            <?php
            if ($patient[0]['request_sp_slide'] == 1) {
                echo '<h3 align="center" style="color: #ff8000;font-weight: bold;">ร้องขอตรวจพิเศษ</h3>';
            } elseif ($patient[0]['request_sp_slide'] == 2) {
                echo '<h3 align="center" style="color: #30A64A;font-weight: bold;">เสร็จสิ้นตรวจพิเศษ</h3>';
            } else {
                echo '<h3 align="center" style="color: #ff8000;font-weight: bold;"></h3>';
            }
            ?>
        </span>
        
        <!--<hr>-->
           
        <!--<hr noshade="noshade" width="" size="4">-->
        <!--<hr>-->
        <!--<h3 align="center" style="color: #30A64A;">เสร็จสิ้นย้อมพิเศษ</h3>-->
        <?php //require 'includes/patient_form_055_job4_prepare_sp_slide.php'; ?>
        
        
        

     

            <?php require 'includes/patient_form_050_slide2_prepare_sp_slide.php'; ?>

            <!--<hr>-->
            <!--<form id="slide_prep" name="" method="post">-->

            
        </div>
    </div>
</div>







 





<div id="diag_result_section" class="container-fluid pt-4 px-4">
    <div class="bg-nb bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1  border border-secondary">
        <h4 align="center"><b>วินิจฉัย/ผลการตรวจ</b><span style="color:orange;"><?= ($curstatusid == "12000") ? "<b> <-ขั้นตอนปัจจุบัน</b>" : "" ?></span>

        </h4>



        <?php if ($isUpdateResultAval) : ?>
            <!--hr noshade="noshade" width="" size="8"-->
            <?php require 'includes/patient_form_080_job6_result.php'; ?>
        <?php endif; ?>
    </div>
</div>


<?php //END == NON PN ============================================================================================================================?>
<?php endif; ?>  




<?php if ($curstatusid == "20000") : ?>
    <div id="finish_section" class="container-fluid pt-4 px-4">
        <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
            <!--hr noshade="noshade" width="" size="8" -->
            <h4 align="center"><b>เสร็จสิ้น</b><span style="color:green;"><?= ($curstatusid == "20000") ? "<b> <-ขั้นตอนปัจจุบัน</b>" : "" ?></span></h4>
        </div>
    </div>
<?php endif; ?>
    
    

    

<div id="diag_result_section" class="container-fluid pt-4 px-4">
    <div class="bg-nb bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1  border border-secondary">
        <h4 align="center"><b>PDF</b><span style="color:orange;"></span>

        </h4>



        <?php if ($isUpdateResultAval) : ?>
            <!--hr noshade="noshade" width="" size="8"-->
            <?php require 'includes/patient_form_100_pdf.php'; ?>
        <?php endif; ?>
    </div>
</div>


<span id="end_section">    </span>

<?php require 'includes/footer.php'; ?>



<script src="<?= Url::getSubFolder1() ?>/ajax_slide1_specimen/specimenlist1.js?v4"></script>
<script src="<?= Url::getSubFolder1() ?>/ajax_slide2__special/specialslide2.js?v5"></script>
<script src="<?= Url::getSubFolder1() ?>/ajax_slide2__Job4_rq/specialslide2_rq.js?v3"></script>

<script src="<?= Url::getSubFolder1() ?>/ajax_job1_crossection/job1.js?v2x"></script>
<script src="<?= Url::getSubFolder1() ?>/ajax_job2_assis_cross/job2.js?v2x"></script>
<script src="<?= Url::getSubFolder1() ?>/ajax_job3_prep_slide/job3.js?v2x"></script>
<script src="<?= Url::getSubFolder1() ?>/ajax_job4_prep_sp_slide/job4.js?v2xxxxxxxxx"></script>
<script src="<?= Url::getSubFolder1() ?>/ajax_job5_patho/job5.js?v3x"></script>
<script src="<?= Url::getSubFolder1() ?>/ajax_job6_patho/job6.js?v6xxxx"></script>
<script src="<?= Url::getSubFolder1() ?>/ajax_job7_cytologist/job7.js?v0aaxaaa"></script>



<script src="<?= Url::getSubFolder1() ?>/ajax_hire1_fluidlab/hire1.js?v2x"></script>

<script src="<?= Url::getSubFolder1() ?>/ajax_patient_diax_result/diagresult.js?v10xxxxxxxxxxxxxxxxxxxxxxxx"></script>
<script src="<?= Url::getSubFolder1() ?>/ajax_template_rs/template_rs.js?v0xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"></script>
<script src="<?= Url::getSubFolder1() ?>/ajax_patient_diax_result/patient_status_control.js?v7"></script>

<script type="text/javascript">
                    var sn_type = '<?= $patient[0]['sn_type']; ?>';
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
                        
                        $("#phospital_id").change(function () {
                            
                            console.log('\n\n\n===================================================================\n');
                            console.log('======================hospital_change==============================\n');
                            console.log('===================================================================\n\n\n');
                            
                            let phospital_id = $("#phospital_id  option").filter(':selected').attr('value');
//
//                            alert('phospital_id:'+phospital_id);
                            $.ajax({
                                type: 'POST',
                                url: "ajax_patient/getUserByHospitalID.php?hospital_id=%s",
                                        
                                data: {hospital_id: phospital_id},
                                success: function (data) {

//                                    console.log('data:'+data)
//                                    alert("data:"+data);
                                    
                                    $('#pclinician_id option').remove();
                                    let datajson = JSON.parse(data);
                                    if(datajson.length == 0){
                                        $('#pclinician_id').append('<option value="0" >ยังไม่มีแพทย์สำหรับโรงพยาบาลนี้</option>');
                                    }else{
                                        $('#pclinician_id').append('<option value="0" >กรุณาเลือก</option>');

                                    }
                                    for (let i in datajson)
                                    {
                                        $('#pclinician_id').append('<option value="' + datajson[i].uid + '">' + datajson[i].name + ' ' + datajson[i].lastname + ' (' + datajson[i].pre_name +')</option>');
                                    }
                                    
                                }
                            });
                            
                        });
                        
                        
                        $("#add_sp2_1_2").on("click",function (e) {
                            let sp2_1 = $("#sp2_1 option").filter(":selected").attr('value');
                            let sp2_2 = $("#sp2_2 option").filter(":selected").attr('value');
                            console.log('\n\n\n===================================================================\n');
                            console.log('sp2_1:'+sp2_1+'\n');
                            console.log('sp2_2:'+sp2_2+'\n');
                            console.log('===================================================================\n\n\n');
                            let str1 = sp2_1 + sp2_2;
                            let str2 = '<input type="checkbox" class="form-check-input"  id="SP2_'+str1+'" name="SP2_'+str1+'" va="'+str1+'" checked><label for="SP2_'+str1+'"  class="form-check-label" >'+str1+'</label>';
                            $('#SP2_Scope').append(str2);
                        });

                    });
</script>


