<?php
$message = "";
$display = "";
if($_FILES)
{
    var_dump($_FILES);
    
    $output_dir = "./";
    ini_set("display_errors",1);
    if(isset($_FILES["myfile"]))
    {
        $RandomNum   = time();

        $ImageName      = str_replace(' ','-',strtolower($_FILES['myfile']['name']));
        $ImageType      = $_FILES['myfile']['type']; //"image/png", image/jpeg etc.

        $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
        $ImageExt       = str_replace('.','',$ImageExt);
        if($ImageExt != "pdf")
        {
            $message = "Invalid file format only <b>\"PDF\"</b> allowed.";
        }
        else
        {
            $ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
            $NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;

            move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir. $NewImageName);

            $location   = "./";
            $name       = $output_dir. $NewImageName;
            $num = count_pages($name);
            $RandomNum   = time();
            $nameto     = $output_dir.$RandomNum.".jpg";
            $convert    = $location . " " . $name . " ".$nameto;
            echo $convert;
            $output = shell_exec('echo "Hello World"');
            echo $output;
         
            for($i = 0; $i<$num;$i++)
            {
                $display .= "<img src='$output_dir$RandomNum-$i.jpg' title='Page-$i' /><br>"; 
            }
            $message = "PDF converted to JPEG sucessfully!!";
        }
    }
}
function count_pages($pdfname) {
      $pdftext = file_get_contents($pdfname);
      $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
      return $num;
    }
$content = $message.'<br />'.$display.'<br><form enctype="multipart/form-data" action="" method="post">
 Please choose a file: <input name="myfile" type="file" /><br />
 <input type="submit" value="Upload" />
 </form>';


echo $content;
?>
