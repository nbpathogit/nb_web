<?php

/**
 * URL
 *
 * Response methods
 */
class Url {

    /**
     * Redirect to another URL on the same site
     *
     * @param string $path The path to redirect to
     *
     * @return void
     */
    // config for sub folder
    //private static $subfolder = "dev3";

    // return     subfolder
    public static function getSubFolder() {
        if (isset($_SESSION['subfolder'])) {
            return $_SESSION['subfolder'];
        } else {
            //get subfolder and keep it in SESSION at the first time log in
            $subfolder = str_replace("/", "", $_SERVER['PHP_SELF']);
            $subfolder = str_replace("\\", "", $subfolder);
            $subfolder = str_replace("Url.php", "", $subfolder);
            $subfolder = str_replace("login.php", "", $subfolder);
            $subfolder = str_replace("logout.php", "", $subfolder);
            //replace file name it self.
            $pieces = explode("/", $_SERVER['PHP_SELF']);
            $subfolder = str_replace($pieces[sizeof($pieces)-1],"",$subfolder);
            return $subfolder;
        }
    }

    // return      /subfolder
    public static function getSubFolder1() {
        if (Url::getSubFolder() != "") {
            return "/" . Url::getSubFolder();
        } else {
            return "";
        }
    }

    //return     subfolder/
    public static function getSubFolder2() {
        if (Url::getSubFolder() != "") {
            return Url::getSubFolder() . "/";
        } else {
            return "";
        }
    }

    public static function redirect($path) {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
            $protocal = 'https';
        } else {
            $protocal = 'http';
        }
        header("Location: $protocal://" . $_SERVER['HTTP_HOST'] . Url::getSubFolder1() . $path);
        //header("Location: article.php?id=$id");
        exit;
    }

    public static function currentURL() {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
            $protocal = 'https';
        } else {
            $protocal = 'http';
        }
        $currentURL = "$protocal://" . $_SERVER['HTTP_HOST'];

        $currentURL = $currentURL . Url::getSubFolder1();


        return $currentURL;
    }

}
