<?php
//require 'includes/init.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ServiceBilling
 *
 * @author 2444536
 */
class ServiceBilling {
    
    public $id;          //int(11)
    public $req_id;      //Request id
    
    public $req_date;      //Request id
    public $req_finish_date;      //Request id
    
    public $specimen_id; //int(11) link ot id in specimenList table
    public $patient_id;  //int(11)		
    public $number;      //varchar(32)	
    public $name;        //varchar(32)	
    public $lastname;    //varchar(32)	
    public $slide_type;  //tinyint(4)
    public $code_description; //Code Name
    public $description; //text	
    
    
    
    
    public $nm_slide_count; //text	
    public $sp_slide_block; //text	
    public $sp_slide_count; //text	
    
    
    
    
    public $import_date; //date			
    public $report_date; //varchar(16)	
    public $hospital;    //varchar(32)	
    public $hospital_id;    //varchar(32)	
    public $hn;          //varchar(32)	
    public $send_doctor; //varchar(32)	
    public $pathologist; //varchar(32)	
    public $cost;        //int(11)		
    public $comment;     //text	    
    public $create_date;     

    //put your code here

    public static function getAll($conn, $patient_id = 0,$type = 0, $start = '0') {
        $sql = "SELECT *
                FROM service_billing ";
        
        $sql = $sql . " WHERE 1=1 ";
        
        
        if ($patient_id != 0) {
            $sql = $sql . " and patient_id = " . $patient_id;
        }
        if ($type != 0) {
            $sql = $sql . " and slide_type = " . $type;
        }
        if ($start != '0') {
            $sql .= " and date(import_date) >= '{$start}'";
        }
        $sql = $sql . " ORDER BY id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public static function getAll145($conn, $patient_id = 0,$type = 0, $start = '0') {
        $sql = "SELECT *
                FROM service_billing ";
        
        $sql = $sql . " WHERE 1=1 ";
        
        
        if ($patient_id != 0) {
            $sql = $sql . " and patient_id = " . $patient_id;
        }
        
        $sql = $sql . " and (slide_type = 1 or slide_type = 4 or slide_type = 5)";
        
        if ($start != '0') {
            $sql .= " and date(import_date) >= '{$start}'";
        }
        $sql = $sql . " ORDER BY id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public static function getAllUnRequest($conn, $patient_id = 0,$type = 0, $start = '0') {
        $sql = "SELECT *
                FROM service_billing ";
        
        $sql = $sql . " WHERE req_id = 0 ";
        
        
        if ($patient_id != 0) {
            $sql = $sql . " and patient_id = " . $patient_id;
        }
        if ($type != 0) {
            $sql = $sql . " and slide_type = " . $type;
        }
        if ($start != '0') {
            $sql .= " and date(import_date) >= '{$start}'";
        }
        $sql = $sql . " ORDER BY id";
        
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('ServiceBilling_getAllUnRequest.txt', $sql);   
        }

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getAllUnRequest2367($conn, $patient_id = 0,$type = 0, $start = '0') {
        $sql = "SELECT *
                FROM service_billing \n";
        
        $sql = $sql . " WHERE req_id = 0 \n";//Unrequest req_id = 0
        
        
        if ($patient_id != 0) {
            $sql = $sql . " and patient_id = " . $patient_id ."\n";
        }
        
        $sql = $sql . " and (slide_type = 2 or slide_type = 3 or slide_type = 6 or slide_type = 7) \n";
        
        if ($start != '0') {
            $sql .= " and date(import_date) >= '{$start}'";
        }
        $sql = $sql . " ORDER BY id";
        
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('ServiceBilling_getAllUnRequest2367.txt', $sql);   
        }

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getAllforBillPage($conn, $start = '0') {
        $sql = "SELECT *, h.id as hid, b.id as bid FROM".
                " service_billing as b ".
                " JOIN hospital as h".
                " JOIN service_type as s".
                " JOIN patient as p".
                " WHERE h.id = p.phospital_id and b.slide_type = s.id and b.patient_id = p.id ";
                if ($start != '0') {
                  $sql .= " and date(b.import_date) >= '{$start}'";
                }
                $sql = $sql . " ORDER by b.id ;";
                
        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllDateforBillPage($conn, $start = '0',$end = '0') {
          
        $sql =  "SELECT p.id as pid,
                 b.id as b_id,
                 p.sn_type as p_sntype ,
                 p.pnum as p_sn_num ,
                 p.phospital_num as p_hn,
                 CONCAT(p.pname,' ',p.plastname) as p_pname,
                 CONCAT(user_cli.name,' ',user_cli.lastname) as user_clinicient,
                 hp.hospital as hp_hospital,
                 CONCAT(j5.name,' ',j5.lastname) as j5_pathologist,
                 DATE(p.date_1000)as p_accept_date,
                 DATE(b.create_date) as b_billing_date,
                 st.service_typea_bill as st_service_typea_bill,
                 st.service_type as st_type,
                 b.code_description as b_code,
                 b.description as b_description,
                 b.sp_slide_block as b_sp_slide_block,
                 b.cost as b_cost
                FROM  patient as p  
                 LEFT JOIN service_billing as b  ON  p.id = b.patient_id
                 LEFT JOIN service_type as st ON st.id = b.slide_type
                 LEFT JOIN job as j5 ON j5.patient_id = p.id and j5.job_role_id = 5
                 LEFT JOIN user as user_cli ON user_cli.id = p.pclinician_id 
                 LEFT JOIN hospital as hp ON hp.id = p.phospital_id
                WHERE  p.movetotrash = 0 ";
                if ($start != '0') {
                  $sql .= " and date(p.date_1000) >= '{$start}' ";
                }  
                if ($end != '0') {
                  $sql .= " and date(p.date_1000) <= '{$end}' ";
                }
                $sql .= " ORDER by p.pnum ASC";

//        Util::writeFile('ServiceBilling_getAllDateforBillPage.txt', $sql);   
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('ServiceBilling_getAllDateforBillPage.txt', $sql);   
        }
                
        $results = $conn->query($sql);
        return $results->fetchAll(PDO::FETCH_ASSOC);
        
//        Util::writeFile('doutput.txt', var_dump($rs));
        
        
    }
    
    public static function getAllDateforBillPageByBillingdate($conn, $start = '0',$end = '0') {
          
        $sql =  "SELECT p.id as pid,
                 b.id as b_id,
                 p.sn_type as p_sntype ,
                 p.pnum as p_sn_num ,
                 p.phospital_num as p_hn,
                 CONCAT(p.pname,' ',p.plastname) as p_pname,
                 CONCAT(user_cli.name,' ',user_cli.lastname) as user_clinicient,
                 hp.hospital as hp_hospital,
                 CONCAT(j5.name,' ',j5.lastname) as j5_pathologist,
                 DATE(p.date_1000)as p_accept_date,
                 DATE(b.create_date) as b_billing_date,
                 st.service_type as st_type,
                 b.code_description as b_code,
                 b.description as b_description,
                 b.sp_slide_block as b_sp_slide_block,
                 b.cost as b_cost
                FROM  patient as p  
                 LEFT JOIN service_billing as b  ON  p.id = b.patient_id
                 LEFT JOIN service_type as st ON st.id = b.slide_type
                 LEFT JOIN job as j5 ON j5.patient_id = p.id and j5.job_role_id = 5
                 LEFT JOIN user as user_cli ON user_cli.id = p.pclinician_id 
                 LEFT JOIN hospital as hp ON hp.id = p.phospital_id
                WHERE  p.movetotrash = 0 ";
                if ($start != '0') {
                  $sql .= " and date(b.create_date) >= '{$start}' ";
                }  
                if ($end != '0') {
                  $sql .= " and date(b.create_date) <= '{$end}' ";
                }
                $sql .= " ORDER by p.pnum ASC";

        //Util::writeFile('dbg.txt', $sql);
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('ServiceBilling_getAllDateforBillPageByBillingdatee.txt', $sql);   
        }
                
        $results = $conn->query($sql);
        return $results->fetchAll(PDO::FETCH_ASSOC);
        
//        Util::writeFile('doutput.txt', var_dump($rs));
        
        
    }
    
    public static function getBillbyHospitalbyDateRange($conn,$hospital_id, $startdate,$enddate, $limit = 0) {


        if($GLOBALS['isBillByAcceptDate']){
               
    $sql="SELECT                                                                                   \n". 
             "#*,                                                                                      \n".
             "h.id as hid, b.id as bid, p.id as pid, p.pnum as p_sn,  job_pathologist.id as job_id,    \n".
             "CONCAT(p.ppre_name,p.pname,' ',p.plastname) as patient_name,                             \n".
             "DATE(p.date_1000) as admit_date,                                                         \n".
             "p.phospital_num as hospital_num,                                                         \n".
             "CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as clinicien_name,               \n".
             "h.hospital as h_hospital,                                                                \n".
             "CONCAT(job_cytologist.name,' ',job_cytologist.lastname) as cytologist_name,              \n".
             "CONCAT(job_pathologist.name,' ',job_pathologist.lastname) as pathologist_name,           \n".
             "b.code_description as b_code,                                                            \n".
             "b.code2 as b_code2,                                                                      \n".
             "b.description as b_description,                                                          \n".
             "b.cost as b_cost,                                                                        \n".
             "s.service_type_bill as s_service_type,                                                   \n".
             "s.service_typea_bill as s_service_typea_bill,                                            \n".
             "DATE(b.create_date) as b_service_date                                                    \n".
             "FROM patient as p                                                                        \n".
             "   JOIN service_billing as b ON  b.patient_id = p.id                                     \n".
             "     and date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'       \n";
              if( ! ((int)$hospital_id == -1) ){
                     $sql.= "and  p.phospital_id = {$hospital_id}                                       \n";
              }   
       $sql.="   JOIN hospital as h ON p.phospital_id = h.id                                           \n".
             "   JOIN service_type as s ON b.slide_type = s.id                                         \n".
             "   JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                    \n".
             "   LEFT JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                 \n".
             "       and job_pathologist.job_role_id = 5                                               \n".
             "   LEFT JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                   \n".   
             "       and job_cytologist.job_role_id = 7                                                \n". 
             "   WHERE   1                                                                            \n".
             "               and p.movetotrash = 0                                                     \n".
             "   ORDER by p.pnum                                                                       \n";
         
        }
        
        if($GLOBALS['isBillByServiceDate']){
        $sql="SELECT                                                                                   \n". 
             "#*,                                                                                      \n".
             "h.id as hid, b.id as bid, p.id as pid, p.pnum as p_sn,  job_pathologist.id as job_id,    \n".
             "CONCAT(p.ppre_name,p.pname,' ',p.plastname) as patient_name,                             \n".
             "DATE(p.date_1000) as admit_date,                                                         \n".
             "p.phospital_num as hospital_num,                                                         \n".
             "CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as clinicien_name,               \n".
             "h.hospital as h_hospital,                                                                \n".
             "CONCAT(job_cytologist.name,' ',job_cytologist.lastname) as cytologist_name,              \n".
             "CONCAT(job_pathologist.name,' ',job_pathologist.lastname) as pathologist_name,           \n".
             "b.code_description as b_code,                                                            \n".
             "b.description as b_description,                                                          \n".
             "b.cost as b_cost,                                                                        \n".
             "s.service_type_bill as s_service_type,                                                   \n".
             "s.service_typea_bill as s_service_typea_bill,                                             \n".
             "DATE(b.create_date) as b_service_date                                                         \n".
             "FROM patient as p                                                                        \n".
             "   JOIN service_billing as b ON  b.patient_id = p.id                                     \n".
             "     and date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'   \n";
              if( ! ((int)$hospital_id == -1) ){
                     $sql.= "and  p.phospital_id = {$hospital_id}                                       \n";
              }   
       $sql.="   JOIN hospital as h ON p.phospital_id = h.id                                           \n".
             "   JOIN service_type as s ON b.slide_type = s.id                                         \n".
             "   JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                    \n".
             "   LEFT JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                 \n".
             "       and job_pathologist.job_role_id = 5                                               \n".
             "   LEFT JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                   \n".   
             "       and job_cytologist.job_role_id = 7                                                \n". 
             "   WHERE   1                                                                            \n".
             "               and p.movetotrash = 0                                                     \n".
             "   ORDER by p.pnum                                                                       \n";
        }
        
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getBillbyHospitalbyDateRange.txt', $sql);   
        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public static function getBillbyPathologistbyDateRange($conn,$patho_id, $startdate,$enddate) {

    if($GLOBALS['isBillByAcceptDate']){
        $sql="SELECT                                                                                   \n". 
             "#*,                                                                                      \n".
             "h.id as hid, b.id as bid, p.id as pid, p.pnum as p_sn,  job_pathologist.id as job_id,    \n".
             "CONCAT(p.ppre_name,p.pname,' ',p.plastname) as patient_name,                             \n".
             "DATE(p.date_1000) as admit_date,                                                         \n".
             "p.phospital_num as hospital_num,                                                         \n".
             "CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as clinicien_name,               \n".
             "h.hospital as h_hospital,                                                                \n".
             "CONCAT(job_cytologist.name,' ',job_cytologist.lastname) as cytologist_name,           \n".
             "CONCAT(job_pathologist.name,' ',job_pathologist.lastname) as pathologist_name,           \n".
             "b.code_description as b_code,                                                            \n".
             "b.description as b_description,                                                          \n".
             "b.cost as b_cost,                                                                        \n".
             "s.service_type_bill as s_service_type                                                    \n".
             "FROM patient as p                                                                        \n".
             "   JOIN service_billing as b on  b.patient_id = p.id                                     \n".
             "   JOIN hospital as h ON p.phospital_id = h.id                                           \n".
             "   JOIN service_type as s ON b.slide_type = s.id                                         \n".
             "   JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                    \n".
             "   JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                 \n";
             if( ! ((int)$patho_id == -1) ){
                $sql.="       and job_pathologist.user_id = {$patho_id}                                \n";
             }
        $sql.="and job_pathologist.job_role_id = 5                                                     \n".
             "   LEFT JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                   \n".   
             "       and job_cytologist.job_role_id = 7                                                \n". 
             "   WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'     \n".
             "               and p.movetotrash = 0                                                     \n".
             "   ORDER by p.pnum                                                                       \n";
        }
             
        if($GLOBALS['isBillByServiceDate']){
        $sql="SELECT                                                                                   \n". 
            "#*,                                                                                      \n".
            "h.id as hid, b.id as bid, p.id as pid, p.pnum as p_sn,  job_pathologist.id as job_id,    \n".
            "CONCAT(p.ppre_name,p.pname,' ',p.plastname) as patient_name,                             \n".
            "DATE(p.date_1000) as admit_date,                                                         \n".
            "p.phospital_num as hospital_num,                                                         \n".
            "CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as clinicien_name,               \n".
            "h.hospital as h_hospital,                                                                \n".
            "CONCAT(job_cytologist.name,' ',job_cytologist.lastname) as cytologist_name,              \n".
            "CONCAT(job_pathologist.name,' ',job_pathologist.lastname) as pathologist_name,           \n".
            "b.code_description as b_code,                                                            \n".
            "b.description as b_description,                                                          \n".
            "b.cost as b_cost,                                                                        \n".
            "s.service_type_bill as s_service_type                                                    \n".
            "FROM patient as p                                                                        \n".
            "   JOIN service_billing as b on  b.patient_id = p.id                                     \n".
            "     and date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'   \n".
            "   JOIN hospital as h ON p.phospital_id = h.id                                           \n".
            "   JOIN service_type as s ON b.slide_type = s.id                                         \n".
            "   JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                    \n".
            "   JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                      \n";
            if( ! ((int)$patho_id == -1) ){
               $sql.="       and job_pathologist.user_id = {$patho_id}                                \n";
            }
       $sql.=" and job_pathologist.job_role_id = 5                                                     \n".
            "   LEFT JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                   \n".   
            "       and job_cytologist.job_role_id = 7                                                \n". 
            "   WHERE   1     \n".
            "               and p.movetotrash = 0                                                     \n".
            "   ORDER by p.pnum                                                                       \n";

        }
             
             
             
             
             
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getBillbyPathologistbyDateRange.txt', $sql);   
//hid	bid	pid	p_sn	job_id	patient_name	admit_date	hospital_num	clinicien_name	h_hospital cytologist_name	pathologist_name	b_code	b_description	b_cost                                   s_service_type
//21	157	410	CN2400001	771	นางวรัญณพัชร พาทา	1/1/2024	117865	กฤติคุณ  ตระกูลสุขรัตน์	โรงพยาบาลหล่มสัก- null	อภิชาติ ชุมทอง	38301	Non-Gynecological specimen (Fluid, FNA) 4 slide	500      ค่าตรวจ
//20	156	411	CN2400002	774	น.ส.ปราณี เพ็ชรอำไพ	1/1/2024	23176	กมเลศวร์ จุลบุตร	โรงพยาบาลพิจิตร null	อภิชาติ ชุมทอง	38301	Non-Gynecological specimen (Fluid, FNA) 4 slide	500      ค่าตรวจ
//20	432	645	CN2400026	1441	นายประทีป คุ้มดำรงค์	1/5/2024	316146	พระทอง  สมบูรณ์เอนก	โรงพยาบาลพิจิตร null	อภิชาติ ชุมทอง	38301	Non-Gynecological specimen (Fluid, FNA) 4 slide	500      ค่าตรวจ

        }      
        
        $results = $conn->query($sql);
        

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public static function getBillbyCytologistbyDateRange($conn,$cytologist_id, $startdate,$enddate) {

        if($GLOBALS['isBillByAcceptDate']){
            $sql="SELECT                                                                                   \n". 
                 "#*,                                                                                      \n".
                 "h.id as hid, b.id as bid, p.id as pid, p.pnum as p_sn,  job_pathologist.id as job_id,    \n".
                 "CONCAT(p.ppre_name,p.pname,' ',p.plastname) as patient_name,                             \n".
                 "DATE(p.date_1000) as admit_date,                                                         \n".
                 "p.phospital_num as hospital_num,                                                         \n".
                 "CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as clinicien_name,               \n".
                 "h.hospital as h_hospital,                                                                \n".
                 "CONCAT(job_cytologist.name,' ',job_cytologist.lastname) as cytologist_name,           \n".
                 "CONCAT(job_pathologist.name,' ',job_pathologist.lastname) as pathologist_name,           \n".
                 "b.code_description as b_code,                                                            \n".
                 "b.description as b_description,                                                          \n".
                 "b.cost as b_cost,                                                                        \n".
                 "s.service_type_bill as s_service_type                                                    \n".
                 "FROM patient as p                                                                        \n".
                 "   JOIN service_billing as b on  b.patient_id = p.id                                     \n".
                 "   JOIN hospital as h ON p.phospital_id = h.id                                           \n".
                 "   JOIN service_type as s ON b.slide_type = s.id                                         \n".
                 "   JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                    \n".
                 "   LEFT JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                  \n".
                 "             and job_pathologist.job_role_id = 5                                          \n".
                 "   JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                         \n";
                 if( ! ((int)$cytologist_id == -1) ){
                    $sql.="           and job_cytologist.user_id = {$cytologist_id}                         \n";
                 }
            $sql.="       and job_cytologist.job_role_id = 7                                                \n". 
                 "   WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'     \n".
                 "               and p.movetotrash = 0                                                     \n".
                 "   ORDER by p.pnum                                                                       \n";
        }

        if($GLOBALS['isBillByServiceDate']){
            $sql="SELECT                                                                                   \n". 
                 "#*,                                                                                      \n".
                 "h.id as hid, b.id as bid, p.id as pid, p.pnum as p_sn,  job_pathologist.id as job_id,    \n".
                 "CONCAT(p.ppre_name,p.pname,' ',p.plastname) as patient_name,                             \n".
                 "DATE(p.date_1000) as admit_date,                                                         \n".
                 "p.phospital_num as hospital_num,                                                         \n".
                 "CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as clinicien_name,               \n".
                 "h.hospital as h_hospital,                                                                \n".
                 "CONCAT(job_cytologist.name,' ',job_cytologist.lastname) as cytologist_name,           \n".
                 "CONCAT(job_pathologist.name,' ',job_pathologist.lastname) as pathologist_name,           \n".
                 "b.code_description as b_code,                                                            \n".
                 "b.description as b_description,                                                          \n".
                 "b.cost as b_cost,                                                                        \n".
                 "s.service_type_bill as s_service_type                                                    \n".
                 "FROM patient as p                                                                        \n".
                 "   JOIN service_billing as b on  b.patient_id = p.id                                     \n".
                 "        and date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'   \n".
                 "   JOIN hospital as h ON p.phospital_id = h.id                                           \n".
                 "   JOIN service_type as s ON b.slide_type = s.id                                         \n".
                 "   JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                    \n".
                 "   LEFT JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                  \n".
                 "             and job_pathologist.job_role_id = 5                                          \n".
                 "   JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                          \n";
                 if( ! ((int)$cytologist_id == -1) ){
                    $sql.="           and job_cytologist.user_id = {$cytologist_id}                         \n";
                 }
            $sql.="       and job_cytologist.job_role_id = 7                                                \n". 
                 "   WHERE   1                                                                             \n".
                 "               and p.movetotrash = 0                                                     \n".
                 "   ORDER by p.pnum                                                                       \n";
        }

        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getBillbyCytologistbyDateRange.txt', $sql);   
//hid	bid	pid	p_sn	job_id	patient_name	admit_date	hospital_num	clinicien_name	h_hospital cytologist_name	pathologist_name	b_code	b_description	b_cost                                   s_service_type
//21	157	410	CN2400001	771	นางวรัญณพัชร พาทา	1/1/2024	117865	กฤติคุณ  ตระกูลสุขรัตน์	โรงพยาบาลหล่มสัก- null	อภิชาติ ชุมทอง	38301	Non-Gynecological specimen (Fluid, FNA) 4 slide	500      ค่าตรวจ
//20	156	411	CN2400002	774	น.ส.ปราณี เพ็ชรอำไพ	1/1/2024	23176	กมเลศวร์ จุลบุตร	โรงพยาบาลพิจิตร null	อภิชาติ ชุมทอง	38301	Non-Gynecological specimen (Fluid, FNA) 4 slide	500      ค่าตรวจ
//20	432	645	CN2400026	1441	นายประทีป คุ้มดำรงค์	1/5/2024	316146	พระทอง  สมบูรณ์เอนก	โรงพยาบาลพิจิตร null	อภิชาติ ชุมทอง	38301	Non-Gynecological specimen (Fluid, FNA) 4 slide	500      ค่าตรวจ

        }      
        
        $results = $conn->query($sql);
        

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public static function getBillbyHospitalbyDateRangeGroupBySN($conn,$hospital_id, $startdate,$enddate, $limit = 0) {
        
        if($GLOBALS['isBillByAcceptDate']){
            $sql="select                                                                                                                             \n".
                 "#* ,                                                                                                                                \n".
                 "IFNULL(aa_p_sn, bb_p_sn) as p_sn,                                                                                                  \n".
                 "IFNULL(aa_p_hn, bb_p_hn) as p_hn,                                                                                                  \n".
                 "IFNULL(aa_p_admit_date, bb_p_admit_date) as p_admit_date,                                                                          \n".
                 "IFNULL(aa_patient_name, bb_patient_name) as patient_name,                                                                          \n".
                 "IFNULL(aa_clinicien_name, bb_clinicien_name) as clinicien_name,                                                                    \n".
                 "                                                                                                                                   \n".
                 "IFNULL(aa_b_description_concat_nm, '') as b_description_concat_nm,                                                                 \n".
                 "IFNULL(bb_b_description_concat_sp, '') as b_description_concat_sp,                                                                 \n".
                 "CONCAT_WS(' / ',IFNULL(aa_b_description_concat_nm, ''),IFNULL(bb_b_description_concat_sp, '')) as b_description_concat_all,        \n".
                 "                                                                                                                                   \n".
                 "IFNULL(aa_b_cost_sum_nm, 0) as b_cost_sum_nm,                                                                                      \n".
                 "IFNULL(bb_b_cost_sum_sp, 0) as b_cost_sum_sp,                                                                                      \n".
                 "(IFNULL(aa_b_cost_sum_nm,0) + IFNULL(bb_b_cost_sum_sp, 0) ) as b_cost_sum_all                                                      \n".
                 "                                                                                                                                   \n".
                 "from                                                                                                                               \n".
                 "                                                                                                                                   \n".
                 "(                                                                                                                                  \n".
                 "    (                                                                                                                              \n".
                 "    select                                                                                                                         \n".
                 "    *                                                                                                                              \n".
                 "    FROM                                                                                                                           \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as aa_p_sn,                                                                                                     \n".
                 "            p.phospital_num as aa_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                             \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id and p.movetotrash = 0                                                            \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON   p.phospital_id = h.id                                                                              \n";
                 if( ! ((int)$hospital_id == -1) ){
                    $sql.= " and   p.phospital_id = $hospital_id                                                                                       \n";
                 }   
          $sql.= "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}'and date(p.date_1000) <= '{$enddate}'                                           \n".
                 "            and st.service_typea_id = 1                                                                                            \n".
                 "            #and p.pnum = 'CN2400032'                                                                                              \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as aa                                                                                                                    \n".
                 "    LEFT JOIN                                                                                                                      \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as bb_p_sn,                                                                                                     \n".
                 "            p.phospital_num as bb_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                             \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                          \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                \n";
                 if( ! ((int)$hospital_id == -1) ){
                         $sql.= "              and  p.phospital_id = $hospital_id                                                                     \n";
                 }        
           $sql.="        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                          \n".
                 "            and st.service_typea_id = 2                                                                                            \n".
                 "            #and p.pnum = 'CN2400032'                                                                                              \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as bb                                                                                                                    \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                            \n".
                 "        and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                               \n".
                 "    )                                                                                                                              \n".
                 "UNION                                                                                                                              \n".
                 "    (                                                                                                                              \n".
                 "    select                                                                                                                         \n".
                 "    *                                                                                                                              \n".
                 "    FROM                                                                                                                           \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as aa_p_sn,                                                                                                     \n".
                 "            p.phospital_num as aa_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                             \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                          \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON  p.phospital_id = h.id                                                                               \n";
                 if( ! ((int)$hospital_id == -1) ){
                         $sql.= "and  p.phospital_id = $hospital_id                                                                                   \n";
                 }        
         $sql.=  "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}'and date(p.date_1000) <= '{$enddate}'                                           \n".
                 "            and st.service_typea_id = 1                                                                                            \n".
                 "            #and p.pnum = 'CN2400032'                                                                                              \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as aa                                                                                                                    \n".
                 "    RIGHT JOIN                                                                                                                     \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as bb_p_sn,                                                                                                     \n".
                 "            p.phospital_num as bb_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                             \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                         \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON  p.phospital_id = h.id                                                                               \n";
                 if( ! ((int)$hospital_id == -1) ){
                      $sql.= "and   p.phospital_id = $hospital_id                                                                                     \n";
                 }        
           $sql.="        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                       \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                          \n".
                 "        and st.service_typea_id = 2                                                                                                \n".
                 "        #and p.pnum = 'CN2400032'                                                                                                  \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as bb                                                                                                                    \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                            \n".
                 "       and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                \n".
                 "    )                                                                                                                              \n".
                 ") as a                                                                                                                             \n".
                 "ORDER by p_sn ASC                                                                                                                  \n";
        }   
        
        if($GLOBALS['isBillByServiceDate']){
            $sql="select                                                                                                                             \n".
                 "#* ,                                                                                                                                \n".
                 "IFNULL(aa_p_sn, bb_p_sn) as p_sn,                                                                                                  \n".
                 "IFNULL(aa_p_hn, bb_p_hn) as p_hn,                                                                                                  \n".
                 "IFNULL(aa_p_admit_date, bb_p_admit_date) as p_admit_date,                                                                          \n".
                 "IFNULL(aa_patient_name, bb_patient_name) as patient_name,                                                                          \n".
                 "IFNULL(aa_clinicien_name, bb_clinicien_name) as clinicien_name,                                                                    \n".
                 "                                                                                                                                   \n".
                 "IFNULL(aa_b_description_concat_nm, '') as b_description_concat_nm,                                                                 \n".
                 "IFNULL(bb_b_description_concat_sp, '') as b_description_concat_sp,                                                                 \n".
                 "CONCAT_WS(' / ',IFNULL(aa_b_description_concat_nm, ''),IFNULL(bb_b_description_concat_sp, '')) as b_description_concat_all,        \n".
                 "                                                                                                                                   \n".
                 "IFNULL(aa_b_cost_sum_nm, 0) as b_cost_sum_nm,                                                                                      \n".
                 "IFNULL(bb_b_cost_sum_sp, 0) as b_cost_sum_sp,                                                                                      \n".
                 "(IFNULL(aa_b_cost_sum_nm,0) + IFNULL(bb_b_cost_sum_sp, 0) ) as b_cost_sum_all                                                      \n".
                 "                                                                                                                                   \n".
                 "from                                                                                                                               \n".
                 "                                                                                                                                   \n".
                 "(                                                                                                                                  \n".
                 "    (                                                                                                                              \n".
                 "    select                                                                                                                         \n".
                 "    *                                                                                                                              \n".
                 "    FROM                                                                                                                           \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as aa_p_sn,                                                                                                     \n".
                 "            p.phospital_num as aa_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                             \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id and p.movetotrash = 0                                                            \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON   p.phospital_id = h.id                                                                              \n";
                 if( ! ((int)$hospital_id == -1) ){
                    $sql.= " and   p.phospital_id = $hospital_id                                                                                       \n";
                 }   
          $sql.= "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
                 "        WHERE   date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                           \n".
                 "            and st.service_typea_id = 1                                                                                            \n".
                 "            #and p.pnum = 'CN2400032'                                                                                              \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as aa                                                                                                                    \n".
                 "    LEFT JOIN                                                                                                                      \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as bb_p_sn,                                                                                                     \n".
                 "            p.phospital_num as bb_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                             \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                          \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                \n";
                 if( ! ((int)$hospital_id == -1) ){
                         $sql.= "              and  p.phospital_id = $hospital_id                                                                     \n";
                 }        
           $sql.="        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
                 "        WHERE    date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                              \n".
                 "            and st.service_typea_id = 2                                                                                            \n".
                 "            #and p.pnum = 'CN2400032'                                                                                              \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as bb                                                                                                                    \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                            \n".
                 "        and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                               \n".
                 "    )                                                                                                                              \n".
                 "UNION                                                                                                                              \n".
                 "    (                                                                                                                              \n".
                 "    select                                                                                                                         \n".
                 "    *                                                                                                                              \n".
                 "    FROM                                                                                                                           \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as aa_p_sn,                                                                                                     \n".
                 "            p.phospital_num as aa_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                             \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                          \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON  p.phospital_id = h.id                                                                               \n";
                 if( ! ((int)$hospital_id == -1) ){
                         $sql.= "and  p.phospital_id = $hospital_id                                                                                   \n";
                 }        
         $sql.=  "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
                 "        WHERE    date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                               \n".
                 "            and st.service_typea_id = 1                                                                                            \n".
                 "            #and p.pnum = 'CN2400032'                                                                                              \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as aa                                                                                                                    \n".
                 "    RIGHT JOIN                                                                                                                     \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as bb_p_sn,                                                                                                     \n".
                 "            p.phospital_num as bb_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                             \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                         \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON  p.phospital_id = h.id                                                                               \n";
                 if( ! ((int)$hospital_id == -1) ){
                      $sql.= "and   p.phospital_id = $hospital_id                                                                                     \n";
                 }        
           $sql.="        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                       \n".
                 "        WHERE    date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                     \n".
                 "        and st.service_typea_id = 2                                                                                                \n".
                 "        #and p.pnum = 'CN2400032'                                                                                                  \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as bb                                                                                                                    \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                            \n".
                 "       and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                \n".
                 "    )                                                                                                                              \n".
                 ") as a                                                                                                                             \n".
                 "ORDER by p_sn ASC                                                                                                                  \n";
                 
        }
        
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getBillbyHospitalbyDateRangeGroupBySN.txt', $sql);   
        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    public static function getBillbyHospitalbyDateRangeGroupBySN_2($conn,$hospital_id, $startdate,$enddate, $limit = 0) {
        /* 
         * รูปแบบตารางที่ต้องการ
            ลำดับที่ เลขที่งาน     วันที่รับ       ผู้ป่วย  เลขที่โรงพยาบาล แพทย์ผู้ส่งตรวจ รายการ           ค่าบริการ ค่าตรวจพิเศษ รวม
            1     CN2600090 2026-01-19 นางเอบี	 565242      แพทซี       Fluid_cytology  500     0        500
            2     CN2600091 2026-01-19 นายเอซี     639969      แพทย์แอล    Fluid_cytology  500     0        500
         
         * 
         * 
         * 
         * Example SQL script
        select                                                                                                                             
        #* ,                                                                                                                                
        IFNULL(aa_p_sn, bb_p_sn) as p_sn,                                                                                                  
        IFNULL(aa_p_hn, bb_p_hn) as p_hn,                                                                                                  
        IFNULL(aa_p_admit_date, bb_p_admit_date) as p_admit_date,                                                                          
        IFNULL(aa_patient_name, bb_patient_name) as patient_name,                                                                          
        IFNULL(aa_clinicien_name, bb_clinicien_name) as clinicien_name,                                                                    

        IFNULL(aa_b_description_concat_nm, '') as b_description_concat_nm,                                                                 
        IFNULL(bb_b_description_concat_sp, '') as b_description_concat_sp,                                                                 
        CONCAT_WS(' / ',IFNULL(aa_b_description_concat_nm, ''),IFNULL(bb_b_description_concat_sp, '')) as b_description_concat_all,        

        IFNULL(aa_b_cost_sum_nm, 0) as b_cost_sum_nm,                                                                                      
        IFNULL(bb_b_cost_sum_sp, 0) as b_cost_sum_sp,                                                                                      
        (IFNULL(aa_b_cost_sum_nm,0) + IFNULL(bb_b_cost_sum_sp, 0) ) as b_cost_sum_all                                                      

        from                                                                                                                               

        (                                                                                                                                  
            (                                                                                                                              
            select                                                                                                                         
            *                                                                                                                              
            FROM                                                                                                                           
                (                                                                                                                          
                SELECT                                                                                                                     
                    #*,                                                                                                                    
                    #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              
                    #st.service_typea_bill as st_service_typea_bill,                                                                       
                    p.pnum as aa_p_sn,                                                                                                     
                    p.phospital_num as aa_p_hn,                                                                                            
                    DATE(p.date_1000) as aa_p_admit_date,                                                                                  
                    CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                        
                    CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                          
                    GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                             
                    SUM(b.cost) as aa_b_cost_sum_nm                                                                                        
                FROM service_billing as b                                                                                                  
                JOIN patient as p on  b.patient_id = p.id and p.movetotrash = 0                                                            
                JOIN service_type as st ON st.id = b.slide_type                                                                            
                JOIN hospital as h ON   p.phospital_id = h.id                                                                              
         and   p.phospital_id = 9                                                                                       
                JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         
                WHERE   date(p.date_1000) >= '2026-01-01'and date(p.date_1000) <= '2026-01-31'                                           
                    and st.service_typea_id = 1                                                                                            
                    #and p.pnum = 'CN2400032'                                                                                              
                GROUP BY p.pnum                                                                                                            
                ORDER by p.pnum                                                                                                            
                ) as aa                                                                                                                    
            LEFT JOIN                                                                                                                      
                (                                                                                                                          
                SELECT                                                                                                                     
                    #*,                                                                                                                    
                    #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              
                    #st.service_typea_bill as st_service_typea_bill,                                                                       
                    p.pnum as bb_p_sn,                                                                                                     
                    p.phospital_num as bb_p_hn,                                                                                            
                    DATE(p.date_1000) as bb_p_admit_date,                                                                                  
                    CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                        
                    CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                          
                    GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                             
                    SUM(b.cost) as bb_b_cost_sum_sp                                                                                        
                FROM service_billing as b                                                                                                  
                JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                          
                JOIN service_type as st ON st.id = b.slide_type                                                                            
                JOIN hospital as h ON p.phospital_id = h.id                                                                                
                      and  p.phospital_id = 9                                                                     
                JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         
                WHERE   date(p.date_1000) >= '2026-01-01' and date(p.date_1000) <= '2026-01-31'                                          
                    and st.service_typea_id = 2                                                                                            
                    #and p.pnum = 'CN2400032'                                                                                              
                GROUP BY p.pnum                                                                                                            
                ORDER by p.pnum                                                                                                            
                ) as bb                                                                                                                    
            ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                            
                and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                               
            )                                                                                                                              
        UNION                                                                                                                              
            (                                                                                                                              
            select                                                                                                                         
            *                                                                                                                              
            FROM                                                                                                                           
                (                                                                                                                          
                SELECT                                                                                                                     
                    #*,                                                                                                                    
                    #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              
                    #st.service_typea_bill as st_service_typea_bill,                                                                       
                    p.pnum as aa_p_sn,                                                                                                     
                    p.phospital_num as aa_p_hn,                                                                                            
                    DATE(p.date_1000) as aa_p_admit_date,                                                                                  
                    CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                        
                    CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                          
                    GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                             
                    SUM(b.cost) as aa_b_cost_sum_nm                                                                                        
                FROM service_billing as b                                                                                                  
                JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                          
                JOIN service_type as st ON st.id = b.slide_type                                                                            
                JOIN hospital as h ON  p.phospital_id = h.id                                                                               
        and  p.phospital_id = 9                                                                                   
                JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         
                WHERE   date(p.date_1000) >= '2026-01-01'and date(p.date_1000) <= '2026-01-31'                                           
                    and st.service_typea_id = 1                                                                                            
                    #and p.pnum = 'CN2400032'                                                                                              
                GROUP BY p.pnum                                                                                                            
                ORDER by p.pnum                                                                                                            
                ) as aa                                                                                                                    
            RIGHT JOIN                                                                                                                     
                (                                                                                                                          
                SELECT                                                                                                                     
                    #*,                                                                                                                    
                    #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              
                    #st.service_typea_bill as st_service_typea_bill,                                                                       
                    p.pnum as bb_p_sn,                                                                                                     
                    p.phospital_num as bb_p_hn,                                                                                            
                    DATE(p.date_1000) as bb_p_admit_date,                                                                                  
                    CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                        
                    CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                          
                    GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                             
                    SUM(b.cost) as bb_b_cost_sum_sp                                                                                        
                FROM service_billing as b                                                                                                  
                JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                         
                JOIN service_type as st ON st.id = b.slide_type                                                                            
                JOIN hospital as h ON  p.phospital_id = h.id                                                                               
        and   p.phospital_id = 9                                                                                     
                JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                       
                WHERE   date(p.date_1000) >= '2026-01-01' and date(p.date_1000) <= '2026-01-31'                                          
                and st.service_typea_id = 2                                                                                                
                #and p.pnum = 'CN2400032'                                                                                                  
                GROUP BY p.pnum                                                                                                            
                ORDER by p.pnum                                                                                                            
                ) as bb                                                                                                                    
            ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                            
               and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                
            )                                                                                                                              
        ) as a                                                                                                                             
        ORDER by p_sn ASC                                                                                                                  

         *          */

        $sql="select                                                                                                                             \n".
             "#* ,                                                                                                                                \n".
             "IFNULL(aa_p_sn, bb_p_sn) as p_sn,                                                                                                  \n".
             "IFNULL(aa_p_hn, bb_p_hn) as p_hn,                                                                                                  \n".
             "IFNULL(aa_p_admit_date, bb_p_admit_date) as p_admit_date,                                                                          \n".
             "IFNULL(aa_patient_name, bb_patient_name) as patient_name,                                                                          \n".
             "IFNULL(aa_clinicien_name, bb_clinicien_name) as clinicien_name,                                                                    \n".
             "                                                                                                                                   \n".
             "IFNULL(aa_b_description_concat_nm, '') as b_description_concat_nm,                                                                 \n".
             "IFNULL(bb_b_description_concat_sp, '') as b_description_concat_sp,                                                                 \n".
             "CONCAT_WS(' / ',IFNULL(aa_b_description_concat_nm, ''),IFNULL(bb_b_description_concat_sp, '')) as b_description_concat_all,        \n".
             "                                                                                                                                   \n".
             "IFNULL(aa_b_cost_sum_nm, 0) as b_cost_sum_nm,                                                                                      \n".
             "IFNULL(bb_b_cost_sum_sp, 0) as b_cost_sum_sp,                                                                                      \n".
             "(IFNULL(aa_b_cost_sum_nm,0) + IFNULL(bb_b_cost_sum_sp, 0) ) as b_cost_sum_all                                                      \n".
             "                                                                                                                                   \n".
             "from                                                                                                                               \n".
             "                                                                                                                                   \n".
             "(                                                                                                                                  \n".
             "    (                                                                                                                              \n".
             "    select                                                                                                                         \n".
             "    *                                                                                                                              \n".
             "    FROM                                                                                                                           \n".
             "        (                                                                                                                          \n".
             "        SELECT                                                                                                                     \n".
             "            #*,                                                                                                                    \n".
             "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
             "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
             "            p.pnum as aa_p_sn,                                                                                                     \n".
             "            p.phospital_num as aa_p_hn,                                                                                            \n".
             "            DATE(p.date_1000) as aa_p_admit_date,                                                                                  \n".
             "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                        \n".
             "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                          \n".
             "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                             \n".
             "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                        \n".
             "        FROM service_billing as b                                                                                                  \n".
             "        JOIN patient as p on  b.patient_id = p.id and p.movetotrash = 0                                                            \n".
             "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
             "        JOIN hospital as h ON   p.phospital_id = h.id                                                                              \n";
             if( ! ((int)$hospital_id == -1) ){
                $sql.= " and   p.phospital_id = $hospital_id                                                                                       \n";
             }   
      $sql.= "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
             "        WHERE   date(p.date_1000) >= '{$startdate}'and date(p.date_1000) <= '{$enddate}'                                           \n".
             "            and st.service_typea_id = 1                                                                                            \n".
             "            #and p.pnum = 'CN2400032'                                                                                              \n".
             "        GROUP BY p.pnum                                                                                                            \n".
             "        ORDER by p.pnum                                                                                                            \n".
             "        ) as aa                                                                                                                    \n".
             "    LEFT JOIN                                                                                                                      \n".
             "        (                                                                                                                          \n".
             "        SELECT                                                                                                                     \n".
             "            #*,                                                                                                                    \n".
             "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
             "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
             "            p.pnum as bb_p_sn,                                                                                                     \n".
             "            p.phospital_num as bb_p_hn,                                                                                            \n".
             "            DATE(p.date_1000) as bb_p_admit_date,                                                                                  \n".
             "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                        \n".
             "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                          \n".
             "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                             \n".
             "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                        \n".
             "        FROM service_billing as b                                                                                                  \n".
             "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                          \n".
             "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
             "        JOIN hospital as h ON p.phospital_id = h.id                                                                                \n";
             if( ! ((int)$hospital_id == -1) ){
                     $sql.= "              and  p.phospital_id = $hospital_id                                                                     \n";
             }        
       $sql.="        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
             "        WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                          \n".
             "            and st.service_typea_id = 2                                                                                            \n".
             "            #and p.pnum = 'CN2400032'                                                                                              \n".
             "        GROUP BY p.pnum                                                                                                            \n".
             "        ORDER by p.pnum                                                                                                            \n".
             "        ) as bb                                                                                                                    \n".
             "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                            \n".
             "        and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                               \n".
             "    )                                                                                                                              \n".
             "UNION                                                                                                                              \n".
             "    (                                                                                                                              \n".
             "    select                                                                                                                         \n".
             "    *                                                                                                                              \n".
             "    FROM                                                                                                                           \n".
             "        (                                                                                                                          \n".
             "        SELECT                                                                                                                     \n".
             "            #*,                                                                                                                    \n".
             "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
             "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
             "            p.pnum as aa_p_sn,                                                                                                     \n".
             "            p.phospital_num as aa_p_hn,                                                                                            \n".
             "            DATE(p.date_1000) as aa_p_admit_date,                                                                                  \n".
             "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                        \n".
             "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                          \n".
             "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                             \n".
             "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                        \n".
             "        FROM service_billing as b                                                                                                  \n".
             "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                          \n".
             "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
             "        JOIN hospital as h ON  p.phospital_id = h.id                                                                               \n";
             if( ! ((int)$hospital_id == -1) ){
                     $sql.= "and  p.phospital_id = $hospital_id                                                                                   \n";
             }        
     $sql.=  "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
             "        WHERE   date(p.date_1000) >= '{$startdate}'and date(p.date_1000) <= '{$enddate}'                                           \n".
             "            and st.service_typea_id = 1                                                                                            \n".
             "            #and p.pnum = 'CN2400032'                                                                                              \n".
             "        GROUP BY p.pnum                                                                                                            \n".
             "        ORDER by p.pnum                                                                                                            \n".
             "        ) as aa                                                                                                                    \n".
             "    RIGHT JOIN                                                                                                                     \n".
             "        (                                                                                                                          \n".
             "        SELECT                                                                                                                     \n".
             "            #*,                                                                                                                    \n".
             "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
             "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
             "            p.pnum as bb_p_sn,                                                                                                     \n".
             "            p.phospital_num as bb_p_hn,                                                                                            \n".
             "            DATE(p.date_1000) as bb_p_admit_date,                                                                                  \n".
             "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                        \n".
             "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                          \n".
             "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                             \n".
             "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                        \n".
             "        FROM service_billing as b                                                                                                  \n".
             "        JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                         \n".
             "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
             "        JOIN hospital as h ON  p.phospital_id = h.id                                                                               \n";
             if( ! ((int)$hospital_id == -1) ){
                  $sql.= "and   p.phospital_id = $hospital_id                                                                                     \n";
             }        
       $sql.="        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                       \n".
             "        WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                          \n".
             "        and st.service_typea_id = 2                                                                                                \n".
             "        #and p.pnum = 'CN2400032'                                                                                                  \n".
             "        GROUP BY p.pnum                                                                                                            \n".
             "        ORDER by p.pnum                                                                                                            \n".
             "        ) as bb                                                                                                                    \n".
             "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                            \n".
             "       and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                \n".
             "    )                                                                                                                              \n".
             ") as a                                                                                                                             \n".
             "ORDER by p_sn ASC                                                                                                                  \n";

        
        Util::writeFile('getBillbyHospitalbyDateRangeGroupBySN_2.txt', $sql);   
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getBillbyHospitalbyDateRangeGroupBySN_2.txt', $sql);   
        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
        /*
        Output Example
p_sn    1    p_hn      p_admit_date    patient_name    clinicien_name    b_description_concat_nm    b_description_concat_sp    b_description_concat_all    b_cost_sum_nm    b_cost_sum_sp    b_cost_sum_all
CN2600002    404996    1/5/2026    นางเอ บี     Fluid cytology        Fluid cytology /    500    0    500
CN2600003    860489    1/5/2026    นางซี ดี     Fluid cytology        Fluid cytology /    500    0    500
CN2600004    757000    1/5/2026    พระอี เอฟ     Fluid cytology        Fluid cytology /    500    0    500
CN2600005    80158     1/5/2026    นางจี เฮช     Fluid cytology        Fluid cytology /    500    0    500
CN2600006    64190     1/5/2026    นางไอ เจ      Fluid cytology / cell block    CK7 / CK20 / HepPar1 / Glypican-3 / CK19    Fluid cytology / cell block / CK7 / CK20 / HepPar1...    1000    4000    5000
CN2600008    286232    1/6/2026    นายเค แอล     Fluid cytology        Fluid cytology /    500    0    500

         *          */
    }
    
    
    public static function getBillbyHospitalbyDateRangeGroupBySN_2_subarray_nm($conn,$hospital_id, $startdate,$enddate,$sn, $limit = 0) {
        

        $sql="SELECT                                                                                                                     
                    #*,                                                                                                                    
                    #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              
                    #st.service_typea_bill as st_service_typea_bill,                                                                       
                    p.pnum as p_sn,                                                                                                     
                    #p.phospital_num as aa_p_hn,                                                                                            
                    DATE(p.date_1000) as aa_p_admit_date,    
                    b.description as b_description,
                    b.cost as b_cost_sum_nm,
                    0 as b_cost_sum_sp,
                    b.cost as b_cost_sum_all
                FROM service_billing as b                                                                                                  
                JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                          
                JOIN service_type as st ON st.id = b.slide_type                                                                            
                JOIN hospital as h ON  p.phospital_id = h.id                                                                               
                and  p.phospital_id = $hospital_id                                                                                   
                JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         
                WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                            
                    and st.service_typea_id = 1                                                                                            
                    and p.pnum = '$sn';";

        
        Util::writeFile('getBillbyHospitalbyDateRangeGroupBySN_2_subarray_nm.txt', $sql);   
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getBillbyHospitalbyDateRangeGroupBySN_2_subarray_nm.txt', $sql);   
        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
        /*
        Output Example
p_sn    1    p_hn      p_admit_date    patient_name    clinicien_name    b_description_concat_nm    b_description_concat_sp    b_description_concat_all    b_cost_sum_nm    b_cost_sum_sp    b_cost_sum_all
CN2600002    404996    1/5/2026    นางเอ บี     Fluid cytology        Fluid cytology /    500    0    500
CN2600003    860489    1/5/2026    นางซี ดี     Fluid cytology        Fluid cytology /    500    0    500
CN2600004    757000    1/5/2026    พระอี เอฟ     Fluid cytology        Fluid cytology /    500    0    500
CN2600005    80158     1/5/2026    นางจี เฮช     Fluid cytology        Fluid cytology /    500    0    500
CN2600006    64190     1/5/2026    นางไอ เจ      Fluid cytology / cell block    CK7 / CK20 / HepPar1 / Glypican-3 / CK19    Fluid cytology / cell block / CK7 / CK20 / HepPar1...    1000    4000    5000
CN2600008    286232    1/6/2026    นายเค แอล     Fluid cytology        Fluid cytology /    500    0    500

         *          */
    }
    
    public static function getBillbyHospitalbyDateRangeGroupBySN_2_subarray_sp($conn,$hospital_id, $startdate,$enddate,$sn, $limit = 0) {
        

        $sql="SELECT                                                                                                                     
                    #*,                                                                                                                    
                    #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              
                    #st.service_typea_bill as st_service_typea_bill,                                                                       
                    p.pnum as p_sn,                                                                                                     
                    #p.phospital_num as aa_p_hn,                                                                                            
                    DATE(p.date_1000) as aa_p_admit_date,   
                    b.description as b_description,
                    0 as b_cost_sum_nm,
                    b.cost as b_cost_sum_sp,
                    b.cost as b_cost_sum_all
                FROM service_billing as b                                                                                                  
                JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                          
                JOIN service_type as st ON st.id = b.slide_type                                                                            
                JOIN hospital as h ON  p.phospital_id = h.id                                                                               
                and  p.phospital_id = $hospital_id                                                                                   
                JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         
                WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                            
                    and st.service_typea_id = 2                                                                                            
                    and p.pnum = '$sn';";

        
        Util::writeFile('getBillbyHospitalbyDateRangeGroupBySN_2_subarray_sp.txt', $sql);   
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getBillbyHospitalbyDateRangeGroupBySN_2_subarray_sp.txt', $sql);   
        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
        /*
        Output Example
p_sn    1    p_hn      p_admit_date    patient_name    clinicien_name    b_description_concat_nm    b_description_concat_sp    b_description_concat_all    b_cost_sum_nm    b_cost_sum_sp    b_cost_sum_all
CN2600002    404996    1/5/2026    นางเอ บี     Fluid cytology        Fluid cytology /    500    0    500
CN2600003    860489    1/5/2026    นางซี ดี     Fluid cytology        Fluid cytology /    500    0    500
CN2600004    757000    1/5/2026    พระอี เอฟ     Fluid cytology        Fluid cytology /    500    0    500
CN2600005    80158     1/5/2026    นางจี เฮช     Fluid cytology        Fluid cytology /    500    0    500
CN2600006    64190     1/5/2026    นางไอ เจ      Fluid cytology / cell block    CK7 / CK20 / HepPar1 / Glypican-3 / CK19    Fluid cytology / cell block / CK7 / CK20 / HepPar1...    1000    4000    5000
CN2600008    286232    1/6/2026    นายเค แอล     Fluid cytology        Fluid cytology /    500    0    500

         *          */
    }
    
    
    public static function getBillbyHospitalbyDateRangeGroupBySNCount($conn,$hospital_id, $startdate,$enddate, $limit = 0) {
        
        if($GLOBALS['isBillByAcceptDate']){
         $sql =  "SELECT COUNT(*) as a_count                                                           \n".
                 "FROM                                                                                 \n".
                 "(                                                                                    \n".
                 "select                                                                                                                             \n".
                 "#* ,                                                                                                                                \n".
                 "IFNULL(aa_p_sn, bb_p_sn) as p_sn,                                                                                                  \n".
                 "IFNULL(aa_p_hn, bb_p_hn) as p_hn,                                                                                                  \n".
                 "IFNULL(aa_p_admit_date, bb_p_admit_date) as p_admit_date,                                                                          \n".
                 "IFNULL(aa_patient_name, bb_patient_name) as patient_name,                                                                          \n".
                 "IFNULL(aa_clinicien_name, bb_clinicien_name) as clinicien_name,                                                                    \n".
                 "                                                                                                                                   \n".
                 "IFNULL(aa_b_description_concat_nm, '') as b_description_concat_nm,                                                                 \n".
                 "IFNULL(bb_b_description_concat_sp, '') as b_description_concat_sp,                                                                 \n".
                 "CONCAT_WS(' / ',IFNULL(aa_b_description_concat_nm, ''),IFNULL(bb_b_description_concat_sp, '')) as b_description_concat_all,        \n".
                 "                                                                                                                                   \n".
                 "IFNULL(aa_b_cost_sum_nm, 0) as b_cost_sum_nm,                                                                                      \n".
                 "IFNULL(bb_b_cost_sum_sp, 0) as b_cost_sum_sp,                                                                                      \n".
                 "(IFNULL(aa_b_cost_sum_nm,0) + IFNULL(bb_b_cost_sum_sp, 0) ) as b_cost_sum_all                                                      \n".
                 "                                                                                                                                   \n".
                 "from                                                                                                                               \n".
                 "                                                                                                                                   \n".
                 "(                                                                                                                                  \n".
                 "    (                                                                                                                              \n".
                 "    select                                                                                                                         \n".
                 "    *                                                                                                                              \n".
                 "    FROM                                                                                                                           \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as aa_p_sn,                                                                                                     \n".
                 "            p.phospital_num as aa_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                             \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id and p.movetotrash = 0                                                            \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON   p.phospital_id = h.id                                                                              \n";
                 if( ! ((int)$hospital_id == -1) ){
                    $sql.= " and   p.phospital_id = $hospital_id                                                                                       \n";
                 }   
          $sql.= "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}'and date(p.date_1000) <= '{$enddate}'                                           \n".
                 "            and st.service_typea_id = 1                                                                                            \n".
                 "            #and p.pnum = 'CN2400032'                                                                                              \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as aa                                                                                                                    \n".
                 "    LEFT JOIN                                                                                                                      \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as bb_p_sn,                                                                                                     \n".
                 "            p.phospital_num as bb_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                             \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                          \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                \n";
                 if( ! ((int)$hospital_id == -1) ){
                         $sql.= "              and  p.phospital_id = $hospital_id                                                                     \n";
                 }        
           $sql.="        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                          \n".
                 "            and st.service_typea_id = 2                                                                                            \n".
                 "            #and p.pnum = 'CN2400032'                                                                                              \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as bb                                                                                                                    \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                            \n".
                 "        and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                               \n".
                 "    )                                                                                                                              \n".
                 "UNION                                                                                                                              \n".
                 "    (                                                                                                                              \n".
                 "    select                                                                                                                         \n".
                 "    *                                                                                                                              \n".
                 "    FROM                                                                                                                           \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as aa_p_sn,                                                                                                     \n".
                 "            p.phospital_num as aa_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                             \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                          \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON  p.phospital_id = h.id                                                                               \n";
                 if( ! ((int)$hospital_id == -1) ){
                         $sql.= "and  p.phospital_id = $hospital_id                                                                                   \n";
                 }        
         $sql.=  "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}'and date(p.date_1000) <= '{$enddate}'                                           \n".
                 "            and st.service_typea_id = 1                                                                                            \n".
                 "            #and p.pnum = 'CN2400032'                                                                                              \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as aa                                                                                                                    \n".
                 "    RIGHT JOIN                                                                                                                     \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as bb_p_sn,                                                                                                     \n".
                 "            p.phospital_num as bb_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                             \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                         \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON  p.phospital_id = h.id                                                                               \n";
                 if( ! ((int)$hospital_id == -1) ){
                      $sql.= "and   p.phospital_id = $hospital_id                                                                                     \n";
                 }        
           $sql.="        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                          \n".
                 "        and st.service_typea_id = 2                                                                                                \n".
                 "        #and p.pnum = 'CN2400032'                                                                                                  \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as bb                                                                                                                    \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                            \n".
                 "       and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                \n".
                 "    )                                                                                                                              \n".
                 ") as a                                                                                                                             \n".
                 ") as xx                                                                               \n";
                
        }
        
        if($GLOBALS['isBillByServiceDate']){
            
         $sql =  "SELECT COUNT(*) as a_count                                                           \n".
                 "FROM                                                                                 \n".
                 "(                                                                                    \n".
                 "select                                                                                                                             \n".
                 "#* ,                                                                                                                                \n".
                 "IFNULL(aa_p_sn, bb_p_sn) as p_sn,                                                                                                  \n".
                 "IFNULL(aa_p_hn, bb_p_hn) as p_hn,                                                                                                  \n".
                 "IFNULL(aa_p_admit_date, bb_p_admit_date) as p_admit_date,                                                                          \n".
                 "IFNULL(aa_patient_name, bb_patient_name) as patient_name,                                                                          \n".
                 "IFNULL(aa_clinicien_name, bb_clinicien_name) as clinicien_name,                                                                    \n".
                 "                                                                                                                                   \n".
                 "IFNULL(aa_b_description_concat_nm, '') as b_description_concat_nm,                                                                 \n".
                 "IFNULL(bb_b_description_concat_sp, '') as b_description_concat_sp,                                                                 \n".
                 "CONCAT_WS(' / ',IFNULL(aa_b_description_concat_nm, ''),IFNULL(bb_b_description_concat_sp, '')) as b_description_concat_all,        \n".
                 "                                                                                                                                   \n".
                 "IFNULL(aa_b_cost_sum_nm, 0) as b_cost_sum_nm,                                                                                      \n".
                 "IFNULL(bb_b_cost_sum_sp, 0) as b_cost_sum_sp,                                                                                      \n".
                 "(IFNULL(aa_b_cost_sum_nm,0) + IFNULL(bb_b_cost_sum_sp, 0) ) as b_cost_sum_all                                                      \n".
                 "                                                                                                                                   \n".
                 "from                                                                                                                               \n".
                 "                                                                                                                                   \n".
                 "(                                                                                                                                  \n".
                 "    (                                                                                                                              \n".
                 "    select                                                                                                                         \n".
                 "    *                                                                                                                              \n".
                 "    FROM                                                                                                                           \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as aa_p_sn,                                                                                                     \n".
                 "            p.phospital_num as aa_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                             \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id and p.movetotrash = 0                                                            \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON   p.phospital_id = h.id                                                                              \n";
                 if( ! ((int)$hospital_id == -1) ){
                    $sql.= " and   p.phospital_id = $hospital_id                                                                                       \n";
                 }   
          $sql.= "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
                 "        WHERE   date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                           \n".
                 "            and st.service_typea_id = 1                                                                                            \n".
                 "            #and p.pnum = 'CN2400032'                                                                                              \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as aa                                                                                                                    \n".
                 "    LEFT JOIN                                                                                                                      \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as bb_p_sn,                                                                                                     \n".
                 "            p.phospital_num as bb_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                             \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                          \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                \n";
                 if( ! ((int)$hospital_id == -1) ){
                         $sql.= "              and  p.phospital_id = $hospital_id                                                                     \n";
                 }        
           $sql.="        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
                 "        WHERE    date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                              \n".
                 "            and st.service_typea_id = 2                                                                                            \n".
                 "            #and p.pnum = 'CN2400032'                                                                                              \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as bb                                                                                                                    \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                            \n".
                 "        and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                               \n".
                 "    )                                                                                                                              \n".
                 "UNION                                                                                                                              \n".
                 "    (                                                                                                                              \n".
                 "    select                                                                                                                         \n".
                 "    *                                                                                                                              \n".
                 "    FROM                                                                                                                           \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as aa_p_sn,                                                                                                     \n".
                 "            p.phospital_num as aa_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                             \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                          \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON  p.phospital_id = h.id                                                                               \n";
                 if( ! ((int)$hospital_id == -1) ){
                         $sql.= "and  p.phospital_id = $hospital_id                                                                                   \n";
                 }        
         $sql.=  "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                         \n".
                 "        WHERE    date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                               \n".
                 "            and st.service_typea_id = 1                                                                                            \n".
                 "            #and p.pnum = 'CN2400032'                                                                                              \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as aa                                                                                                                    \n".
                 "    RIGHT JOIN                                                                                                                     \n".
                 "        (                                                                                                                          \n".
                 "        SELECT                                                                                                                     \n".
                 "            #*,                                                                                                                    \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,              \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                       \n".
                 "            p.pnum as bb_p_sn,                                                                                                     \n".
                 "            p.phospital_num as bb_p_hn,                                                                                            \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                  \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                        \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                          \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                             \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                        \n".
                 "        FROM service_billing as b                                                                                                  \n".
                 "        JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                         \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                            \n".
                 "        JOIN hospital as h ON  p.phospital_id = h.id                                                                               \n";
                 if( ! ((int)$hospital_id == -1) ){
                      $sql.= "and   p.phospital_id = $hospital_id                                                                                     \n";
                 }        
           $sql.="        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                       \n".
                 "        WHERE    date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                     \n".
                 "        and st.service_typea_id = 2                                                                                                \n".
                 "        #and p.pnum = 'CN2400032'                                                                                                  \n".
                 "        GROUP BY p.pnum                                                                                                            \n".
                 "        ORDER by p.pnum                                                                                                            \n".
                 "        ) as bb                                                                                                                    \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                            \n".
                 "       and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                \n".
                 "    )                                                                                                                              \n".
                 ") as a                                                                                                                             \n".
                 ") as xx                                                                               \n";
                
        }
        
        
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getBillbyHospitalbyDateRangeGroupBySNCount.txt', $sql);   
        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public static function getBillbyPathobyDateRangeGroupBySN($conn,$patho_id, $startdate, $enddate, $limit = 0) {
        
        if($GLOBALS['isBillByAcceptDate']){
            $sql="select                                                                                                                                                  \n".
                 "#* ,                                                                                                                                                    \n".
                 "IFNULL(aa_p_sn, bb_p_sn) as p_sn,                                                                                                                       \n".
                 "IFNULL(aa_p_hn, bb_p_hn) as p_hn,                                                                                                                       \n".
                 "IFNULL(aa_p_admit_date, bb_p_admit_date) as p_admit_date,                                                                                               \n".
                 "IFNULL(aa_patient_name, bb_patient_name) as patient_name,                                                                                               \n".
                 "IFNULL(aa_clinicien_name, bb_clinicien_name) as clinicien_name,                                                                                         \n".
                 "                                                                                                                                                        \n".
                 "IFNULL(aa_b_description_concat_nm, '') as b_description_concat_nm,                                                                                      \n".
                 "IFNULL(bb_b_description_concat_sp, '') as b_description_concat_sp,                                                                                      \n".
                 "CONCAT_WS(' / ',IFNULL(aa_b_description_concat_nm, ''),IFNULL(bb_b_description_concat_sp, '')) as b_description_concat_all,                             \n".
                 "                                                                                                                                                        \n".
                 "IFNULL(aa_b_cost_sum_nm, 0) as b_cost_sum_nm,                                                                                                           \n".
                 "IFNULL(bb_b_cost_sum_sp, 0) as b_cost_sum_sp,                                                                                                           \n".
                 "(IFNULL(aa_b_cost_sum_nm,0) + IFNULL(bb_b_cost_sum_sp, 0) ) as b_cost_sum_all                                                                           \n".
                 "                                                                                                                                                        \n".
                 "from                                                                                                                                                    \n".
                 "                                                                                                                                                        \n".
                 "(                                                                                                                                                       \n".
                 "    (                                                                                                                                                   \n".
                 "    select                                                                                                                                              \n".
                 "    *                                                                                                                                                   \n".
                 "    FROM                                                                                                                                                \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as aa_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as aa_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                                                  \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id  and p.movetotrash = 0                                                                                                         \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON  p.phospital_id = h.id                                                                                                    \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                                                                                \n";
                 if( ! ((int)$patho_id == -1) ){
                 $sql.= "                     and job_pathologist.user_id = {$patho_id}                                                                                   \n";
                 }
                 $sql.= "                     and job_pathologist.job_role_id = 5                                                                                         \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}'and date(p.date_1000) <= '{$enddate}'                                                                \n".
                 "            and st.service_typea_id = 1                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as aa                                                                                                                                         \n".
                 "    LEFT JOIN                                                                                                                                           \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as bb_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as bb_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                                                  \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                                                                       \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                                                                                \n";
                 if( ! ((int)$patho_id == -1) ){
                 $sql.="                     and job_pathologist.user_id = {$patho_id}                                                                                    \n";
                 }
                 $sql.="                     and job_pathologist.job_role_id = 5          \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                                                 \n".
                 "            and st.service_typea_id = 2                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as bb                                                                                                                                         \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                                                 \n".
                 "        and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                                    \n".
                 "    )                                                                                                                                                   \n".
                 "UNION                                                                                                                                                   \n".
                 "    (                                                                                                                                                   \n".
                 "    select                                                                                                                                              \n".
                 "    *                                                                                                                                                   \n".
                 "    FROM                                                                                                                                                \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as aa_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as aa_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                                                  \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                                                                        \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                                                                                \n";
                 if( ! ((int)$patho_id == -1) ){
                 $sql.= "                    and job_pathologist.user_id = {$patho_id}                                                                                    \n";
                 }
                 $sql.= "                     and job_pathologist.job_role_id = 5                                                                                         \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                                               \n".
                 "            and st.service_typea_id = 1                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as aa                                                                                                                                         \n".
                 "    RIGHT JOIN                                                                                                                                          \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as bb_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as bb_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                                                  \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id     and p.movetotrash = 0                                                                                                      \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                                                                                \n";
                 if( ! ((int)$patho_id == -1) ){
                 $sql.= "                     and job_pathologist.user_id = {$patho_id}                                                                                   \n";
                 }
                 $sql.= "                     and job_pathologist.job_role_id = 5                                                                                         \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                                               \n".
                 "        and st.service_typea_id = 2                                                                                                                     \n".
                 "        #and p.pnum = 'CN2400032'                                                                                                                       \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as bb                                                                                                                                         \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                                                 \n".
                 "       and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                                     \n".
                 "    )                                                                                                                                                   \n".
                 ") as a                                                                                                                                                  \n";

             
        }
        
        if($GLOBALS['isBillByServiceDate']){
            $sql="select                                                                                                                                                  \n".
                 "#* ,                                                                                                                                                    \n".
                 "IFNULL(aa_p_sn, bb_p_sn) as p_sn,                                                                                                                       \n".
                 "IFNULL(aa_p_hn, bb_p_hn) as p_hn,                                                                                                                       \n".
                 "IFNULL(aa_p_admit_date, bb_p_admit_date) as p_admit_date,                                                                                               \n".
                 "IFNULL(aa_patient_name, bb_patient_name) as patient_name,                                                                                               \n".
                 "IFNULL(aa_clinicien_name, bb_clinicien_name) as clinicien_name,                                                                                         \n".
                 "                                                                                                                                                        \n".
                 "IFNULL(aa_b_description_concat_nm, '') as b_description_concat_nm,                                                                                      \n".
                 "IFNULL(bb_b_description_concat_sp, '') as b_description_concat_sp,                                                                                      \n".
                 "CONCAT_WS(' / ',IFNULL(aa_b_description_concat_nm, ''),IFNULL(bb_b_description_concat_sp, '')) as b_description_concat_all,                             \n".
                 "                                                                                                                                                        \n".
                 "IFNULL(aa_b_cost_sum_nm, 0) as b_cost_sum_nm,                                                                                                           \n".
                 "IFNULL(bb_b_cost_sum_sp, 0) as b_cost_sum_sp,                                                                                                           \n".
                 "(IFNULL(aa_b_cost_sum_nm,0) + IFNULL(bb_b_cost_sum_sp, 0) ) as b_cost_sum_all                                                                           \n".
                 "                                                                                                                                                        \n".
                 "from                                                                                                                                                    \n".
                 "                                                                                                                                                        \n".
                 "(                                                                                                                                                       \n".
                 "    (                                                                                                                                                   \n".
                 "    select                                                                                                                                              \n".
                 "    *                                                                                                                                                   \n".
                 "    FROM                                                                                                                                                \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as aa_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as aa_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                                                  \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id  and p.movetotrash = 0                                                                                                         \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON  p.phospital_id = h.id                                                                                                    \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                                                                                \n";
                 if( ! ((int)$patho_id == -1) ){
                 $sql.= "                     and job_pathologist.user_id = {$patho_id}                                                                                   \n";
                 }
                 $sql.= "                     and job_pathologist.job_role_id = 5                                                                                         \n".
                 "        WHERE   date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                                                \n".
                 "            and st.service_typea_id = 1                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as aa                                                                                                                                         \n".
                 "    LEFT JOIN                                                                                                                                           \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as bb_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as bb_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                                                  \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                                                                       \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                                                                                \n";
                 if( ! ((int)$patho_id == -1) ){
                 $sql.="                     and job_pathologist.user_id = {$patho_id}                                                                                    \n";
                 }
                 $sql.="                     and job_pathologist.job_role_id = 5          \n".
                 "        WHERE   date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                                                 \n".
                 "            and st.service_typea_id = 2                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as bb                                                                                                                                         \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                                                 \n".
                 "        and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                                    \n".
                 "    )                                                                                                                                                   \n".
                 "UNION                                                                                                                                                   \n".
                 "    (                                                                                                                                                   \n".
                 "    select                                                                                                                                              \n".
                 "    *                                                                                                                                                   \n".
                 "    FROM                                                                                                                                                \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as aa_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as aa_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                                                  \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                                                                        \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                                                                                \n";
                 if( ! ((int)$patho_id == -1) ){
                 $sql.= "                    and job_pathologist.user_id = {$patho_id}                                                                                    \n";
                 }
                 $sql.= "                     and job_pathologist.job_role_id = 5                                                                                         \n".
                 "        WHERE   date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                                               \n".
                 "            and st.service_typea_id = 1                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as aa                                                                                                                                         \n".
                 "    RIGHT JOIN                                                                                                                                          \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as bb_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as bb_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                                                  \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id     and p.movetotrash = 0                                                                                                      \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                                                                                \n";
                 if( ! ((int)$patho_id == -1) ){
                 $sql.= "                     and job_pathologist.user_id = {$patho_id}                                                                                   \n";
                 }
                 $sql.= "                     and job_pathologist.job_role_id = 5                                                                                         \n".
                 "        WHERE   date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                                              \n".
                 "        and st.service_typea_id = 2                                                                                                                     \n".
                 "        #and p.pnum = 'CN2400032'                                                                                                                       \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as bb                                                                                                                                         \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                                                 \n".
                 "       and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                                     \n".
                 "    )                                                                                                                                                   \n".
                 ") as a                                                                                                                                                  \n";

            
        }
        
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getBillbyPathobyDateRangeGroupBySN.txt', $sql);   
        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public static function getBillbyCytobyDateRangeGroupBySN($conn,$cytologist_id, $startdate,$enddate, $limit = 0) {
        if($GLOBALS['isBillByAcceptDate']){
            $sql="select                                                                                                                                                  \n".
                 "#* ,                                                                                                                                                    \n".
                 "IFNULL(aa_p_sn, bb_p_sn) as p_sn,                                                                                                                       \n".
                 "IFNULL(aa_p_hn, bb_p_hn) as p_hn,                                                                                                                       \n".
                 "IFNULL(aa_p_admit_date, bb_p_admit_date) as p_admit_date,                                                                                               \n".
                 "IFNULL(aa_patient_name, bb_patient_name) as patient_name,                                                                                               \n".
                 "IFNULL(aa_clinicien_name, bb_clinicien_name) as clinicien_name,                                                                                         \n".
                 "                                                                                                                                                        \n".
                 "IFNULL(aa_b_description_concat_nm, '') as b_description_concat_nm,                                                                                      \n".
                 "IFNULL(bb_b_description_concat_sp, '') as b_description_concat_sp,                                                                                      \n".
                 "CONCAT_WS(' / ',IFNULL(aa_b_description_concat_nm, ''),IFNULL(bb_b_description_concat_sp, '')) as b_description_concat_all,                             \n".
                 "                                                                                                                                                        \n".
                 "IFNULL(aa_b_cost_sum_nm, 0) as b_cost_sum_nm,                                                                                                           \n".
                 "IFNULL(bb_b_cost_sum_sp, 0) as b_cost_sum_sp,                                                                                                           \n".
                 "(IFNULL(aa_b_cost_sum_nm,0) + IFNULL(bb_b_cost_sum_sp, 0) ) as b_cost_sum_all                                                                           \n".
                 "                                                                                                                                                        \n".
                 "from                                                                                                                                                    \n".
                 "                                                                                                                                                        \n".
                 "(                                                                                                                                                       \n".
                 "    (                                                                                                                                                   \n".
                 "    select                                                                                                                                              \n".
                 "    *                                                                                                                                                   \n".
                 "    FROM                                                                                                                                                \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as aa_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as aa_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                                                  \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                                                                       \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON  p.phospital_id = h.id                                                                                                    \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                                                                   \n";
                 if( ! ((int)$cytologist_id == -1) ){
                           $sql.= "            and job_cytologist.user_id = {$cytologist_id}                                                                  \n";
                 }
            $sql.="                       and job_cytologist.job_role_id = 7          \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}'and date(p.date_1000) <= '{$enddate}'                                                                  \n".
                 "            and st.service_typea_id = 1                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as aa                                                                                                                                         \n".
                 "    LEFT JOIN                                                                                                                                           \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as bb_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as bb_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                                                  \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                                                                        \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                  \n";
                  if( ! ((int)$cytologist_id == -1) ){
                         $sql.= "            and job_cytologist.user_id = {$cytologist_id}  \n";
                  }
            $sql.="                and job_cytologist.job_role_id = 7          \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                                                 \n".
                 "            and st.service_typea_id = 2                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as bb                                                                                                                                         \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                                                 \n".
                 "        and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                                    \n".
                 "    )                                                                                                                                                   \n".
                 "UNION                                                                                                                                                   \n".
                 "    (                                                                                                                                                   \n".
                 "    select                                                                                                                                              \n".
                 "    *                                                                                                                                                   \n".
                 "    FROM                                                                                                                                                \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as aa_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as aa_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                                                  \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                                                                        \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                                                    \n";
                 if( ! ((int)$cytologist_id == -1) ){
                         $sql.= "                     and job_cytologist.user_id = {$cytologist_id}                       \n";
                 }
            $sql.="                      and job_cytologist.job_role_id = 7          \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                                                  \n".
                 "            and st.service_typea_id = 1                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as aa                                                                                                                                         \n".
                 "    RIGHT JOIN                                                                                                                                          \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as bb_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as bb_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                                                  \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                                                                       \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id           \n";
                 if( ! ((int)$cytologist_id == -1) ){
                         $sql.= "                 and job_cytologist.user_id = {$cytologist_id}   \n";
                 }
            $sql.= "               and job_cytologist.job_role_id = 7          \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                                                 \n".
                 "        and st.service_typea_id = 2                                                                                                                     \n".
                 "        #and p.pnum = 'CN2400032'                                                                                                                       \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as bb                                                                                                                                         \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                                                 \n".
                 "       and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                                     \n".
                 "    )                                                                                                                                                   \n".
                 ") as a                                                                                                                                                  \n";
        
                 
        }
        
        if($GLOBALS['isBillByServiceDate']){
                $sql="select                                                                                                                                                  \n".
                 "#* ,                                                                                                                                                    \n".
                 "IFNULL(aa_p_sn, bb_p_sn) as p_sn,                                                                                                                       \n".
                 "IFNULL(aa_p_hn, bb_p_hn) as p_hn,                                                                                                                       \n".
                 "IFNULL(aa_p_admit_date, bb_p_admit_date) as p_admit_date,                                                                                               \n".
                 "IFNULL(aa_patient_name, bb_patient_name) as patient_name,                                                                                               \n".
                 "IFNULL(aa_clinicien_name, bb_clinicien_name) as clinicien_name,                                                                                         \n".
                 "                                                                                                                                                        \n".
                 "IFNULL(aa_b_description_concat_nm, '') as b_description_concat_nm,                                                                                      \n".
                 "IFNULL(bb_b_description_concat_sp, '') as b_description_concat_sp,                                                                                      \n".
                 "CONCAT_WS(' / ',IFNULL(aa_b_description_concat_nm, ''),IFNULL(bb_b_description_concat_sp, '')) as b_description_concat_all,                             \n".
                 "                                                                                                                                                        \n".
                 "IFNULL(aa_b_cost_sum_nm, 0) as b_cost_sum_nm,                                                                                                           \n".
                 "IFNULL(bb_b_cost_sum_sp, 0) as b_cost_sum_sp,                                                                                                           \n".
                 "(IFNULL(aa_b_cost_sum_nm,0) + IFNULL(bb_b_cost_sum_sp, 0) ) as b_cost_sum_all                                                                           \n".
                 "                                                                                                                                                        \n".
                 "from                                                                                                                                                    \n".
                 "                                                                                                                                                        \n".
                 "(                                                                                                                                                       \n".
                 "    (                                                                                                                                                   \n".
                 "    select                                                                                                                                              \n".
                 "    *                                                                                                                                                   \n".
                 "    FROM                                                                                                                                                \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as aa_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as aa_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                                                  \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                                                                       \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON  p.phospital_id = h.id                                                                                                    \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                                                                   \n";
                 if( ! ((int)$cytologist_id == -1) ){
                           $sql.= "            and job_cytologist.user_id = {$cytologist_id}                                                                  \n";
                 }
            $sql.="                       and job_cytologist.job_role_id = 7          \n".
                 "        WHERE  date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                                                \n".
                 "            and st.service_typea_id = 1                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as aa                                                                                                                                         \n".
                 "    LEFT JOIN                                                                                                                                           \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as bb_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as bb_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                                                  \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                                                                        \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                  \n";
                  if( ! ((int)$cytologist_id == -1) ){
                         $sql.= "            and job_cytologist.user_id = {$cytologist_id}  \n";
                  }
            $sql.="                and job_cytologist.job_role_id = 7          \n".
                 "        WHERE  date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                                                \n".
                 "            and st.service_typea_id = 2                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as bb                                                                                                                                         \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                                                 \n".
                 "        and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                                    \n".
                 "    )                                                                                                                                                   \n".
                 "UNION                                                                                                                                                   \n".
                 "    (                                                                                                                                                   \n".
                 "    select                                                                                                                                              \n".
                 "    *                                                                                                                                                   \n".
                 "    FROM                                                                                                                                                \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as aa_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as aa_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                                                  \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                                                                        \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                                                    \n";
                 if( ! ((int)$cytologist_id == -1) ){
                         $sql.= "                     and job_cytologist.user_id = {$cytologist_id}                       \n";
                 }
            $sql.="                      and job_cytologist.job_role_id = 7          \n".
                 "        WHERE  date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                                                 \n".
                 "            and st.service_typea_id = 1                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as aa                                                                                                                                         \n".
                 "    RIGHT JOIN                                                                                                                                          \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as bb_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as bb_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                                                  \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                                                                       \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id           \n";
                 if( ! ((int)$cytologist_id == -1) ){
                         $sql.= "                 and job_cytologist.user_id = {$cytologist_id}   \n";
                 }
            $sql.= "               and job_cytologist.job_role_id = 7          \n".
                 "        WHERE   date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                                                \n".
                 "        and st.service_typea_id = 2                                                                                                                     \n".
                 "        #and p.pnum = 'CN2400032'                                                                                                                       \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as bb                                                                                                                                         \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                                                 \n".
                 "       and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                                     \n".
                 "    )                                                                                                                                                   \n".
                 ") as a                                                                                                                                                  \n";
        
        }
        
        
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getBillbyCytobyDateRangeGroupBySN.txt', $sql);   
        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getBillbyCytobyDateRangeGroupBySNCount($conn,$cytologist_id, $startdate,$enddate, $limit = 0) {
        if($GLOBALS['isBillByAcceptDate']){
         $sql =  "SELECT COUNT(*) as a_count                                                           \n".
                 "FROM                                                                                 \n".
                 "(                                                                                    \n".
                 "select                                                                                                                                                  \n".
                 "#* ,                                                                                                                                                    \n".
                 "IFNULL(aa_p_sn, bb_p_sn) as p_sn,                                                                                                                       \n".
                 "IFNULL(aa_p_hn, bb_p_hn) as p_hn,                                                                                                                       \n".
                 "IFNULL(aa_p_admit_date, bb_p_admit_date) as p_admit_date,                                                                                               \n".
                 "IFNULL(aa_patient_name, bb_patient_name) as patient_name,                                                                                               \n".
                 "IFNULL(aa_clinicien_name, bb_clinicien_name) as clinicien_name,                                                                                         \n".
                 "                                                                                                                                                        \n".
                 "IFNULL(aa_b_description_concat_nm, '') as b_description_concat_nm,                                                                                      \n".
                 "IFNULL(bb_b_description_concat_sp, '') as b_description_concat_sp,                                                                                      \n".
                 "CONCAT_WS(' / ',IFNULL(aa_b_description_concat_nm, ''),IFNULL(bb_b_description_concat_sp, '')) as b_description_concat_all,                             \n".
                 "                                                                                                                                                        \n".
                 "IFNULL(aa_b_cost_sum_nm, 0) as b_cost_sum_nm,                                                                                                           \n".
                 "IFNULL(bb_b_cost_sum_sp, 0) as b_cost_sum_sp,                                                                                                           \n".
                 "(IFNULL(aa_b_cost_sum_nm,0) + IFNULL(bb_b_cost_sum_sp, 0) ) as b_cost_sum_all                                                                           \n".
                 "                                                                                                                                                        \n".
                 "from                                                                                                                                                    \n".
                 "                                                                                                                                                        \n".
                 "(                                                                                                                                                       \n".
                 "    (                                                                                                                                                   \n".
                 "    select                                                                                                                                              \n".
                 "    *                                                                                                                                                   \n".
                 "    FROM                                                                                                                                                \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as aa_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as aa_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                                                  \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                                                                       \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON  p.phospital_id = h.id                                                                                                    \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                                                                   \n";
                 if( ! ((int)$cytologist_id == -1) ){
                           $sql.= "            and job_cytologist.user_id = {$cytologist_id}                                                                  \n";
                 }
            $sql.="                       and job_cytologist.job_role_id = 7          \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}'and date(p.date_1000) <= '{$enddate}'                                                                  \n".
                 "            and st.service_typea_id = 1                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as aa                                                                                                                                         \n".
                 "    LEFT JOIN                                                                                                                                           \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as bb_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as bb_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                                                  \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                                                                        \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                  \n";
                  if( ! ((int)$cytologist_id == -1) ){
                         $sql.= "            and job_cytologist.user_id = {$cytologist_id}  \n";
                  }
            $sql.="                and job_cytologist.job_role_id = 7          \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                                                 \n".
                 "            and st.service_typea_id = 2                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as bb                                                                                                                                         \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                                                 \n".
                 "        and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                                    \n".
                 "    )                                                                                                                                                   \n".
                 "UNION                                                                                                                                                   \n".
                 "    (                                                                                                                                                   \n".
                 "    select                                                                                                                                              \n".
                 "    *                                                                                                                                                   \n".
                 "    FROM                                                                                                                                                \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as aa_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as aa_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                                                  \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                                                                        \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                                                    \n";
                 if( ! ((int)$cytologist_id == -1) ){
                         $sql.= "                     and job_cytologist.user_id = {$cytologist_id}                       \n";
                 }
            $sql.="                      and job_cytologist.job_role_id = 7          \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                                                  \n".
                 "            and st.service_typea_id = 1                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as aa                                                                                                                                         \n".
                 "    RIGHT JOIN                                                                                                                                          \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as bb_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as bb_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                                                  \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                                              \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id           \n";
                 if( ! ((int)$cytologist_id == -1) ){
                         $sql.= "                 and job_cytologist.user_id = {$cytologist_id}   \n";
                 }
            $sql.= "               and job_cytologist.job_role_id = 7          \n".
                 "        WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'                                                               \n".
                 "        and st.service_typea_id = 2                                                                                                                     \n".
                 "        #and p.pnum = 'CN2400032'                                                                                                                       \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as bb                                                                                                                                         \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                                                 \n".
                 "       and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                                     \n".
                 "    )                                                                                                                                                   \n".
                 ") as a                                                                                                                                                  \n".
                 ") as xx                                                                               \n";
                 
        }
        
        if($GLOBALS['isBillByServiceDate']){
         $sql =  "SELECT COUNT(*) as a_count                                                           \n".
                 "FROM                                                                                 \n".
                 "(                                                                                    \n".
                 "select                                                                                                                                                  \n".
                 "#* ,                                                                                                                                                    \n".
                 "IFNULL(aa_p_sn, bb_p_sn) as p_sn,                                                                                                                       \n".
                 "IFNULL(aa_p_hn, bb_p_hn) as p_hn,                                                                                                                       \n".
                 "IFNULL(aa_p_admit_date, bb_p_admit_date) as p_admit_date,                                                                                               \n".
                 "IFNULL(aa_patient_name, bb_patient_name) as patient_name,                                                                                               \n".
                 "IFNULL(aa_clinicien_name, bb_clinicien_name) as clinicien_name,                                                                                         \n".
                 "                                                                                                                                                        \n".
                 "IFNULL(aa_b_description_concat_nm, '') as b_description_concat_nm,                                                                                      \n".
                 "IFNULL(bb_b_description_concat_sp, '') as b_description_concat_sp,                                                                                      \n".
                 "CONCAT_WS(' / ',IFNULL(aa_b_description_concat_nm, ''),IFNULL(bb_b_description_concat_sp, '')) as b_description_concat_all,                             \n".
                 "                                                                                                                                                        \n".
                 "IFNULL(aa_b_cost_sum_nm, 0) as b_cost_sum_nm,                                                                                                           \n".
                 "IFNULL(bb_b_cost_sum_sp, 0) as b_cost_sum_sp,                                                                                                           \n".
                 "(IFNULL(aa_b_cost_sum_nm,0) + IFNULL(bb_b_cost_sum_sp, 0) ) as b_cost_sum_all                                                                           \n".
                 "                                                                                                                                                        \n".
                 "from                                                                                                                                                    \n".
                 "                                                                                                                                                        \n".
                 "(                                                                                                                                                       \n".
                 "    (                                                                                                                                                   \n".
                 "    select                                                                                                                                              \n".
                 "    *                                                                                                                                                   \n".
                 "    FROM                                                                                                                                                \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as aa_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as aa_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                                                  \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                                                                       \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON  p.phospital_id = h.id                                                                                                    \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                                                                   \n";
                 if( ! ((int)$cytologist_id == -1) ){
                           $sql.= "            and job_cytologist.user_id = {$cytologist_id}                                                                  \n";
                 }
            $sql.="                       and job_cytologist.job_role_id = 7          \n".
                 "        WHERE  date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                                                \n".
                 "            and st.service_typea_id = 1                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as aa                                                                                                                                         \n".
                 "    LEFT JOIN                                                                                                                                           \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as bb_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as bb_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                                                  \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                                                                        \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                  \n";
                  if( ! ((int)$cytologist_id == -1) ){
                         $sql.= "            and job_cytologist.user_id = {$cytologist_id}  \n";
                  }
            $sql.="                and job_cytologist.job_role_id = 7          \n".
                 "        WHERE  date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                                                \n".
                 "            and st.service_typea_id = 2                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as bb                                                                                                                                         \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                                                 \n".
                 "        and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                                    \n".
                 "    )                                                                                                                                                   \n".
                 "UNION                                                                                                                                                   \n".
                 "    (                                                                                                                                                   \n".
                 "    select                                                                                                                                              \n".
                 "    *                                                                                                                                                   \n".
                 "    FROM                                                                                                                                                \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as aa_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as aa_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as aa_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as aa_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as aa_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS aa_b_description_concat_nm,                                                                  \n".
                 "            SUM(b.cost) as aa_b_cost_sum_nm                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id   and p.movetotrash = 0                                                                                                        \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                                                    \n";
                 if( ! ((int)$cytologist_id == -1) ){
                         $sql.= "                     and job_cytologist.user_id = {$cytologist_id}                       \n";
                 }
            $sql.="                      and job_cytologist.job_role_id = 7          \n".
                 "        WHERE  date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                                                 \n".
                 "            and st.service_typea_id = 1                                                                                                                 \n".
                 "            #and p.pnum = 'CN2400032'                                                                                                                   \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as aa                                                                                                                                         \n".
                 "    RIGHT JOIN                                                                                                                                          \n".
                 "        (                                                                                                                                               \n".
                 "        SELECT                                                                                                                                          \n".
                 "            #*,                                                                                                                                         \n".
                 "            #b.id as bid, p.id as pid, b.code_description as b_code, b.code2 as b_code2, st.id as stid,  h.id as hid,                                   \n".
                 "            #st.service_typea_bill as st_service_typea_bill,                                                                                            \n".
                 "            p.pnum as bb_p_sn,                                                                                                                          \n".
                 "            p.phospital_num as bb_p_hn,                                                                                                                 \n".
                 "            DATE(p.date_1000) as bb_p_admit_date,                                                                                                       \n".
                 "            CONCAT(p.ppre_name,p.pname,' ',p.plastname) as bb_patient_name,                                                                             \n".
                 "            CONCAT(user_clinicien.name,' ',user_clinicien.lastname) as bb_clinicien_name,                                                               \n".
                 "            GROUP_CONCAT(b.description SEPARATOR ' / ') AS bb_b_description_concat_sp,                                                                  \n".
                 "            SUM(b.cost) as bb_b_cost_sum_sp                                                                                                             \n".
                 "        FROM service_billing as b                                                                                                                       \n".
                 "        JOIN patient as p on  b.patient_id = p.id    and p.movetotrash = 0                                                                                                       \n".
                 "        JOIN service_type as st ON st.id = b.slide_type                                                                                                 \n".
                 "        JOIN hospital as h ON p.phospital_id = h.id                                                                                                     \n".
                 "        JOIN user as user_clinicien ON user_clinicien.id = p.pclinician_id                                                                              \n".
                 "        JOIN job as job_cytologist ON job_cytologist.patient_id = p.id           \n";
                 if( ! ((int)$cytologist_id == -1) ){
                         $sql.= "                 and job_cytologist.user_id = {$cytologist_id}   \n";
                 }
            $sql.= "               and job_cytologist.job_role_id = 7          \n".
                 "        WHERE   date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'                                                                \n".
                 "        and st.service_typea_id = 2                                                                                                                     \n".
                 "        #and p.pnum = 'CN2400032'                                                                                                                       \n".
                 "        GROUP BY p.pnum                                                                                                                                 \n".
                 "        ORDER by p.pnum                                                                                                                                 \n".
                 "        ) as bb                                                                                                                                         \n".
                 "    ON aa.aa_p_sn = bb.bb_p_sn and aa.aa_p_hn = bb.bb_p_hn and  aa.aa_p_admit_date = bb.bb_p_admit_date                                                 \n".
                 "       and aa.aa_patient_name = bb.bb_patient_name and  aa.aa_clinicien_name = bb.bb_clinicien_name                                                     \n".
                 "    )                                                                                                                                                   \n".
                 ") as a                                                                                                                                                  \n".
                 ") as xx                                                                               \n";
                 
        }
        
        
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getBillbyCytobyDateRangeGroupBySN.txt', $sql);   
        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
        
    
    //b_code    b_description    b_cost   bcost_count  bcost_sum  hid   bid   pid
    //0         cell_block       500      5            2500       9     53019 34652
    //0         ER_PR_Her-2      1800     13           23400      9     53086 34640
    public static function getBillbyHospitalbyDateRangeGroupByCode($conn,$hospital_id, $startdate,$enddate, $limit = 0) {
        if($GLOBALS['isBillByAcceptDate']){
            $sql = "SELECT                                                                               \n".
                    "b.code_description as b_code,                                                        \n".
                    "b.description as b_description,                                                      \n".
                    "b.cost as b_cost,                                                                    \n".
                    "count(b.cost) as bcost_count,                                                        \n".
                    "SUM(b.cost) as bcost_sum,                                                            \n".
                    "h.id as hid, b.id as bid, p.id as pid                                                \n".
                    "FROM patient as p                                                                    \n".  
                    "   JOIN service_billing as b on  b.patient_id = p.id                                 \n".    
                    "   JOIN hospital as h ON   p.phospital_id = h.id                                     \n";
                    if( ! ((int)$hospital_id == -1) ){
                    $sql.= "                and h.id = $hospital_id                                       \n";
                    }
              $sql.="   JOIN service_type as s ON b.slide_type = s.id                                     \n".
                    "   WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}' \n".
                    "              and p.movetotrash = 0                                                  \n".
                    "    GROUP BY b.code_description  , b.code2 , b.description  , b.cost                 \n".
                    "    ORDER by b.code_description                                                      \n";
        }
        
        if($GLOBALS['isBillByServiceDate']){
//            $sql = "SELECT                                                                               \n".
//                    "b.code_description as b_code,                                                        \n".
//                    "b.description as b_description,                                                      \n".
//                    "b.cost as b_cost,                                                                    \n".
//                    "count(b.cost) as bcost_count,                                                        \n".
//                    "SUM(b.cost) as bcost_sum,                                                            \n".
//                    "h.id as hid, b.id as bid, p.id as pid                                                \n".
//                    "FROM patient as p                                                                    \n".  
//                    "   JOIN service_billing as b on  b.patient_id = p.id                                 \n".    
//                    "   JOIN hospital as h ON   p.phospital_id = h.id                                     \n";
//                    if( ! ((int)$hospital_id == -1) ){
//                    $sql.= "                and h.id = $hospital_id                                       \n";
//                    }
//              $sql.="   JOIN service_type as s ON b.slide_type = s.id                                     \n".
//                    "   WHERE   date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}' \n".
//                    "              and p.movetotrash = 0                                                  \n".
//                    "    GROUP BY b.code_description  , b.code2                                             \n".
//                    "    ORDER by b.code_description                                                      \n";
//            
        }

        if($GLOBALS['isSqlWriteFileForDBG']){
                Util::writeFile('getBillbyHospitalbyDateRangeGroupByCode.txt', $sql);   
        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getBillbyHospitalbyDateRangeGroupByCodeCount($conn,$hospital_id, $startdate,$enddate, $limit = 0) {
        if($GLOBALS['isBillByAcceptDate']){
            $sql =  "SELECT COUNT(*) as a_count                                                          \n".
                    "FROM                                                                                 \n".
                    "(                                                                                    \n".
                    "SELECT                                                                               \n".
                    "b.code_description as b_code,                                                        \n".
                    "b.description as b_description,                                                      \n".
                    "b.cost as b_cost,                                                                    \n".
                    "count(b.cost) as bcost_count,                                                        \n".
                    "SUM(b.cost) as bcost_sum,                                                            \n".
                    "h.id as hid, b.id as bid, p.id as pid                                                \n".
                    "FROM patient as p                                                                    \n".  
                    "   JOIN service_billing as b on  b.patient_id = p.id                                 \n".    
                    "   JOIN hospital as h ON   p.phospital_id = h.id                                     \n";
                    if( ! ((int)$hospital_id == -1) ){
                    $sql.= "                and h.id = $hospital_id                                       \n";
                    }
              $sql.="   JOIN service_type as s ON b.slide_type = s.id                                     \n".
                    "   WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}' \n".
                    "              and p.movetotrash = 0                                                  \n".
                    "    GROUP BY b.code_description  , b.code2 , b.description  , b.cost                 \n".
                    "    ORDER by b.code_description                                                      \n".
                    ") as a                                                                               \n";
        }
        
        if($GLOBALS['isBillByServiceDate']){

//            $sql =  "SELECT COUNT(*) as a_count                                                          \n".
//                    "FROM                                                                                 \n".
//                    "(                                                                                    \n".
//                    "SELECT                                                                               \n".
//                    "b.code_description as b_code,                                                        \n".
//                    "b.description as b_description,                                                      \n".
//                    "b.cost as b_cost,                                                                    \n".
//                    "count(b.cost) as bcost_count,                                                        \n".
//                    "SUM(b.cost) as bcost_sum,                                                            \n".
//                    "h.id as hid, b.id as bid, p.id as pid                                                \n".
//                    "FROM patient as p                                                                    \n".  
//                    "   JOIN service_billing as b on  b.patient_id = p.id                                 \n".    
//                    "   JOIN hospital as h ON   p.phospital_id = h.id                                     \n";
//                    if( ! ((int)$hospital_id == -1) ){
//                    $sql.= "                and h.id = $hospital_id                                       \n";
//                    }
//              $sql.="   JOIN service_type as s ON b.slide_type = s.id                                     \n".
//                    "   WHERE   date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}' \n".
//                    "              and p.movetotrash = 0                                                  \n".
//                    "    GROUP BY b.code_description  , b.code2                                             \n".
//                    "    ORDER by b.code_description                                                      \n".
//                    ") as a                                                                               \n";
            
        }

        if($GLOBALS['isSqlWriteFileForDBG']){
                Util::writeFile('getBillbyHospitalbyDateRangeGroupByCodeCount.txt', $sql);   
        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
            
    public static function getBillbyPathobyDateRangeGroupByCode($conn,$patho_id, $startdate,$enddate, $limit = 0) {

        if($GLOBALS['isBillByAcceptDate']){
            $sql="SELECT                                                                                  \n".
                 "b.code_description as b_code,                                                           \n".
                 "b.description as b_description,                                                         \n".
                 "b.cost as b_cost,                                                                       \n".
                 "count(b.cost) as bcost_count,                                                           \n".
                 "SUM(b.cost) as bcost_sum,                                                               \n".
                 "h.id as hid, b.id as bid, p.id as pid                                                   \n".
                 "FROM patient as p                                                                       \n".
                 "   JOIN service_billing as b on  b.patient_id = p.id                                    \n".
                 "   JOIN hospital as h ON  p.phospital_id = h.id                                         \n".
                 "   JOIN service_type as s ON b.slide_type = s.id                                        \n".
                 "   JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                     \n";
                 if( ! ((int)$patho_id == -1) ){
                 $sql.="                 and job_pathologist.user_id = {$patho_id}                        \n";
                 }
                 $sql.="                 and job_pathologist.job_role_id = 5                              \n".
                 "   WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'    \n".
                 "             and p.movetotrash = 0                                                      \n".
                 "    GROUP BY b.code_description  , b.code2 , b.description  , b.cost                    \n".
                 "    ORDER by b.code_description                                                         \n";
        }
        
        if($GLOBALS['isBillByServiceDate']){
//                        $sql="SELECT                                                                                  \n".
//                 "b.code_description as b_code,                                                           \n".
//                 "b.description as b_description,                                                         \n".
//                 "b.cost as b_cost,                                                                       \n".
//                 "count(b.cost) as bcost_count,                                                           \n".
//                 "SUM(b.cost) as bcost_sum,                                                               \n".
//                 "h.id as hid, b.id as bid, p.id as pid                                                   \n".
//                 "FROM patient as p                                                                       \n".
//                 "   JOIN service_billing as b on  b.patient_id = p.id                                    \n".
//                 "   JOIN hospital as h ON  p.phospital_id = h.id                                         \n".
//                 "   JOIN service_type as s ON b.slide_type = s.id                                        \n".
//                 "   JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                     \n";
//                 if( ! ((int)$patho_id == -1) ){
//                 $sql.="                 and job_pathologist.user_id = {$patho_id}                        \n";
//                 }
//                 $sql.="                 and job_pathologist.job_role_id = 5                              \n".
//                 "   WHERE   date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'    \n".
//                 "             and p.movetotrash = 0                                                      \n".
//                 "    GROUP BY b.code_description  , b.code2                                               \n".
//                 "    ORDER by b.code_description                                                         \n";
        }

        if($GLOBALS['isSqlWriteFileForDBG']){
                Util::writeFile('getBillbyPathobyDateRangeGroupByCode.txt', $sql);   
        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getBillbyCytobyDateRangeGroupByCode($conn,$cytologist_id, $startdate,$enddate, $limit = 0) {
        if($GLOBALS['isBillByAcceptDate']){
            $sql="SELECT                                                                                  \n".
                 "b.code_description as b_code,                                                           \n".
                 "b.description as b_description,                                                         \n".
                 "b.cost as b_cost,                                                                       \n".
                 "count(b.cost) as bcost_count,                                                           \n".
                 "SUM(b.cost) as bcost_sum,                                                               \n".
                 "h.id as hid, b.id as bid, p.id as pid                                                   \n".
                 "FROM patient as p                                                                       \n".
                 "   JOIN service_billing as b on  b.patient_id = p.id                                    \n".
                 "   JOIN hospital as h ON  p.phospital_id = h.id                                         \n".
                 "   JOIN service_type as s ON b.slide_type = s.id                                        \n".
                 "   JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                     \n";
                 if( ! ((int)$cytologist_id == -1) ){
                 $sql.="         and job_cytologist.user_id = {$cytologist_id}                          \n";
                 }
            $sql.="               and job_cytologist.job_role_id = 7                                      \n".
                 "   WHERE   date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'    \n".
                 "             and p.movetotrash = 0                                                         \n".
                 "    GROUP BY b.code_description   , b.code2 , b.description  , b.cost                    \n".
                 "    ORDER by b.code_description                                                         \n";

           }

        if($GLOBALS['isBillByServiceDate']){
//            $sql="SELECT                                                                                  \n".
//                 "b.code_description as b_code,                                                           \n".
//                 "b.description as b_description,                                                         \n".
//                 "b.cost as b_cost,                                                                       \n".
//                 "count(b.cost) as bcost_count,                                                           \n".
//                 "SUM(b.cost) as bcost_sum,                                                               \n".
//                 "h.id as hid, b.id as bid, p.id as pid                                                   \n".
//                 "FROM patient as p                                                                       \n".
//                 "   JOIN service_billing as b on  b.patient_id = p.id                                    \n".
//                 "   JOIN hospital as h ON  p.phospital_id = h.id                                         \n".
//                 "   JOIN service_type as s ON b.slide_type = s.id                                        \n".
//                 "   JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                     \n";
//                 if( ! ((int)$cytologist_id == -1) ){
//                 $sql.="         and job_cytologist.user_id = {$cytologist_id}                          \n";
//                 }
//            $sql.="               and job_cytologist.job_role_id = 7                                      \n".
//                 "   WHERE   date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'    \n".
//                 "             and p.movetotrash = 0                                                         \n".
//                 "    GROUP BY b.code_description  , b.code2                                                 \n".
//                 "    ORDER by b.code_description                                                         \n";

        }  
             

        if($GLOBALS['isSqlWriteFileForDBG']){
                Util::writeFile('getBillbyCytobyDateRangeGroupByCode.txt', $sql);   
        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getBillbyHospitalbyDateRangeSumPrice($conn,$hospital_id, $startdate,$enddate, $limit = 0) {

        if($GLOBALS['isBillByAcceptDate']){
            $sql = "SELECT SUM(b.cost) as bcost, COUNT(*) as bcount                                          \n".
                   " FROM  patient as p                                                                      \n".
                   "   JOIN service_billing as b  ON b.patient_id = p.id    \n".
                   "   JOIN hospital as h  ON p.phospital_id = h.id                                          \n";
                   if( ! ((int)$hospital_id == -1) ){
                   $sql.=" and  p.phospital_id = $hospital_id                                                \n";
                   } 
            $sql.="   JOIN service_type as s ON  b.slide_type = s.id                                  \n".
                   "                                                                                         \n".
                   "  WHERE   p.movetotrash = 0                                                              \n".
                   "  and date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'          \n".
                   "  ORDER by b.id;                                                                         \n";

         }

        if($GLOBALS['isBillByServiceDate']){
            $sql = "SELECT SUM(b.cost) as bcost, COUNT(*) as bcount                                          \n".
                   " FROM  patient as p                                                                      \n".
                   "   JOIN service_billing as b  ON b.patient_id = p.id    \n".
                   "   JOIN hospital as h  ON p.phospital_id = h.id                                          \n";
                   if( ! ((int)$hospital_id == -1) ){
                   $sql.=" and  p.phospital_id = $hospital_id                                                \n";
                   } 
            $sql.="   JOIN service_type as s ON  b.slide_type = s.id                                  \n".
                   "                                                                                         \n".
                   "  WHERE   p.movetotrash = 0                                                              \n".
                   "  and date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'          \n".
                   "  ORDER by b.id;                                                                         \n";

        }


        if($GLOBALS['isSqlWriteFileForDBG']){
                Util::writeFile('getBillbyHospitalbyDateRangeSumPrice.txt', $sql);  
        }
        $results = $conn->query($sql);

        return  $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
     
    public static function getBillbyPathoDateRangeSumPrice($conn, $patho_id , $startdate, $enddate) {
        
        if($GLOBALS['isBillByAcceptDate']){
            $sql= "SELECT SUM(b.cost) as bcost, COUNT(*) as bcount                                   \n".
                  "  FROM  patient as p                                                              \n".
                  "    JOIN service_billing as b  ON  b.patient_id = p.id                            \n".
                  "    JOIN hospital as h ON p.phospital_id = h.id                                   \n".
                  "    JOIN service_type as s ON  b.slide_type = s.id                                \n".
                  "    JOIN job as job_pathologist ON job_pathologist.patient_id = p.id              \n";
                  if( ! ((int)$patho_id == -1) ){
                  $sql.="          and job_pathologist.user_id = {$patho_id}                         \n";
                  }
            $sql.="and job_pathologist.job_role_id = 5                                                \n".                                                 
                  "   WHERE   p.movetotrash = 0                                                    \n".
                  "   and date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'    \n".
                  "   ORDER by b.id                                                                   \n";
              
        }

        if($GLOBALS['isBillByServiceDate']){
            $sql= "SELECT SUM(b.cost) as bcost, COUNT(*) as bcount                                   \n".
                  "  FROM  patient as p                                                              \n".
                  "    JOIN service_billing as b  ON  b.patient_id = p.id                            \n".
                  "    JOIN hospital as h ON p.phospital_id = h.id                                   \n".
                  "    JOIN service_type as s ON  b.slide_type = s.id                                \n".
                  "    JOIN job as job_pathologist ON job_pathologist.patient_id = p.id              \n";
                  if( ! ((int)$patho_id == -1) ){
                  $sql.="          and job_pathologist.user_id = {$patho_id}                         \n";
                  }
            $sql.="and job_pathologist.job_role_id = 5                                                \n".                                                 
                  "   WHERE   p.movetotrash = 0                                                    \n".
                  "   and date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'    \n".
                  "   ORDER by b.id                                                                   \n";
        }      

        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getBillbyPathoDateRangeSumPrice.txt', $sql);  
            //bcost	bcount
            //27130	43
        }
        $results = $conn->query($sql);

        return  $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
         
    public static function getBillbyCytoDateRangeSumPrice($conn, $cytologist_id , $startdate, $enddate) {
    if($GLOBALS['isBillByAcceptDate']){
        $sql= "SELECT SUM(b.cost) as bcost, COUNT(*) as bcount                                   \n".
              "  FROM  patient as p                                                              \n".
              "    JOIN service_billing as b  ON  b.patient_id = p.id                            \n".
              "    JOIN hospital as h ON p.phospital_id = h.id                                   \n".
              "    JOIN service_type as s ON  b.slide_type = s.id                                \n".
              "    JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                 \n";
              if( ! ((int)$cytologist_id == -1) ){
              $sql.="          and job_cytologist.user_id = {$cytologist_id}  \n";
              }
       $sql.= "                and job_cytologist.job_role_id = 7                                  \n".                                                 
              "   WHERE  p.movetotrash = 0                                                              \n".
              "       and date(p.date_1000) >= '{$startdate}' and date(p.date_1000) <= '{$enddate}'    \n".
              "   ORDER by b.id                                                                     \n";
        }

        if($GLOBALS['isBillByServiceDate']){
        $sql= "SELECT SUM(b.cost) as bcost, COUNT(*) as bcount                                   \n".
              "  FROM  patient as p                                                              \n".
              "    JOIN service_billing as b  ON  b.patient_id = p.id                            \n".
              "    JOIN hospital as h ON p.phospital_id = h.id                                   \n".
              "    JOIN service_type as s ON  b.slide_type = s.id                                \n".
              "    JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                 \n";
              if( ! ((int)$cytologist_id == -1) ){
              $sql.="          and job_cytologist.user_id = {$cytologist_id}  \n";
              }
       $sql.= "                and job_cytologist.job_role_id = 7                                  \n".                                                 
              "   WHERE  p.movetotrash = 0                                                              \n".
              "       and date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'   \n".
              "   ORDER by b.id                                                                     \n";
        }
        
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getBillbyCytoDateRangeSumPrice.txt', $sql);  
            //bcost	bcount
            //27130	43
        }
        $results = $conn->query($sql);

        return  $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    //SELECT h.id as hid, b.id as bid, p.id as pid,s.id as sid,service_type, Name_by_spcimen, count(b.cost) as bcost_count, SUM(b.cost) as bcost_sum FROM service_billing as b JOIN hospital as h JOIN service_type as s JOIN patient as p WHERE p.phospital_id = 0 and p.phospital_id = h.id and b.slide_type = s.id and b.patient_id = p.id and date(b.import_date) >= '2023-01-01'and date(b.import_date) <= '2023-03-01' GROUP BY service_type ORDER by s.id;
    //   service_type    cost_sum  cost_count
    //   ตรวจธรรมดา       10400     26
    //   ตรวจพิเศษ         800       2
    public static function getCostGroupbyServiceTyoebyHospitalbyDateRange($conn,$hospital_id, $startdate,$enddate, $limit = 0) {
    //        $sql = "SELECT h.id as hid, b.id as bid, p.id as pid,s.id as sid,service_type, Name_by_spcimen, count(b.cost) as bcost_count, SUM(b.cost) as bcost_sum FROM service_billing as b JOIN hospital as h JOIN service_type as s JOIN patient as p WHERE p.phospital_id = $hospital_id and p.phospital_id = h.id and b.slide_type = s.id and b.patient_id = p.id and date(b.import_date) >= '{$startdate}'and date(b.import_date) <= '{$enddate}' GROUP BY service_type ORDER by s.id;";
    //        if($limit != 0){
    //            $sql = $sql . " LIMIT $limit ";
    //        }
        if($GLOBALS['isBillByAcceptDate']){
            $sql = "SELECT h.id as hid, b.id as bid, p.id as pid,s.id as sid,                        \n".
                "  service_type,                                                                     \n".
                "  service_type_bill,                                                                 \n".
                "  Name_by_spcimen,                                                                  \n".
                "  count(b.cost) as bcost_count,                                                     \n".
                "  SUM(b.cost) as bcost_sum                                                          \n".
                "FROM patient as p                                                                   \n".
                "JOIN service_billing as b ON  b.patient_id = p.id                                   \n".
                "JOIN hospital as h ON p.phospital_id = h.id                                     \n";
                if( ! ((int)$hospital_id == -1) ){
                $sql.="and p.phospital_id = $hospital_id                                              \n";
                }
            $sql.="JOIN service_type as s ON b.slide_type = s.id                             \n".
                "WHERE date(p.date_1000) >= '{$startdate}'and date(p.date_1000) <= '{$enddate}'        \n".
                "and  p.movetotrash = 0                                                              \n".
                "      GROUP BY service_type                                                         \n".
                "      ORDER by s.order_list                                                          \n";

        }

        if($GLOBALS['isBillByServiceDate']){
            $sql = "SELECT h.id as hid, b.id as bid, p.id as pid,s.id as sid,                        \n".
                "  service_type,                                                                     \n".
                "  service_type_bill,                                                                 \n".
                "  Name_by_spcimen,                                                                  \n".
                "  count(b.cost) as bcost_count,                                                     \n".
                "  SUM(b.cost) as bcost_sum                                                          \n".
                "FROM patient as p                                                                   \n".
                "JOIN service_billing as b ON  b.patient_id = p.id                                   \n".
                "JOIN hospital as h ON p.phospital_id = h.id                                     \n";
                if( ! ((int)$hospital_id == -1) ){
                $sql.="and p.phospital_id = $hospital_id                                              \n";
                }
            $sql.="JOIN service_type as s ON b.slide_type = s.id                             \n".
                "WHERE date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'        \n".
                "and  p.movetotrash = 0                                                              \n".
                "      GROUP BY service_type                                                         \n".
                "      ORDER by s.order_list                                                          \n";

        }    


            
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getCostGroupbyServiceTyoebyHospitalbyDateRange.txt', $sql);
        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public static function getCostGroupbyServiceTyoebyPathobyDateRange($conn, $patho_id, $startdate,$enddate) {
        if($GLOBALS['isBillByAcceptDate']){
            $sql="SELECT h.id as hid, b.id as bid, p.id as pid,s.id as sid,                           \n".
                "  service_type,                                                                     \n".
                "  service_type_bill,                                                                \n".
                "  Name_by_spcimen,                                                                  \n".
                "  count(b.cost) as bcost_count,                                                     \n".
                "  SUM(b.cost) as bcost_sum                                                          \n".
                "FROM patient as p                                                                   \n".
                "JOIN service_billing as b ON  b.patient_id = p.id                                   \n".
                "JOIN hospital as h ON p.phospital_id = h.id                                    \n".
                "JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                    \n";
                if( ! ((int)$patho_id == -1) ){
                $sql.="      and job_pathologist.user_id = {$patho_id}                                 \n";
                }
            $sql.= "and job_pathologist.job_role_id = 5                                               \n".
                "JOIN service_type as s ON b.slide_type = s.id                                  \n".
                "WHERE date(p.date_1000) >= '$startdate'and date(p.date_1000) <= '$enddate'           \n".
                "       and  p.movetotrash = 0                                                           \n".
                "GROUP BY service_type                                                               \n".
                "ORDER by s.order_list                                                                \n";
        }

        if($GLOBALS['isBillByServiceDate']){
            $sql="SELECT h.id as hid, b.id as bid, p.id as pid,s.id as sid,                           \n".
                "  service_type,                                                                     \n".
                "  service_type_bill,                                                                \n".
                "  Name_by_spcimen,                                                                  \n".
                "  count(b.cost) as bcost_count,                                                     \n".
                "  SUM(b.cost) as bcost_sum                                                          \n".
                "FROM patient as p                                                                   \n".
                "JOIN service_billing as b ON  b.patient_id = p.id                                   \n".
                "JOIN hospital as h ON p.phospital_id = h.id                                         \n".
                "JOIN job as job_pathologist ON job_pathologist.patient_id = p.id                    \n";
                if( ! ((int)$patho_id == -1) ){
                $sql.="      and job_pathologist.user_id = {$patho_id}                                 \n";
                }
            $sql.= "and job_pathologist.job_role_id = 5                                               \n".
                "JOIN service_type as s ON b.slide_type = s.id                                  \n".
                "WHERE date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'          \n".
                "       and  p.movetotrash = 0                                                           \n".
                "GROUP BY service_type                                                               \n".
                "ORDER by s.order_list                                                                \n";
        }
        
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getCostGroupbyServiceTyoebyPathobyDateRange.txt', $sql);
            //hid	bid	pid	sid	service_type	service_type_bill	Name_by_spcimen	bcost_count	bcost_sum
            //21	128	373	1	1 ตรวจชิ้นเนื้อศัลยพยาธิวิทยา (SN or IN)	ค่าตรวจชิ้นเนื้อศัลยพยาธิวิทยา	สิ่งส่งตรวจ (SN or IN) with group type 1	409	309460
            //9         1908	1537	4	2 ตรวจเซลล์วิทยา (CN)                                     ค่าตรวจเซลล์วิทยา	สิ่งส่งตรวจ CN group type 1	16	8400
            //31	1952	1632	5	3 ตรวจเซลล์มะเร็งปากมดลูก (PN or LN)	ค่าตรวจเซลล์มะเร็งปากมดลูก	สิ่งส่งตรวจ PN LN group type 1	1	120
            //20	98	401	2	5 ตรวจพิเศษ (Special Staining)	ค่าตรวจพิเศษ (Special Staining)	ย้อมพิเศษ group type 2	92	71365
            //10	1831	638	3	6 ตรวจพิเศษ (Immuno Staining)	ค่าตรวจพิเศษ (Immuno Staining)	ย้อมพิเศษ group type 2	38	35000

        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public static function getCostGroupbyServiceTyoebyCytobyDateRange($conn, $cytologist_id, $startdate,$enddate) {
        if($GLOBALS['isBillByAcceptDate']){
            $sql="SELECT h.id as hid, b.id as bid, p.id as pid,s.id as sid,                           \n".
                "  service_type,                                                                     \n".
                "  service_type_bill,                                                                \n".
                "  Name_by_spcimen,                                                                  \n".
                "  count(b.cost) as bcost_count,                                                     \n".
                "  SUM(b.cost) as bcost_sum                                                          \n".
                "FROM patient as p                                                                   \n".
                "JOIN service_billing as b ON  b.patient_id = p.id                                   \n".
                "JOIN hospital as h ON p.phospital_id = h.id                                    \n".
                "JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                      \n";
                if( ! ((int)$cytologist_id == -1) ){
                $sql.="        and job_cytologist.user_id = $cytologist_id                           \n";
                }
            $sql.=" and job_cytologist.job_role_id = 7                                               \n".
                "JOIN service_type as s ON b.slide_type = s.id                                  \n".
                "WHERE date(p.date_1000) >= '$startdate'and date(p.date_1000) <= '$enddate'           \n".
                "         and  p.movetotrash = 0                                                      \n".
                "GROUP BY service_type                                                                  \n".
                "ORDER by s.order_list                                                                 \n";
        }

        if($GLOBALS['isBillByServiceDate']){
            $sql="SELECT h.id as hid, b.id as bid, p.id as pid,s.id as sid,                           \n".
                "  service_type,                                                                     \n".
                "  service_type_bill,                                                                \n".
                "  Name_by_spcimen,                                                                  \n".
                "  count(b.cost) as bcost_count,                                                     \n".
                "  SUM(b.cost) as bcost_sum                                                          \n".
                "FROM patient as p                                                                   \n".
                "JOIN service_billing as b ON  b.patient_id = p.id                                   \n".
                "JOIN hospital as h ON p.phospital_id = h.id                                    \n".
                "JOIN job as job_cytologist ON job_cytologist.patient_id = p.id                      \n";
                if( ! ((int)$cytologist_id == -1) ){
                $sql.="        and job_cytologist.user_id = $cytologist_id                           \n";
                }
            $sql.=" and job_cytologist.job_role_id = 7                                               \n".
                "JOIN service_type as s ON b.slide_type = s.id                                  \n".
                "WHERE date(b.create_date) >= '{$startdate}' and date(b.create_date) <= '{$enddate}'           \n".
                "         and  p.movetotrash = 0                                                      \n".
                "GROUP BY service_type                                                                  \n".
                "ORDER by s.order_list                                                                 \n";
        }
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('getCostGroupbyServiceTyoebyCytobyDateRange.txt', $sql);
            //hid	bid	pid	sid	service_type	service_type_bill	Name_by_spcimen	bcost_count	bcost_sum
            //21	128	373	1	1 ตรวจชิ้นเนื้อศัลยพยาธิวิทยา (SN or IN)	ค่าตรวจชิ้นเนื้อศัลยพยาธิวิทยา	สิ่งส่งตรวจ (SN or IN) with group type 1	409	309460
            //9         1908	1537	4	2 ตรวจเซลล์วิทยา (CN)                                     ค่าตรวจเซลล์วิทยา	สิ่งส่งตรวจ CN group type 1	16	8400
            //31	1952	1632	5	3 ตรวจเซลล์มะเร็งปากมดลูก (PN or LN)	ค่าตรวจเซลล์มะเร็งปากมดลูก	สิ่งส่งตรวจ PN LN group type 1	1	120
            //20	98	401	2	5 ตรวจพิเศษ (Special Staining)	ค่าตรวจพิเศษ (Special Staining)	ย้อมพิเศษ group type 2	92	71365
            //10	1831	638	3	6 ตรวจพิเศษ (Immuno Staining)	ค่าตรวจพิเศษ (Immuno Staining)	ย้อมพิเศษ group type 2	38	35000

        }
        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }


    public function create($conn) {
        $cur_thai_time = Util::get_curreint_thai_date_time();
        //$specimen_id
        $sql = "INSERT INTO `service_billing` (`id`, `req_date` ,`req_id`, `specimen_id` , `patient_id`, `number`, `name`, `lastname`, `slide_type`, `code_description`, `description`,   `nm_slide_count` ,  `sp_slide_block` ,  `sp_slide_count` ,  `import_date`, `report_date`, " ./*`hospital`,*/" `hospital_id`, `hn`, `send_doctor`, `pathologist`, `cost`, `comment`,`create_date`) \n"
        . "VALUES                     (NULL, NULL , :req_id , :specimen_id  , :patient_id,  :number , :name , :lastname,  :slide_type , :code_description , :description ,   :nm_slide_count  ,   :sp_slide_block ,  :sp_slide_count  ,  :import_date , :report_date, "./*:hospital,*/"  :hospital_id,  :hn,  :send_doctor , :pathologist , :cost, :comment , '{$cur_thai_time}' )";


        if($GLOBALS['isSqlWriteFileForDBG']){
                Util::writeFile('ServiceBilling_create.txt', $sql);   
        }
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':req_id',$this->req_id ,PDO::PARAM_INT);      //int(11)
        
        $stmt->bindValue(':specimen_id',$this->specimen_id ,PDO::PARAM_INT);      //int(11)		
        $stmt->bindValue(':patient_id' ,$this->patient_id  ,PDO::PARAM_INT);      //int(11)		
        $stmt->bindValue(':number'     ,$this->number      ,PDO::PARAM_STR);      //varchar(32)	patient pnum
        $stmt->bindValue(':name'       ,$this->name        ,PDO::PARAM_STR);      //varchar(32)	
        $stmt->bindValue(':lastname'   ,$this->lastname    ,PDO::PARAM_STR);      //varchar(32)	
        $stmt->bindValue(':slide_type' ,$this->slide_type  ,PDO::PARAM_STR);      //tinyint(4)	
        $stmt->bindValue(':code_description' ,$this->code_description  ,PDO::PARAM_STR);   
        
        $stmt->bindValue(':nm_slide_count' ,$this->nm_slide_count ,PDO::PARAM_INT);      //int(11)
        $stmt->bindValue(':sp_slide_block' ,$this->sp_slide_block ,PDO::PARAM_STR); 
        $stmt->bindValue(':sp_slide_count' ,$this->sp_slide_count ,PDO::PARAM_INT);      //int(11)

        $stmt->bindValue(':description',$this->description ,PDO::PARAM_STR);      //text	        
        $stmt->bindValue(':import_date',$this->import_date ,PDO::PARAM_STR);      //date			
        $stmt->bindValue(':report_date',$this->report_date ,PDO::PARAM_STR);      //varchar(16)	
//        $stmt->bindValue(':hospital'   ,$this->hospital    ,PDO::PARAM_STR);      //varchar(32)	
        $stmt->bindValue(':hospital_id'   ,$this->hospital_id    ,PDO::PARAM_INT);      //varchar(32)	
        $stmt->bindValue(':hn'         ,$this->hn          ,PDO::PARAM_STR);      //varchar(32)	
        $stmt->bindValue(':send_doctor',$this->send_doctor ,PDO::PARAM_STR);      //varchar(32)	
        $stmt->bindValue(':pathologist',$this->pathologist ,PDO::PARAM_STR);      //varchar(32)	
        $stmt->bindValue(':cost'       ,$this->cost        ,PDO::PARAM_STR);      //int(11)		
        $stmt->bindValue(':comment'    ,$this->comment     ,PDO::PARAM_STR);      //text	

        //var_dump($stmt);

        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }
    
    
    public static function getInitObj()
    {
        $billing = new ServiceBilling();
        
        $billing->patient_id  =0;      //int(11)	
        $billing->req_id = 0;          //Request id
        $billing->number      ="";      //varchar(32)	patient pnum
        $billing->name        ="";      //varchar(32)	
        $billing->lastname    ="";      //varchar(32)	
        $billing->slide_type  =0;      //tinyint(4)
        $billing->code_description = "";
        $billing->description ="";      //text	

        $billing->nm_slide_count=0; //text	
        $billing->sp_slide_block=""; //text	
        $billing->sp_slide_count=0; //text

        
        $billing->import_date ="0000-00-00 00:00:00";      //datetime			
        $billing->report_date =null;      //datetime	
        $billing->hospital    ="";      //varchar(32)	
        $billing->hospital_id =0;      //varchar(32)	
        $billing->hn          ="";      //varchar(32)	
        $billing->send_doctor ="";      //varchar(32)	
        $billing->pathologist ="";      //varchar(32)	
        $billing->cost        =0;      //int(11)		
        $billing->comment     ="";      //text
        
        return $billing;
       
    }

    public static function delete($conn,$id)
    {
        
        $sql = "DELETE FROM `service_billing` WHERE `service_billing`.`id` = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function update($conn)
    {

        $sql = "UPDATE service_billing
                    SET cost = :cost,
                        description =:description,
                    WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
        $stmt->bindValue(':cost', $this->cost, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function getByID($conn, $id = 0)
    {
        $sql = "SELECT * FROM `service_billing` ";

        $sql = $sql . " WHERE id=$id;";
        
        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
//    public static function setRequestIDifNotSet($conn, $patient_id, $req_id)
//    {
//        $sql = "UPDATE `service_billing` "
//                . "SET `req_id` = :req_id "
//                . "WHERE (`patient_id` = :patient_id and `req_id` = 0)";
//
//        $stmt = $conn->prepare($sql);
//
//        $stmt->bindValue(':req_id', $req_id, PDO::PARAM_INT);
//        $stmt->bindValue(':patient_id', $patient_id, PDO::PARAM_INT);
//
//        return $stmt->execute();
//    }
    
    public static function setRequestIDifNotSet_SlideType2367($conn, $patient_id, $req_id,$req_date)
    {
        $sql = "UPDATE `service_billing` "
                . "SET `req_id` = :req_id,  `req_date` = :req_date "
                . "WHERE (`patient_id` = :patient_id and `req_id` = 0 and (slide_type = 2 or slide_type = 3 or slide_type = 6 or slide_type = 7  ) )";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':req_id', $req_id, PDO::PARAM_INT);
        $stmt->bindValue(':req_date', $req_date, PDO::PARAM_STR);
        $stmt->bindValue(':patient_id', $patient_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
    
    public static function setFinishDate($conn, $rid, $finishdate)
    {
        
        $sql = 'UPDATE service_billing '
        . ' SET req_finish_date = :req_finish_date '
        . ' WHERE req_id = :req_id';

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':req_id', $rid, PDO::PARAM_INT);
        $stmt->bindValue(':req_finish_date', $finishdate, PDO::PARAM_STR);
        return $stmt->execute();
        
    }
    
        public static function setCode2($conn, $id, $code2)
    {
        
        $sql = 'UPDATE service_billing '
        . ' SET code2 = :code2 '
        . ' WHERE id = :id';

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id , PDO::PARAM_INT);
        $stmt->bindValue(':code2', $code2, PDO::PARAM_INT);
        return $stmt->execute();
        
    }

}
