<?php

require_once __DIR__ . '/vendor/autoload.php';


require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

// true = Disable Edit page, false canEditPage
$isEditModePageOn = false;




//Get Specific Row from Table
if (isset($_GET['id'])) {
    $patient = Patient::getAll($conn, $_GET['id']);
} else {
    $patient = null;
}
//var_dump($patient);
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

//var_dump($userPathos);
//die();
//Get one by id
$presultupdates = Presultupdate::getAllDesc($conn, $_GET['id']);
//var_dump($patient[0]['pclinician_id']);


$clinician = User::getAll($conn, $patient[0]['pclinician_id']);
$hospital = Hospital::getAll($conn, $patient[0]['phospital_id']);
$pathologist = User::getAllbyPathologis($conn, $patient[0]['ppathologist_id']);
$pathologist2 = User::getAllbyPathologis($conn, $patient[0]['ppathologist2_id']);

//var_dump($patient[0]['ppathologist_id']);
//var_dump($patient[0]['ppathologist2_id']);
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


//Prepare Status
$curstatus = Status::getAll($conn, $patient[0]['status_id']);
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

        var_dump($lang);
        var_dump($country);
        var_dump($script);
        echo "<br>";
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

$mpdf->shrink_tables_to_fit = 1;

$header = file_get_contents('pdf_result/patient_format_header_pdf.php');
$header = str_replace("border: 1px solid green;", "", $header);
$header = str_replace("border: 1px solid red;", "", $header);
$header = str_replace("<pname>", $patient[0]['pname'], $header);
$header = str_replace("<plastname>", $patient[0]['plastname'], $header);
$header = str_replace("<Surgical_Number>", "", $header);
$header = str_replace("<pg>", $patient[0]['pgender'], $header);
$header = str_replace("<pedge>", $patient[0]['pedge'], $header);
$header = str_replace("<plabnum>", $patient[0]['pname'], $header);
$header = str_replace("<phospital_num>", $patient[0]['phospital_num'], $header);
$header = str_replace("<pclinician>", $clinician[0]['name'], $header);
//$header = str_replace("<ward>"           ,"", $header);
$header = str_replace("<hospital>", $hospital[0]['hospital'], $header);
$header = str_replace("<date_1000>", $patient[0]['date_1000'], $header);
//$header = str_replace("<an_name>"          ,$patient[0][''], $header);
$header = str_replace("<date_first_report>", $patient[0]['date_20000'], $header);
$mpdf->SetHTMLHeader($header);

$footer = '<hr><div style="text-align: center; font-weight: bold;font-family:angsana; font-size:14pt; color:#000000;"> page {PAGENO} of {nb} </div>';
$mpdf->SetHTMLFooter($footer);



if (isset($presultupdates)) {
//    <?php foreach ($presultupdates as $presultupdate): 
    foreach ($presultupdates as $prsu) {
        $u_result2 = file_get_contents('pdf_result/patient_format_result_pdf_2.php');
        $u_result2 = str_replace("border: 1px solid green;", "", $u_result2);
        $u_result2 = str_replace("<result_type>", isset($prsu['result_type']) ? $prsu['result_type'] : "", $u_result2);
        $u_result2 = str_replace("<result_date>", isset($prsu['release_time']) ? $prsu['release_time'] : "", $u_result2);
        $u_result2 = str_replace("<result_message>", isset($prsu['result_message']) ? $prsu['result_message'] : "", $u_result2);
        if ($prsu['pathologist2_id'] != 0) {
            $confirm_msg = "<br>NOTE: According to the first diagnosis of malignancy, this case was discussed with the second pathologist";
            $secondPatho = User::getByID($conn, $prsu['pathologist2_id']);
            $confirm_msg = $confirm_msg . "(" . $secondPatho->name_e . " " . $secondPatho->lastname_e . ")";
//            var_dump($confirm_msg);
//            die();
            $u_result2 = str_replace("<confirm_message>", $confirm_msg, $u_result2);
        } else {
            $u_result2 = str_replace("<confirm_message>", "", $u_result2);
        }

        $mpdf->WriteHTML($u_result2);
    }
} else {
    
}



$u_result1 = file_get_contents('pdf_result/patient_format_result_pdf_1.php');
$u_result1 = str_replace("border: 1px solid green;", "", $u_result1);

$p_rs_diagnosis = str_replace("\n", "<br>", $patient[0]['p_rs_diagnosis']);
$p_rs_diagnosis = str_replace(" ", "&nbsp;", $p_rs_diagnosis);
$u_result1 = str_replace("<p_rs_diagnosis>", $p_rs_diagnosis, $u_result1);
$u_result1 = str_replace("<date_first_report>", $patient[0]['date_first_report'], $u_result1);

$p_rs_specimen = str_replace("\n", "<br>", $patient[0]['p_rs_specimen']);
$p_rs_specimen = str_replace(" ", "&nbsp;", $p_rs_specimen);
$u_result1 = str_replace("<p_rs_specimen>", $p_rs_specimen, $u_result1);

$p_rs_gross_desc = str_replace("\n", "<br>", $patient[0]['p_rs_specimen']);
$p_rs_gross_desc = str_replace(" ", "&nbsp;", $p_rs_gross_desc);
$u_result1 = str_replace("<p_rs_gross_desc>", $p_rs_gross_desc, $u_result1);

$p_rs_microscopic_desc = str_replace("\n", "<br>", $patient[0]['p_rs_microscopic_desc']);
$p_rs_microscopic_desc = str_replace(" ", "&nbsp;", $p_rs_microscopic_desc);
$u_result1 = str_replace("<p_rs_microscopic_desc>", $p_rs_microscopic_desc, $u_result1);

$p_rs_clinical_diag = str_replace("\n", "<br>", $patient[0]['p_rs_clinical_diag']);
$p_rs_clinical_diag = str_replace(" ", "&nbsp;", $p_rs_clinical_diag);
$u_result1 = str_replace("<p_rs_clinical_diag>", $p_rs_clinical_diag, $u_result1);


$mpdf->WriteHTML($u_result1);


$signature = file_get_contents('pdf_result/patient_format_signature_pdf.php');
$signature = str_replace("border: 1px solid green;", "", $signature);
$signature = str_replace("border: 1px solid red;", "", $signature);
$mpdf->WriteHTML($signature);

//die();
$mpdf->Output();
?>