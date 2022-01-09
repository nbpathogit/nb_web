<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Presultupdate
 *
 * @author 2444536
 */
class Presultupdate {

    //put your code here



    public $id;
    public $patient_id;
    public $result_type;
    public $result_message;
    public $pathologist_id;
    public $result2_message;
    public $pathologist2_id;
    public $release_time;

    public static function getAll($conn, $id = 0) {
        $sql = "SELECT * 
                FROM presultupdate ";

        if ($id != 0) {
            $sql = $sql . " WHERE id = " . $id;
        }

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

}
