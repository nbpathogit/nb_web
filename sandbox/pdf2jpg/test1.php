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
    $command1 = 'magick -density 300  a.pdf -density 300 bb.jpg';
    $command2 = '7z';
}
if ($os == "Linux") {
    $command1 = '/usr/local/bin/magick -density 300  a.pdf -density 300 bb.jpg';
    $command2 = '7z';
}
//$command = 'whoami';
if (exec($command1, $output, $retval) == 0) {
    echo 'execute command1 "'. $command1 . '" successful.<br>';
    echo "Returned with status $retval and output:\n<br>";
    print_r($output);
    echo "<br><br>";
} else {
    echo 'execute command1 "'. $command1 . '" fail.<br>';
    echo "Returned with status $retval and output:\n<br>";
    print_r($output);
    echo "<br><br>";
}

if (exec($command2, $output, $retval) == 0) {
    echo 'execute command2 "'. $command2 . '" successful.<br>';
    echo "Returned with status $retval and output:\n<br>";
    print_r($output);
    echo "<br><br>";
} else {
    echo 'execute command2 "'. $command2 . '" fail.<br>';
    echo "Returned with status $retval and output:\n<br>";
    print_r($output);
    echo "<br><br>";
}

