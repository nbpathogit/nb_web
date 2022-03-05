<?php

/**
 * LabFluid
 *
 * A person or entity that can log in to the site
 */
class LabFluid {

    /**
     * Unique identifier
     * @var integer
     */
    public $id;
    public $labname;
    public $lab_des;
    

    public static function getAll($conn) {
        $sql = "SELECT * FROM lab_fluid;";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByID($conn, $id, $columns = '*') {
        $sql = "SELECT $columns
                FROM lab_fluid
                WHERE id= :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'LabFluid');

        if ($stmt->execute()) {
            return $stmt->fetch();
        }
    }
}
    