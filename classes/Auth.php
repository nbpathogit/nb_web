<?php

/**
 * Authentication
 *
 * Login and logout
 */
class Auth {

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
    
    public static function requireLogin($page_name,$page_id) {
        if (!static::isLoggedIn()) {
            if(isset($page_name)){
                Url::redirect('/login.php?page_name='.$page_name.'&page_id='.$page_id);
            }else{
                Url::redirect('/login.php');
            }
        }
    }

    /**
     * Log in using the session
     *
     * @return void
     */
    public static function login($conn, $username) {
        $user = User::getByUserName($conn, $username);
        $userid = $user->id;
        $ugroup = Ugroup::getByID($conn, $user->ugroup_id);

        session_regenerate_id(true);
        
        $_SESSION['is_logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user'] = $user;
        $_SESSION['userid'] = $userid;
        $_SESSION['usergroup'] = $ugroup;
        $_SESSION['skey'] = self::randomString();

//                var_dump($user);
//                var_dump($ugroup);
//                die();
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

    /**
     * Log out using the session
     *
     * @return void
     */
    public static function logout() {
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
    
    public static function block()
    {
        header("HTTP/1.1 403 Forbidden");
        exit;
    }

    public static function randomString($length = 10)
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $randomString = "";
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    
    
    
    
}
