<?php

/**
 * Database
 *
 *
 * A connection to the database
 *
 */
class Database {

    public $db_host = "";
    public $db_name = "";
    public $db_user = "";
    public $db_pass = "";
    
    public function __construct() {

        if ($_SERVER['HTTP_HOST'] == "localhost") {

            if (Url::getSubfolder() != "") {
                $this->db_host = "localhost";
                $this->db_name = "nbpa_data" . "_" . Url::getSubfolder();
                $this->db_user = "nbpa_nbpatho";
                $this->db_pass = "@6i3#xdyJusmDfBJ";
            } else {
                $this->db_host = "localhost";
                $this->db_name = "nbpa_data";
                $this->db_user = "nbpa_nbpatho";
                $this->db_pass = "@6i3#xdyJusmDfBJ";
            }
        }

        if ($_SERVER['HTTP_HOST'] == "nb_web.localhost") {
            $this->db_host = "localhost";
            $this->db_name = "zp12370_abc";
            $this->db_user = "root";
            $this->db_pass = "";
        }

        
        if ($_SERVER['HTTP_HOST'] == "nbpatho.iotkiddie.com") {
            $this->db_host = "localhost";
            $this->db_name = "u189879599_nb";
            $this->db_user = "u189879599_nb";
            $this->db_pass = "tO22dE^4&$";
        }
    }

 

    public function getDBHost() {
        return self::$db_host;
    }

    public function getDBName() {
        return self::$db_name;
    }

    public function getDBUser() {
        return self::$db_user;
    }

    public function getDBPass() {
        return self::$db_pass;
    }

    /**
     * Get the dastabase connection
     *
     *
     *
     * @return object Connection to a MySQL server
     *
     */
    public function getConn() {


        $dsn = 'mysql:host=' . $this->db_host . ';dbname=' . $this->db_name . ';charset=utf8';

        try {
            $db = new PDO($dsn, $this->db_user, $this->db_pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
    
    public function getConnMysqli() {

        try {
            $conn = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
            return $conn;
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

}
