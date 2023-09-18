<?php

echo "a<br>";



//$output = exec('magick -units PixelsPerInch -density 300 a.pdf -density 300 -quality 100 bb.jpg');
$output=null;
$retval=null;
$command = 'magick -units PixelsPerInch -density 300 a.pdf -density 300 -quality 100 bb.jpg';
exec($command, $output, $retval);
echo "Returned with status $retval and output:\n";
print_r($output);

echo "b<br>";


