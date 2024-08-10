<!-- For window require 7z mv and magick command -->

<!-- Customized Bootstrap Stylesheet -->
<link href="css/bootstrap.min.css" rel="stylesheet">

<?php


require_once __DIR__ . '/vendor/autoload.php';
if (!isset($inited)) {
    require 'includes/init.php';
}
Auth::requireLogin();
if(!isset($dbg_print_patient_pdf)){
    $dbg_print_patient_pdf = FALSE;
}
?>

<?php require 'user_auth.php'; ?>
<?php //require 'includes/header.php';   ?>

<?php
if (!Auth::isLoggedIn()) {
    Util::alert(" You are not login.");
    die();
}

$file_patient_pdf_php = "patient_pdf_php.txt";

$skey = $_SESSION['skey'];
$os = PHP_OS;
if ($dbg_print_patient_pdf) {
    $txt = '<br>Server run on OS : ' . $os . '<br>';
    echo $txt;
    Util::writeFile($file_patient_pdf_php, $txt);
}

$isPreviewMode = FALSE;
if (isset($_GET['preview'])) {
    $isPreviewMode = TRUE;
}

if (!isset($requestFrom)) {
    $requestFrom = 'patient_php';
}

if (!isset($patient_id)) {
    if (isset($_GET['id'])) {
        $patient_id = $_GET['id'];
    } else {
        Util::alert("id not avalable");
        die();
    }
}
// show/hide table for see layout
if (!isset($hideTable)) {
    if (isset($_GET['layout'])) {
        $hideTable = false;
    } else {
        $hideTable = true;
    }
}
// Set out put option
//$pdfOutputOption = 'I';
if (!isset($pdfOutputOption)) {
    if (isset($_GET['option'])) {
        $pdfOutputOption = $_GET['option'];
    } else {
        $pdfOutputOption = 'I';
    }
}

//Get Specific Patient Row from Table
if (!isset($conn)) {
    $conn = require 'includes/db.php';
}
if (isset($patient_id)) {
    $patient = Patient::getAll($conn, $patient_id);
} else {
    $patient = null;
    Util::alert('no data');
    die();
}

require 'user_auth.php';
if (($isCurUserClinicianCust || $isCurUserHospitalCust) && !$isUnderCurHospital) {
    Util::alert("You have no authorize to view other hospital group.");
    die();
}

if (!$patient) {
    $str = "No Patient ID " . $patient_id . ".";

    require 'blockopen.php';
    echo $str;
    require 'blockclose.php';
    echo $str;
    Util::alert($str);
    die();
}

// die if not relesed yet(date20000 == null)  
// or Not in preview mode
//if( ($patient[0]['date_20000'] == NULL) or ($isPreviewMode==1)   ){
$is_released = ($patient[0]['date_20000'] == NULL) ? FALSE : TRUE;

if ($isPreviewMode) {
    // skip check released status
} else {
    if (!$is_released) {
        // If not release then stop here
        $str = 'id ' . $patient[0]['id'] . ' Number ' . $patient[0]['pnum'] . ' Not released yet';
        require 'blockopen.php';
        echo $str;
        require 'blockclose.php';
        Util::alert($str);
        die();
    } else {
        
    }
}




//var_dump($patient);
//$status_cur = Status::getAll($conn, $patient[0]['status_id']);s
//$patientLists = Patient::getAll($conn);
//Get List of Table
$hospitals = Hospital::getAll($conn);
$specimens = ServicePriceList::getAll($conn, 1);
$specimen2s = ServicePriceList::getAll($conn, 2);
$clinicians = User::getAllbyClinicians($conn);
$userPathos = User::getAllbyPathologis($conn);
$userTechnic = User::getAllbyTeachien($conn);
$prioritys = Priority::getAll($conn);
$statusLists = Status::getAll($conn);
$labFluids = LabFluid::getAll($conn);



//var_dump($userPathos);
//die();
//Get one by id
$presultupdate1s = Presultupdate::getAllofGroup1Asc($conn, $patient_id);
$presultupdate2s = Presultupdate::getAllofGroup2Desc($conn, $patient_id);
$presultupdate3s = Presultupdate::getAllofGroup3Asc($conn, $patient_id);
//var_dump($patient[0]['pclinician_id']);

$clinician = User::getAll($conn, $patient[0]['pclinician_id']);
$hospital = Hospital::getAll($conn, $patient[0]['phospital_id']);
$pathologist = User::getAllbyPathologis($conn, $patient[0]['ppathologist_id']);


//var_dump($patient[0]['ppathologist_id']);
//var_dump($pathologist);
//var_dump($pathologist2);
//die();
//var_dump($clinician);
//die();

$isset_date_first_report = 0;
if (isset($patient[0]['date_first_report'])) {
    $isset_date_first_report = 1;
} else {
    $isset_date_first_report = 0;
}




require 'user_auth.php';

class CustomLanguageToFontImplementation extends \Mpdf\Language\LanguageToFont {

    public function getLanguageOptions($llcc, $adobeCJK) {

        $tags = explode('-', $llcc);
        $lang = strtolower($tags[0]);
        $country = '';
        $script = '';
        if (!empty($tags[1])) {
            if (strlen($tags[1]) === 4) {
                $script = strtolower($tags[1]);
            } else {
                $country = strtolower($tags[1]);
            }
        }
        if (!empty($tags[2])) {
            $country = strtolower($tags[2]);
        }

        $unifont = '';
        $coreSuitable = false;

        // var_dump($lang);
        // var_dump($country);
        // var_dump($script);
        // echo "<br>";
//            die();
        if ($llcc === 'el' || $llcc === 'ell') {
//        if ($llcc === 'th' || $llcc === 'tha') {
//            var_dump($llcc);
//             var_dump($adobeCJK);
////            die();
            return [false, 'freeserif']; // for Greek language, font is not core suitable and the font is Frutiger
        } else {
            return [true, 'angsana'];
            ; // for Greek language, font is not core suitable and the font is Frutiger
        }

        return parent::getLanguageOptions($llcc, $adobeCJK);
    }

}

//custom font
$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

//$mpdf = new \Mpdf\Mpdf([
//    'fontDir' => array_merge($fontDirs, [
//        __DIR__ . '/fonts',
//    ]),
//    'fontdata' => $fontData + [
//            'sarabun' => [
//                'R' => 'THSarabunNew.ttf',
//                'I' => 'THSarabunNew Italic.ttf',
//                'B' =>  'THSarabunNew Bold.ttf',
//            ]
//        ],
//]);

$mpdf = new \Mpdf\Mpdf(
        [
    'mode' => 'utf-8',
    'format' => 'A4' . ('orientation' == 'L' ? '-L' : ''),
    'orientation' => 0,
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 65,
    'margin_bottom' => 15,
    'margin_header' => 10,
    'margin_footer' => 4,
    'useFixedNormalLineHeight' => true,
    'languageToFont' => new CustomLanguageToFontImplementation(),
    'autoScriptToLang' => true,
    'autoLangToFont' => true,
    'default_font' => 'angsana',
    'fontDir' => array_merge($fontDirs, [
        './fonts',
    ]),
    'fontdata' => $fontData + [
'angsana' => [
    'R' => 'angsau.ttf',
    'B' => 'angsaub.ttf',
    'I' => 'angsaui.ttf',
    'BI' => 'angsauz.ttf',
    'useOTL' => 0xFF, //แก้สระซ้อนทับกัน
    'useKashida' => 75, //แก้สระซ้อนทับกัน
],
 'sarabun' => [
    'R' => 'THSarabunNew.ttf',
    'I' => 'THSarabunNewItalic.ttf',
    'B' => 'THSarabunNewBold.ttf',
    'BI' => "THSarabunNewBoldItalic.ttf",
]
    ],
        ]
);

$mpdf->SetDisplayMode('fullwidth');
$mpdf->shrink_tables_to_fit = 1;


$txtWriteOut = "";

$header = file_get_contents('pdf_result/patient_format_header_pdf.php');
$header_txt = file_get_contents('pdf_result/patient_format_header_pdf_txt.php');

if ($hideTable) {
    $header = str_replace("border: 1px solid green;", "", $header);
    $header = str_replace("border: 1px solid red;", "", $header);
}

$preName = "";
if($patient[0]['ppre_name'] == 'NA'){
    $preName = "";
}else{
    $preName = $patient[0]['ppre_name']." ";
}

$pgender = "";
if($patient[0]['pgender'] == 'NA'){
    $pgender = "";
}else{
    $pgender = $patient[0]['pgender']." ";
}

$titleName = "PATHOLOGY REPORT";
if ($patient[0]['sn_type'] == 'SN') {
  $titleName = "SURGICAL PATHOLOGY REPORT";  
}else if($patient[0]['sn_type'] == 'CN' || $patient[0]['sn_type'] == 'PN' || $patient[0]['sn_type'] == 'LN'){
  $titleName = "CYTOLOGIC PATHOLOGY REPORT";
}
$header = str_replace("<PATHOLOGY_REPORT>", $titleName, $header);

$header = str_replace("<pname>", $preName . $patient[0]['pname'], $header);
$header = str_replace("<plastname>", $patient[0]['plastname'], $header);
$header = str_replace("<surgical_number>", $patient[0]['pnum'], $header);
$header = str_replace("<pg>", $pgender, $header);
$header = str_replace("<pedge>", $patient[0]['pedge'], $header);
$header = str_replace("<plabnum>", $patient[0]['plabnum'], $header);
$header = str_replace("<phospital_num>", $patient[0]['phospital_num'], $header);
$header = str_replace("<pclinician>", $clinician[0]['name'].' '.$clinician[0]['lastname'], $header);
$header = str_replace("<ward>"           ,"", $header);
$header = str_replace("<hospital>", $hospital[0]['hospital'], $header);
$header = str_replace("<date_1000>", $patient[0]['date_1000'], $header);
$header = str_replace("<an_name>"          ,'', $header);
$header = str_replace("<date_first_report>", $patient[0]['date_20000'], $header);


$header_txt = str_replace("<PATHOLOGY_REPORT>", $titleName, $header_txt);

$header_txt = str_replace("<pname>", $preName . $patient[0]['pname'], $header_txt);
$header_txt = str_replace("<plastname>", $patient[0]['plastname'], $header_txt);
$header_txt = str_replace("<surgical_number>", $patient[0]['pnum'], $header_txt);
$header_txt = str_replace("<pg>", $pgender, $header_txt);
$header_txt = str_replace("<pedge>", $patient[0]['pedge'], $header_txt);
$header_txt = str_replace("<plabnum>", $patient[0]['plabnum'], $header_txt);
$header_txt = str_replace("<phospital_num>", $patient[0]['phospital_num'], $header_txt);
$header_txt = str_replace("<pclinician>", $clinician[0]['name'], $header_txt);
$header_txt = str_replace("<ward>"           ,"", $header_txt);
$header_txt = str_replace("<hospital>", $hospital[0]['hospital'], $header_txt);
$header_txt = str_replace("<date_1000>", $patient[0]['date_1000'], $header_txt);
$header_txt = str_replace("<an_name>"          ,'', $header_txt);
$header_txt = str_replace("<date_first_report>", $patient[0]['date_20000'], $header_txt);


$mpdf->SetHTMLHeader($header);
$txtWriteOut .= $header_txt;

$footer = '<hr><div style="text-align: center; font-weight: bold;font-family:angsana; font-size:14pt; color:#000000;"> page {PAGENO} of {nb} </div>';
$mpdf->SetHTMLFooter($footer);

$file_rev=1;

//==START PN/LN type =====================================================================================================================================

if ($patient[0]['sn_type'] == 'PN' || $patient[0]['sn_type'] == 'LN') {


//=======START Result3 Group=========================================================================================================================================
    $job7s = Job::getByPatientJobRole($conn, $patient[0]['id'], 7); // Cycologist
    if(!empty($job7s)){
        $cytologist = User::getByID($conn, $job7s[0]['user_id']);
//        echo "cytologist";
//        var_dump($cytologist);
//        echo "\n";
        $signedcytologist = $cytologist->name_e . " " . $cytologist->lastname_e . " " . $cytologist->educational_bf;
    }
//    var_dump($job7s[0]['user_id']);
//    die();
    
    $signedpatho = "";
    $i = 0;
    $counter_result3 = 0;
    if (isset($presultupdate3s)) {
//    <?php foreach ($presultupdates as $presultupdate): 
        $count_presultupdate3s = count($presultupdate3s);
        foreach ($presultupdate3s as $key => $prsu) {
            $isFinished = $prsu['release_time'] != NULL;
            if ($isFinished || $isPreviewMode) {


                $result_id = $prsu['id'];
                $job = Job::getByPatientJobRoleUResult($conn, $patient_id, 6, $result_id);
                $isGroup3SecondPathoAval = isset($job[0]['name']) ? TRUE : FALSE;




                $u_result3 = file_get_contents('pdf_result/patient_format_result_pdf_3.php');
                $u_result3_txt = file_get_contents('pdf_result/patient_format_result_pdf_3_txt.php');
                
                if ($hideTable) {
                    $u_result3 = str_replace("border: 1px solid green;", "", $u_result3);
                }
                $u_result3 = str_replace("<result_type>", isset($prsu['result_type']) ? $prsu['result_type'] : "", $u_result3);
                $u_result3_txt = str_replace("<result_type>", isset($prsu['result_type']) ? $prsu['result_type'] : "", $u_result3_txt);
                $u_result3 = str_replace("<result_date>", isset($prsu['release_time']) ? $prsu['release_time'] : "", $u_result3);
                $u_result3_txt = str_replace("<result_date>", isset($prsu['release_time']) ? $prsu['release_time'] : "", $u_result3_txt);
//            $result_message = htmlspecialchars($prsu['result_message']);
                $result_message = ($prsu['result_message']);
                $result_message_txt = $result_message;
                $result_message = str_replace("\n", "<br>", $result_message);
//            $result_message = str_replace(" ", "&nbsp;", $result_message);
                $result_message = Util::space2nbsp($result_message);
                $u_result3 = str_replace("<result_message>", isset($prsu['result_message']) ? $result_message : "", $u_result3);
                $u_result3_txt = str_replace("<result_message>", isset($prsu['result_message']) ? $result_message_txt : "", $u_result3_txt);
                //            if ($prsu['pathologist2_id'] != 0) {
                if ($isGroup3SecondPathoAval) {
                    //$confirm_msg = "<br>NOTE: According to the first diagnosis of malignancy, this case was discussed with the second pathologist";
                    $secondPatho = User::getByID($conn, $prsu['pathologist2_id']);
                    //$confirm_msg = $confirm_msg . "(" . $secondPatho->name_e . " " . $secondPatho->lastname_e . ")";
                    //            var_dump($confirm_msg);
                    //            die();
                    //$u_result3 = str_replace("<confirm_message>", $confirm_msg, $u_result3);
                    $signedSecondPatho = $secondPatho->name_e . " " . $secondPatho->lastname_e . " " . $secondPatho->educational_bf;
                } else {
                    //$u_result3 = str_replace("<confirm_message>", "", $u_result3);
                }
                $mpdf->WriteHTML($u_result3);
                $txtWriteOut .= $u_result3_txt;
                if ($count_presultupdate3s == $key+1) {
                    //
                    $i = $i + 1;
                    $jobPatho = Job::getAll($conn, $patient[0]['id'], 5);
//                    echo "jobPatho";
//                    var_dump($jobPatho);
//                    echo "\n";
                    if(!empty($jobPatho)){
                        $pathoUserID = $jobPatho[0]['user_id'];
                        $pathoUser = User::getByID($conn, $pathoUserID);
                        $signedpatho = $pathoUser->name_e . " " . $pathoUser->lastname_e . " " . $pathoUser->educational_bf;
                    }

                    
                    if ($isFinished) {
                        $release_time = $prsu['release_time'];
                    } else {
                        $release_time = "[Time Release]";
                    }
                }
            }
            $counter_result3++;
        }
    } else {
        
    }
    $file_rev=1;
    

    $signature = file_get_contents('pdf_result/patient_format_signature_pdf_0.php');// CSS 
    $signature_txt = file_get_contents('pdf_result/patient_format_signature_pdf_0_txt.php');// CSS 
    $signature = $signature . file_get_contents('pdf_result/patient_format_signature_pdf_1.php');// Table open with Blank
    $signature_txt = $signature_txt . file_get_contents('pdf_result/patient_format_signature_pdf_1_txt.php');// Table open with Blank
    if ($patient[0]['iscritical'] == 1) {
        $signature = $signature . file_get_contents('pdf_result/patient_format_signature_pdf_2.php'); // Inform as critical report in Red
        $signature_txt = $signature_txt . file_get_contents('pdf_result/patient_format_signature_pdf_2_txt.php'); // Inform as critical report in Red
    }
    $signature = $signature . file_get_contents('pdf_result/patient_format_signature_pdf_3.php');// Table close with Blank
    $signature_txt = $signature_txt . file_get_contents('pdf_result/patient_format_signature_pdf_3_txt.php');// Table close with Blank

    if(!empty($cytologist)){
        $signature = $signature . file_get_contents('pdf_result/patient_format_signature_digital_pdf_cytologist.php');// Digital signed Cytologist
        $signature_txt = $signature_txt . file_get_contents('pdf_result/patient_format_signature_digital_pdf_cytologist_txt.php');// Digital signed Cytologist
        $signature = str_replace("<signedcyto>", $signedcytologist, $signature);
        $signature_txt = str_replace("<signedcyto>", $signedcytologist, $signature_txt);
    }
    
    if(!empty($jobPatho)){
        $signature = $signature . file_get_contents('pdf_result/patient_format_signature_digital_pdf_a.php');// Digital signed patho
        $signature_txt = $signature_txt . file_get_contents('pdf_result/patient_format_signature_digital_pdf_a_txt.php');// Digital signed patho
        $signature = str_replace("<signedpatho>", $signedpatho, $signature);
        $signature_txt = str_replace("<signedpatho>", $signedpatho, $signature_txt);
    }
    

    

    
    $signature = str_replace("<release_time>", $release_time, $signature);
    $signature_txt = str_replace("<release_time>", $release_time, $signature_txt);
    
    // If second pathologist confirm
    if($isGroup3SecondPathoAval){
        $signature = $signature . file_get_contents('pdf_result/patient_format_signature_digital_pdf_Secondpatho.php');// Digital signed by Second patho
        $signature_txt = $signature_txt . file_get_contents('pdf_result/patient_format_signature_digital_pdf_Secondpatho_txt.php');// Digital signed by Second patho
        $signature = str_replace("<signedpatho>", $signedSecondPatho, $signature);
        $signature_txt = str_replace("<signedpatho>", $signedSecondPatho, $signature_txt);
    }
    
    
    
    $signature = str_replace("<release_time>", $release_time, $signature);
    $signature_txt = str_replace("<release_time>", $release_time, $signature_txt);

    if ($hideTable) {
        $signature = str_replace("border: 1px solid green;", "", $signature);
        $signature = str_replace("border: 1px solid red;", "", $signature);
    }
    $mpdf->WriteHTML($signature);
    $txtWriteOut .= $signature_txt;
    
    
    
    
//=======END Result3 Group========================================================================================================================================
//==START PN/LN type =====================================================================================================================================

//==START NON PN/LN type =====================================================================================================================================
} else {


//=======START Result2 Group=========================================================================================================================================

    $signedpatho = "";
    $i = 0;
    $counter_result2 = 0;
    if (isset($presultupdate2s)) {
//    <?php foreach ($presultupdates as $presultupdate): 

        foreach ($presultupdate2s as $prsu) {
            $isFinished = $prsu['release_time'] != NULL;
            if ($isFinished || $isPreviewMode) {


                $result_id = $prsu['id'];
                $job = Job::getByPatientJobRoleUResult($conn, $patient_id, 6, $result_id);
                $isGroup2SecondPathoAval = isset($job[0]['name']) ? TRUE : FALSE;




                $u_result2 = file_get_contents('pdf_result/patient_format_result_pdf_2.php');
                $u_result2_txt = file_get_contents('pdf_result/patient_format_result_pdf_2_txt.php');
                if ($hideTable) {
                    $u_result2 = str_replace("border: 1px solid green;", "", $u_result2);
                }
                $u_result2 = str_replace("<result_type>", isset($prsu['result_type']) ? $prsu['result_type'] : "", $u_result2);
                $u_result2_txt = str_replace("<result_type>", isset($prsu['result_type']) ? $prsu['result_type'] : "", $u_result2_txt);
                $u_result2 = str_replace("<result_date>", isset($prsu['release_time']) ? $prsu['release_time'] : "", $u_result2);
                $u_result2_txt = str_replace("<result_date>", isset($prsu['release_time']) ? $prsu['release_time'] : "", $u_result2_txt);
//            $result_message = htmlspecialchars($prsu['result_message']);
                $result_message = ($prsu['result_message']);
                $result_message_txt = $result_message;
                $result_message = str_replace("\n", "<br>", $result_message);
//            $result_message = str_replace(" ", "&nbsp;", $result_message);
                $result_message = Util::space2nbsp($result_message);
                $u_result2 = str_replace("<result_message>", isset($prsu['result_message']) ? $result_message : "", $u_result2);
                $u_result2_txt = str_replace("<result_message>", isset($prsu['result_message']) ? $result_message_txt : "", $u_result2_txt);
                //            if ($prsu['pathologist2_id'] != 0) {
                if ($isGroup2SecondPathoAval) {
                    $confirm_msg = "<br>NOTE: According to the first diagnosis of malignancy, this case was discussed with the second pathologist";
                    $secondPatho = User::getByID($conn, $prsu['pathologist2_id']);
                    $confirm_msg = $confirm_msg . "(" . $secondPatho->name_e . " " . $secondPatho->lastname_e . ")";
                    //            var_dump($confirm_msg);
                    //            die();
                    $u_result2 = str_replace("<confirm_message>", $confirm_msg, $u_result2);
                    $u_result2_txt = str_replace("<confirm_message>", $confirm_msg, $u_result2_txt);
                } else {
                    $u_result2 = str_replace("<confirm_message>", "", $u_result2);
                    $u_result2_txt = str_replace("<confirm_message>", "", $u_result2_txt);
                }
                $mpdf->WriteHTML($u_result2);
                $txtWriteOut .= $u_result2_txt;
                if ($i == 0) {
                    //
                    $i = $i + 1;
                    $jobPatho = Job::getAll($conn, $patient[0]['id'], 5);
                    $pathoUserID = $jobPatho[0]['user_id'];
                    $pathoUser = User::getByID($conn, $pathoUserID);
                    $signedpatho = $pathoUser->name_e . " " . $pathoUser->lastname_e . " " . $pathoUser->educational_bf;
                    if ($isFinished) {
                        $release_time = $prsu['release_time'];
                    } else {
                        $release_time = "[Time Release]";
                    }
                }
            }
            $counter_result2++;
        }
    } else {
        
    }
    $file_rev=$counter_result2;
//=======END Result2 Group========================================================================================================================================



//=======Result1 Group=========

    if (isset($presultupdate1s)) {
//    <?php foreach ($presultupdates as $presultupdate): 
        foreach ($presultupdate1s as $prsu) {
            //---Dont show Clinical Diagnosis:--
            if ($prsu['result_type_id'] == 2) {
                continue;
            }

            $u_result2 = file_get_contents('pdf_result/patient_format_result_pdf_1.php');
            $u_result2_txt = file_get_contents('pdf_result/patient_format_result_pdf_1_txt.php');
            if ($hideTable) {
                $u_result2 = str_replace("border: 1px solid green;", "", $u_result2);
                $u_result2_txt = str_replace("border: 1px solid green;", "", $u_result2_txt);
            }
            $u_result2 = str_replace("<result_type>", isset($prsu['result_type']) ? $prsu['result_type'] : "", $u_result2);
            $u_result2_txt = str_replace("<result_type>", isset($prsu['result_type']) ? $prsu['result_type'] : "", $u_result2_txt);
            $u_result2 = str_replace("<result_date>", isset($prsu['release_time']) ? $prsu['release_time'] : "", $u_result2);
            $u_result2_txt = str_replace("<result_date>", isset($prsu['release_time']) ? $prsu['release_time'] : "", $u_result2_txt);
//        $result_message = htmlspecialchars($prsu['result_message']);
            $result_message = ($prsu['result_message']);
            $result_message_txt = $result_message;
            $result_message = str_replace("\n", "<br>", $result_message);
//        $result_message = str_replace(" ", "&nbsp;", $result_message);
            $result_message = Util::space2nbsp($result_message);
            $u_result2 = str_replace("<result_message>", isset($prsu['result_message']) ? $result_message : "", $u_result2);
            $u_result2_txt = str_replace("<result_message>", isset($prsu['result_message']) ? $result_message_txt : "", $u_result2_txt);
            if ($prsu['pathologist2_id'] != 0) {
                $confirm_msg = "<br>NOTE: According to the first diagnosis of malignancy, this case was discussed with the second pathologist";
                $secondPatho = User::getByID($conn, $prsu['pathologist2_id']);
                $confirm_msg = $confirm_msg . "(" . $secondPatho->name_e . " " . $secondPatho->lastname_e . ")";
//            var_dump($confirm_msg);
//            die();
                $u_result2 = str_replace("<confirm_message>", $confirm_msg, $u_result2);
                $u_result2_txt = str_replace("<confirm_message>", $confirm_msg, $u_result2_txt);
            } else {
                $u_result2 = str_replace("<confirm_message>", "", $u_result2);
                $u_result2_txt = str_replace("<confirm_message>", "", $u_result2_txt);
            }

            $mpdf->WriteHTML($u_result2);
            $txtWriteOut .= $u_result2_txt;
        }
    } else {
        
    }


    $signature = file_get_contents('pdf_result/patient_format_signature_pdf_0.php');// CSS 
    $signature_txt = file_get_contents('pdf_result/patient_format_signature_pdf_0_txt.php');// CSS 
    $signature = $signature . file_get_contents('pdf_result/patient_format_signature_pdf_1.php');// Table open with Blank
    $signature_txt = $signature_txt . file_get_contents('pdf_result/patient_format_signature_pdf_1_txt.php');// Table open with Blank
    if ($patient[0]['iscritical'] == 1) {
        $signature = $signature . file_get_contents('pdf_result/patient_format_signature_pdf_2.php'); // Inform as critical report in Red
        $signature_txt = $signature_txt . file_get_contents('pdf_result/patient_format_signature_pdf_2_txt.php'); // Inform as critical report in Red
    }
    $signature = $signature . file_get_contents('pdf_result/patient_format_signature_pdf_3.php');// Table close with Blank
    $signature_txt = $signature_txt . file_get_contents('pdf_result/patient_format_signature_pdf_3_txt.php');// Table close with Blank

    $signature = $signature . file_get_contents('pdf_result/patient_format_signature_digital_pdf_a.php');// Digital signed patho
    $signature_txt = $signature_txt . file_get_contents('pdf_result/patient_format_signature_digital_pdf_a_txt.php');// Digital signed patho

    $signature = str_replace("<signedpatho>", $signedpatho, $signature);
    $signature_txt = str_replace("<signedpatho>", $signedpatho, $signature_txt);
    $signature = str_replace("<release_time>", $release_time, $signature);
    $signature_txt = str_replace("<release_time>", $release_time, $signature_txt);

    if ($hideTable) {
        $signature = str_replace("border: 1px solid green;", "", $signature);
        $signature = str_replace("border: 1px solid red;", "", $signature);
    }
    $mpdf->WriteHTML($signature);
    $txtWriteOut .= $signature_txt;

//die();
}
//==END NON PN/LN type =====================================================================================================================================



//========CREATE AND Generate report file following th option send=================
//$mpdf->Output();
//$pdfOutputOption
//'D': download the PDF file
//'I': serves in-line to the browser
//'S': returns the PDF document as a string
//'F': save as file $file_out


$reportFileName = $patient[0]['pnum'] . '_R' . $file_rev . '_' . $patient[0]['phospital_num']; //$file_rev

if ($isPreviewMode == TRUE) {
    $reportFileName = 'PREVIEW_' . $reportFileName;
}
if ($hideTable == FALSE) {
    $reportFileName = 'LAYOUTVIEW_' . $reportFileName;
}

$reportFileNameFormat2 = $reportFileName . '_' . $patient[0]['pname'] . '_' . $patient[0]['plastname'];

$reportFileName = str_replace(' ', '-', $reportFileName);
$reportFileNameFormat2 = str_replace(' ', '-', $reportFileNameFormat2);

if ($pdfOutputOption == 'F') {

    if ($requestFrom == 'patient_edit_php') { // Generate and keep on server
    // 
        //=====================================================================================================================
        //=================== Create Variable to keep Folder file for store report file========================================
        //=====================================================================================================================
       
        $targetFolderRelease1 = './cus';

        $pdffilepath = $targetFolderRelease1 . '/' . $reportFileName . '.pdf';
        $txtfilepath = $targetFolderRelease1 . '/' . $reportFileName . '.txt';
        
        $pdffilepathFormat2 = $targetFolderRelease1 . '/' . $reportFileNameFormat2 . '.pdf';
        $txtfilepathFormat2 = $targetFolderRelease1 . '/' . $reportFileNameFormat2 . '.txt';
        $jpgfilepathFormat2 = $targetFolderRelease1 . '/' . $reportFileNameFormat2 . '.jpg';
        $zipfilepathFormat2 = $targetFolderRelease1 . '/' . $reportFileNameFormat2 . '.zip';


        if ($dbg_print_patient_pdf) {
            $txt = "";
            $txt .= '<br>$reportFileName : ' . $reportFileName;
            $txt .=  '<br>$reportFileNameFormat2 : ' . $reportFileNameFormat2;
            $txt .=  '<br>$targetFolderRelease1 : ' . $targetFolderRelease1;
            $txt .=  '<br>';
            $txt .=  '<br>$pdffilepath : ' . $pdffilepath;
            $txt .=  '<br>$txtfilepath : ' . $txtfilepath;
            $txt .=  '<br>$pdffilepathFormat2 : ' . $pdffilepathFormat2;
            $txt .=  '<br>$jpgfilepathFormat2 : ' . $jpgfilepathFormat2;
            $txt .=  '<br>$zipfilepathFormat2 : ' . $zipfilepathFormat2;
            $txt .=  '<br>$targetFolderRelease1 : ' . $targetFolderRelease1;
            $txt .=  '<br>';
            
            echo $txt;
            $txt = str_replace("<br>", "\n", $txt);
            Util::writeFileAppend($file_patient_pdf_php, $txt);
        }


        if ($dbg_print_patient_pdf) {
            $txt = "";
            $txt .= '<br>run:$mpdf->Output($pdffilepath, $pdfOutputOption)';
            $txt .= '<br>$pdffilepath :' . $pdffilepath . '';
            $txt .= '<br>$pdfOutputOption :' . $pdfOutputOption . '';
            $txt .= '<br>';
            
            echo $txt;
            $txt = str_replace("<br>", "\n", $txt);
            Util::writeFileAppend($file_patient_pdf_php, $txt);
        }
        $mpdf->Output($pdffilepath, $pdfOutputOption);
        Util::writeFileSpecificPath($txtfilepath, $txtWriteOut);


        //============================================================================================
        //===================Prepare $commandRename ==================================================
        //============================================================================================
        $commandRename = 'mv ' . $pdffilepath . ' ' . $pdffilepathFormat2;
        $commandRename_txt = 'mv ' . $txtfilepath . ' ' . $txtfilepathFormat2;
        if ($dbg_print_patient_pdf) {
            $txt = '<br>===========================================================================';
            $txt .= '<br>Start "exec($commandRename, $output, $retval) : ' . $commandRename . '<br>';
            echo $txt;
            $txt = str_replace("<br>", "\n", $txt);
            Util::writeFileAppend($file_patient_pdf_php, $txt);
        }
        if (exec($commandRename, $output, $retval) == 0) {
            if ($retval == 0) {
                if ($dbg_print_patient_pdf) {
                    $txt = '<br>execute command "' . $commandRename . '" .<br>';
                    $txt .= "<br>Returned with status $retval and output:\n<br>";
                    $txt .= print_r($output,TRUE);
                    $txt .= '<br>';
                    echo $txt;
                    $txt = str_replace("<br>", "\n", $txt);
                    Util::writeFileAppend($file_patient_pdf_php, $txt);
                    
                }
            } else {
                $txt = '<br>execute command "' . $commandRename . '" .<br>';
                $txt .= "<br>Returned with status $retval and output:\n<br>";
                $txt .= print_r($output,TRUE);
                $txt .= '<br>';
                echo $txt;
                $txt = str_replace("<br>", "\n", $txt);
                Util::writeFileAppend($file_patient_pdf_php, $txt);
            }
        } else {
                $txt = '<br>execute command "' . $commandRename . '" .<br>';
                $txt .= "<br>Returned with status $retval and output:\n<br>";
                $txt .= print_r($output,TRUE);
                $txt .= '<br>';
                echo $txt;
                $txt = str_replace("<br>", "\n", $txt);
                Util::writeFileAppend($file_patient_pdf_php, $txt);
        }

        if ($dbg_print_patient_pdf) {
            $txt = '<br>===========================================================================';
            $txt .= '<br>Start "exec($commandRename_txt, $output, $retval) : ' . $commandRename_txt . '<br>';
            echo $txt;
            $txt = str_replace("<br>", "\n", $txt);
            Util::writeFileAppend($file_patient_pdf_php, $txt);
        }
        if (exec($commandRename_txt, $output, $retval) == 0) {
            if ($retval == 0) {
                if ($dbg_print_patient_pdf) {
                    $txt = 'execute command "' . $commandRename_txt . '" successful.<br>';
                    $txt .= "Returned with status $retval and output:\n<br>";
                    $txt .= print_r($output,TRUE);
                    echo $txt;
                    $txt = str_replace("<br>", "\n", $txt);
                    Util::writeFileAppend($file_patient_pdf_php, $txt);
                }
            } else {
                $txt = 'execute command "' . $commandRename_txt . '" successful.<br>';
                $txt .= "Returned with status $retval and output:\n<br>";
                $txt .= print_r($output,TRUE);
                echo $txt;
                $txt = str_replace("<br>", "\n", $txt);
                Util::writeFileAppend($file_patient_pdf_php, $txt);
            }
        } else {
            $txt = 'execute command "' . $commandRename_txt . '" successful.<br>';
            $txt .= "Returned with status $retval and output:\n<br>";
            $txt .= print_r($output,TRUE);
            echo $txt;
            $txt = str_replace("<br>", "\n", $txt);
            Util::writeFileAppend($file_patient_pdf_php, $txt);
        }


        //===================================================================================================
        //===================Prepare $command1 , Convert PDF to JPG===========================================
        //===================================================================================================
        if ($os == "WINNT") {
            $command1 = 'magick -density 300 ' . $pdffilepathFormat2 . ' -density 300 ' . $jpgfilepathFormat2;
        }
        if ($os == "Linux") {
            $command1 = '/usr/local/bin/magick -density 300 ' . $pdffilepathFormat2 . ' -density 300 ' . $jpgfilepathFormat2;
        }
        if ($dbg_print_patient_pdf) {
            $txt = '<br>===========================================================================';
            $txt .= '<br>$command1 :' . $command1 . '';
            $txt .= '<br>exec($command1, $output, $retval)';
            $txt .= '<br>';
            echo $txt;
            $txt = str_replace("<br>", "\n", $txt);
            Util::writeFileAppend($file_patient_pdf_php, $txt);
        }
        if (exec($command1, $output, $retval) == 0) {
            if ($retval == 0) {
                if ($dbg_print_patient_pdf) {
                    $txt = 'execute command "' . $command1 . '" .<br>';
                    $txt .= "Returned with status $retval and output:\n<br>";
                    $txt .= print_r($output,TRUE);
                    $txt .= '<br>';
                    echo $txt;
                    $txt = str_replace("<br>", "\n", $txt);
                    Util::writeFileAppend($file_patient_pdf_php, $txt);
                }
            } else {
                $txt = 'execute command "' . $command1 . '" .<br>';
                $txt .= "Returned with status $retval and output:\n<br>";
                $txt .= print_r($output,TRUE);
                $txt .= '<br>';
                echo $txt;
                $txt = str_replace("<br>", "\n", $txt);
                Util::writeFileAppend($file_patient_pdf_php, $txt);
            }
        } else {
            $txt = 'execute command "' . $command1 . '" .<br>';
            $txt .= "Returned with status $retval and output:\n<br>";
            $txt .= print_r($output,TRUE);
            $txt .= '<br>';
            echo $txt;
            $txt = str_replace("<br>", "\n", $txt);
            Util::writeFileAppend($file_patient_pdf_php, $txt);
        }

        //====================================================================================
        //=============== Copy file to customer Sharing folder if any ========================
        //====================================================================================
        $cusReportFolder = Hospital::getReportFolder($conn, $patient[0]['phospital_id']);
        if ($dbg_print_patient_pdf) {
            echo '<br>$cusReportFolder : "' . $cusReportFolder . '"';
            echo '<br>';
        }
        //==Copy report to customer foldera====
        if ($cusReportFolder != "") {
            if ($dbg_print_patient_pdf) {
                echo '<br>==Copy report to customer foldera====';
                echo '<br>';
            }
//            echo "customer folder =" . $cusReportFolder;
            $targetFolderRelease2 = './customerfileb/' . $cusReportFolder;
            if ($dbg_print_patient_pdf) {
                $txt = '<br>==================================================================';
                $txt .= '<br>Make new Folder if not avalable : "' . $cusReportFolder . '"';
                $txt .= '<br>call  mkdir($targetFolderRelease2, 0777, true);';
                $txt .= '<br>';
                echo $txt;
                $txt = str_replace("<br>", "\n", $txt);
                Util::writeFileAppend($file_patient_pdf_php, $txt);
            }
            if (!file_exists($targetFolderRelease2) && !is_dir($targetFolderRelease2)) {
                mkdir($targetFolderRelease2, 0777, true);
            }

//============Copy file from "release1" to customer "customerfile2" folder===================================
//            cp ./release1/SN2303647_R1*.jpg ./customerfile2/pathokph
//            cp ./release1/SN2303647_R1*.pdf ./customerfile2/pathokph
//            cp ./release1/SN2303647_R1*.txt ./customerfile2/pathokph
//            $cmd_copy_pdf = "cp '".$targetFolderRelease1 . '/' . $reportFileNameFormat2 . ".pdf' ".$targetFolderRelease2;
//            $cmd_copy_pdf = "cd release1 && cp *.jpg ./../customerfile2/pathokph";
            //$cmd_copy_jpg = "cp '".$targetFolderRelease1 . '/' . $reportFileNameFormat2 . ".jpg' ".$targetFolderRelease2;

            //=============copy pdf file=====================
            $copy_pdf_from = $targetFolderRelease1 . '/' . $reportFileNameFormat2 . '.pdf';
            $copy_pdf_to = $targetFolderRelease2 . '/' . $reportFileNameFormat2 . '.pdf';
            $copy_command = 'cp '.$copy_pdf_from.' '.$copy_pdf_to;
            if ($dbg_print_patient_pdf) {
                $txt = '<br>=========Copy file from "release1" to customer "customerfile2" folder==========';
                $txt .= '<br>$copy_pdf_from : "' . $copy_pdf_from . '"';
                $txt .= '<br>$copy_pdf_to : "' . $copy_pdf_to . '"';
//                $txt .= 'call copy($copy_pdf_from, $copy_pdf_to);';
                $txt .= '<br>$copy_command : "' . $copy_command . '"';
                $txt .= '<br>';
                echo $txt;
                $txt = str_replace("<br>", "\n", $txt);
                Util::writeFileAppend($file_patient_pdf_php, $txt);
            }
//            copy($copy_pdf_from, $copy_pdf_to);
            exec($copy_command, $output, $retval);
            if ($dbg_print_patient_pdf) {
                $txt  = '<br>Returned with status : "' . $retval . '"';
                $txt .= '<br>output : "' . print_r($output,TRUE) . '"';
                $txt .= '<br>';
                echo $txt;
                $txt = str_replace("<br>", "\n", $txt);
                Util::writeFileAppend($file_patient_pdf_php, $txt);
            }
            //=============copy txt file=====================
            $copy_txt_from = $targetFolderRelease1 . '/' . $reportFileNameFormat2 . '.txt';
            $copy_txt_to = $targetFolderRelease2 . '/' . $reportFileNameFormat2 . '.txt';
            $copy_command = 'cp '.$copy_txt_from.' '.$copy_txt_to;
            if ($dbg_print_patient_pdf) {
                $txt = '<br>=========Copy text file from "release1" to customer "customerfile2" folder==========';
                $txt .= '<br>$copy_txt_from : "' . $copy_txt_from . '"';
                $txt .= '<br>$copy_txt_to : "' . $copy_txt_to . '"';
                $txt .= '<br>$copy_command : "' . $copy_command . '"';
//                $txt .= '<br>Call copy($copy_txt_from, $copy_txt_to);';
                $txt .= '<br>';
                echo $txt;
                $txt = str_replace("<br>", "\n", $txt);
                Util::writeFileAppend($file_patient_pdf_php, $txt);
            }
//            copy($copy_txt_from, $copy_txt_to);
            exec($copy_command, $output, $retval);
            if ($dbg_print_patient_pdf) {
                $txt  = '<br>Returned with status : "' . $retval . '"';
                $txt .= '<br>output : "' . print_r($output,TRUE) . '"';
                $txt .= '<br>';
                echo $txt;
                $txt = str_replace("<br>", "\n", $txt);
                Util::writeFileAppend($file_patient_pdf_php, $txt);
            }
            //============copy jpg in case of one page=============
            $copy_jpg_from = $targetFolderRelease1 . '/' . $reportFileNameFormat2 . '.jpg';
            $copy_jpg_to = $targetFolderRelease2 . '/' . $reportFileNameFormat2 . '.jpg';
            $copy_command = 'cp '.$copy_jpg_from.' '.$copy_jpg_to;
            if (file_exists($copy_jpg_from)) {
                if ($dbg_print_patient_pdf) {
                    $txt = '<br>=========Copy pdf file from "release1" to customer "customerfile2" folder==========';
                    $txt .= '<br>$copy_jpg_from : "' . $copy_jpg_from . '"';
                    $txt .= '<br>$copy_jpg_to : "' . $copy_jpg_to . '"';
                    $txt .= '<br>$copy_command : "' . $copy_command . '"';
//                        $txt .= '<br> Call copy($copy_jpg_from, $copy_jpg_to);';
                    $txt .= '<br>';
                    echo $txt;
                    $txt = str_replace("<br>", "\n", $txt);
                    Util::writeFileAppend($file_patient_pdf_php, $txt);
                }
                exec($copy_command, $output, $retval);
//                    copy($copy_jpg_from, $copy_jpg_to);
                if ($dbg_print_patient_pdf) {
                    $txt  = '<br>Returned with status : "' . $retval . '"';
                    $txt .= '<br>output : "' . print_r($output,TRUE) . '"';
                    $txt .= '<br>';
                    echo $txt;
                    $txt = str_replace("<br>", "\n", $txt);
                    Util::writeFileAppend($file_patient_pdf_php, $txt);
                }
            } else {
                if ($dbg_print_patient_pdf) {
                    $txt = '<br>=========Skip copy file from "release1" to customer "customerfile2" folder==========';
                    $txt .= '<br> File Dosnt exist $copy_jpg_from : "' . $copy_jpg_from . '"';
                    $txt .= '<br>';
                    echo $txt;
                    $txt = str_replace("<br>", "\n", $txt);
                    Util::writeFileAppend($file_patient_pdf_php, $txt);
                }
            }

            //============copy jpg in case of many page=============
            for ($x = 0; $x <= 10; $x++) {
                echo "The number is: $x <br>";
                $copy_jpg_from = $targetFolderRelease1 . '/' . $reportFileNameFormat2 . '-' . $x . '.jpg';
                $copy_jpg_to = $targetFolderRelease2 . '/' . $reportFileNameFormat2 . '-' . $x . '.jpg';
                $copy_command = 'cp '.$copy_jpg_from.' '.$copy_jpg_to;

                if (file_exists($copy_jpg_from)) {
                    if ($dbg_print_patient_pdf) {
                        $txt = '<br>=========Copy file from "release1" to customer "customerfile2" folder==========';
                        $txt .= '<br>$copy_jpg_from : "' . $copy_jpg_from . '"';
                        $txt .= '<br>$copy_jpg_to : "' . $copy_jpg_to . '"';
                        $txt .= '<br>$copy_command : "' . $copy_command . '"';
//                        $txt .= '<br> Call copy($copy_jpg_from, $copy_jpg_to);';
                        $txt .= '<br>';
                        echo $txt;
                        $txt = str_replace("<br>", "\n", $txt);
                        Util::writeFileAppend($file_patient_pdf_php, $txt);
                    }
                    exec($copy_command, $output, $retval);
//                    copy($copy_jpg_from, $copy_jpg_to);
                    if ($dbg_print_patient_pdf) {
                        $txt  = '<br>Returned with status : "' . $retval . '"';
                        $txt .= '<br>output : "' . print_r($output,TRUE) . '"';
                        $txt .= '<br>';
                        echo $txt;
                        $txt = str_replace("<br>", "\n", $txt);
                        Util::writeFileAppend($file_patient_pdf_php, $txt);
                    }
                } else {
                    if ($dbg_print_patient_pdf) {
                        $txt = '<br>=========Skip copy file from "release1" to customer "customerfile2" folder==========';
                        $txt .= '<br> File Dosnt exist $copy_jpg_from : "' . $copy_jpg_from . '"';
                        $txt .= '<br>';
                        echo $txt;
                        $txt = str_replace("<br>", "\n", $txt);
                        Util::writeFileAppend($file_patient_pdf_php, $txt);
                    }
                }
            }

            //====================================================================================
            //=============== Send report for Winmed to Rest API =================================
            //====================================================================================
            $is_Cur_Cust_Winmed = ($patient[0]['phospital_id'] == '38'
                    || $patient[0]['phospital_id'] == '40'
                    || $patient[0]['phospital_id'] == '41');
            
            
            if($is_Cur_Cust_Winmed){
                $txt = '<br>=========Send message to Winmed==========<br>';
                //Perform send message to Winmed API
                $lab_no = $patient[0]['plabnum'];
                $nb_no = $patient[0]['pnum'];
                $pdf_path = $copy_pdf_to;
                $txt_path = $copy_txt_to;

                $winmed = new WinmedAPI();
                $txt .= "<br>time:".Util::get_curreint_thai_date_time();
                $txt .= "<br>lab_no:".$lab_no;
                $txt .= "<br>nb_no:".$nb_no;
                $txt .= "<br>pdf_path:".$pdf_path;
                $txt .= "<br>txt_path:".$txt_path;
                $txt .= '<br> Call $res=json_decode($winmed->sentAPI($lab_no, $nb_no, $pdf_path, $txt_path));';
                $txt .= "<br>";
                if ($dbg_print_patient_pdf) {
                    echo $txt;
                    $txt = str_replace("<br>", "\n", $txt);
                    Util::writeFileAppend($file_patient_pdf_php, $txt);
                }
                $res=json_decode($winmed->sentAPI($lab_no, $nb_no, $pdf_path, $txt_path));

                // var_dump($res->resCode);
                
                $fileName = $copy_pdf_to.'_return_message.log';
                $textlog .= "<br>ResponseMessage:". json_encode($res). "";
                Util::writeFileSpecificPath($fileName, $textlog);
                
                $txt = $textlog;
                if ($dbg_print_patient_pdf) {
                    echo $txt;
                    $txt = str_replace("<br>", "\n", $txt);
                    Util::writeFileAppend($file_patient_pdf_php, $txt);
                }

            } else {
                if ($dbg_print_patient_pdf) {
                    $txt = '<br>=========Not Winmed ,Do not send message to Winmed==========<br>';
                    echo $txt;
                    $txt = str_replace("<br>", "\n", $txt);
                    Util::writeFileAppend($file_patient_pdf_php, $txt);
                }
            }
            //====================================================================================
            //===============End Send report for Winmed to Rest API ==============================
            //====================================================================================
            
            

        } else {
            if ($dbg_print_patient_pdf) {
                echo '<br>==Dont copy report to customer folder====';
                echo '<br>';
            }
        }
    } elseif ($requestFrom == 'patient_php') { //Generate and zip then download
        //===========================================================================================================\
        //=================== Create temporary Folder file for store zip file========================================\
        //===========================================================================================================\
        $targetFolderForDownload = './customerfile/' . $patient[0]['pnum'] . '_' . $skey . '_' . Time();
        if (!mkdir($targetFolderForDownload, 0777, true)) {
            die('Failed to create directories...' . $targetFolderForDownload);
        } else {

            //===================Prepare Path/File =================================================================

            $pdffilepath = $targetFolderForDownload . '/' . $reportFileName . '.pdf';
            $txtfilepath = $targetFolderForDownload . '/' . $reportFileName . '.txt';
            
            
            $pdffilepathFormat2 = $targetFolderForDownload . '/' . $reportFileNameFormat2 . '.pdf';
            $txtfilepathFormat2 = $targetFolderForDownload . '/' . $reportFileNameFormat2 . '.txt';

            $jpgfilepathFormat2 = $targetFolderForDownload . '/' . $reportFileNameFormat2 . '.jpg';
            $zipfilepathFormat2 = $targetFolderForDownload . '/' . $reportFileNameFormat2 . '.zip';

            $inputtargetpdf2zipfile = $targetFolderForDownload . '/' . $reportFileNameFormat2 . '*.pdf';
            $inputtargetjpg2zipfile = $targetFolderForDownload . '/' . $reportFileNameFormat2 . '*.jpg';
            $inputtargettxt2zipfile = $targetFolderForDownload . '/' . $reportFileNameFormat2 . '*.txt';
            
            
        if ($dbg_print_patient_pdf) {
            echo '<br>$reportFileName : ' . $reportFileName;
            echo '<br>$reportFileNameFormat2 : ' . $reportFileNameFormat2;
//            echo '<br>$targetFolderRelease1 : ' . $targetFolderRelease1;
            echo '<br>';
            echo '<br>$pdffilepath : ' . $pdffilepath;
            echo '<br>$txtfilepath : ' . $txtfilepath;
            echo '<br>$pdffilepathFormat2 : ' . $pdffilepathFormat2;
            echo '<br>$txtfilepathFormat2 : ' . $txtfilepathFormat2;
            echo '<br>$jpgfilepathFormat2 : ' . $jpgfilepathFormat2;
            echo '<br>$zipfilepathFormat2 : ' . $zipfilepathFormat2;
            //echo '<br>$targetFolderRelease1 : ' . $targetFolderRelease1;
            echo '<br>$inputtargetpdf2zipfile : '.$inputtargetpdf2zipfile;
            echo '<br>$inputtargetjpg2zipfile : '.$inputtargetjpg2zipfile;
            echo '<br>$inputtargettxt2zipfile : '.$inputtargettxt2zipfile;
            echo '<br>';
        }


        if ($dbg_print_patient_pdf) {
            echo '<br>run:$mpdf->Output($pdffilepath, $pdfOutputOption)';
            echo '<br>run:Util::writeFileSpecificPath($txtfilepath, $txtWriteOut)';
            echo '<br>$pdffilepath :' . $pdffilepath . '';
            echo '<br>$txtfilepath :' . $txtfilepath . '';
            echo '<br>$pdfOutputOption :' . $pdfOutputOption . '';
            echo '<br>';
        }

            //===================Output PDF file====================================================================
            $mpdf->Output($pdffilepath, $pdfOutputOption);
            Util::writeFileSpecificPath($txtfilepath, $txtWriteOut);

            //===================Prepare anu run rename : $commandRename ===========================================
            $commandRename = 'mv ' . $pdffilepath . ' ' . $pdffilepathFormat2;
            $commandRename_txt = 'mv ' . $txtfilepath . ' ' . $txtfilepathFormat2;

            if ($dbg_print_patient_pdf) {
                echo '<br>Start "exec($commandRename, $output, $retval) : ' . $commandRename . '<br>';
            }
            if (exec($commandRename, $output, $retval) == 0) {
                if ($retval == 0) {
                    if ($dbg_print_patient_pdf) {
                        echo '<br>Prepare $commandRename : ' . $commandRename . '<br>';
                    }
                } else {
                    echo 'execute command "' . $commandRename . '" successful.<br>';
                    echo "Returned with status $retval and output:\n<br>";
                    print_r($output);
                }
            } else {
                echo 'execute command "' . $commandRename . '" Fail.<br>';
                echo "Returned with status $retval and output:\n<br>";
                print_r($output);
            }
            
            
            if ($dbg_print_patient_pdf) {
                echo '<br>Start "exec($commandRename_txt, $output, $retval) : ' . $commandRename_txt . '<br>';
            }
            if (exec($commandRename_txt, $output, $retval) == 0) {
                if ($retval == 0) {
                    if ($dbg_print_patient_pdf) {
                        echo '<br>Prepare $commandRename : ' . $commandRename_txt . '<br>';
                    }
                } else {
                    echo 'execute command "' . $commandRename_txt . '" successful.<br>';
                    echo "Returned with status $retval and output:\n<br>";
                    print_r($output);
                }
            } else {
                echo 'execute command "' . $commandRename_txt . '" Fail.<br>';
                echo "Returned with status $retval and output:\n<br>";
                print_r($output);
            }


            //===================Prepare $command1 $command2 ===========================================
            // command1 example:  "magick -density 300 ./customerfile/SN2303646_Rt0FEhUQMI_1696062511/SN2303646.pdf -density 300 ./customerfile/SN2303646_Rt0FEhUQMI_1696062511/SN2303646.jpg" 
            // command2 example:"7z a -tzip ./customerfile/SN2303646_Rt0FEhUQMI_1696062511/SN2303646.zip ./customerfile/SN2303646_Rt0FEhUQMI_1696062511/SN2303646*.pdf ./customerfile/SN2303646_Rt0FEhUQMI_1696062511/SN2303646*.jpg"

            if ($os == "WINNT") {
                $command1 = 'magick -density 300 ' . $pdffilepathFormat2 . ' -density 300 ' . $jpgfilepathFormat2;
                $command2 = '7z a -tzip ' . $zipfilepathFormat2 . ' ' . $inputtargetpdf2zipfile . ' ' . $inputtargetjpg2zipfile . ' ' . $inputtargettxt2zipfile;
            }
            if ($os == "Linux") {
                $command1 = '/usr/local/bin/magick -density 300 ' . $pdffilepathFormat2 . ' -density 300 ' . $jpgfilepathFormat2;
                //$command2 = '7z a -tzip ' . $zipfilepathFormat2 . ' ' . $inputtargetpdf2zipfile . ' ' . $inputtargetjpg2zipfile;
                $command2 = 'cd ' . $targetFolderForDownload . ' && zip  ' . $reportFileNameFormat2 . '.zip *.jpg *.pdf *.txt';
            }

            if ($dbg_print_patient_pdf) {
                echo '<br>Prepare command1 : ' . $command1 . '';
                echo '<br>Prepare command2 : ' . $command2 . '<br>';
            }




            //===================$command1===========================================
            if (exec($command1, $output, $retval) == 0) {
                if ($retval == 0) {
                    if ($dbg_print_patient_pdf) {
                        echo '<br>execute command "' . $command1 . '" successful<br>';
                        echo "Returned with status $retval and output:\n<br>";
                        print_r($output);
                        echo '<br>';
                    }
                } else {
                    echo '<br>execute command "' . $command1 . '" .<br>';
                    echo "Returned with status $retval and output:\n<br>";
                    print_r($output);
                    echo '<br>';
                }
            } else {
                echo '<br>execute command "' . $command1 . '" Fail.<br>';
                echo "Returned with status $retval and output:\n<br>";
                print_r($output);
                echo '<br>';
            }





            //===================$command2===========================================

            if ($dbg_print_patient_pdf) {
                echo '<br>:Start  "exec($command2, $output, $retval)" : ' . $command2 . '<br>';
            }
            if (exec($command2, $output, $retval) == 0) {
                if ($retval == 0) {
                    if ($dbg_print_patient_pdf) {
                        echo '<br>execute command "' . $command2 . '" successful<br>';
                        echo "Returned with status $retval and output:\n<br>";
                        print_r($output);
                        echo '<br>';
                    }
                } else {
                    echo '<br>execute command "' . $command2 . '" .<br>';
                    echo "Returned with status $retval and output:\n<br>";
                    print_r($output);
                    echo '<br>';
                }
            } else {
                echo '<br>execute command "' . $command2 . '" fail.<br>';
                echo "Returned with status $retval and output:\n<br><br>";
                print_r($output);
                echo '<br>';
            }

            //=====================================================================
        }
    }
} else {
    //View or download mode
    $mpdf->Output($reportFileName . '.pdf', $pdfOutputOption);
    //Util::writeFile('testWrite.txt', $txtWriteOut);
}
?>

<?php if (!($requestFrom == 'patient_edit_php')): ?>
    <br>
    <p style="text-align:center;">
        <a id="downloadLink" aligned="center" href="<?= $zipfilepathFormat2 ?>" download >
            If not download automatically, <br>
            Please Download PDF/JPG in Zip File Here.
        </a>
        <br> You can close this window.
    </p>
<?php endif; ?>

<script>
    var downloadTimeout = setTimeout(function () {
        window.location = document.getElementById('downloadLink').href;
    }, 1000);
</script>