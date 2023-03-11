<?php

/**
 * Description of Util
 *
 * @author 
 */
class Util {

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
        echo ' alert("'.$message.'")';  //not showing an alert box.
        echo '</script>';
    }

}
