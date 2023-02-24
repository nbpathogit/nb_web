<?php


echo 'date_default_timezone_get(); ==> '.date_default_timezone_get();
echo '<br>';
echo 'date("m/d/Y h:i:s a", time()); ==> '.date('m/d/Y h:i:s a', time());
echo '<br>';
echo '===================';
echo '<br>';
echo 'date_default_timezone_set("Europe/Berlin"); ==> '.date_default_timezone_set('Europe/Berlin');
echo '<br>';
echo 'date("m/d/Y h:i:s a", time()); ==> '.date('m/d/Y h:i:s a', time());
echo '<br>';
echo '===================';
echo '<br>';
echo 'date_default_timezone_set("Asia/Bangkok"); ==> '.date_default_timezone_set('Asia/Bangkok');
echo '<br>';
echo 'date_default_timezone_get(); ==> '.date_default_timezone_get();
echo '<br>';
echo 'date("m/d/Y h:i:s a", time()); ==> '.date('m/d/Y h:i:s a', time());
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
        date_default_timezone_set('Asia/Bangkok');
echo         date('m/d/Y h:i:s a', time()).' ('.date_default_timezone_get().')';



