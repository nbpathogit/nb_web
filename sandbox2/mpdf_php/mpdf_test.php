<?php

require_once './../../vendor/autoload.php';

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
        }else{
            return [true, 'angsana'];; // for Greek language, font is not core suitable and the font is Frutiger
            
        }

        return parent::getLanguageOptions($llcc, $adobeCJK);
    }

}

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];
$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf(
        [
    'mode' => 'utf-8',
    'format' => 'A4' . ('orientation' == 'L' ? '-L' : ''),
    'orientation' => 0,
    'margin_left' => 3,
    'margin_right' => 3,
    'margin_top' => 3,
    'margin_bottom' => 0,
    'margin_header' => 0,
    'margin_footer' => 0,
    'languageToFont' => new CustomLanguageToFontImplementation(),
    'autoScriptToLang' => true,
    'autoLangToFont' => true,
    'default_font' => 'angsana',
    'fontDir' => array_merge($fontDirs, [
        './../../fonts',
    ]),
    'fontdata' => $fontData + [
            'angsana' => [
                'R' => 'angsau.ttf',
                'B' => 'angsaub.ttf',
                'I' =>  'angsaui.ttf',
                'BI' => 'angsaubz.ttf',
            ]
        ],
        ]
);




$texttt = '
    
    
    <p >ไทยengβไทยengβไทยβไทยβeng</p>
   
 
 
    ';
$mpdf->WriteHTML($texttt, \Mpdf\HTMLParserMode::HTML_BODY);
//die();
$mpdf->Output();
