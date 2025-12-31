<?php
$targetDir = "changehistoryuploads/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$fileName = basename($_FILES["file"]["name"]);
$targetFile = $targetDir . time() . "_" . $fileName;

if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
    // Return the file path to Summernote
    echo $targetFile;
} else {
    http_response_code(400);
    echo "Upload failed";
}
?>
