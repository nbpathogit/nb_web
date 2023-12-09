<?php
require './../includes/init.php';

if(isset($_SESSION["subfolder"])){
    echo '<br>';
    echo '$_SESSION["subfolder"] ::'.$_SESSION['subfolder'];
    echo '<br>';
}else{
    echo 'Need to login first';
    die();
}

echo '$_SERVER["PHP_SELF"] ::'.$_SERVER['PHP_SELF'];
echo '<br>';
echo 'Url::getSubFolder1() ::'.Url::getSubFolder1();
echo '<br>';



$sql_dbg = "TEST\nTEST";
$file2write=$_SERVER['DOCUMENT_ROOT'].Url::getSubFolder1()."/writefiletest.txt";
echo 'write file to :: ' . $file2write . '<br>';
$myfile = fopen($file2write, "w") or die("Unable to open file!");
fwrite($myfile, $sql_dbg);
fclose($myfile);