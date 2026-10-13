<?php

require 'includes/init.php';

$conn = require 'includes/db.php';

require 'user_auth.php';

$targetRoot = 'pdf_attach_uploads/';

$patientId = $_POST['patient_id'] ?? null;
//Util::writeFile('dbg.txt', $_POST['patient_id']);
$pnum = $_POST['pnum'] ?? null;
//Util::writeFile('dbg2.txt', $_POST['pnum']);

$originalFileName = preg_replace('/[^A-Za-z0-9_.-]+/', '-', basename($_FILES["file"]["name"]));
$originalFileName = preg_replace('/-+/', '-', $originalFileName);
$fileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

// Validate the file extension.
if ($fileType !== 'pdf') {
    echo 'Only PDF files are allowed.';
    exit;
}

// Detect the MIME type; it is not currently used for validation.
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $_FILES["file"]["tmp_name"]);
finfo_close($finfo);

$curdatetime = Util::get_curreint_thai_date_time();
$timestampPrefix = Util::cleanString($curdatetime);
$subfolder = $targetRoot . $pnum . "_" . substr($timestampPrefix, 0, 16) . "/";
$targetDir = $subfolder;

if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true)) {
    echo 'Unable to create the attachment folder.';
    exit;
}

$fileName = htmlspecialchars($originalFileName);
$targetFile = $targetDir . Util::cleanString($timestampPrefix . '_' . htmlspecialchars($originalFileName));

if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
    echo 'Sorry, there was an error uploading your file.';
    exit;
}

echo 'The file ' . $fileName . ' has been uploaded successfully.';

$os = PHP_OS;
if ($os === 'WINNT') {
    $magickCommand = 'magick';
} elseif ($os === 'Linux' && is_executable('/usr/bin/magick')) {
    $magickCommand = '/usr/bin/magick';
} elseif ($os === 'Linux' && is_executable('/usr/local/bin/magick')) {
    $magickCommand = '/usr/local/bin/magick';
} else {
    $magickCommand = null;
}

if ($magickCommand !== null) {
    $pdfPath = __DIR__ . DIRECTORY_SEPARATOR . $targetFile;
    $jpgPath = __DIR__ . DIRECTORY_SEPARATOR . $targetDir . pathinfo($targetFile, PATHINFO_FILENAME) . '.jpg';
    $command = $magickCommand . ' -density 300 '
        . escapeshellarg($pdfPath)
        . ' ' . escapeshellarg($jpgPath)
        . ' 2>&1';
    echo "Executing command: $command\n"; // Debugging line

    $output = [];
    exec($command, $output, $returnCode);
    if ($returnCode !== 0 || !is_file($jpgPath)) {
        error_log('PDF-to-JPG conversion failed: ' . implode("\n", $output));
        echo ' JPG conversion failed; check the PHP error log for details.';
    }
} else {
    echo ' JPG conversion is not configured for this operating system.';
}

// Save the attachment details to pdf_attach.
$pdfAttach = Pdfattach::getInitObj();
$pdfAttach->filelocation = $targetFile;
$pdfAttach->subfolder = $subfolder;
$pdfAttach->patient_id = $patientId;
$pdfAttach->fileName = htmlspecialchars($timestampPrefix . '_' . $originalFileName);

if ($pdfAttach->create($conn)) {
    echo '';
} else {
    echo 'error';
}
