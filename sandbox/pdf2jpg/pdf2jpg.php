<?php
//https://stackoverflow.com/questions/40100562/how-to-convert-pdf-to-jpg-image-format

var_dump(PHP_OS);echo "<br>";

$os = PHP_OS;
$message = "";
$display = "";
if ($_FILES) {
    var_dump($_FILES);

    $output_dir = "./";
//    ini_set("display_errors", 1);
    if (isset($_FILES["myfile"])) {

        $pdfFileName = $_FILES['myfile']['name'];

//        $inputFileName = preg_replace("/\.[^.\s]{3,4}$/", "", $inputFileName); // Remove special charactor
//        $NewImageName = $inputFileName . '.' . $ImageExt;

        move_uploaded_file($_FILES["myfile"]["tmp_name"], "./" . strtolower($_FILES['myfile']['name']));
        
        //substr(string,start,length)
        $name = substr($pdfFileName,0 ,strrpos($pdfFileName, '.'));
        echo "name=".$name."<br>";
        

//        $location = "./";
//        $name = $output_dir . $NewImageName;
//        $num = count_pages($name);
//        $RandomNum = time();
//        $nameto = $output_dir . $RandomNum . ".jpg";



        echo "a<br>";
//$output = exec('magick  -density 300 a.pdf -density 300 -quality 100 bb.jpg');
        $output = null;
        $retval = null;
        if ($os == "WINNT") {
            $command1 = 'magick -density 300 ' . $pdfFileName . ' -density 300 ' . $name .'.jpg';
            $command2 = '7z a -tzip '.$name.'.zip *.pdf *.jpg';
        }
        if ($os == "Linux") {
            $command1 = '/usr/local/bin/magick -density 300 ' . $pdfFileName . ' -density 300 ' . $name .'.jpg';
            $command2 = '7z a -tzip '.$name.'.zip *.pdf *.jpg';
        }
//$command = 'whoami';
        if (exec($command1, $output, $retval) == 0) {
            echo 'execute command "' . $command1 . '" successful.<br>';
            echo "Returned with status $retval and output:\n<br>";
            print_r($output);
        } else {
            echo 'execute command "' . $command1 . '" successful.<br>';
            echo "Returned with status $retval and output:\n<br>";
            print_r($output);
        }
        
        if (exec($command2, $output, $retval) == 0) {
            echo 'execute command "' . $command2 . '" successful.<br>';
            echo "Returned with status $retval and output:\n<br>";
            print_r($output);
        } else {
            echo 'execute command "' . $command2 . '" successful.<br>';
            echo "Returned with status $retval and output:\n<br>";
            print_r($output);
        }
        


        $num = count_pages($pdfFileName);
        if($num==1){
            $display .= "<img src='$name.jpg'  width='500' style='border: 5px solid #555;' title='Page-$i' /><br>";
            
        }else{
            for ($i = 0; $i < $num; $i++) {
                $display .= "<img src='$name-$i.jpg' width='500' style='border: 5px solid #555;' title='Page-$i' /><br>";
            }

        }
        
        $message = "PDF converted to JPEG sucessfully!!";
    }
}

function count_pages($pdfname) {
    $pdftext = file_get_contents($pdfname);
    $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
    return $num;
}

$content = $message . '<br />' . $display . '<br><form enctype="multipart/form-data" action="" method="post">
 Please choose a file: <input name="myfile" type="file" /><br />
 <input type="submit" value="Upload" />
 </form>';


echo $content;
?>

<br>
<a href="<?=$name?>.zip" download >
     Download Zip File
</a>
