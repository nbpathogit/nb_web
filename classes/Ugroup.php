<?php

/**
 * Ugroup
 *
 * A person or entity that can log in to the site
 */
class Ugroup {

    /**
     * Unique identifier
     * @var integer
     */
    public $id;

    /**
     * Unique name
     * @var string
     */
    public $ugroup;
    
    public $ugroupe;
    
    public $ppatient;
    
    public $pnbcenter;
    
    public $presult;

    public static function getAll($conn) {
        $sql = "SELECT * FROM user_groups;";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    //get only customer group id 5000 ro 5100
    public static function getCust($conn) {
        $sql = "SELECT * FROM user_groups WHERE id = 0 or id=5000 or id=5100;";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getByID($conn, $id, $columns = '*') {
        $sql = "SELECT $columns
                FROM user_groups
                WHERE id= :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Ugroup');

        if ($stmt->execute()) {
            return $stmt->fetch();
        }
    }
    
}
