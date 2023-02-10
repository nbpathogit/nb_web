<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SnPrefix
 *
 * @author 2444536
 */
class SnPrefix {

    //put your code here
    public $id;
    public $name;
    public $description;
    
      public static function getAll($conn, $id = 0)
    {
        $sql = "SELECT * 
                FROM sn_prefix ";

        if ($id != 0) {
            $sql = $sql . " WHERE id = " . $id;
        }

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

}
