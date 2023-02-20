<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OutsideContract
 *
 * @author 2444536
 */
class OutsideContract {

    public $id; //      Primary	int(11)			
    public $name; //     varchar(50)	
    public $cost; //    float			
    public $comment; //	    varchar(70)	   

    public static function getAll($conn, $id = 0) {
        
        $sql = "SELECT * FROM `outside_contract`";

        $sql = $sql . " WHERE 1=1 ";

        if ($id != 0) {
            $sql = $sql . " and id = " . $id;
        }

        $sql = $sql . " ORDER BY id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

}
