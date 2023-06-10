<?php







/**
 *Database
 *
 *
 *A connection to the database
 *
 */
class Database
{

    /**
     *Get the dastabase connection
     *
     *
     *
     *@return object Connection to a MySQL server
     *
     */
    public function getConn()
    {

        // Uncomment for local PC Development
        if ($_SERVER['HTTP_HOST'] == "localhost") {
            
            if (Url::getSubfolder() != "") {
                $db_host = "localhost";
                $db_name = "nbpa_data"."_".Url::getSubfolder();
                $db_user = "nbpa_nbpatho";
                $db_pass = "@6i3#xdyJusmDfBJ";
            } else {
                $db_host = "localhost";
                $db_name = "nbpa_data";
                $db_user = "nbpa_nbpatho";
                $db_pass = "@6i3#xdyJusmDfBJ";
            }
        } 
        
        if ($_SERVER['HTTP_HOST'] == "nb_web.localhost") {
            $db_host = "localhost";
            $db_name = "zp12370_abc";
            $db_user = "root";
            $db_pass = "";
        }

        // Uncomment for IOTKIDDLE database
        if ($_SERVER['HTTP_HOST'] == "nbpatho.iotkiddie.com") {
            $db_host = "localhost";
            $db_name = "u189879599_nb";
            $db_user = "u189879599_nb";
            $db_pass = "tO22dE^4&$";
        }
        
        //
        if ($_SERVER['HTTP_HOST'] == "nbpathology.com"  || $_SERVER['HTTP_HOST'] == "www.nbpathology.com") {

            if (Url::getSubfolder() != "") {
                $db_host = "localhost";
                $db_name = "nbpa_data" . "_" . Url::getSubfolder();
                $db_user = "nbpa_nbpatho";
                $db_pass = "@6i3#xdyJusmDfBJ";
            } else {
                $db_host = "localhost";
                $db_name = "nbpa_data";
                $db_user = "nbpa_nbpatho";
                $db_pass = "@6i3#xdyJusmDfBJ";
            }
        }


        $dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8';

        try {
            $db = new PDO($dsn, $db_user, $db_pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
}
