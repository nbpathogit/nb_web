<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Priority
 *
 * @author 2444536
 */
class Priority {
    //put your code here
    
    public $id;
    public $priority;
    
    
    public static function getAll($conn,$id=0) {
        $sql = "SELECT *
                FROM priority";

        if($id!=0){
           $sql = $sql . " WHERE id = " . $id;
        }

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getInit() {
        
        return [["id"=>5,"priority"=>"ปรกติ"]];

    }
    
    
    
}
