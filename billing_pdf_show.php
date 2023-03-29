<?php


require 'includes/init.php';
require_once __DIR__ . '/vendor/autoload.php';
$conn = require 'includes/db.php';
Auth::requireLogin();
require 'user_auth.php';

$costs = Billing::getCostGroupbyServiceTyoebyHospitalbyDateRange($conn, $_POST['hospital_id'], $_POST['startdate'], $_POST['enddate']);
$billings = Billing::getBillbyHospitalbyDateRange($conn, $_POST['hospital_id'], $_POST['startdate'], $_POST['enddate']);
?>


<?php


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


$mpdf = new \Mpdf\Mpdf(
        [
    'mode' => 'utf-8',
    'format' => 'A4' . ('orientation' == 'L' ? '-L' : ''),
    'orientation' => 0,
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 15,
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




//$mpdf->setLogger(new class extends \Psr\Log\AbstractLogger {
//    public function log($level, $message, array $context = [])
//    {
//        $myLog = $level . ': ' . $message . "\n";
//        $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
//        fwrite($myfile, $myLog);
//        fclose($myfile);
//    }
//});

//$mpdf->SetDisplayMode('fullwidth');
//$mpdf->shrink_tables_to_fit = 1;
//$mpdf->table_error_report = TRUE;

$str1 = file_get_contents('billinngpdftemplate.php');


 $str_head = '<tr>'.    
         '<th>#</th>' .
            '<th >เลขที่งาน</th>' .
            '<th >ผู้ป่วย</th>' .

            '<th >code</th>' .
            '<th >description</th>' .
            '<th >วันที่รับ</th>' .

            '<th >เลขที่โรงพยาบาล</th>' .
            '<th >แพทย์ผู้ส่งตรวจ</th>' .
            '<th >ค่าตรวจ</th>' .
           
         '</tr>';
 //            '<th >โรงพยาบาล</th>' .
 //                        '<th >ชนิดค่าบริการ</th>' .
//echo $str1;

$str1 = str_replace("<header_message>", $str_head, $str1);

$str_body="";
foreach ($billings as $index => $bill){
    
    $str_body = $str_body  .'<tr>
                <td>' . $index . '</td>
                <td>' . $bill['number'] . '</td>
                <td>' . $bill['ppre_name'] . ' ' . $bill['name'] . ' ' . $bill['lastname'] . '</td>

                <td>' . $bill['code_description'] . '</td>
                <td>' . $bill['description'] . '</td>
                <td>' . substr($bill['import_date'],0,10) . '</td>
                
                <td>' . $bill['phospital_num'] . '</td>
                <td>' . $bill['send_doctor'] . '</td>
                <td>' . $bill['cost'] . '</td>

                </tr>';
    //<td>' . $bill['hospital'] . '</td>
//                    <td>' . $bill['service_type'] . '</td>
    
}

$str1 = str_replace("<body_message>", $str_body, $str1);

try {
    
    

    $mpdf->WriteHTML($str1);


    $mpdf->Output();
    
} catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
    // Process the exception, log, print etc.
    echo $e->getMessage();
}






?>