<?php
echo "---------------------------------------------------<br>";
var_dump($_SERVER);

echo "<br>";
echo "---------------------------------------------------<br>";



echo '$_SERVER["SCRIPT_NAME"] = '.$_SERVER['SCRIPT_NAME']; echo "<br>";
$pieces = explode("/", $_SERVER['SCRIPT_NAME']);
echo sizeof($pieces)."<br>";
foreach($pieces as $a){
 echo $a, ", "; 
} 