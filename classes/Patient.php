<?php

/**
*Article
*
*A piece of writing for publication
*/
class Patient{
    
    /**
    * Uniqure identifier
    * @var integer
    */
    public $id;
    
    /**
    * The patient number
    * @var string
    */
    public $number;
    
    public $name;
    
    public $lastname;
    
    public $hospital;
    
    public $pathologist;
    
    public $importdate;
    
    public $reportdate;
    
    public $status;
    
    public $priority;
    

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
                FROM patient
                ORDER BY import_date;";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    
    /**
    *Get the article record based on the ID
    *
    *@param object $conn Connection to the database
    *$param integer $id article ID
    *@param String $columns Optional list of columns foe thr select, defaults to *
    *
    *@return mixed An object of this class, or null if not found
    */
    public static function getByID($conn,$id, $columns = '*')
    {
        $sql = "SELECT $columns
                FROM patient
                WHERE id= :id";
                
        $stmt = $conn->prepare($sql);
        
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Patient');

        if($stmt->execute()){
            return $stmt->fetch();
        }

    }

}