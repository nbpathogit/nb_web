<!-- Customized Bootstrap Stylesheet -->
<link href="css/bootstrap.min.css" rel="stylesheet">

<?php
require_once __DIR__ . '/vendor/autoload.php';
if (!isset($inited)) {
    require 'includes/init.php';
}
Auth::requireLogin();
$dbg_print_patient_pdf = FALSE;
?>

<?php require 'user_auth.php'; ?>
<?php //require 'includes/header.php';   ?>

<?php
if (!Auth::isLoggedIn()) {
    Util::alert(" You are not login.");
    die();
}

$skey = $_SESSION['skey'];
$os = PHP_OS;
if ($dbg_print_patient_pdf) {
    echo '<br>Server run on OS : ' . $os . '<br>';
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


$header = file_get_contents('pdf_note/patient_format_header_pdf_note.php');
if ($hideTable) {
    $header = str_replace("border: 1px solid green;", "", $header);
    $header = str_replace("border: 1px solid red;", "", $header);
}

//Job5
$signedpatho = "";
$job5s = Job::getByPatientJobRole($conn, $patient[0]['id'], 5); // Patho1

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

$header = str_replace("<pname>", $preName . $patient[0]['pname'], $header);
$header = str_replace("<plastname>", $patient[0]['plastname'], $header);
$header = str_replace("<surgical_number>", $patient[0]['pnum'], $header);
$header = str_replace("<pg>", $pgender, $header);
$header = str_replace("<pedge>", $patient[0]['pedge'], $header);
$header = str_replace("<plabnum>", $patient[0]['plabnum'], $header);
$header = str_replace("<phospital_num>", $patient[0]['phospital_num'], $header);
$header = str_replace("<pclinician>", $clinician[0]['name'], $header);
//$header = str_replace("<ward>"           ,"", $header);
$header = str_replace("<hospital>", $hospital[0]['hospital'], $header);
$header = str_replace("<date_1000>", $patient[0]['date_1000'], $header);
//$header = str_replace("<an_name>"          ,$patient[0][''], $header);
$header = str_replace("<date_first_report>", $patient[0]['date_20000'], $header);

$header = str_replace("<signedpatho>", $signedpatho, $header);

echo
$mpdf->SetHTMLHeader($header);

$footer = '<hr>';
$mpdf->SetHTMLFooter($footer);

//==START PN type =====================================================================================================================================
//==END PN type =====================================================================================================================================

//
//$mpdf->Output();
//$pdfOutputOption
//'D': download the PDF file
//'I': serves in-line to the browser
//'S': returns the PDF document as a string
//'F': save as file $file_out


$reportFileName = 'Note_' . $patient[0]['pnum'] . '_' . $patient[0]['phospital_num'];

if ($isPreviewMode == TRUE) {
    $reportFileName = 'PREVIEW_' . $reportFileName;
}
if ($hideTable == FALSE) {
    $reportFileName = 'LAYOUTVIEW_' . $reportFileName;
}

$reportFileNameFormat2 = $reportFileName . '_' . $patient[0]['pname'] . '_' . $patient[0]['plastname'];

$reportFileName = str_replace(' ', '-', $reportFileName);
$reportFileNameFormat2 = str_replace(' ', '-', $reportFileNameFormat2);

//View or download mode
$mpdf->Output($reportFileName . '.pdf', $pdfOutputOption);

?>



<script>

</script>