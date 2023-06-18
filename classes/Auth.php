<?php

/**
 * Authentication
 *
 * Login and logout
 */
class Auth {

    // time limit setting at server side
    // public static $sesstion_timeout_int_sec = 15; //60second = 1 min.; <-- Write to DOM
    public static $sesstion_timelimit_int_sec = 1200; //1200second = 20 min.; <-- Write to DOM
    public $curSrvTimeIntSecs;  // <-- Write to DOM
    public $curSrvTime;  // <-- Write to DOM

    /**
     * return the user authentication status
     *
     * @return boolean True if a user is logged in, false otherwies
     */

    public static function isLoggedIn() {
        return isset($_SESSION['is_logged_in']) &&
                $_SESSION['is_logged_in'] &&
                isset($_SESSION['username']) &&
                isset($_SESSION['user']) &&
                isset($_SESSION['usergroup']);
    }

    /**
     * return the user to be logged in, stopping with an unauthorised message if not
     *
     * @return void
     */
    public static function requireLogin($page_name = "na", $page_id = 0) {


        if (!static::isLoggedIn()) {
            if ($page_name != "na") {
                //If have tar get page. Will send target page name and page id too
                Url::redirect('/login.php?page_name=' . $page_name . '&page_id=' . $page_id);
            } else {
                Url::redirect('/login.php');
            }
        }

        $curTime = Time();
        $_SESSION['setSrvTimeLimitIntSecs'] = Auth::$sesstion_timelimit_int_sec;

        if ($curTime >= $_SESSION['targetSrvTimeOutIntSecs']) {
            Auth::logout();
            Url::redirect('/login.php');
        } else {
            $_SESSION['curSrvTimeIntSecs'] = $curTime;
            $curSrvTimeIntSecs = $_SESSION['curSrvTimeIntSecs'];
            $_SESSION['targetSrvTimeOutIntSecs'] = $curTime + $_SESSION['setSrvTimeLimitIntSecs'];
        }



        session_regenerate_id(false);
    }

    /**
     * Log in using the session
     *
     * @return void
     */
    public static function login($conn, $username, $subfolder) {
        $user = User::getByUserName($conn, $username);
        $userid = $user->id;
        $ugroup = Ugroup::getByID($conn, $user->ugroup_id);

        session_regenerate_id(false);


        $_SESSION['username'] = $username;
        $_SESSION['user'] = $user;
        $_SESSION['userid'] = $userid;
        $_SESSION['usergroup'] = $ugroup;
        $_SESSION['subfolder'] = $subfolder;
        $_SESSION['skey'] = self::randomString();


        date_default_timezone_set('Asia/Bangkok');
        $_SESSION['setSrvTimeLimitIntSecs'] = Auth::$sesstion_timelimit_int_sec; // time limit setting at server side 20
        $curTime = Time();
        $_SESSION['curSrvTimeIntSecs'] = $curTime;
        $curSrvTimeIntSecs = $_SESSION['curSrvTimeIntSecs'];
        $_SESSION['targetSrvTimeOutIntSecs'] = $curTime + $_SESSION['setSrvTimeLimitIntSecs']; //current time + 20 min
        $_SESSION['targetSrvTimeRemainIntSecs'] = $_SESSION['targetSrvTimeOutIntSecs']; //20 min


        $curSrvTime = date_default_timezone_get() . date('m/d/Y h:i:s a', time());


        $_SESSION['is_logged_in'] = true;





//        Log::setuser($conn,  $_SESSION['username'], $user->name .' '. $user->lastname);
        $_SESSION['log_username'] = $_SESSION['username'];
        $_SESSION['log_name'] = $user->name . ' ' . $user->lastname;
        $thai_date = Util::get_curreint_thai_date_time();
        Log::add($conn, $_SESSION['log_username'], $_SESSION['log_name'], "login", "login", $thai_date);
//                var_dump($user);
//                var_dump($ugroup);
//                die();
    }

    /**
     * Log in using the session
     *
     * @return void
     */
    public static function adminSimulatelogin($conn, $userid) {
        $user = User::getByID($conn, $userid);
        $userid = $user->id;
        $ugroup = Ugroup::getByID($conn, $user->ugroup_id);

        //session_regenerate_id(false);

        $_SESSION['adminusername'] = $_SESSION['username'];

        $_SESSION['username'] = $user->username;
        $_SESSION['user'] = $user;
        $_SESSION['userid'] = $userid;
        $_SESSION['usergroup'] = $ugroup;
    }

    public static function getSrvTimeRemain() {
        date_default_timezone_set('Asia/Bangkok');
        $curTime = Time();
        $_SESSION['targetSrvTimeRemainIntSecs'] = $_SESSION['targetSrvTimeOutIntSecs'] - $curTime;
        return $_SESSION['targetSrvTimeRemainIntSecs'];
//        return Auth::$sesstion_timelimit_int_sec;
    }

    /**
     * Log out using the session
     *
     * @return void
     */
    public static function logout() {
        $conn = require 'includes/db.php';

        $thai_date = Util::get_curreint_thai_date_time();
        Log::add($conn, $_SESSION['log_username'], $_SESSION['log_name'], "logout", "logout", $thai_date);

        $_SESSION = [];
        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
    }

    public static function getUser() {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        } else {
            return null;
        }
    }

    public static function getUserId() {
        if (isset($_SESSION['userid'])) {
            return $_SESSION['userid'];
        } else {
            return null;
        }
    }

    public static function getUsername() {
        if (isset($_SESSION['username'])) {
            return $_SESSION['username'];
        } else {
            return null;
        }
    }

    public static function getUserGroup() {
        if (isset($_SESSION['usergroup'])) {
            return $_SESSION['usergroup'];
        } else {
            return null;
        }
    }

    public static function canViewPatientResult() {

        $str = $_SESSION['usergroup']->presult;

        if ($str[0] == '1') {
            return true;
        } else {
            return false;
        }
    }

    public static function isDisableEditPatientResult() {

        $str = $_SESSION['usergroup']->presult;

        if ($str[1] == '0') {
            return true;
        } else {
            return false;
        }
    }

    public static function canViewNBCenter() {

        $str = $_SESSION['usergroup']->pnbcenter;

        if ($str[0] == '1') {
            return true;
        } else {
            return false;
        }
    }

    public static function isDisableEditNBCenter() {

        $str = $_SESSION['usergroup']->pnbcenter;

        if ($str[1] == '0') {

            return true;
        } else {
            return false;
        }
    }

    public static function canViewPatientInfo() {
        $str = $_SESSION['usergroup']->ppatient;

        if ($str[0] == '1') {
            return true;
        } else {
            return false;
        }
    }

    public static function isDisableEditPatientInfo() {

        $str = $_SESSION['usergroup']->ppatient;

        if ($str[1] == '0') {
            return true;
        } else {
            return false;
        }
    }

    public static function block($str = "HTTP/1.1 403 Forbidden") {
        header($str);
        exit;
    }

    public static function randomString($length = 10) {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $randomString = "";
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
