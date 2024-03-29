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
    
    
    public $req_date;
    public $req_finish_date;
        //$job->req_id = 0;
    
    public $req_id; // int
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
    public $qty;  //  	

    public static function getInitObj()
    {
        $job = new Job();

        $job->id = null;
        
        $job->req_date = null;
        $job->req_finish_date = null;
        $job->req_id = 0;
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
        $job->qty = 1;
        return $job;
    }

    public function create($conn)
    {

        $sql = "INSERT INTO `job` (`id`,`req_date` ,`job_role_id`, `patient_id`, `result_id`, `patient_number`, `user_id`, `pre_name`, `name`, `lastname`, `jobname`, `pay`, `cost_count_per_day`, `comment`, `finish_date`) "
             . "VALUES             (NULL,:req_date, :job_role_id,  :patient_id,  :result_id,  :patient_number,  :user_id,  :pre_name,  :name,  :lastname,  :jobname,  :pay,  :cost_count_per_day,  :comment,   :finish_date)";
  
        $sql_dbg = $sql; 
        

//        $sql_dbg=str_replace(':job_role_id', $this->job_role_id, $sql_dbg);
//        $sql_dbg=str_replace(':patient_id', $this->patient_id, $sql_dbg);
//        $sql_dbg=str_replace(':result_id', $this->result_id, $sql_dbg);
//        $sql_dbg=str_replace(':patient_number', '"'.$this->patient_number.'"' , $sql_dbg);
//        $sql_dbg=str_replace(':user_id', $this->user_id, $sql_dbg);
//        $sql_dbg=str_replace(':pre_name', '"'.$this->pre_name.'"', $sql_dbg);
//        $sql_dbg=str_replace(':name', '"'.$this->name.'"', $sql_dbg);
//        $sql_dbg=str_replace(':lastname', '"'.$this->lastname.'"', $sql_dbg);
//        $sql_dbg=str_replace(':jobname', '"'.$this->jobname.'"', $sql_dbg);
//        $sql_dbg=str_replace(':pay', '"'.$this->pay.'"', $sql_dbg);
//        $sql_dbg=str_replace(':cost_count_per_day', $this->cost_count_per_day, $sql_dbg);
//        $sql_dbg=str_replace(':comment', '"'.$this->comment.'"', $sql_dbg);
//        
//        $myfile = fopen("Job_create.txt", "w") or die("Unable to open file!");
//        fwrite($myfile, $sql_dbg);
//        fclose($myfile);
//        
        
        $stmt = $conn->prepare($sql);
        // $stmt = $bindValue(':id Primary'        ,$this->id Primary        ,PDO::PARAM_STR);      
        $stmt->bindValue(':req_date', $this->req_date, PDO::PARAM_STR);
        $stmt->bindValue(':job_role_id', $this->job_role_id, PDO::PARAM_INT);
        $stmt->bindValue(':patient_id', $this->patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':result_id', $this->result_id, PDO::PARAM_INT);
        $stmt->bindValue(':patient_number', $this->patient_number, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->bindValue(':pre_name', $this->pre_name, PDO::PARAM_STR);
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $stmt->bindValue(':jobname', $this->jobname, PDO::PARAM_STR);
        $stmt->bindValue(':pay', $this->pay,PDO::PARAM_STR);
        $stmt->bindValue(':cost_count_per_day', $this->cost_count_per_day, PDO::PARAM_INT);
        $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);
        if($this->finish_date != null){
            $stmt->bindValue(':finish_date', $this->finish_date, PDO::PARAM_STR);
        }else{
            $stmt->bindValue(':finish_date', NULL, PDO::PARAM_STR);
        }


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

        $sql = $sql . " WHERE `movetotrash` = 0 ";


        if ($patient_id != 0) {
            $sql = $sql . " and patient_id = " . $patient_id;
        }
        if ($job_role_id != 0) {
            $sql = $sql . " and job_role_id = " . $job_role_id;
        }
        if ($start != '0') {
            $sql .= " and date(req_date) >= '{$start}'";
        }
        $sql = $sql . " ORDER BY id DESC";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public static function getAll_v2($conn, $patient_id = 0, $job_role_id = 0, $start = '0')
    {
        $sql = "SELECT *, DATE(finish_date) as finish_date_date, DATE(req_date) as req_date_date FROM `job` ";

        $sql = $sql . " WHERE `movetotrash` = 0 ";


        if ($patient_id != 0) {
            $sql = $sql . " and patient_id = " . $patient_id;
        }
        if ($job_role_id != 0) {
            $sql = $sql . " and job_role_id = " . $job_role_id;
        }
        if ($start != '0') {
            $sql .= " and date(req_date) >= '{$start}'";
        }
        $sql = $sql . " ORDER BY id DESC";

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
    
    public static function getSlidePreparation($conn, $patient_id = 0)
    {
        $sql = "SELECT * FROM `job` ";

        $sql = $sql . " WHERE 1=1 ";


        if ($patient_id != 0) {
            $sql = $sql . " and patient_id = " . $patient_id;
        }
        $sql = $sql . " and job_role_id = 3";

        $sql = $sql . " ORDER BY id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

    //
    public static function getByPatientJobRole($conn, $patient_id = 0, $jobrole_id = 0)
    {
        $sql = "SELECT * FROM `job` ";

        $sql = $sql . " WHERE 0 = 0 ";


        if ($patient_id != 0) {
            $sql = $sql . " and patient_id = " . $patient_id;
        }
        $sql = $sql . " and job_role_id = " . $jobrole_id;

        $sql = $sql . " ORDER BY id DESC";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getByPatientJobRole_Unassigned($conn, $patient_id = 0, $jobrole_id = 0)
    {
        $sql = "SELECT * FROM `job` ";

        $sql = $sql . " WHERE req_id = 0 ";


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
    
    public static function getJobCountByDaily($conn, $startdate, $enddate, $user_job_daily, $role_job_daily)
    {
        $sql = "SELECT                                                \n".
            " # * ,                                                       \n".
            " CONCAT(u_owner.name,' ',u_owner.lastname) as owner_name,    \n".
            " DATE(p.date_1000) as accept_date,                           \n".
            " jr.name as job_role_name,                                   \n".
            " SUM(j.qty) as job_qty_sum                                   \n".
            " FROM `job` as j                                             \n".
            " JOIN patient as p ON j.patient_id = p.id                    \n".
            " JOIN job_role as jr on jr.id = j.job_role_id                \n".
            " JOIN user as u_owner on u_owner.id = j.user_id              \n".
            " WHERE p.movetotrash = 0                                     \n".
            " and ( date(p.date_1000) >= '{$startdate}'and date(p.date_1000) <= '{$enddate}' )   \n";
            if($user_job_daily != 0){
                 $sql .= " and u_owner.id = $user_job_daily ";
            }
            if($role_job_daily != 0){
                 $sql .= " and jr.id = $role_job_daily ";
            }
            $sql .= " GROUP BY owner_name, accept_date, job_role_name             \n";
            
//            Util::writeFile('sqldbg.txt', $sql);

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
    
    public static function deleteJob1($conn, $patient_id,$user_id)
    {

        $sql = "DELETE FROM `job` WHERE patient_id = $patient_id and user_id=$user_id and job_role_id = 1";

//        Util::writeFile(dbg.txt, $sql);
        
        $results = $conn->query($sql);

        return $results;
    }
    
    public static function deleteJob2($conn, $patient_id,$user_id)
    {

        $sql = "DELETE FROM `job` WHERE patient_id = $patient_id and user_id=$user_id and job_role_id = 2";

//        Util::writeFile(dbg.txt, $sql);
        
        $results = $conn->query($sql);

        return $results;
    }
    
    public static function deleteJob3($conn, $patient_id,$user_id)
    {

        $sql = "DELETE FROM `job` WHERE patient_id = $patient_id and user_id=$user_id and job_role_id = 3";

//        Util::writeFile(dbg.txt, $sql);
        
        $results = $conn->query($sql);

        return $results;
    }
    
    
    public static function deleteAllJob1($conn, $patient_id)
    {

        $sql = "DELETE FROM `job` WHERE patient_id = $patient_id and job_role_id = 1";

//        Util::writeFile(dbg.txt, $sql);
        
        $results = $conn->query($sql);

        return $results;
    }
    
    public static function deleteAllJob2($conn, $patient_id)
    {

        $sql = "DELETE FROM `job` WHERE patient_id = $patient_id and job_role_id = 2";

//        Util::writeFile(dbg.txt, $sql);
        
        $results = $conn->query($sql);

        return $results;
    }
    
    public static function deleteAllJob3($conn, $patient_id)
    {

        $sql = "DELETE FROM `job` WHERE patient_id = $patient_id and job_role_id = 3";

//        Util::writeFile(dbg.txt, $sql);
        
        $results = $conn->query($sql);

        return $results;
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
    
    public static function setRequestIDifNotSet($conn, $patient_id, $req_id, $job_role_id)
    {
        $sql = "UPDATE `job` "
                . "SET `req_id` = :req_id "
                . "WHERE (`patient_id` = :patient_id and `req_id` = 0 and job_role_id = :job_role_id)";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':req_id', $req_id, PDO::PARAM_INT);
        $stmt->bindValue(':patient_id', $patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':job_role_id', $job_role_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
    
    public static function setRequestIDifNotSetJobRole4($conn, $patient_id, $req_id, $req_date)
    {
        $sql = "UPDATE `job` "
                . "SET `req_id` = :req_id ,  `req_date` = :req_date "
                . "WHERE (`patient_id` = :patient_id and `req_id` = 0 and job_role_id = 4)";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':req_id', $req_id, PDO::PARAM_INT);
        $stmt->bindValue(':req_date', $req_date, PDO::PARAM_STR);
        $stmt->bindValue(':patient_id', $patient_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
    
    public static function setFinishDate($conn, $rid, $finishdate)
    {
        $sql = 'UPDATE job '
               . ' SET req_finish_date = :req_finish_date, finish_date = :finish_date '
               . ' WHERE req_id = :req_id';

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':req_id', $rid, PDO::PARAM_INT);
        $stmt->bindValue(':req_finish_date', $finishdate, PDO::PARAM_STR);
        $stmt->bindValue(':finish_date', $finishdate, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    public function job2_finish($conn,$patient_id,$job_role_id)
    {
        //SELECT * FROM `job` WHERE `job_role_id` = 2 ORDER BY `id` DESC
        $cur_time = Util::get_curreint_thai_date_time();

        $sql = "UPDATE job
                    SET finish_date = $cur_time,
    user_id
    pre_name
    name
    lastname
    WHERE patient_id = :patient_id and ";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':jobname', $this->jobname, PDO::PARAM_STR);
        $stmt->bindValue(':pay', $this->pay, PDO::PARAM_STR);
        $stmt->bindValue(':cost_count_per_day', $this->cost_count_per_day, PDO::PARAM_INT);

        return $stmt->execute();
    }
    
    public static function setJob1Qty($conn, $patient_id, $job1qty) {
        $sql = "UPDATE `job` 
                SET `qty` = :job1qty
                WHERE  patient_id = :patient_id and job_role_id = 1 ";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':patient_id', $patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':job1qty', $job1qty, PDO::PARAM_INT);

        return $stmt->execute();
    }
    
    public static function setJob2Qty($conn, $patient_id, $job2qty) {
        $sql = "UPDATE `job` 
                SET `qty` = :job2qty
                WHERE  patient_id = :patient_id and job_role_id = 2 ";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':patient_id', $patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':job2qty', $job2qty, PDO::PARAM_INT);

        return $stmt->execute();
    }
    
    public static function setJob3Qty($conn, $patient_id, $job3qty) {
        $sql = "UPDATE `job` 
                SET `qty` = :job3qty
                WHERE  patient_id = :patient_id and job_role_id = 3 ";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':patient_id', $patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':job3qty', $job3qty, PDO::PARAM_INT);

        return $stmt->execute();
    }
    
    public static function movetotrash($conn, $id, $patient_num) {
        $thai_date = Util::get_curreint_thai_date_time();
        Log::add($conn, $_SESSION['log_username'], $_SESSION['log_name'], "Job::movetotrash(id)", $id.' : '.$patient_num , $thai_date);
        $sql = "UPDATE job
                SET movetotrash = 1
                WHERE patient_id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
//        $stmt->bindValue(':pnum',$patient_num .'_'.$thai_date , PDO::PARAM_STR);

        return $stmt->execute();
    }
    
}
