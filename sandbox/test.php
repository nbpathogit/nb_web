<?php

$startdate = '2023-04-01 02:03:04';
$enddate = '2023-04-03 07:02:14';
$starttimestamp = strtotime($startdate);
$endtimestamp = strtotime($enddate);
$difference = abs($endtimestamp - $starttimestamp) / 3600 / 24;

echo round($difference, 2);
