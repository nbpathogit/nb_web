<?php
require_once __DIR__ . '/vendor/autoload.php';
require 'includes/init.php';
Auth::requireLogin();
?>
<?php
$conn = require 'includes/db.php'; ?>

<?php require 'user_auth.php'; ?>
<?php //require 'includes/header.php';?>
<?php if (!Auth::isLoggedIn()) : ?>
    <?php require 'blockopen.php'; ?>
    You are not login.
    <?php require 'blockclose.php'; ?>
<?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust) && !$isUnderCurHospital): ?>   
    <?php require 'blockopen.php'; ?>
    You have no authorize to view other hospital group. 
    <?php require 'blockclose.php'; ?>
<?php else : ?>
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
        'margin_left' => 1.5,
        'margin_right' => 0,
        'margin_top' => 1.5,
        'margin_bottom' => 0,
        'margin_header' => 0,
        'margin_footer' => 0,
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








 



    $sn = file_get_contents('pdf_sn1/sn1.php');

    $mpdf->WriteHTML($sn);

//die();
    $mpdf->Output();
    
    ?>



<?php endif; ?>