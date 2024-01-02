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
        
        
//          $sql_dbg = $sql; 
//          
//        $sql_dbg=str_replace(':patient_id', $this->patient_id,$sql_dbg);      //int(11)
//        $sql_dbg=str_replace(':job_id', $this->job_id,$sql_dbg);      //int(11)		
//        $sql_dbg=str_replace(':req_date', '"'.$this->req_date.'"',$sql_dbg);      //int(11)		
//        $sql_dbg=str_replace(':create_user_id', $this->create_user_id,$sql_dbg);      //varchar(32)	patient pnum
//        $sql_dbg=str_replace(':comment', '"'.$this->comment.'"',$sql_dbg);      //varchar(32)	patient pnum
//        
//        $myfile = fopen("ReqSpSlideID_create.txt", "w") or die("Unable to open file!");
//        fwrite($myfile, $sql_dbg);
//        fclose($myfile);

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

        $sql = "SELECT *, r.id as rid, b.id as bid FROM req_id_sp_slide as r INNER JOIN service_billing as b ON r.id = b.req_id WHERE r.id = $req_id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public static function getBillandJobFromDateRange($conn, $startdate, $enddate) {

        $sql = "SELECT *, r.id as rid, b.id as bid FROM req_id_sp_slide as r INNER JOIN service_billing as b ON r.id = b.req_id "
                . "WHERE   date(b.req_date) >= '{$startdate}'and date(b.req_date) <= '{$enddate}' ";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getBillandJobTableWithStart($conn, $start = '0') {
        
        
//        select * 
//        FROM 
//            (SELECT  r.id AS rid, b.id AS bid, j.id AS jid, j.patient_id AS patient_id_key
//                , b.number, r.req_date , r.finish_date ,r.comment,  CONCAT(j.name,' ', j.lAStname) AS jowowner
//                ,CONCAT(b.code_description,' ', b.description) AS  req_sp_type, GROUP_CONCAT(b.sp_slide_block) AS bjob 
//            FROM (req_id_sp_slide AS r JOIN service_billing AS b)  JOIN job AS j ON r.id = b.req_id and r.id = j.req_id   
//            WHERE  date(r.req_date) >= '2023-06-18'
//            GROUP by rid, b.description
//            ) AS aa
//        JOIN 
//            ( 
//            SELECT jp.patient_id AS patient_id_key,  CONCAT(jp.name,' ', jp.lAStname) AS pathologist   
//            FROM job AS jp 
//            Where jp.job_role_id = 5
//            ) AS bb
//
//        ON aa.patient_id_key=bb.patient_id_key
        
        
//rid|bid|jid|patient_id_key|number   |req_date       |finish_date|comment|jowowner|req_sp_type|bjob|patient_id_key|pathologist|
//5  |86 |167|176           |CN2300001|6/18/2023 14:53|NULL       |0|ภูศิษฏ์ เรืองวาณิชยกุล|33000 Bcl-6|Z1,G,Z1|176|อภิชาติ ชุมทอง|
//5  |91 |167|176           |CN2300001|6/18/2023 14:53|NULL       |0|ภูศิษฏ์ เรืองวาณิชยกุล|33000 Calcitonin|F|176|อภิชาติ ชุมทอง|
//5  |83 |167|176           |CN2300001|6/18/2023 14:53|NULL       |0|ภูศิษฏ์ เรืองวาณิชยกุล|33000 CD117|M,R1,S1|176|อภิชาติ ชุมทอง|
//5  |89 |167|176           |CN2300001|6/18/2023 14:53|NULL       |0|ภูศิษฏ์ เรืองวาณิชยกุล|33000 CD1a|G,Z1|176|อภิชาติ ชุมทอง|
//6  |92 |170|176           |CN2300001|6/18/2023 15:01|NULL       |0|วรินทร เหลืองทอง|33000 CD1a|A|176|อภิชาติ ชุมทอง|
//8  |94 |171|176           |CN2300001|6/18/2023 20:17|NULL       |fkjdsfjcoweacfaw|พีรยุทธ สิทธิไชยากุล|33000 Caldesmon|C,D|176|อภิชาติ ชุมทอง|
//9  |96 |172|176           |CN2300001|6/18/2023 20:22|NULL       |xcxcx|จุลินทร สำราญ|33000 Calretinin|A,B|176|อภิชาติ ชุมทอง|
//10 |98 |173|176           |CN2300001|6/18/2023 20:25|NULL       |ffff|นันท์ สิงห์ปาน|33000 CD1a|A,B|176|อภิชาติ ชุมทอง|



        $sql = "select * 
            FROM 
                (SELECT  r.id AS rid, b.id AS bid, j.id AS jid, j.patient_id AS patient_id_key
                    , b.number, r.req_date , r.finish_date ,r.comment,  CONCAT(j.name,' ', j.lastname) AS jowowner
                    ,CONCAT(b.code_description,' ', b.description) AS  req_sp_type, GROUP_CONCAT(b.sp_slide_block) AS bjob 
                FROM (req_id_sp_slide AS r JOIN service_billing AS b)  JOIN job AS j ON r.id = b.req_id and r.id = j.req_id  and r.movetotrash = 0 "; 
        if ($start != '0') {
            $sql = $sql . "WHERE  date(r.req_date) >= '{$start}' ";
        }

        $sql = $sql . "GROUP by rid, b.description
                ) AS aa
            JOIN 
                ( 
                SELECT jp.patient_id AS patient_id_key,  CONCAT(jp.name,' ', jp.lastname) AS pathologist   
                FROM job AS jp 
                Where jp.job_role_id = 5
                ) AS bb

            ON aa.patient_id_key=bb.patient_id_key";
                
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
                
                
//array(1) {
//  [0]=>
//  array(12) {
//    ["rid"]=>
//    string(1) "4"
//    ["bid"]=>
//    string(2) "68"
//    ["jid"]=>
//    string(3) "163"
//    ["patient_id_key"]=>
//    string(3) "176"
//    ["number"]=>
//    string(9) "CN2300001"
//    ["req_date"]=>
//    string(19) "2023-06-17 13:17:03"
//    ["finish_date"]=>
//    NULL
//    ["comment"]=>
//    string(1) "0"
//    ["jowowner"]=>
//    string(58) "พีรยุทธ สิทธิไชยากุล"
//    ["req_sp_type"]=>
//    string(15) "33000 Amyloid A"
//    ["bjob"]=>
//    string(17) "A,O,W,C1,G1,T1,Z1"
//    ["pathologist"]=>
//    string(40) "อภิชาติ ชุมทอง"
//  }
//}
        
    }
    
    public static function setFinishDate($conn, $rid, $finishdate)
    {
        $sql = "UPDATE `req_id_sp_slide` "
                . "SET `finish_date` = :finish_date "
                . "WHERE id = :req_id";

        $stmt = $conn->prepare($sql);
        
        $stmt->bindValue(':req_id', $rid, PDO::PARAM_INT);
        $stmt->bindValue(':finish_date', $finishdate, PDO::PARAM_STR);

        return $stmt->execute();
    }
    

}
