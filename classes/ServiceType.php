<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ServiceType {

    //put your code here
    public $id;
    public $order_list;
    public $service_type;
    public $Name_by_spcimen;
    
      public static function getAll($conn, $id = 0)
    {
        $sql = "SELECT * FROM `service_type` ";

        if ($id != 0) {
            $sql = $sql . " WHERE id = " . $id;
        }

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

}
