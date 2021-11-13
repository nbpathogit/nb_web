<?php

/**
* User
*
* A person or entity that can log in to the site
*/
class User{
    
    /**
    * Unique identifier
    * @var integer
    */
    public $id;
    
    /**
    * Unique name
    * @var string
    */
    public $name;
    
    /**
    * Unique name
    * @var string
    */
    public $lastname;
    
    /**
    * Unique username
    * @var string
    */
    public $username;
    
    /**
    * Unique password
    * @var string
    */
    public $password;
    
    
    /**
    * Unique hospital_id
    * @var string
    */
    public $hospital_id;   
    
    /**
    * Unique hospital_id
    * @var string
    */
    public $group_id; 
    
    /**
    * Authenticate a user by username and password
    *
    * @param object $conn Connection to the database
    * @param string $username Username
    * @param string $password Password
    *
    * @return boolean True if the credentials are correct, false otherwise
    */
    public static function authenticate($conn,$username,$password){
        
        $sql = "SELECT *
                FROM user
                WHERE username= :username";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username',$username,PDO::PARAM_STR);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS,'User');
        
        $stmt->execute();
        
        if($user=$stmt->fetch()){
            return password_verify($password,$user->password);
        }
    }
    
    
    public static function getAll($conn){
        $sql = "SELECT *
                FROM user
                ORDER BY id;";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
}