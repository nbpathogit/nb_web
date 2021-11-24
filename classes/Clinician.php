<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Clinician
 *
 * @author 2444536
 */
class Clinician {
    //put your code here
    public $id;
    
    public $clinician;

    public static function getAll($conn){
        $sql = "SELECT *
                FROM clinician_list
                ORDER BY id;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
}
