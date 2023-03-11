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
class Job
{

    //put your code here
    public $id;  //int(11) Primary	
    public $job_role_id;  //int(11)			
    public $patient_id;  // int(11)		
    public $result_id;  // int(11)		
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
    public $second_patho_review;  //  
    public $request_sp_slide;  //  	

    public static function getInitObj()
    {
        $job = new Job();

        $job->id = null;
        $job->job_role_id = 0;
        $job->patient_id = 0;
        $job->result_id = 0;
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

    public function create($conn)
    {

        $sql = "INSERT INTO `job` (`id`, `job_role_id`, `patient_id`, `result_id`, `patient_number`, `user_id`, `pre_name`, `name`, `lastname`, `jobname`, `pay`, `cost_count_per_day`, `comment`, `finish_date`) "
            . "VALUES             (NULL, :job_role_id,  :patient_id,  :result_id,  :patient_number,  :user_id,  :pre_name,  :name,  :lastname,  :jobname,  :pay,  :cost_count_per_day,  :comment,   NULL)";

        $stmt = $conn->prepare($sql);

        // $stmt = $bindValue(':id Primary'        ,$this->id Primary        ,PDO::PARAM_STR);                
        $stmt->bindValue(':job_role_id', $this->job_role_id, PDO::PARAM_INT);
        $stmt->bindValue(':patient_id', $this->patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':result_id', $this->result_id, PDO::PARAM_INT);
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
            return $this->id;
        } else {
            return false;
        }
    }

    public static function getAll($conn, $patient_id = 0, $job_role_id = 0, $start = '0')
    {
        $sql = "SELECT * FROM `job` ";

        $sql = $sql . " WHERE 1=1 ";


        if ($patient_id != 0) {
            $sql = $sql . " and patient_id = " . $patient_id;
        }
        if ($job_role_id != 0) {
            $sql = $sql . " and job_role_id = " . $job_role_id;
        }
        if ($start != '0') {
            $sql .= " and date(insert_time) >= '{$start}'";
        }
        $sql = $sql . " ORDER BY id";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByID($conn, $id = 0)
    {
        $sql = "SELECT * FROM `job` ";

        $sql = $sql . " WHERE id=$id;";
        
        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    //คนตัดเนื้อ
    public static function getCrossSection($conn, $patient_id = 0)
    {
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

    //คนช่วยตัดเนื้อ
    public static function getAssisCrossSection($conn, $patient_id = 0)
    {
        $sql = "SELECT * FROM `job` ";

        $sql = $sql . " WHERE 1=1 ";


        if ($patient_id != 0) {
            $sql = $sql . " and patient_id = " . $patient_id;
        }
        $sql = $sql . " and job_role_id = 2";

        $sql = $sql . " ORDER BY id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

    //
    public static function getByPatientJobRole($conn, $patient_id = 0, $jobrole_id = 0)
    {
        $sql = "SELECT * FROM `job` ";

        $sql = $sql . " WHERE 1=1 ";


        if ($patient_id != 0) {
            $sql = $sql . " and patient_id = " . $patient_id;
        }
        $sql = $sql . " and job_role_id = " . $jobrole_id;

        $sql = $sql . " ORDER BY id DESC";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
     public static function getByPatientJobRoleUResult($conn, $patient_id = 0, $jobrole_id = 0, $result_id = 0)
    {
        $sql = "SELECT * FROM `job` ";

        $sql = $sql . " WHERE  ";

        $sql = $sql . "  patient_id = " . $patient_id;
        
        $sql = $sql . " and job_role_id = " . $jobrole_id;
        
        $sql = $sql . " and result_id = " . $result_id;

        $sql = $sql . " ORDER BY id DESC";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function delete($conn, $id)
    {

        $sql = "DELETE FROM `job` WHERE `job`.`id` = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function update($conn)
    {

        $sql = "UPDATE job
                    SET jobname = :jobname,
                        pay =:pay,
                        cost_count_per_day =:cost_count_per_day
                    WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':jobname', $this->jobname, PDO::PARAM_STR);
        $stmt->bindValue(':pay', $this->pay, PDO::PARAM_STR);
        $stmt->bindValue(':cost_count_per_day', $this->cost_count_per_day, PDO::PARAM_INT);

        return $stmt->execute();
    }
    
    public static function setSecondPathoReview($conn, $id, $second_patho_review)
    {
        $sql = "UPDATE job
                SET second_patho_review = :second_patho_review
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':second_patho_review', $second_patho_review, PDO::PARAM_INT);

        return $stmt->execute();
    }
    
    public static function set_request_sp_slide($conn, $id, $request_sp_slide)
    {
        $sql = "UPDATE job
                SET request_sp_slide = :request_sp_slide
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':request_sp_slide', $request_sp_slide, PDO::PARAM_INT);

        return $stmt->execute();
    }
    
    public static function setUresultID($conn, $id, $result_id)
    {
        $sql = "UPDATE job
                SET result_id = :result_id
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':result_id', $result_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
    
}
