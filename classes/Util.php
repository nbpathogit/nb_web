<?php

/**
 * Description of Util
 *
 * @author 
 */
class Util {
    
    /**
    * Clean up spaces and special characters from a string
    *
    * @param string $input
    * @return string
    */
   public static function cleanString($input) {
       // 1. Trim leading/trailing spaces
       $clean = trim($input);

       // 2. Replace multiple spaces with a single space
       $clean = preg_replace('/\s+/', '_', $clean);

       // 3. Remove special characters (keep letters, numbers, spaces, underscores, hyphens)
       $clean = preg_replace('/[^A-Za-z0-9 _\.-]/', '', $clean);
       
       // 4. Replace spaces with underscores 
       $clean = str_replace(' ', '_', $clean);

       return $clean;
   }
    
    public static function writeFile($fileName,$text){
        if (isset($_SESSION["subfolder"])) {
//            echo '<br>';
//            echo '$_SESSION["subfolder"] ::' . $_SESSION['subfolder'];
//            echo '<br>';
        } else {
//            echo 'Need to login first';
//            die();
            //If not login, Do nothing.
            return 1;
        }
        $file2write = $_SERVER['DOCUMENT_ROOT'] . Url::getSubFolder1() . "/" . $fileName;
        //echo 'write file to :: ' . $file2write . '<br>';
        $myfile = fopen($file2write, "w") or die("Unable to open file!");
        fwrite($myfile, $text);
        fclose($myfile);
    }
    
    public static function writeFileAppend($fileName,$text){
        if (isset($_SESSION["subfolder"])) {
//            echo '<br>';
//            echo '$_SESSION["subfolder"] ::' . $_SESSION['subfolder'];
//            echo '<br>';
        } else {
//            echo 'Need to login first';
//            die();
            //If not login, Do nothing.
            return 1;
        }
        $file2write = $_SERVER['DOCUMENT_ROOT'] . Url::getSubFolder1() . "/" . $fileName;
        //echo 'write file to :: ' . $file2write . '<br>';
        $myfile = fopen($file2write, "a") or die("Unable to open file!");
        fwrite($myfile, $text);
        fclose($myfile);
    }
    
    public static function writeFileSpecificPath($fileName,$text){

        $myfile = fopen($fileName, "w") or die("Unable to open file!");
        fwrite($myfile, $text);
        fclose($myfile);
    }

    public static function Convert($amount_number) {
        //str_replace(find,replace,string,count)
        $amount_number = str_replace(",", "", $amount_number);
        $amount_number = number_format($amount_number, 2, ".", "");
        $pt = strpos($amount_number, ".");
        $number = $fraction = "";
        if ($pt === false)
            $number = $amount_number;
        else {
            $number = substr($amount_number, 0, $pt);
            $fraction = substr($amount_number, $pt + 1);
        }

        $ret = "";
        $baht = Util::ReadNumber($number);
        if ($baht != "")
            $ret .= $baht . "บาท";

        $satang = Util::ReadNumber($fraction);
        if ($satang != "")
            $ret .= $satang . "สตางค์";
        else
            $ret .= "ถ้วน";
        return $ret;
    }

    public static function ReadNumber($number) {
        $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
        $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
        $number = $number + 0;
        $ret = "";
        if ($number == 0)
            return $ret;
        if ($number > 1000000) {
            $ret .= Util::ReadNumber(intval($number / 1000000)) . "ล้าน";
            $number = intval(fmod($number, 1000000));
        }

        $divider = 100000;
        $pos = 0;
        while ($number > 0) {
            $d = intval($number / $divider);
            $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" :
                    ((($divider == 10) && ($d == 1)) ? "" :
                    ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
            $ret .= ($d ? $position_call[$pos] : "");
            $number = $number % $divider;
            $divider = $divider / 10;
            $pos++;
        }
        return $ret;
    }

//## วิธีใช้งาน
//$num1 = '3500.01'; 
//$num2 = '120000.50'; 
//echo  $num1  . "&nbsp;=&nbsp;" .Convert($num1),"<br>"; 
//echo  $num2  . "&nbsp;=&nbsp;" .Convert($num2),"<br>"; 




    public static function isMobile() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    // Function to prepend a string 
    // 
    //input: int(5) , string(1) "1"
    //Output: string(5) "00001"
    //
    //input: int(5) , string(2) "11"
    //Output: string(5) "00011"
    //
    //input: int(5) , string(3) "111"
    //Output: string(5) "00111"
    // 
    //input: int(5) , string(4) "1111"
    //Output: string(5) "01111"  
    //
    //input: int(5) , string(5) "11111"
    //Output: string(5) "11111"
    //
    //input: int(5) , string(6) "111111"
    //Output: string(6) "111111"
    //
    ////////////////////////////
    public static function prepend_string_with_zero($numdigit, $runstr) {
        $res = "null";
        // Using concatenation operator (.)
        if (strlen($runstr) >= $numdigit) {
            $res = $runstr;
        } elseif ($numdigit - strlen($runstr) == 1) {
            $res = "0" . $runstr;
        } elseif ($numdigit - strlen($runstr) == 2) {
            $res = "00" . $runstr;
        } elseif ($numdigit - strlen($runstr) == 3) {
            $res = "000" . $runstr;
        } elseif ($numdigit - strlen($runstr) == 4) {
            $res = "0000" . $runstr;
        } elseif ($numdigit - strlen($runstr) == 5) {
            $res = "00000" . $runstr;
        } elseif ($numdigit - strlen($runstr) == 6) {
            $res = "000000" . $runstr;
        } elseif ($numdigit - strlen($runstr) == 7) {
            $res = "0000000" . $runstr;
        } elseif ($numdigit - strlen($runstr) == 8) {
            $res = "00000000" . $runstr;
        } elseif ($numdigit - strlen($runstr) == 9) {
            $res = "00000000" . $runstr;
        }


        // Returning the result 
        return $res;
    }

    public static function get_curreint_year() {

        date_default_timezone_set('Asia/Bangkok');
        return date('y');
    }

    public static function alert($message) {
        echo '<script type="text/javascript">';
        echo ' alert("' . $message . '")';  //not showing an alert box.
        echo '</script>';
    }

    public static function get_curreint_thai_date_time() {

        if(isset($_SESSION['cur_date'])){
            //Return simulate time
            return $_SESSION['cur_date'] . ' 12:00:00';
        }else{
            date_default_timezone_set('Asia/Bangkok');
            return date('Y-m-d H:i:s');
        }
    }
    
    public static function get_curreint_thai_date() {

        if(isset($_SESSION['cur_date'])){
            //Return simulate time
            return $_SESSION['cur_date'];
        }else{
            date_default_timezone_set('Asia/Bangkok');
            return date('Y-m-d');
        }
    }

    public static function get_diff_time_in_days($enddate, $startdate) {

        $starttimestamp = strtotime($startdate);
        $endtimestamp = strtotime($enddate);
        $difference = abs($endtimestamp - $starttimestamp) / 3600 / 24;

        return round($difference, 2);
    }
    
    public static function space2nbsp(string $strin){
        $strout = str_replace("                    ", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $strin); //20
        $strout = str_replace("                   ", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $strin); //19
        $strout = str_replace("                  ", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $strin); //18
        $strout = str_replace("                 ", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $strin); //17
        $strout = str_replace("                ", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $strin); //16
        $strout = str_replace("               ", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $strin); //15
        $strout = str_replace("              ", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $strin); //14
        $strout = str_replace("             ", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $strin); //13
        $strout = str_replace("            ", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $strin); //12
        $strout = str_replace("           ", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $strin); //11
        $strout = str_replace("          ", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $strin); //10
        $strout = str_replace("         ", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $strout); //9
        $strout = str_replace("        ", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $strout); //8
        $strout = str_replace("       ", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $strout); //7
        $strout = str_replace("      ", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $strout); //6
        $strout = str_replace("     ", " &nbsp;&nbsp;&nbsp;&nbsp;", $strout); //5
        $strout = str_replace("    ", " &nbsp;&nbsp;&nbsp;", $strout); //4
        $strout = str_replace("   ", " &nbsp;&nbsp;", $strout); //3
        $strout = str_replace("  ", " &nbsp;", $strout); //2
        
        return $strout;
    }
    
    
    
    public static function getBillCutOffDate(string $acceptDate){
        
        //  Degug on  ==> sandbox\getbillingdate.php
        date_default_timezone_set('Asia/Bangkok');
        $daycutoff = 20;

        //$datea = '2024-01-31 13:58:51';
        //$datea = '2024-09-31 13:58:51';
        //$datea = '2024-12-31 13:58:51';
        $datea=$acceptDate;

        $year = substr($datea,0,4);
        $month = substr($datea,5,2);
        $day = substr($datea,8,2);

        $yearInt = intval($year);
        $monthInt = intval($month);
        $dayInt = intval($day);

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

        $dateb = $year.'-'.$month.'-'.$day.' 00:00:00';

        return $dateb;
    }
    

}
