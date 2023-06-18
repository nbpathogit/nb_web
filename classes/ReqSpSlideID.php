<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReqSpSlideID
 *
 * @author 2444536
 */
class ReqSpSlideID {

    public $id; //Primary	int(11)
    public $patient_id; //int(11)
    public $job_id; //int(11)
    public $req_date; //datetime
    public $finish_date; //datetime
    public $create_user_id; //int(11)
    public $comment; //text

    public function create($conn) {


        $sql = "INSERT INTO `req_id_sp_slide` (`id`, `patient_id`, `job_id`, `req_date`, `finish_date`, `create_user_id`, `comment`) "
                . "VALUES                     (NULL, :patient_id, :job_id, :req_date , NULL, :create_user_id, :comment)";


        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':patient_id', $this->patient_id, PDO::PARAM_INT);      //int(11)

        $stmt->bindValue(':job_id', $this->job_id, PDO::PARAM_INT);      //int(11)		
        $stmt->bindValue(':req_date', $this->req_date, PDO::PARAM_STR);      //int(11)		
        $stmt->bindValue(':create_user_id', $this->create_user_id, PDO::PARAM_INT);      //varchar(32)	patient pnum
        $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);      //varchar(32)	patient pnum
        //var_dump($stmt);

        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public static function getInitObj() {
        $req = new ReqSpSlideID();

        $req->patient_id = 0;      //int(11)	
        $req->job_id = 0;          //Request id
        $req->req_date = NULL;      //varchar(32)	patient pnum
        $req->finish_date = NULL;      //varchar(32)		
        $req->create_user_id = 0;      //tinyint(4)
        $req->comment = "";

        return $req;
    }

    public static function updatePatientID($conn, $id, $patient_id) {

        $sql = "UPDATE `req_id_sp_slide` "
                . "SET `patient_id` = :patient_id "
                . "WHERE `req_id_sp_slide`.`id` = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);      //int(11)
        $stmt->bindValue(':patient_id', $patient_id, PDO::PARAM_INT);      //int(11)
        //var_dump($stmt);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function updateJobID($conn, $id, $job_id) {

        $sql = "UPDATE `req_id_sp_slide` "
                . "SET `job_id` = :job_id "
                . "WHERE `req_id_sp_slide`.`id` = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);      //int(11)
        $stmt->bindValue(':job_id', job_id, PDO::PARAM_INT);      //int(11)
        //var_dump($stmt);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function getReqIdFromPatientId($conn, $patient_id) {

        $sql = "SELECT * FROM `req_id_sp_slide` WHERE `patient_id`= $patient_id";


        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getJobFromReqId($conn, $req_id) {

        $sql = "SELECT *, r.id as rid, j.id as jid FROM req_id_sp_slide as r INNER JOIN job as j ON r.id = j.req_id WHERE r.id = $req_id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getBillFromReqId($conn, $req_id) {

        $sql = "SELECT *, r.id as rid, b.id as bid FROM req_id_sp_slide as r INNER JOIN billing as b ON r.id = b.req_id WHERE r.id = $req_id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    

}
