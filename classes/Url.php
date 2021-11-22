<?php
/**
* URL
*
* Response methods
*/
class Url{
    /**
    *Redirect to another URL on the same site
    *
    *@param string $path The path to redirect to
    *
    *@return void
    */
    public static function redirect($path)
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']){
            $protocal = 'https';
        }else{
            $protocal = 'http';
        }
        header("Location: $protocal://" . $_SERVER['HTTP_HOST'] . $path);
        //header("Location: article.php?id=$id");
        exit;
    }
    
    public static function currentURL(){
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']){
            $protocal = 'https';
        }else{
            $protocal = 'http';
        }
        return "$protocal://" . $_SERVER['HTTP_HOST'];
    }
    
    
    
}