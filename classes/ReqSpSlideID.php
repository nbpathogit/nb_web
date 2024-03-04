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

        $sql = "SELECT \n".
               " *, \n". 
               " r.id as rid, b.id as bid,b.slide_type as b_slide_type, b.patient_id as b_patient_id, b.create_date as b_create_date  \n" .
               "FROM req_id_sp_slide as r INNER JOIN service_billing as b ON r.id = b.req_id WHERE r.id =  $req_id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public static function getBillandJobFromDateRange($conn, $startdate, $enddate) {

        $sql = "SELECT *, r.id as rid, b.id as bid FROM req_id_sp_slide as r INNER JOIN service_billing as b ON r.id = b.req_id "
                . "WHERE   date(b.req_date) >= '{$startdate}' and date(b.req_date) <= '{$enddate}' ";
                
        //Util::writeFile('dbg.txt', $sql);

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getBillandJobFromDateRange_v2($conn, $startdate, $enddate) {

//        $sql = "SELECT *, r.id as rid, b.id as bid
//                    , j.name as j_name , j.lastname as j_lastname
//                    ,CONCAT(j.name,' ',j.lastname) as pathologist
//                FROM req_id_sp_slide as r 
//                INNER JOIN service_billing as b ON r.id = b.req_id 
//                INNER JOIN job AS j ON j.patient_id = r.patient_id
//                WHERE   date(b.req_date) >= '{$startdate}' and date(b.req_date) <= '{$enddate}' 
//                    and j.job_role_id = 5";
                
                
    $sql="SELECT                                                                             \n".
        "    #*,                                                                             \n".
        "    r.id as rid, b.id as bid,                                                       \n".
        "    date(b.req_date) AS b_req_date,                                                 \n".
        "    p.pnum as p_sn,                                                                 \n".
        "    IF(p.priority_id = 10 ,'ด่วน', '') as priority,                                   \n".
        "    b.description AS b_description,                                                 \n".
        "    b.sp_slide_block AS b_sp_slide_block,                                           \n".
        "    j.name as j_name , j.lastname as j_lastname,                                    \n".
        "    CONCAT(j.name,' ',j.lastname) as pathologist                                    \n".
        "FROM req_id_sp_slide as r                                                           \n".
        "JOIN service_billing as b ON r.id = b.req_id                                        \n".
        "JOIN patient AS p ON p.id = b.patient_id and p.movetotrash = 0                      \n".
        "JOIN hospital AS h ON h.id = p.phospital_id                                         \n".
        "JOIN job AS j ON j.patient_id = r.patient_id and j.job_role_id = 5                  \n".
        "WHERE   date(b.req_date) >= '{$startdate}' and date(b.req_date) <= '{$enddate}'     \n".
        "  and r.id != 0                                                                     \n".
        "ORDER BY b.req_date, p.pnum ASC                                                     \n";                
  
                
                
        //Util::writeFile('dbg.txt', $sql);
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('ReqSpSlideID_getBillandJobFromDateRange_v2.txt', $sql);   
        }

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



//        $sql = "select * 
//            FROM 
//                (SELECT  r.id AS rid, b.id AS bid, j.id AS jid, j.patient_id AS patient_id_key
//                    , b.number, r.req_date , r.finish_date ,r.comment,  CONCAT(j.name,' ', j.lastname) AS jowowner
//                    ,CONCAT(b.code_description,' ', b.description) AS  req_sp_type, GROUP_CONCAT(b.sp_slide_block) AS bjob 
//                FROM (req_id_sp_slide AS r JOIN service_billing AS b)  JOIN job AS j ON r.id = b.req_id and r.id = j.req_id  and r.movetotrash = 0 "; 
//        if ($start != '0') {
//            $sql = $sql . "WHERE  date(r.req_date) >= '{$start}' ";
//        }
//
//        $sql = $sql . "GROUP by rid, b.description
//                ) AS aa
//            JOIN 
//                ( 
//                SELECT jp.patient_id AS patient_id_key,  CONCAT(jp.name,' ', jp.lastname) AS pathologist   
//                FROM job AS jp 
//                Where jp.job_role_id = 5
//                ) AS bb
//
//            ON aa.patient_id_key=bb.patient_id_key";
        
        
        $sql = "SELECT  r.id AS r_id, b.id AS b_id, j4.id AS j4_id, j5.id AS j5_id,  r.patient_id AS rpatient_id ,".
               "    b.number AS b_patient_num, r.req_date , r.finish_date ,r.comment,  ".
               "     CONCAT(j4.name,' ', j4.lastname) AS j4owowner,CONCAT(j5.name,' ', j5.lastname) AS pathologist, ".
               "     CONCAT(b.code_description,' ', b.description) AS  req_sp_type, GROUP_CONCAT(b.sp_slide_block) AS bjob ".
               " FROM ".
               " 	req_id_sp_slide AS r ".
               "     LEFT JOIN ".
               "    service_billing AS b ON r.id = b.req_id ".
               "     LEFT JOIN ".
               "    job AS j4 ON r.id = j4.req_id AND j4.job_role_id = 4 ".
               "    LEFT JOIN ".
               "    job AS j5 ON  r.patient_id = j5.patient_id AND j5.job_role_id = 5 ".
               "WHERE   r.movetotrash = 0 ";
        if ($start != '0') {
            $sql = $sql . " and  date(r.req_date) >= '{$start}' ";
        }

        $sql = $sql . " GROUP by r_id, b.description ";
                
//        $sql2 = $sql;
//        Util::writeFile('dbg.txt', $sql2);
//        
        $results = $conn->query($sql);
        $articles = $results->fetchAll(PDO::FETCH_ASSOC);
        
//        $debug = var_export($articles, true);
//        Util::writeFile('vardump.txt', $debug);

        return $articles;
                
                
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
    
    // req date as DATE only
    public static function getBillandJobTableWithStart_v2($conn, $start = '0') {
        

    $sql="SELECT                                                                                                          \n".
        "    #*,                                                                                                         \n".
        "    r.id AS r_id, b.id AS b_id, j4.id AS j4_id, j5.id AS j5_id,  r.patient_id AS rpatient_id ,                  \n".
        "    b.number AS b_patient_num,                                                                                  \n".
        "    r.req_date ,                                                                                                \n".
        "    r.finish_date ,                                                                                             \n".
        "    r.comment,                                                                                                  \n".
        "    CONCAT(j4.name,' ', j4.lastname) AS j4owowner,CONCAT(j5.name,' ', j5.lastname) AS pathologist,              \n".
        "    CONCAT(b.code_description,' ', b.description) AS  req_sp_type, GROUP_CONCAT(b.sp_slide_block) AS bjob ,     \n".
        "    DATE(r.req_date) as req_datedate ,                                                                          \n".
        "    DATE(r.finish_date) as  finish_datedate ,                                                                   \n".
        "    h.hospital as h_hospital                                                                                    \n".
        "FROM  req_id_sp_slide AS r                                                                                      \n".
        "LEFT JOIN  service_billing AS b ON r.id = b.req_id                                                              \n".
        "JOIN patient AS p ON p.id = b.patient_id                                                                        \n".
        "JOIN hospital AS h ON h.id = p.phospital_id                                                                     \n".
        "LEFT JOIN  job AS j4 ON r.id = j4.req_id AND j4.job_role_id = 4                                                 \n".      
        "LEFT JOIN  job AS j5 ON  r.patient_id = j5.patient_id AND j5.job_role_id = 5                                    \n".
        "WHERE   r.movetotrash = 0                                                                                       \n";
        if ($start != '0') {
            $sql = $sql .  "    and  date(r.req_date) >= '{$start}'                                                            \n";
        }
    $sql = $sql .  "GROUP by r_id, b.description                                                                                    \n".
        "ORDER by b_patient_num ASC                                                                                      \n";


//        $sql2 = $sql;
//        Util::writeFile('dbg.txt', $sql2);
//        
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('ReqSpSlideID_getBillandJobTableWithStart_v2.txt', $sql);   
        }
//        
        $results = $conn->query($sql);
        $articles = $results->fetchAll(PDO::FETCH_ASSOC);
        
//        $debug = var_export($articles, true);
//        Util::writeFile('vardump.txt', $debug);

        return $articles;
                
                
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
    
    public static function movetotrash($conn, $id, $patient_num) {
        $thai_date = Util::get_curreint_thai_date_time();
        Log::add($conn, $_SESSION['log_username'], $_SESSION['log_name'], "ReqSpSlideID::movetotrash(id:pnum)", $id.' : '.$patient_num , $thai_date);
        $sql = "UPDATE req_id_sp_slide
                SET movetotrash = 1
                WHERE patient_id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
//        $stmt->bindValue(':pnum',$patient_num .'_'.$thai_date , PDO::PARAM_STR);

        return $stmt->execute();
    }

}
