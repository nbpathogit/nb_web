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
} elseif (($isCurUserClinicianCust || $isCurUserHospitalCust) && !$isUnderCurHospital) {
    Util::alert("You have no authorize to view other hospital group.");
} else {
    //Allow to do next 
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

$mpdf->SetHTMLHeader("Header");
$mpdf->WriteHTML("Data");
$mpdf->SetHTMLFooter("footer");

$mpdf->Output();
