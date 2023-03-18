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
    public $group_type;     // 1 for mandatory require 2 for added later
    public $patient_id;
    public $result_type;    // EX Provisional Diagnosis, Pathological Diagnosis
    public $result_type_id; //  ID Link to DB   table "report_type"
    public $result_message;
    public $pathologist_id; 
    public $pathologist2_id;
    public $release_time;
    public $release_type;
    public $second_patho_review;    
    public $create_date;


    public static function getInitObj() {
        $resultupdate = new Presultupdate();

        $resultupdate->id = NULL; //       
        $resultupdate->group_type = 0;     // 1 for mandatory require 2 for added later        
        $resultupdate->patient_id = 0; //        		
        $resultupdate->result_type = ""; //          	
        $resultupdate->result_type_id = 0; //        		
        $resultupdate->result_message = ""; //         		
        $resultupdate->pathologist_id = 0; //         		
        $resultupdate->pathologist2_id = 0; //        	
        $resultupdate->release_time = NULL; //       
        $resultupdate->release_type = ""; //       

        return $resultupdate;
    }
    
    
    public static function getAll($conn, $patient_id = 0) {
        $sql = "SELECT * ".
                " FROM presultupdate ";

        if ($patient_id != 0) {
            $sql = $sql . " WHERE patient_id = " . $patient_id;
        }

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getAllofGroup1($conn, $patient_id = 0) {
        $sql = "SELECT * ".
                " FROM presultupdate ";

        if ($patient_id != 0) {
            $sql = $sql . " WHERE patient_id = " . $patient_id;
        }
        //group_type
        $sql = $sql . " AND group_type = 1 ";
        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getAllofGroup1Asc($conn, $patient_id = 0) {
        $sql = "SELECT * ".
                " FROM presultupdate ";

        if ($patient_id != 0) {
            $sql = $sql . " WHERE patient_id = " . $patient_id;
        }
        //group_type
        $sql = $sql . " AND group_type = 1 ";
        $sql = $sql . " ORDER BY id ASC";
        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getAllofGroup1Desc($conn, $patient_id = 0) {
        $sql = "SELECT * ".
                " FROM presultupdate ";

        if ($patient_id != 0) {
            $sql = $sql . " WHERE patient_id = " . $patient_id;
        }
        //group_type
        $sql = $sql . " AND group_type = 1 ";
        $sql = $sql . " ORDER BY id DESC";
        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getAllofGroup2Desc($conn, $patient_id = 0) {
        $sql = "SELECT * ".
                " FROM presultupdate ";

        if ($patient_id != 0) {
            $sql = $sql . " WHERE patient_id = " . $patient_id;
        }
        //group_type
        $sql = $sql . " AND group_type = 2 ";
        $sql = $sql . " ORDER BY id DESC";
        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
        
    public static function getAllofGroup2($conn, $patient_id = 0) {
        $sql = "SELECT * ".
                " FROM presultupdate ";

        if ($patient_id != 0) {
            $sql = $sql . " WHERE patient_id = " . $patient_id;
        }
        //group_type
        $sql = $sql . " AND group_type = 2 ";
        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public static function getByID($conn, $id = 0) {
        $sql = "SELECT * ".
                " FROM presultupdate ";

        if ($id != 0) {
            $sql = $sql . " WHERE id = " . $id;
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

        $sql = "INSERT INTO `presultupdate` (`id`,   `group_type`   ,   `patient_id`   , `result_type`, `result_type_id`  , `result_message`, `pathologist_id`, `pathologist2_id` , `release_time`, `release_type`) "
                . "VALUES                   (NULL,   :group_type    ,   :patient_id    , :result_type , :result_type_id   , ''              , 0               , 0                 ,  NULL         , :release_type)";

        $stmt = $conn->prepare($sql);

        //var_dump($this->name);

        $stmt->bindValue(':group_type', $this->group_type, PDO::PARAM_INT);
        $stmt->bindValue(':patient_id', $this->patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':result_type', $this->result_type, PDO::PARAM_STR);
        $stmt->bindValue(':result_type_id', $this->result_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':release_type', $this->release_type, PDO::PARAM_STR);


        //var_dump($stmt);

        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();

            return true;
        } else {
            return false;
        }
    }

    public static function updateResult($conn, $id, $pathologist_id, $pathologist2_id, $result_message) {

        $sql = "UPDATE presultupdate".
                " SET result_message = :result_message,".
                " pathologist_id = :pathologist_id,".
                " pathologist2_id = :pathologist2_id".
                " WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':pathologist_id', $pathologist_id, PDO::PARAM_INT);
        $stmt->bindValue(':pathologist2_id', $pathologist2_id, PDO::PARAM_INT);
        $stmt->bindValue(':result_message', $result_message, PDO::PARAM_STR);

        return $stmt->execute();
    }
    
    
    public static function updateSecondPatho($conn, $id, $pathologist2_id) {

        $sql = "UPDATE presultupdate".
                " SET pathologist2_id = :pathologist2_id".
                " WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':pathologist2_id', $pathologist2_id, PDO::PARAM_INT);

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
    
    public static function setPatho2ID($conn, $id, $pathologist2_id)
    {
        $sql = "UPDATE presultupdate".
                " SET pathologist2_id = :pathologist2_id".
                " WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':pathologist2_id', $pathologist2_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
    
    public static function setTxtResult($conn, $id, $result_message)
    {
        $sql = "UPDATE presultupdate".
                " SET result_message = :result_message".
                " WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':result_message', $result_message, PDO::PARAM_STR);

        return $stmt->execute();
    }
    
    public static function getTxtResult($conn, $id)
    {
        $sql = "SELECT result_message ".
                " FROM presultupdate".
                " WHERE id = " . $id;

                $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public static function setReleaseTimeIfNull($conn, $patient_id, $release_time)
    {
        
        
        
        
        
        $sql = "UPDATE presultupdate " .
                " SET release_time = :release_time ".
                " WHERE patient_id = :patient_id and `release_time` IS NULL;";


        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':patient_id', $patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':release_time', $release_time, PDO::PARAM_STR);

        return $stmt->execute();

    }
    
    public static function setSecondPathoReview($conn, $id, $second_patho_review)
    {
        $sql = "UPDATE presultupdate ".
                " SET second_patho_review = :second_patho_review".
                " WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':second_patho_review', $second_patho_review, PDO::PARAM_INT);

        return $stmt->execute();
    }

}
