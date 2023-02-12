<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Billing
 *
 * @author 2444536
 */
class Billing {
    //put your code here
    public static function getAll($conn, $id = 0)
    {
        $sql = "SELECT *
                FROM billing
                ";
        
        if ($id != 0) {
             $sql = $sql . " WHERE ";
            $sql = $sql . " patient_id = " . $id;
        }
         $sql = $sql . " ORDER BY id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
}
