<?php

require_once('vendor/autoload.php');

require 'includes/init.php';
Auth::requireLogin();

$conn = require 'includes/db.php';




$labelPrints = LabelPrint::getAllbyUserID($conn, $_GET['userid']);

if (!$labelPrints) {
    // Skip show table
}


$num_cal = 8;
$num_row = 12;

if (isset($_GET['a'])) {
    $space_cal_padding = $_GET['a']."mm";
}else{
    $space_cal_padding = "2.5mm";
}

if (isset($_GET['b'])) {
    $space_row_padding = $_GET['b']."mm";
}else{
    $space_row_padding = "2.5mm";
}

//====Use A4=======
//$pageHight = 23;
//$pageWidth = 140;

if (isset($_GET['x'])) {
    $pdf_margin_left = $_GET['x'];
} else {
    $pdf_margin_left = 2.5;
}

if (isset($_GET['y'])) {
    $pdf_margin_top = $_GET['x'];
} else {
    $pdf_margin_top = 2.5;
}
$pdf_margin_right = 0;




// create new PDF document
//============================================================+
// File name   : example_021.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 021 for TCPDF class
//               WriteHTML text flow
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: WriteHTML text flow.
 * @author Nicola Asuni
 * @since 2008-03-04
 */
// Include the main TCPDF library (search for installation path).
//require_once('tcpdf_include.php');
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('N.B.Patho');
$pdf->SetTitle('N.B.Patho lable');
$pdf->SetSubject('SN Label Printing');
$pdf->SetKeywords('');

// set default header data
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

$pdf->SetMargins($pdf_margin_left, $pdf_margin_top, $pdf_margin_right);


// set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//$pdf->SetAutoPageBreak(TRUE, 0);
$pdf->SetAutoPageBreak(FALSE, 0);


// set image scale factor
// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}



$count_element = 0;

$sn = file_get_contents('pdf_sn1/sn1.php');

if(isset($_GET['ishideborder'])){
$sn = str_replace("border: 1px solid black;", "border: none;", $sn);
}

$sn = str_replace("background-color: darkgray;", "", $sn);
$sn = str_replace("display: inline-block;", "", $sn);

$sn = str_replace("widthmm", $space_cal_padding, $sn);
$sn = str_replace("heightmm", $space_row_padding, $sn);

$trflag1 = false;

$htmltxt = "";
$htmltxt = $htmltxt . $sn;

$htmltxt2 = "";
$htmltxt2 = $htmltxt2 . $sn;





foreach ($labelPrints as $element) {

    $count_element ++;
    
    
    //Add new table row then start of num col
    if ( ($count_element % ($num_cal*$num_row)) == 1) {
//        echo "count_element::".$count_element;
//        echo "num_cal::".$num_cal;
//        echo "num_row::".$num_row;
//        echo "num_cal*num_row::".($num_cal*$num_row);
//        echo "<br>";
        $istableclose = false;// Open element of table
        $htmltxt = $htmltxt . "<table>\n";
        
    }
    

    
    //Add new Row of table then start of num col
    if ($count_element % $num_cal == 1) {
        $htmltxt = $htmltxt . "<tr>\n";
    }
    
    //==Add Element Column Section==============================================
    $htmltxt = $htmltxt . "<td  class=\"datatd\">";

    $htmltxt = $htmltxt . "<span class=\"r1\" >" . $element['sn_num']  . "</span>". "<br>";
    $htmltxt = $htmltxt . "<span class=\"r2_1\" >" . $element['patho_abbreviation'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class=\"r2_2\">" . $element['speciment_abbreviation'] . "</span><br>";
    //$htmltxt = $htmltxt . "<span class=\"r3\" >" . $element['hn_num']. "</span><br>";
    $htmltxt = $htmltxt . "<span class=\"r4\" >" . $element['accept_date'] . "</span><br>";
    $htmltxt = $htmltxt . "<span class=\"r5\" >" . $element['company_name'] . "</span>";

    $htmltxt = $htmltxt . "</td>\n";
    //==End Add Element column Section==========================================

    //==Add Space Column Section==============================================
    $htmltxt = $htmltxt . "<td class=\"padwidth\"  style=\" font-size: 1pt \"></td>\n";
    //==Add Space Column Section==============================================
    
    
    //==IF Element add reach num_cal, then add TR with horizontal space for new table row
    if ($count_element % $num_cal == 0) {
        $htmltxt = $htmltxt . "</tr>\n<tr><td class=\"padhigh \" style=\" font-size: 1pt \"  > </td></tr>\n";
        $trflag1 = false;
    } else {
        $trflag1 = true;
    }
    //==IF Element add reach num_cal, then add TR for new table row
    
        //Add new table row then start of num col
    if (($count_element % ($num_cal * $num_row)) == 0) {
//        echo "count_element::".$count_element;
//        echo "num_cal::".$num_cal;
//        echo "num_row::".$num_row;
//        echo "num_cal*num_row::".($num_cal*$num_row);
//        echo "<br>";
        $istableclose = true;
        $htmltxt = $htmltxt . "</table>\n";
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 7);
        // output the HTML content
        $pdf->writeHTML($htmltxt, true, 0, true, 0);
        $htmltxt2 .= $htmltxt;
        $htmltxt =  $sn;
    } 
}
if ($trflag1) {
    $htmltxt = $htmltxt . "</tr>\n<tr><td class=\"rolspacetbl\"></td></tr>\n";
    $trflag1 = false;
}
//$htmltxt = $htmltxt . "</table>\n";
if(!$istableclose){
    $istableclose = true;
    $htmltxt = $htmltxt . "</table>\n";
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 7);
    // output the HTML content
    $pdf->writeHTML($htmltxt, true, 0, true, 0);
    $htmltxt2 .= $htmltxt;
    $htmltxt = $sn;
}

//echo $htmltxt2;
//die();





$pdf->SetFont('helvetica', '', 7);
// add a page
//$pdf->AddPage('P', 'A4');
//$pdf->AddPage();


//echo $htmltxt;
//die();
// output the HTML content
//$pdf->writeHTML($htmltxt, true, 0, true, 0);



// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('labelprint.pdf', 'I');




// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//============================================================+