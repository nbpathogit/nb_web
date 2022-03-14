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
    public $release_time;

    public static function getAll($conn, $patient_id = 0) {
        $sql = "SELECT * 
                FROM presultupdate ";

        if ($patient_id != 0) {
            $sql = $sql . " WHERE patient_id = " . $patient_id;
        }

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
        public static function getAllDesc($conn, $patient_id = 0) {
        $sql = "SELECT * 
                FROM presultupdate ";

        if ($patient_id != 0) {
            $sql = $sql . " WHERE patient_id = " . $patient_id;
        }
        
        $sql = $sql . " ORDER BY id DESC";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($conn) {

        $sql = "INSERT INTO `presultupdate` (`id`, `patient_id`, `result_type`, `result_message`, `pathologist_id` , `release_time`) "
                . "VALUES                   (NULL, :patient_id  ,:result_type  , ''              , ''              , NULL)";

        $stmt = $conn->prepare($sql);

        //var_dump($this->name);

        $stmt->bindValue(':patient_id', $this->patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':result_type', $this->result_type, PDO::PARAM_STR);


        //var_dump($stmt);

        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();

            return true;
        } else {
            return false;
        }
    }

    public static function updateResult($conn, $id, $pathologist_id, $result_message) {

        $sql = "UPDATE presultupdate
                SET result_message = :result_message,
                pathologist_id = :pathologist_id
                    WHERE id = :id";

        $stmt = $conn->prepare($sql);



        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':pathologist_id', $pathologist_id, PDO::PARAM_INT);
        $stmt->bindValue(':result_message', $result_message, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function updateReleaseTime($conn, $id) {

        $sql = "UPDATE presultupdate
                SET release_time = NOW()
                    WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

}
