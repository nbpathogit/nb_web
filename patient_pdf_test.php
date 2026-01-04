<?php
// FOR TEST CALL REQUIRE
$patient_id = 10288;
$dbg_print_patient_pdf = FALSE;

//========CREATE AND Generate report file following th option send=================
//$mpdf->Output();
//$pdfOutputOption
//'D': download the PDF file
//'I': serves in-line to the browser
//'S': returns the PDF document as a string
//'F': save as file $file_out
$pdfOutputOption = 'I';

$hideTable = true;
$requestFrom = 'patient_edit_php';
//$inited = TRUE;

require 'patient_pdf.php';

