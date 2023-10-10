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
$presultupdate1s = Presultupdate::getAllofGroup1Asc($conn, $patient_id);
$presultupdate2s = Presultupdate::getAllofGroup2Desc($conn, $patient_id);
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






//=======Result2 Group=========

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
    $counter_result2++;
    }
} else {
    
}





//=======Result1 Group=========

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

$reportFileName = $patient[0]['pnum'] . '_R' . $counter_result2 . '_' . $patient[0]['phospital_num'];
if ($isPreviewMode == TRUE) {
    $reportFileName = 'PREVIEW_' . $reportFileName;
}
if ($hideTable == FALSE) {
    $reportFileName = 'LAYOUTVIEW_' . $reportFileName;
}
$reportFileName = str_replace(' ', '-', $reportFileName);

if ($pdfOutputOption == 'F') {
    //Create new folder after 'customerfile' Append subforlder wiht SergicalNumber_SecurityKey_TimeInSec

    $targetFolderRelease1 = './release1';
    



//    echo $targetFolder . '<br>';
    if ($requestFrom == 'patient_edit_php') {
        $pdffilepath = $targetFolderRelease1 . '/' . $reportFileName . '.pdf';
        $jpgfilepath = $targetFolderRelease1 . '/' . $reportFileName . '.jpg';
        $zipfilepath = $targetFolderRelease1 . '/' . $reportFileName . '.zip';
        $mpdf->Output($pdffilepath, $pdfOutputOption);

        if ($os == "WINNT") {
            $command1 = 'magick -density 300 ' . $pdffilepath . ' -density 300 ' . $jpgfilepath;
            
            
        }
        if ($os == "Linux") {
            $command1 = '/usr/local/bin/magick -density 300 ' . $pdffilepath . ' -density 300 ' . $jpgfilepath;
        }
        if (exec($command1, $output, $retval) == 0) {
//            echo 'execute command "' . $command1 . '" successful.<br>';
//            echo "Returned with status $retval and output:\n<br>";
//            print_r($output);
        } else {
            echo 'execute command "' . $command1 . '" Fail.<br>';
            echo "Returned with status $retval and output:\n<br>";
            print_r($output);
        }
        

        $cusReportFolder = Hospital::getReportFolder($conn, $patient[0]['phospital_id']);
        if ($cusReportFolder != "") {
//            echo "customer folder =" . $cusReportFolder;
            $targetFolderRelease2 = './customerfile2/' . $cusReportFolder;
//            echo 'targetFolderRelease2=' . $targetFolderRelease2;
            if (!file_exists($targetFolderRelease2) && !is_dir($targetFolderRelease2)) {
                mkdir($targetFolderRelease2, 0777, true);
            }
            //Copy file from "release1" to customer "customerfile2" folder
//            cp ./release1/SN2303647_R1*.jpg ./customerfile2/pathokph
//            cp ./release1/SN2303647_R1*.pdf ./customerfile2/pathokph
            $cmd_copy_pdf = 'cp '.$targetFolderRelease1 . '/' . $reportFileName . '*.pdf '.$targetFolderRelease2;
            $cmd_copy_jpg = 'cp '.$targetFolderRelease1 . '/' . $reportFileName . '*.jpg '.$targetFolderRelease2;
            
//            echo 'cmd_copy_pdf=' .$cmd_copy_pdf .'<br>';
//            echo 'cmd_copy_jpg=' .$cmd_copy_jpg .'<br>';
            
            if (exec($cmd_copy_pdf, $output, $retval) == 0) {
//            echo 'execute command "' . $command1 . '" successful.<br>';
//            echo "Returned with status $retval and output:\n<br>";
//            print_r($output);
            } else {
                echo 'execute command "' . $cmd_copy_pdf . '" Fail.<br>';
                echo "Returned with status $retval and output:\n<br>";
                print_r($output);
            }
            
            if (exec($cmd_copy_jpg, $output, $retval) == 0) {
//            echo 'execute command "' . $command1 . '" successful.<br>';
//            echo "Returned with status $retval and output:\n<br>";
//            print_r($output);
            } else {
                echo 'execute command "' . $cmd_copy_pdf . '" Fail.<br>';
                echo "Returned with status $retval and output:\n<br>";
                print_r($output);
            }

        }else{
            echo "no customer folder";
        }
        
    } elseif ($requestFrom == 'patient_php') {
        $targetFolderForDownload = './customerfile/' . $patient[0]['pnum'] . '_' . $skey . '_' . Time();
        if (!mkdir($targetFolderForDownload, 0777, true)) {
            die('Failed to create directories...' . $targetFolderForDownload);
        } else {
//        echo 'successfull create "' . $targetFolder . '"<br>';
            $pdffilepath = $targetFolderForDownload . '/' . $reportFileName . '.pdf';
            $jpgfilepath = $targetFolderForDownload . '/' . $reportFileName . '.jpg';
            $zipfilepath = $targetFolderForDownload . '/' . $reportFileName . '.zip';

            $inputtargetpdf2zipfile = $targetFolderForDownload . '/' . $reportFileName . '*.pdf';
            $inputtargetjpg2zipfile = $targetFolderForDownload . '/' . $reportFileName . '*.jpg';

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


//            echo '<br>';
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
    }
} else {
    $mpdf->Output($reportFileName . '.pdf', $pdfOutputOption);
}
?>

<?php if (!($requestFrom == 'patient_edit_php')): ?>
    <br>
    <p style="text-align:center;">
        <a id="downloadLink" aligned="center" href="<?= $zipfilepath ?>" download >
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