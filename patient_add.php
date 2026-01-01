<?php
require 'includes/init.php';

$conn = require 'includes/db.php';

if (!Auth::isLoggedIn()) {
    echo "time out plese login again";
    Url::redirect("/login.php");
} else {
    
}

$debug = false;


$isAddPage = true; // if add page then diable edit almost of all.
// true = Disable Edit page, false canEditPage
$isEditModePageOn = true;      //flase = view mode, true = editing mode
$isEditModePageForPatientInfoDataOn = true;  //flase = view mode, true = editing mode
$isEditModePageForPlaningDataOn = false;      //flase = view mode, true = editing mode
$isEditModePageForIniResultDataOn = false;    //flase = view mode, true = editing mode
$isEditModePageForFinResultDataOn = false;    //flase = view mode, true = editing mode



$patientini = Patient::getInit();
//var_dump($patientini);
//die();



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    try {
//        var_dump($_POST); die();
//        echo '<br>';


        $curyear = ""; // string
        $runnum = 0; // Integer
        $runstr = ""; // String
        $patientNumber = ""; //String

        if ($_POST['is_autogen'] == "yes") {
            //echo 'is autogen = yes <br>';
            $dbg_autogen = false;
            
            $sn_type=$_POST['snprefix'];

            $curyear = Util::get_curreint_year(); // string
//        $curyear = "23";              //fake 23 for debug new generate

            if ($dbg_autogen) {
                echo "<br>Current year (String) = " . $curyear . "<br>";
            }
            if ($dbg_autogen) {
                echo "Init runnum (Integer) = " . $runnum . "<br>";
            }
            if ($dbg_autogen) {
                echo "Init runstr (String) = " . $runstr . "<br>";
            }
            if ($dbg_autogen) {
                echo "Init sn (String) = " . $patientNumber . "<br><br>";
            }

            // If no record of new year is zero  then Set runing =1 else set runing = cur_runing + 1
            $count_sn_year = Patient::get_count_sn_year($conn, $curyear);

            if ($dbg_autogen) {
                var_dump($count_sn_year);
            }
            if ($dbg_autogen) {
                echo '<br>';
            }
            if ($dbg_autogen) {
                echo "Record Count of year " . $curyear . " = " . $count_sn_year[0]['count'] . "<br><br>";
            }

            if ($count_sn_year[0]['count'] == 0) {
                if ($dbg_autogen) {
                    echo "count = 0 <br>";
                }
                if ($dbg_autogen) {
                    echo "initial runing number = 1<br>";
                }
                $runnum = 1;
            } else {
                if ($dbg_autogen) {
                    echo "count > 0<br>";
                }
                if ($dbg_autogen) {
                    echo "get runing from DB and increasing running number by one <br>";
                }
                $a = Patient::get_max_sn_run_by_year($conn, $curyear, $sn_type);
                $b = $a[0]['max_sn_run'];
                $runnum = intval($b);
//            var_dump($runnum);
                if ($dbg_autogen) {
                    echo "Current runnum = " . $runnum . " <br>";
                }
                $runnum += 1;
                if ($dbg_autogen) {
                    echo "New runnum = " . $runnum . " <br>";
                }
            }
            $runstr = Util::prepend_string_with_zero(5, $runnum);
            $patientNumber = $sn_type . $curyear . $runstr;
            if ($dbg_autogen) {
                echo " <br>New SN = " . $patientNumber . " <br>";
            }

            if ($dbg_autogen) {
                die("stop debug");
            }
        } elseif ($_POST['is_autogen'] == "no") {
            echo 'is autogen = No <br>';
            echo "Manual fill in<br><br>";
            $sn_type = $_POST['prenum']; //// string
            $curyear = $_POST['yearnum']; // string
            $runnum = (int)$_POST['runnum']; // Integer
            $runstr = Util::prepend_string_with_zero(5, $runnum);
            $patientNumber = $sn_type . $curyear . $runstr; // String  keep manual input
        } else {
            $errors[] = " 'is_autogen' = " . $_POST['is_autogen'];
        }

//    echo "<br>Current year (String) = " . $curyear . "<br>";
//    echo "Calculate runnum (Integer) = " . $runnum . "<br>";
//    echo "Calculate runstr (String) = " . $runstr . "<br>";
//    echo "Calculate sn (String) = " . $sn . "<br><br>";


        if(isset( $_POST["super_id"])){
            $patientini = Patient::getAll($conn, $_POST["super_id"]);
        }else{
            $patientini = Patient::getInit();
        }
        

        $patient = new Patient();
        $patient->is_autogen = $_POST['is_autogen'];

        $patient->sn_type = $sn_type; // SN // string
        $patient->sn_year = $curyear; // SN // string
        $patient->sn_run = $runnum; // SN // Integer

        $patient->pnum = $patientNumber; // SN
//    die();
        isset($_POST['super_id']) ? $patient->super_id = $_POST['super_id'] : $patient->super_id = $patientini[0]['super_id'];
        isset($_POST['super_pnum']) ? $patient->super_pnum = $_POST['super_pnum'] : $patient->super_pnum = $patientini[0]['super_pnum'];
        

        
        
        
        isset($_POST['plabnum']) ? $patient->plabnum = $_POST['plabnum'] : $patient->plabnum = $patientini[0]['plabnum'];
        isset($_POST['ppre_name']) ? $patient->ppre_name = $_POST['ppre_name'] : $patient->ppre_name = $patientini[0]['ppre_name'];
        isset($_POST['pname']) ? $patient->pname = $_POST['pname'] : $patient->pname = $patientini[0]['pname'];
        isset($_POST['plastname']) ? $patient->plastname = $_POST['plastname'] : $patient->plastname = $patientini[0]['plastname'];
        isset($_POST['pgender']) ? $patient->pgender = $_POST['pgender'] : $patient->pgender = $patientini[0]['pgender'];
        isset($_POST['pedge']) ? $patient->pedge = $_POST['pedge'] : $patient->pedge = $patientini[0]['pedge'];
        isset($_POST['date_1000']) ? $patient->date_1000 = $_POST['date_1000'] : $patient->date_1000 = $patientini[0]['date_1000'];


        isset($_POST['date_2000']) ? $patient->date_2000 = $_POST['date_2000'] : $patient->date_2000 = $patientini[0]['date_2000'];
        isset($_POST['date_3000']) ? $patient->date_3000 = $_POST['date_3000'] : $patient->date_3000 = $patientini[0]['date_3000'];
        isset($_POST['date_6000']) ? $patient->date_6000 = $_POST['date_6000'] : $patient->date_6000 = $patientini[0]['date_6000'];
        isset($_POST['date_8000']) ? $patient->date_8000 = $_POST['date_8000'] : $patient->date_8000 = $patientini[0]['date_8000'];
        isset($_POST['date_10000']) ? $patient->date_10000 = $_POST['date_10000'] : $patient->date_10000 = $patientini[0]['date_10000'];
        isset($_POST['date_12000']) ? $patient->date_12000 = $_POST['date_12000'] : $patient->date_12000 = $patientini[0]['date_12000'];
        isset($_POST['date_13000']) ? $patient->date_13000 = $_POST['date_13000'] : $patient->date_13000 = $patientini[0]['date_13000'];
        isset($_POST['date_14000']) ? $patient->date_14000 = $_POST['date_14000'] : $patient->date_14000 = $patientini[0]['date_14000'];
        isset($_POST['date_20000']) ? $patient->date_20000 = $_POST['date_20000'] : $patient->date_20000 = $patientini[0]['date_20000'];
        isset($_POST['date_first_report']) ? $patient->date_first_report = $_POST['date_first_report'] : $patient->date_first_report = $patientini[0]['date_first_report'];


        isset($_POST['status_id']) ? $patient->status_id = $_POST['status_id'] : $patient->status_id = $patientini[0]['status_id'];
        isset($_POST['priority_id']) ? $patient->priority_id = $_POST['priority_id'] : $patient->priority_id = $patientini[0]['priority_id'];
        isset($_POST['phospital_id']) ? $patient->phospital_id = $_POST['phospital_id'] : $patient->phospital_id = $patientini[0]['phospital_id'];
        isset($_POST['phospital_num']) ? $patient->phospital_num = $_POST['phospital_num'] : $patient->phospital_num = $patientini[0]['phospital_num'];
        isset($_POST['ppathologist_id']) ? $patient->ppathologist_id = $_POST['ppathologist_id'] : $patient->ppathologist_id = $patientini[0]['ppathologist_id'];
        isset($_POST['pspecimen_id']) ? $patient->pspecimen_id = $_POST['pspecimen_id'] : $patient->pspecimen_id = $patientini[0]['pspecimen_id'];
        isset($_POST['pclinician_id']) ? $patient->pclinician_id = $_POST["pclinician_id"] : $patient->pclinician_id = $patientini[0]["pclinician_id"];

        isset($_POST['p_cross_section_id']) ? $patient->p_cross_section_id = $_POST["p_cross_section_id"] : $patient->p_cross_section_id = $patientini[0]["p_cross_section_id"];
        isset($_POST['p_cross_section_ass_id']) ? $patient->p_cross_section_ass_id = $_POST["p_cross_section_ass_id"] : $patient->p_cross_section_ass_id = $patientini[0]["p_cross_section_ass_id"];
        isset($_POST['p_slide_prep_id']) ? $patient->p_slide_prep_id = $_POST["p_slide_prep_id"] : $patient->p_slide_prep_id = $patientini[0]["p_slide_prep_id"];
        isset($_POST['p_slide_prep_sp_id']) ? $patient->p_slide_prep_sp_id = $_POST["p_slide_prep_sp_id"] : $patient->p_slide_prep_sp_id = $patientini[0]["p_slide_prep_sp_id"];
        isset($_POST['pprice']) ? $patient->pprice = $_POST['pprice'] : $patient->pprice = $patientini[0]['pprice'];
        isset($_POST['pspprice']) ? $patient->pspprice = $_POST['pspprice'] : $patient->pspprice = $patientini[0]['pspprice'];
        isset($_POST['p_rs_specimen']) ? $patient->p_rs_specimen = $_POST['p_rs_specimen'] : $patient->p_rs_specimen = $patientini[0]['p_rs_specimen'];
        isset($_POST['p_rs_clinical_diag']) ? $patient->p_rs_clinical_diag = $_POST['p_rs_clinical_diag'] : $patient->p_rs_clinical_diag = $patientini[0]['p_rs_clinical_diag'];
        isset($_POST['p_rs_gross_desc']) ? $patient->p_rs_gross_desc = $_POST['p_rs_gross_desc'] : $patient->p_rs_gross_desc = $patientini[0]['p_rs_gross_desc'];
        isset($_POST['p_rs_microscopic_desc']) ? $patient->p_rs_microscopic_desc = $_POST['p_rs_microscopic_desc'] : $patient->p_rs_microscopic_desc = $patientini[0]['p_rs_microscopic_desc'];

        isset($_POST['p_speciment_type']) ? $patient->p_speciment_type = $_POST['p_speciment_type'] : $patient->p_speciment_type = $patientini[0]['p_speciment_type'];
        isset($_POST['p_slide_lab_id']) ? $patient->p_slide_lab_id = $_POST['p_slide_lab_id'] : $patient->p_slide_lab_id = $patientini[0]['p_slide_lab_id'];
        isset($_POST['p_slide_lab_price']) ? $patient->p_slide_lab_price = $_POST['p_slide_lab_price'] : $patient->p_slide_lab_price = $patientini[0]['p_slide_lab_price'];
        $patient->isautoeditmode = "patient_detail_section"; //save and auto move to edit next section
        $patient->pautoscroll = "patient_detail_section"; //set auto scroll
        
        $patient->create_by = $_POST['create_by']; //set auto scroll
        
        if(isset( $_POST["super_id"])){
            $patient->isautoeditmode = ""; 
            $patient->pautoscroll = "";
        }



        if ($patient->create($conn)) {
            
            //==Create Presultupdate ============================================== 
            if($sn_type=='PN'){                                                 //=
                $cur_patient_id = $patient->id;                                 //=
                $typelists = ReportType::getAllbyGroup3($conn);                 //=
                foreach($typelists as $typelist){                               //=
                    $resultreport = Presultupdate::getInitObj();                //=
                    $resultreport->group_type = $typelist['group_type'];        //=
                    $resultreport->patient_id = $cur_patient_id;                //=
                    $resultreport->result_type = $typelist['name'];             //=
                    $resultreport->result_type_id = $typelist['id'];            //=
                    $resultreport->release_type = $typelist['release_type'];    //=
                    $resultreport->create($conn);                               //=
                }                                                               //=
            }else{                                                              //=
                $cur_patient_id = $patient->id;                                 //=
                $typelists = ReportType::getAllbyGroup1($conn);                 //=
                foreach($typelists as $typelist){                               //=
                    $resultreport = Presultupdate::getInitObj();                //=
                    $resultreport->group_type = $typelist['group_type'];        //=
                    $resultreport->patient_id = $cur_patient_id;                //=
                    $resultreport->result_type = $typelist['name'];             //=
                    $resultreport->result_type_id = $typelist['id'];            //=
                    $resultreport->release_type = $typelist['release_type'];    //=
                    $resultreport->create($conn);                               //=
                }                                                               //=
            }                                                                   //=
            //==End of Create Presultupdate =======================================
            
            //==Create Questionare ================================================
            if($sn_type=='SN'){                                                 //=
                $qa = QuesSN::getInitObj();                                     //=
                $qa->patient_id=$patient->id;                                   //=
                $qa->patient_num=$patient->pnum;                                //=
                $qa->create($conn);                                             //=
            }                                                                   //=
            if($sn_type=='CN'){                                                 //=
                $qa = QuesCN::getInitObj();                                     //=
                $qa->patient_id=$patient->id;                                   //=
                $qa->patient_num=$patient->pnum;                                //=
                $qa->create($conn);                                             //=
            }                                                                   //=
            //==End Create Questionare ============================================
            

            Url::redirect("/patient_edit.php?id=$patient->id");
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}






//Get Specific Row from Table
$patient = Patient::getInit();
$snPrefixs = SnPrefix::getAll($conn);
//var_dump($SnPrefix);die();
//$status_cur = Status::getAll($conn, $patient[0]['status_id']);
//$patientLists = Patient::getAll($conn);
//
//Get List of Table
//$hospitals = Hospital::getAll($conn);
//$specimens = Specimen::getAll($conn);
//$clinicians = User::getAllbyClinicians($conn);
//$userPathos = User::getAllbyPathologis($conn);
//$userTechnic = User::getAllbyTeachien($conn);
//$prioritys = Priority::getAll($conn);
//$statusLists = Status::getAll($conn);
//$labFluids = LabFluid::getAll($conn);



//$ug = Auth::getUserGroup();
//var_dump($patient);
//
//var_dump($patientLists);
//var_dump($Specimens);
//var_dump($clinicians);
//var_dump($users);
//var_dump($userPathos);
//var_dump($userTechnic);
//var_dump($status);
// true = Disable Edit page, false canEditPage
$isEditModePageOn = true;


require 'user_auth.php';

//Prepare Status
$curstatus[0]['id'] = 1000;
//เช็คและเตรียมตัวแปรสถานะปัจจุบัน
require 'includes/status_cur.php';
?>


            <?php require 'includes/header.php'; ?>
            <?php require 'user_auth.php'; ?>





<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">


<?php if (!Auth::isLoggedIn()) : ?>
            You are not login.
<?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)): ?>   
            You have no authorize to view this content.
            คุณไม่มีสิทธิ์ในการเข้าถึงส่วนนี้
<?php else : ?>

            <div class="d-flex align-items-center justify-content-between">
                <a href="patient.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-bed-pulse me-2"></i>ข้อมูลผู้รักษาทั้งหมด</a>
            </div>
    <?php if (!empty($errors)) : ?>
                <br>
                <div class="alert alert-warning" role="alert">
        <?php foreach ($errors as $error) : ?>
                        <li><?= $error ?></li>
        <?php endforeach; ?>
                </div>
    <?php endif; ?>
        </div>
    </div>



    <form id="formAddPatient" class="" name="" method="post">
    <?php require 'includes/patient_form_005_add_pat_num.php'; ?>


    </form>


<?php endif; ?>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    // set active tab
    $("#addpatienttab").addClass("active");
    
    
                        $(document).ready(function () {
                        //set active tab
                        $("#addpatienttab").addClass("active");
                        
                        function selectElement(id, valueToSelect) {    
                            let element = document.getElementById(id);
                            element.value = valueToSelect;
                        }

                        

                        $("#ppre_name_add").change(function () {
                            var ppre_name = document.getElementById("ppre_name_add").value;

                            console.log('A====='+ppre_name+'===='+'=======');
                            
                            let pre_name_list_add = document.getElementById("pre_name_list_add");
                            
                            let optioninside = pre_name_list_add.getElementsByTagName("option");
                            for (i = 0; i < optioninside.length; i++) {
                                console.log(optioninside[i].textContent);
                                console.log(optioninside[i].getAttribute("pgender"));
                                if(optioninside[i].textContent == ppre_name){
                                    console.log('B====='+ppre_name+'===='+optioninside[i].getAttribute("pgender")+'=======');
                                    selectElement('pgender_add', optioninside[i].getAttribute("pgender"));
                                }
                            }

                        });

                    });
</script>
