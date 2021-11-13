<?php

/**
*Patient
*
*A piece of writing for publication
*/
class Hospital{
    
    /**
    * Uniqure identifier
    * @var integer
    */
    public $id;
    
    public $hospital;
    
    public $name;
    
    public $address;
    
    public $detail;

    

    /**
    * Validation errors
    * @var array
    */
    public $errors = [];
    
    
    /**
    *Get all the articles
    *
    *@param object $conn Connection to the database
    *
    *@return array An associative array of all the article records
    */
    public static function getAll($conn){
        $sql = "SELECT *
                FROM hospital
                ORDER BY id;";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

}