<?php

require 'includes/init.php';

$conn = require 'includes/db.php';

require 'user_auth.php';

$targetDir = "pdf_attach_uploads/";

$patient_id = $_POST['patient_id'] ?? null;
Util::writeFile("dbg.txt", $_POST['patient_id'] );

// Create folder if not exists
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$targetFile = $targetDir . basename($_FILES["file"]["name"]);
$fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check if file is a PDF by extension
if ($fileType !== "pdf") {
    echo "Only PDF files are allowed.";
    exit;
}

// Extra check: validate MIME type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $_FILES["file"]["tmp_name"]);
finfo_close($finfo);

if ($mimeType !== "application/pdf") {
    echo "Invalid file type. Only PDF files are allowed.";
    exit;
}



// Move uploaded file to new folder
$curdatetime=Util::get_curreint_thai_date_time();
$fileName = htmlspecialchars(basename($_FILES["file"]["name"]));
//Create target file again with htmlspecialchars
$targetFile = $targetDir .  Util::cleanString($curdatetime . '_' .htmlspecialchars(basename($_FILES["file"]["name"])));

if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
    echo "The file ". $fileName . " has been uploaded successfully.";
} else {
    echo "Sorry, there was an error uploading your file.";
}

//Update file location to database pdf_attach
$pdfath = Pdfattach::getInitObj();
$pdfath->filelocation = $targetFile;
$pdfath->patient_id = $patient_id;
$pdfath->fileName = htmlspecialchars(basename($_FILES["file"]["name"]));

if($pdfath->create($conn)){
    echo '';
}else{
    echo 'error';
}

?>
