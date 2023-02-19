<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Job
 *
 * @author 2444536
 */
class Job {

    //put your code here
    public $id;  //int(11) Primary	
    public $job_role_id;  //int(11)			
    public $patient_id;  // int(11)		
    public $patient_number; //  varchar(10)	
    public $user_id;  //   int(11)		
    public $pre_name;  //     varchar(20)
    public $name;  //    varchar(30)	
    public $lastname;  //   varchar(30)	
    public $jobname;  //  varchar(30)	
    public $pay;  //  float	
    public $cost_count_per_day;
    public $comment;  //   varchar(50)	
    public $finish_date;  //  datetime	

    public static function getInitObj() {
        $job = new Job();

        $job->id = null;
        $job->job_role_id = 0;
        $job->patient_id = 0;
        $job->patient_number = "";
        $job->user_id = 0;
        $job->pre_name = "";
        $job->name = "";
        $job->lastname = "";
        $job->jobname = "";
        $job->pay = 0.0;
        $job->cost_count_per_day = 0;
        $job->comment = "";
        $job->finish_date = null;
        return $job;
    }

    public function create($conn) {

        $sql = "INSERT INTO `job` (`id`, `job_role_id`, `patient_id`, `patient_number`, `user_id`, `pre_name`, `name`, `lastname`, `jobname`, `pay`, `cost_count_per_day`, `comment`, `finish_date`) "
                . "VALUES         (NULL, :job_role_id,  :patient_id,  :patient_number,  :user_id,  :pre_name,  :name,  :lastname,  :jobname,  :pay,  :cost_count_per_day,  :comment,   NULL)";

        $stmt = $conn->prepare($sql);

        // $stmt = $bindValue(':id Primary'        ,$this->id Primary        ,PDO::PARAM_STR);                
        $stmt->bindValue(':job_role_id', $this->job_role_id, PDO::PARAM_INT);
        $stmt->bindValue(':patient_id', $this->patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':patient_number', $this->patient_number, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->bindValue(':pre_name', $this->pre_name, PDO::PARAM_STR);
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $stmt->bindValue(':jobname', $this->jobname, PDO::PARAM_STR);
        $stmt->bindValue(':pay', $this->pay);
        $stmt->bindValue(':cost_count_per_day', $this->cost_count_per_day, PDO::PARAM_INT);
        $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);
//        $stmt->bindValue(':finish_date', $this->finish_date, PDO::PARAM_STR);


        //var_dump($stmt);

        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public static function getAll($conn, $patient_id = 0, $job_role_id = 0) {
        $sql = "SELECT * FROM `job` ";

        $sql = $sql . " WHERE 1=1 ";


        if ($patient_id != 0) {
            $sql = $sql . " and patient_id = " . $patient_id;
        }
        if ($type != 0) {
            $sql = $sql . " and job_role_id = " . $job_role_id;
        }
        $sql = $sql . " ORDER BY id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

    //คนตัดเนื้อ
    public static function getCrossSection($conn, $patient_id = 0) {
        $sql = "SELECT * FROM `job` ";

        $sql = $sql . " WHERE 1=1 ";


        if ($patient_id != 0) {
            $sql = $sql . " and patient_id = " . $patient_id;
        }
        $sql = $sql . " and job_role_id = 1";

        $sql = $sql . " ORDER BY id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function delete($conn,$id)
    {
        
        $sql = "DELETE FROM `job` WHERE `job`.`id` = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

}
