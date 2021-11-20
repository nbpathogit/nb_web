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

    public static function getAll($conn) {
        $sql = "SELECT * FROM user_group;";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

}
