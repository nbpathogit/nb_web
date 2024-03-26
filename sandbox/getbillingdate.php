<?php


spl_autoload_register(function ($class){
    require dirname(__DIR__)."/classes/{$class}.php";
});

date_default_timezone_set('Asia/Bangkok');
$curdate = date('Y-m-d H:i:s', strtotime('+1 month'));
$daycutoff = 20;

//$datea = '2024-01-31 13:58:51';
//$datea = '2024-09-31 13:58:51';
$datea = '2024-12-31 13:58:51';



echo 'datea='.$datea;
echo '<br>';

$year = substr($datea,0,4);
$month = substr($datea,5,2);
$day = substr($datea,8,2);

$yearInt = intval($year);
$monthInt = intval($month);
$dayInt = intval($day);

echo 'year='.$year;
echo '<br>';
echo 'month='.$month;
echo '<br>';
echo 'day='.$day;
echo '<br>';
echo '<br>';

echo '========calculated==========<br>';

$dayInt = $daycutoff;
$monthInt = $monthInt + 1;
if($monthInt == 13){
	$monthInt = 1;
	$yearInt = $yearInt + 1;
}


$year = strval($yearInt);
$month = strval($monthInt);
$month = '0'.$month;
$month = substr($month, -2);
$day = strval($dayInt);

echo 'year='.$year;
echo '<br>';
echo 'month='.$month;
echo '<br>';
echo 'day='.$day;
echo '<br>';


$dateb = $year.'-'.$month.'-'.$day.' 00:00:00';

echo 'dateb='.$dateb;
echo '<br>';

echo '========Test Util==========<br>';

$dateb = Util::getBillCutOffDate($datea);

echo 'dateb='.$dateb;
echo '<br>';

echo '========Test Date Time compare==========<br>';


$acceptDate = new DateTime("2024-12-10 00:00:00");
echo '$acceptDate='.$acceptDate->format('Y-m-d H:i:s').'<br>';
$acceptDate2 = new DateTime("2024-12-10 00:00:00");
echo '$acceptDate2='.$acceptDate->format('Y-m-d H:i:s').'<br>';
$todayDate = new DateTime(Util::get_curreint_thai_date_time());
echo '$todayDate='.$todayDate->format('Y-m-d H:i:s').'<br>';
$cutoffDate = new DateTime(Util::getBillCutOffDate($acceptDate->format('Y-m-d H:i:s')));
echo '$cutoffDate='.$cutoffDate->format('Y-m-d H:i:s').'<br>';

if ($todayDate < $cutoffDate) {
    echo '$todayDate < $cutoffDate'.'<br>';
}
if ($todayDate > $cutoffDate) {
    echo '$todayDate > $cutoffDate'.'<br>';
}
if ($todayDate == $cutoffDate) {
    echo '$todayDate == $cutoffDate'.'<br>';
}

if ($acceptDate == $acceptDate2) {
    echo '$acceptDate == $acceptDate2'.'<br>';
}