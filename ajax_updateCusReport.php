<?php

$dbg_print_patient_pdf = TRUE;


require 'includes/init.php';
$inited = TRUE;

$patient_id = intval($_POST['patient_id']);

$pdfOutputOption = 'F';
$hideTable = true;
$requestFrom = 'patient_edit_php';


require 'patient_pdf.php';

