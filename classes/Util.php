<?php

/**
 * Description of Util
 *
 * @author 
 */
class Util {

    public static function Convert($amount_number) {
        //str_replace(find,replace,string,count)
        $amount_number = str_replace(",","",$amount_number);
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
            $ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
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

        date_default_timezone_set('Asia/Bangkok');
        return date('Y-m-d H:i:s');
    }

}
