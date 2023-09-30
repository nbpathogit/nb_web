<!-- Customized Bootstrap Stylesheet -->
<link href="css/bootstrap.min.css" rel="stylesheet">

<?php
require_once __DIR__ . '/vendor/autoload.php';
require 'includes/init.php';
Auth::requireLogin();
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

$isPreviewMode = FALSE;
if (isset($_GET['preview'])) {
    $isPreviewMode = TRUE;
}


$patient_id = 0;
if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];
} else {
    Util::alert("id not avalable");
    die();
}

// show/hide table for see layout
if (isset($_GET['layout'])) {
    $hideTable = false;
} else {
    $hideTable = true;
}

// Set out put option
$pdfOutputOption = 'I';
if (isset($_GET['option'])) {
    $pdfOutputOption = $_GET['option'];
} else {
    $pdfOutputOption = 'I';
}

//Get Specific Patient Row from Table
$conn = require 'includes/db.php';
if (isset($_GET['id'])) {
    $patient = Patient::getAll($conn, $_GET['id']);
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
    $str = "No Patient ID " . $_GET['id'] . ".";

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
$specimens = Specimen::getAll($conn, 1);
$specimen2s = Specimen::getAll($conn, 2);
$clinicians = User::getAllbyClinicians($conn);
$userPathos = User::getAllbyPathologis($conn);
$userTechnic = User::getAllbyTeachien($conn);
$prioritys = Priority::getAll($conn);
$statusLists = Status::getAll($conn);
$labFluids = LabFluid::getAll($conn);

//var_dump($userPathos);
//die();
//Get one by id
$presultupdate1s = Presultupdate::getAllofGroup1Asc($conn, $_GET['id']);
$presultupdate2s = Presultupdate::getAllofGroup2Desc($conn, $_GET['id']);
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


$header = file_get_contents('pdf_result/patient_format_header_pdf.php');
if ($hideTable) {
    $header = str_replace("border: 1px solid green;", "", $header);
    $header = str_replace("border: 1px solid red;", "", $header);
}
$header = str_replace("<pname>", $patient[0]['pname'], $header);
$header = str_replace("<plastname>", $patient[0]['plastname'], $header);
$header = str_replace("<surgical_number>", $patient[0]['pnum'], $header);
$header = str_replace("<pg>", $patient[0]['pgender'], $header);
$header = str_replace("<pedge>", $patient[0]['pedge'], $header);
$header = str_replace("<plabnum>", $patient[0]['plabnum'], $header);
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








$signedpatho = "";
$i = 0;

if (isset($presultupdate2s)) {
//    <?php foreach ($presultupdates as $presultupdate): 

    foreach ($presultupdate2s as $prsu) {
        $isFinished = $prsu['release_time'] != NULL;
        if ($isFinished || $isPreviewMode) {


            $result_id = $prsu['id'];
            $job = Job::getByPatientJobRoleUResult($conn, $patient_id, 6, $result_id);
            $isGroup2SecondPathoAval = isset($job[0]['name']) ? TRUE : FALSE;




            $u_result2 = file_get_contents('pdf_result/patient_format_result_pdf_2.php');
            if ($hideTable) {
                $u_result2 = str_replace("border: 1px solid green;", "", $u_result2);
            }
            $u_result2 = str_replace("<result_type>", isset($prsu['result_type']) ? $prsu['result_type'] : "", $u_result2);
            $u_result2 = str_replace("<result_date>", isset($prsu['release_time']) ? $prsu['release_time'] : "", $u_result2);
//            $result_message = htmlspecialchars($prsu['result_message']);
            $result_message = ($prsu['result_message']);
            $result_message = str_replace("\n", "<br>", $result_message);
//            $result_message = str_replace(" ", "&nbsp;", $result_message);
            $u_result2 = str_replace("<result_message>", isset($prsu['result_message']) ? $result_message : "", $u_result2);
            //            if ($prsu['pathologist2_id'] != 0) {
            if ($isGroup2SecondPathoAval) {
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
    }
} else {
    
}







if (isset($presultupdate1s)) {
//    <?php foreach ($presultupdates as $presultupdate): 
    foreach ($presultupdate1s as $prsu) {
        $u_result2 = file_get_contents('pdf_result/patient_format_result_pdf_1.php');
        if ($hideTable) {
            $u_result2 = str_replace("border: 1px solid green;", "", $u_result2);
        }
        $u_result2 = str_replace("<result_type>", isset($prsu['result_type']) ? $prsu['result_type'] : "", $u_result2);
        $u_result2 = str_replace("<result_date>", isset($prsu['release_time']) ? $prsu['release_time'] : "", $u_result2);
//        $result_message = htmlspecialchars($prsu['result_message']);
        $result_message = ($prsu['result_message']);
        $result_message = str_replace("\n", "<br>", $result_message);
//        $result_message = str_replace(" ", "&nbsp;", $result_message);
        $u_result2 = str_replace("<result_message>", isset($prsu['result_message']) ? $result_message : "", $u_result2);
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


$signature = file_get_contents('pdf_result/patient_format_signature_pdf.php');
$signature = $signature . file_get_contents('pdf_result/patient_format_signature_pdf_1.php');
if ($patient[0]['iscritical'] == 1) {
    $signature = $signature . file_get_contents('pdf_result/patient_format_signature_pdf_2.php');
}
$signature = $signature . file_get_contents('pdf_result/patient_format_signature_pdf_3.php');

$signature = $signature . file_get_contents('pdf_result/patient_format_signature_digital_pdf.php');

$signature = str_replace("<signedpatho>", $signedpatho, $signature);
$signature = str_replace("<release_time>", $release_time, $signature);

if ($hideTable) {
    $signature = str_replace("border: 1px solid green;", "", $signature);
    $signature = str_replace("border: 1px solid red;", "", $signature);
}
$mpdf->WriteHTML($signature);

//die();
//$mpdf->Output();
//$pdfOutputOption
//'D': download the PDF file
//'I': serves in-line to the browser
//'S': returns the PDF document as a string
//'F': save as file $file_out
if ($pdfOutputOption == 'F') {
    //Create new folder after 'customerfile' Append subforlder wiht SergicalNumber_SecurityKey_TimeInSec
    $targetFolder = './customerfile/' . $patient[0]['pnum'] . '_' . $skey . '_' . Time();
//    echo $targetFolder . '<br>';
    if (!mkdir($targetFolder, 0777, true)) {
        die('Failed to create directories...' . $targetFolder);
    } else {
//        echo 'successfull create "' . $targetFolder . '"<br>';
        $pdffilepath = $targetFolder . '/' . $patient[0]['pnum'] . '.pdf';
        $jpgfilepath = $targetFolder . '/' . $patient[0]['pnum'] . '.jpg';
        $zipfilepath = $targetFolder . '/' . $patient[0]['pnum'] . '.zip';

        $inputtargetpdf2zipfile = $targetFolder . '/' . $patient[0]['pnum'] . '*.pdf';
        $inputtargetjpg2zipfile = $targetFolder . '/' . $patient[0]['pnum'] . '*.jpg';

        $mpdf->Output($pdffilepath, $pdfOutputOption);

        // command1 example:  "magick -density 300 ./customerfile/SN2303646_Rt0FEhUQMI_1696062511/SN2303646.pdf -density 300 ./customerfile/SN2303646_Rt0FEhUQMI_1696062511/SN2303646.jpg" 
        // command2 example:"7z a -tzip ./customerfile/SN2303646_Rt0FEhUQMI_1696062511/SN2303646.zip ./customerfile/SN2303646_Rt0FEhUQMI_1696062511/SN2303646*.pdf ./customerfile/SN2303646_Rt0FEhUQMI_1696062511/SN2303646*.jpg"
        
        if ($os == "WINNT") {
            $command1 = 'magick -density 300 ' . $pdffilepath . ' -density 300 ' . $jpgfilepath;
            $command2 = '7z a -tzip ' . $zipfilepath . ' ' . $inputtargetpdf2zipfile . ' ' . $inputtargetjpg2zipfile;
        }
        if ($os == "Linux") {
            $command1 = '/usr/local/bin/magick -density 300 ' . $pdffilepath . ' -density 300 ' . $jpgfilepath;
            $command2 = '7z a -tzip ' . $zipfilepath . ' ' . $inputtargetpdf2zipfile . ' ' . $inputtargetjpg2zipfile;
        }


        echo '<br>';
        if (exec($command1, $output, $retval) == 0) {
//            echo 'execute command "' . $command1 . '" successful.<br>';
//            echo "Returned with status $retval and output:\n<br>";
//            print_r($output);
        } else {
            echo 'execute command "' . $command1 . '" Fail.<br>';
            echo "Returned with status $retval and output:\n<br>";
            print_r($output);
        }

        if (exec($command2, $output, $retval) == 0) {
//            echo 'execute command "' . $command2 . '" successful.<br>';
//            echo "Returned with status $retval and output:\n<br>";
//            print_r($output);
        } else {
            echo 'execute command "' . $command2 . '" fail.<br>';
            echo "Returned with status $retval and output:\n<br><br>";
            print_r($output);
        }
    }
} else {
    $mpdf->Output($patient[0]['pnum'] . '.pdf', $pdfOutputOption);
}
?>
<br>
<p style="text-align:center;">
<a id="downloadLink" aligned="center" href="<?= $zipfilepath ?>" download >
    Download PDF/JPG in Zip File Here.
</a>
</p>
<script> 
    var downloadTimeout = setTimeout(function () {
        window.location = document.getElementById('downloadLink').href;
    }, 1000);
</script>