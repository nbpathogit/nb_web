<?php

require_once('vendor/autoload.php');

require 'includes/init.php';
Auth::requireLogin();

$conn = require 'includes/db.php';




$labelPrints = LabelPrint::getAllbyUserID($conn, $_GET['userid']);

if (!$labelPrints) {
    // Skip show table
}



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
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
$pdf->SetMargins(2.5, 2.5, 0);


// set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetAutoPageBreak(TRUE, 0);


// set image scale factor
// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}



$num_cal = 8;
$num_row = 10;

$count_element = 0;

$sn = file_get_contents('pdf_sn1/sn1.php');

$sn = str_replace("background-color: darkgray;", "", $sn);
$sn = str_replace("display: inline-block;", "", $sn);

$htmltxt = "";
$htmltxt = $htmltxt . $sn;
$htmltxt = $htmltxt . "<table>";
$trflag1 = false;
foreach ($labelPrints as $element) {
    $count_element ++;
    if ($count_element % $num_cal == 1) {
        $htmltxt = $htmltxt . "<tr>\n";
    }
    $htmltxt = $htmltxt . "<td  class=\"datatd\">";

    $htmltxt = $htmltxt . "<span class=\"r1\" >" . $element['sn_num']  . "</span>". "<br>";
    $htmltxt = $htmltxt . "<span class=\"r2_1\" >" . $element['patho_abbreviation'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class=\"r2_2\">" . $element['speciment_abbreviation'] . "</span><br>";
    $htmltxt = $htmltxt . "<span class=\"r3\" >" . $element['hn_num']. "</span><br>";
    $htmltxt = $htmltxt . "<span class=\"r4\" >" . $element['accept_date'] . "</span><br>";
    $htmltxt = $htmltxt . "<span class=\"r5\" >" . $element['company_name'] . "</span>";

    $htmltxt = $htmltxt . "</td>\n";

    $htmltxt = $htmltxt . "<td class=\"padwidth25\"  style=\" font-size: 1pt \"></td>\n";
    if ($count_element % $num_cal == 0) {
        $htmltxt = $htmltxt . "</tr>\n<tr><td class=\"padhigh25 \" style=\" font-size: 1pt \"  > </td></tr>\n";
        $trflag1 = false;
    } else {
        $trflag1 = true;
    }
}
if ($trflag1) {
    $htmltxt = $htmltxt . "</tr>\n<tr><td class=\"rolspacetbl\"></td></tr>\n";
    $trflag1 = false;
}
$htmltxt = $htmltxt . "</table>\n";

//echo $htmltxt;
//die();



$htmltxt2 = file_get_contents('pdf_sn1/sn1.php');

$pdf->SetFont('helvetica', '', 7);
// add a page
$pdf->AddPage('P', 'A4');


//echo $htmltxt;
//die();
// output the HTML content
$pdf->writeHTML($htmltxt, true, 0, true, 0);



// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('labelprint.pdf', 'I');




// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//============================================================+