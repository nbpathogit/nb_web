<?php


require 'includes/init.php';
require_once __DIR__ . '/vendor/autoload.php';
$conn = require 'includes/db.php';
Auth::requireLogin();
require 'user_auth.php';

// show/hide table for see layout
if (isset($_POST['layout'])) {
    $hideLayout = false;
} else {
    $hideLayout = true;
}

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
    'margin_left' => 25,
    'margin_right' => 25,
    'margin_top' => 25,
    'margin_bottom' => 25,
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


$str1 = file_get_contents('pdf_invoice/billingletter1.php');
if ($hideLayout) {
    $str1 = str_replace("border: 1px solid green;", "", $str1);
}





//echo $_POST['page1'];
//var_dump($_POST['page1']);
$page1 = $_POST['page1'];
if ($hideLayout) {
    $page1 = str_replace("border: 1px solid green;", "", $page1);
    $page1 = str_replace('color:red', "", $page1);
}
//$mpdf->WriteHTML($page1);
$html = $page1;
//============================================================
$chunk = 1000000;
$long_html = strlen($html);
//echo "page4_size=".$long_html;
$long_int  = intval($long_html/$chunk);

if($long_int > 0)
{
    for($i = 0; $i<$long_int; $i++)
    {
        $temp_html = substr($html, ($i*$chunk),999999);
        $mpdf->WriteHTML($temp_html);
    }
    //Last block
    $temp_html = substr($html, ($i*$chunk),($long_html-($i*$chunk)));
    $mpdf->WriteHTML($temp_html);
}
else
{
    $mpdf->WriteHTML($html);
}
//===============================================================


$mpdf->AddPage();
$page2 = $_POST['page2'];
if ($hideLayout) {
    $page2 = str_replace("border: 1px solid green;", "", $page2);
    $page2 = str_replace('color:red', "", $page2);
}
//$mpdf->WriteHTML($page2);
$html = $page2;
//============================================================
$chunk = 1000000;
$long_html = strlen($html);
//echo "page4_size=".$long_html;
$long_int  = intval($long_html/$chunk);

if($long_int > 0)
{
    for($i = 0; $i<$long_int; $i++)
    {
        $temp_html = substr($html, ($i*$chunk),999999);
        $mpdf->WriteHTML($temp_html);
    }
    //Last block
    $temp_html = substr($html, ($i*$chunk),($long_html-($i*$chunk)));
    $mpdf->WriteHTML($temp_html);
}
else
{
    $mpdf->WriteHTML($html);
}
//===============================================================

$mpdf->AddPage();
$page3 = $_POST['page3'];
if ($hideLayout) {
    $page3 = str_replace("border: 1px solid green;", "", $page3);
    $page3 = str_replace('color:red', "", $page3);
}
//$mpdf->WriteHTML($page3);
$html = $page3;
//============================================================
$chunk = 1000000;
$long_html = strlen($html);
//echo "page4_size=".$long_html;
$long_int  = intval($long_html/$chunk);

if($long_int > 0)
{
    for($i = 0; $i<$long_int; $i++)
    {
        $temp_html = substr($html, ($i*$chunk),999999);
        $mpdf->WriteHTML($temp_html);
    }
    //Last block
    $temp_html = substr($html, ($i*$chunk),($long_html-($i*$chunk)));
    $mpdf->WriteHTML($temp_html);
}
else
{
    $mpdf->WriteHTML($html);
}
//===============================================================

//https://mpdf.github.io/reference/mpdf-functions/addpage.html
//$mpdf->AddPage('L','','','','',15,15,17,17,0,0);
//$page4 = $_POST['page4'];
//if ($hideLayout) {
//    $page4 = str_replace("border: 1px solid green;", "", $page4);
//    $page4 = str_replace('color:red', "", $page4);
//}
//$mpdf->WriteHTML($page4);

//https://mpdf.github.io/reference/mpdf-functions/addpage.html
$mpdf->AddPage('L','','','','',15,15,17,17,0,0);
$page4 = $_POST['page4'];
if ($hideLayout) {
    $page4 = str_replace("border: 1px solid green;", "", $page4);
    $page4 = str_replace('color:red', "", $page4);
}
//$mpdf->WriteHTML($page4);
$html = $page4;
//============================================================
$chunk = 1000000;
$long_html = strlen($html);
//echo "page4_size=".$long_html;
$long_int  = intval($long_html/$chunk);

if($long_int > 0)
{
    for($i = 0; $i<$long_int; $i++)
    {
        $temp_html = substr($html, ($i*$chunk),999999);
        $mpdf->WriteHTML($temp_html);
    }
    //Last block
    $temp_html = substr($html, ($i*$chunk),($long_html-($i*$chunk)));
    $mpdf->WriteHTML($temp_html);
}
else
{
    $mpdf->WriteHTML($html);
}
//===============================================================





$mpdf->AddPage();
$page5 = $_POST['page5'];
if ($hideLayout) {
    $page5 = str_replace("border: 1px solid green;", "", $page5);
    $page5 = str_replace('color:red', "", $page5);
}
//$mpdf->WriteHTML($page5);
$html = $page5;
//============================================================
$chunk = 1000000;
$long_html = strlen($html);
//echo "page4_size=".$long_html;
$long_int  = intval($long_html/$chunk);

if($long_int > 0)
{
    for($i = 0; $i<$long_int; $i++)
    {
        $temp_html = substr($html, ($i*$chunk),999999);
        $mpdf->WriteHTML($temp_html);
    }
    //Last block
    $temp_html = substr($html, ($i*$chunk),($long_html-($i*$chunk)));
    $mpdf->WriteHTML($temp_html);
}
else
{
    $mpdf->WriteHTML($html);
}
//===============================================================


$mpdf->Output();

die();



//ต้นฉบับ
$str1 = file_get_contents('pdf_invoice/billingletterinvoice.php');

if ($hideLayout) {
    $str1 = str_replace("border: 1px solid green;", "", $str1);
}
$mpdf->WriteHTML($str1);



//สำเนา
$mpdf->AddPage();
$mpdf->WriteHTML($str1);



//$mpdf->AddPage();
//void AddPage ( [ string $orientation [, string $type [, string $resetpagenum [, string $pagenumstyle [, string $suppress [, float $margin-left [, float $margin-right [, float $$margin-top [, float $$margin-bottom [, float $$margin-header [, float $margin-footer [, string $odd-header-name [, string $even-header-name [, string $$odd-footer-name [, string $$even-footer-name [, mixed $$odd-header-value [, mixed $even-header-value [, mixed $odd-footer-value [, mixed $$even-footer-value [, string $pageselector [, mixed $sheet-size ]]]]]]]]]]]]]]]]]]]]]);
$mpdf->AddPage('','','','','',15,15,17,17,0,0);

//'margin_left' => 25,
//    'margin_right' => 25,
//    'margin_top' => 25,
//    'margin_bottom' => 25,
//$mpdf->SetMargins($left, $right, $top);
$mpdf->SetMargins(17, 17, 17);
$mpdf->shrink_tables_to_fit = 1;
//$mpdf->table_error_report = TRUE;

$str1 = file_get_contents('pdf_invoice/billinngpdftemplate.php');


 $str_head = '<tr>'.    
         '<th >#</th>' .
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

    $mpdf->SetDisplayMode('fullwidth','single');  
    $mpdf->Output();
    
} catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
    // Process the exception, log, print etc.
    echo $e->getMessage();
}






?>