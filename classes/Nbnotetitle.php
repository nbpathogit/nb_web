<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Clinician
 *
 * @author 
 */
class Nbnotetitle {
    //put your code here
    public $id;
    public $title;
    public $createdate;

    public static function getAll($conn){
        $sql = "SELECT `id` ,`title`, `createdate` 
            FROM `nbnotetitle`
                ORDER BY id;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
}
