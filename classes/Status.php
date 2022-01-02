<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WorkFlow
 *
 * @author 2444536
 */
class Status {
    //put your code here
    public $id;

    public static function getAll($conn, $id = 0){
        $sql = "SELECT *
                FROM status";
        
        if ($id != 0) {
            $sql = $sql . " WHERE id = " . $id;
        }
        
        $sql = $sql . " ORDER BY id;";
        
        //echo $sql;
        //die();

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
}



