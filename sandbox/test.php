<?php
require '../includes/init.php';

$startdate = '2023-04-01 02:03:04';
$enddate = '2023-04-03 07:02:14';
$starttimestamp = strtotime($startdate);
$endtimestamp = strtotime($enddate);
$difference = abs($endtimestamp - $starttimestamp) / 3600 / 24;

echo round($difference, 2);

echo "<br>";
echo "---------------------------------------------------<br>";
echo '$_SERVER[HTTP_HOST] ="'.$_SERVER['HTTP_HOST'].'"';
echo "<br>";

echo "---------------------------------------------------<br>";
   
echo "<br>";
echo "Url::subfolder = ".Url::getSubfolder();
echo "<br>";
echo "Url::getSubFolder1() = ".Url::getSubFolder1();

echo "<br>";

echo "Url::getSubFolder2() = ".Url::getSubFolder2();

echo "---------------------------------------------------<br>";
var_dump($_SERVER);

?>

<script> 
     
function trimslash(str) {
    let start = 0, 
        end = str.length;
    while(start < end && str[start].match(/\//)){
        //alert('found match at start');
        ++start;
    }
    while(end > start && str[end - 1] .match(/\//)){
        //alert('found match at start');
        --end;
    }
    return (start > 0 || end < str.length) ? str.substring(start, end) : str;
}

console.log(trimslash('Uterus ไม่มีปีกมดลูก /'));

    
</script>