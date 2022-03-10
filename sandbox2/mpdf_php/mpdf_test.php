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
//    'orientation' => 'L',
//    'format' => 'A4' . ('orientation' == 'L' ? '-L' : ''),
    'format' => 'A4',
//    'autoPageBreak' => true,
    'autoPageBreak' => true,
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 15,
    'margin_bottom' => 15,
    'margin_header' => 15,
    'margin_footer' => 15,
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
                'BI' => 'angsauz.ttf',
            ]
        ],
        ]
);




$texttt = '
<style>
    div { border: 1px solid black; padding: 1em; }
    .level1 { box-decoration-break: slice; }
    .level2 { box-decoration-break: clone; }
    .level3 { box-decoration-break: clone; }
</style>

<div class="level1">
    level1
    <div class="level2">
    level2
        <div class="level3">
            level3
        </div>
    </div>
</div>
';

$texttt2 = '
<style>
    div { border: 1px solid black;  }
    .level1 { box-decoration-break: slice; }
    .level2 { box-decoration-break: clone; }
    .level3 { box-decoration-break: clone; }
</style>

<div class="level1">
    level1
    <div class="level2">
    level2
        <div class="level3">
            level3
        </div>
    </div>
</div>
';

$mpdf->autoPageBreak = true;

$mpdf->SetHeader('Your header [pagetotal]');
//$mpdf->SetFooter('Document Title');

$mpdf->WriteHTML($texttt);
$mpdf->WriteHTML($texttt2);
$mpdf->WriteHTML($texttt);
$mpdf->WriteHTML($texttt2);
$mpdf->WriteHTML($texttt);
$mpdf->WriteHTML($texttt2);
$mpdf->WriteHTML($texttt);
$mpdf->WriteHTML($texttt2);
$mpdf->WriteHTML($texttt);
$mpdf->WriteHTML($texttt2);
$mpdf->WriteHTML($texttt);
$mpdf->WriteHTML($texttt2);


//$mpdf->AddPage();
//$mpdf->WriteHTML($texttt);
//die();
$mpdf->Output();
