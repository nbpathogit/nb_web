<?php

$os = PHP_OS;
if ($os == "WINNT") {
    echo "Window Process<br>";
} else if ($os == "Linux") {
    echo "Linux Process<br>";
} else {
    echo "Dont know OS:" . $os;
    die();
}

echo "a<br>";
//$output = exec('magick  -density 300 a.pdf -density 300 -quality 100 bb.jpg');
$output = null;
$retval = null;
if ($os == "WINNT") {
    $command = 'magick -density 300  a.pdf -density 300 bb.jpg';
}
if ($os == "Linux") {
    $command = '/usr/local/bin/magick -density 300  a.pdf -density 300 bb.jpg';
}
//$command = 'whoami';
if (exec($command, $output, $retval) == 0) {
    echo 'execute command "'. $command . '" successful.<br>';
    echo "Returned with status $retval and output:\n<br>";
    print_r($output);
} else {
    echo 'execute command "'. $command . '" successful.<br>';
    echo "Returned with status $retval and output:\n<br>";
    print_r($output);
}



