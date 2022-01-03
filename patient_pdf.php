<?php

require_once __DIR__ . '/vendor/autoload.php';

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
$mpdf = new \Mpdf\Mpdf(array(
//        'format' => array(57,160),
        'mode'   => 'utf-8',
        'default_font' => 'angsana',
        'default_font_size' => 18,
//        'tempDir' => '/tmp',
//        'margin_left' => 4,
//        'margin_right' => 6,
//        'margin_top' => 1,
//        'margin_bottom' => 5,
        ));

$content = file_get_contents('patient_format_pdf.php');

$mpdf->WriteHTML($content);
$mpdf->Output();
?>