<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportType
 *
 * @author 2444536
 */
class ReportType {
    //put your code here
    public $id;
    public $group_type;
    public $name;
    
    public static function getAll($conn){
        $sql = "SELECT *
                FROM `report_type`
                ORDER BY id;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getAllbyGroup1($conn){
        $sql = "SELECT *".
                " FROM `report_type`".
                " WHERE group_type = 1 ".
                " ORDER BY id;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getAllbyGroup2($conn){
        $sql = "SELECT *".
                " FROM `report_type`".
                " WHERE group_type = 2 ".
                " ORDER BY id;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getAllbyGroup3($conn){
        $sql = "SELECT *".
                " FROM `report_type`".
                " WHERE group_type = 3 ".
                " ORDER BY id;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
}
