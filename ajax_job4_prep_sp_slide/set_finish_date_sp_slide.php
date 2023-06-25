<?php

require '../includes/init.php';
$conn = require '../includes/db.php';


$finishdate = Util::get_curreint_thai_date_time();
$result = 0;
$result = $result + Job::setFinishDate($conn, $_POST['rid'], $finishdate);
$result = $result + Billing::setFinishDate($conn,  $_POST['rid'], $finishdate);
$result = $result + ReqSpSlideID::setFinishDate($conn,  $_POST['rid'], $finishdate);

echo $finishdate;




