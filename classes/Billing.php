<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Billing
 *
 * @author 2444536
 */
class Billing {
    
    public $id;          //int(11)
    public $specimen_id; //int(11) link ot id in specimenList table
    public $patient_id;  //int(11)		
    public $number;      //varchar(32)	
    public $name;        //varchar(32)	
    public $lastname;    //varchar(32)	
    public $slide_type;  //tinyint(4)
    public $code_description; //Code Name
    public $description; //text	        
    public $import_date; //date			
    public $report_date; //varchar(16)	
    public $hospital;    //varchar(32)	
    public $hospital_id;    //varchar(32)	
    public $hn;          //varchar(32)	
    public $send_doctor; //varchar(32)	
    public $pathologist; //varchar(32)	
    public $cost;        //int(11)		
    public $comment;     //text	        

    //put your code here

    public static function getAll($conn, $patient_id = 0,$type = 0, $start = '0') {
        $sql = "SELECT *
                FROM billing ";
        
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
    
    public static function getAllforBillPage($conn, $start = '0') {
        $sql = "SELECT *, h.id as hid, b.id as bid FROM".
                " billing as b ".
                " JOIN hospital as h".
                " JOIN service_type as s".
                " JOIN patient as p".
                " WHERE h.id = b.hospital_id and b.slide_type = s.id and b.patient_id = p.id ";
                if ($start != '0') {
                  $sql .= " and date(b.import_date) >= '{$start}'";
                }
                $sql = $sql . " ORDER by b.id ;";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getBillbyHospitalbyDateRange($conn,$hospital_id, $startdate,$enddate, $limit = 0) {
        $sql = "SELECT *, h.id as hid, b.id as bid, p.id as pid FROM".
                " billing as b ".
                " JOIN hospital as h".
                " JOIN service_type as s".
                " JOIN patient as p".
                " WHERE  b.hospital_id = $hospital_id and b.hospital_id = h.id  and b.slide_type = s.id and b.patient_id = p.id ";
                
                $sql .= " and date(b.import_date) >= '{$startdate}'and date(b.import_date) <= '{$enddate}' ";
                
                $sql = $sql . " ORDER by b.id ";
                if($limit != 0){
                    $sql = $sql . " LIMIT $limit ";
                }

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getBillbyHospitalbyDateRangeSumPrice($conn,$hospital_id, $startdate,$enddate, $limit = 0) {
        $sql = "SELECT SUM(b.cost) as bcost, COUNT(*) as bcount FROM".
                " billing as b ".
                " JOIN hospital as h".
                " JOIN service_type as s".
                " JOIN patient as p".
                " WHERE  b.hospital_id = $hospital_id and b.hospital_id = h.id  and b.slide_type = s.id and b.patient_id = p.id ";
                
                $sql .= " and date(b.import_date) >= '{$startdate}'and date(b.import_date) <= '{$enddate}' ";
                
                $sql = $sql . " ORDER by b.id ";
                if($limit != 0){
                    $sql = $sql . " LIMIT $limit ";
                }
//                return $sql;

        $results = $conn->query($sql);

        return  $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    //SELECT h.id as hid, b.id as bid, p.id as pid,s.id as sid,service_type, Name_by_spcimen, count(b.cost) as bcost_count, SUM(b.cost) as bcost_sum FROM billing as b JOIN hospital as h JOIN service_type as s JOIN patient as p WHERE b.hospital_id = 0 and b.hospital_id = h.id and b.slide_type = s.id and b.patient_id = p.id and date(b.import_date) >= '2023-01-01'and date(b.import_date) <= '2023-03-01' GROUP BY service_type ORDER by s.id;
    //   service_type    cost_sum  cost_count
    //   ตรวจธรรมดา       10400     26
    //   ตรวจพิเศษ         800       2
    public static function getCostGroupbyServiceTyoebyHospitalbyDateRange($conn,$hospital_id, $startdate,$enddate, $limit = 0) {
        $sql = "SELECT h.id as hid, b.id as bid, p.id as pid,s.id as sid,service_type, Name_by_spcimen, count(b.cost) as bcost_count, SUM(b.cost) as bcost_sum FROM billing as b JOIN hospital as h JOIN service_type as s JOIN patient as p WHERE b.hospital_id = $hospital_id and b.hospital_id = h.id and b.slide_type = s.id and b.patient_id = p.id and date(b.import_date) >= '{$startdate}'and date(b.import_date) <= '{$enddate}' GROUP BY service_type ORDER by s.id;";
                if($limit != 0){
                    $sql = $sql . " LIMIT $limit ";
                }

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($conn) {

        //$specimen_id
        $sql = "INSERT INTO `billing` (`id`, `specimen_id` , `patient_id`, `number`, `name`, `lastname`, `slide_type`, `code_description`, `description`, `import_date`, `report_date`, `hospital`, `hospital_id`, `hn`, `send_doctor`, `pathologist`, `cost`, `comment`) "
        . "VALUES                     (NULL, :specimen_id  , :patient_id,  :number , :name , :lastname,  :slide_type , :code_description , :description , :import_date ,  :report_date, :hospital,  :hospital_id,  :hn,  :send_doctor , :pathologist , :cost, :comment)";


        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':specimen_id',$this->specimen_id ,PDO::PARAM_INT);      //int(11)		
        $stmt->bindValue(':patient_id' ,$this->patient_id  ,PDO::PARAM_INT);      //int(11)		
        $stmt->bindValue(':number'     ,$this->number      ,PDO::PARAM_STR);      //varchar(32)	patient pnum
        $stmt->bindValue(':name'       ,$this->name        ,PDO::PARAM_STR);      //varchar(32)	
        $stmt->bindValue(':lastname'   ,$this->lastname    ,PDO::PARAM_STR);      //varchar(32)	
        $stmt->bindValue(':slide_type' ,$this->slide_type  ,PDO::PARAM_STR);      //tinyint(4)	
        $stmt->bindValue(':code_description' ,$this->code_description  ,PDO::PARAM_STR);      

        $stmt->bindValue(':description',$this->description ,PDO::PARAM_STR);      //text	        
        $stmt->bindValue(':import_date',$this->import_date ,PDO::PARAM_STR);      //date			
        $stmt->bindValue(':report_date',$this->report_date ,PDO::PARAM_STR);      //varchar(16)	
        $stmt->bindValue(':hospital'   ,$this->hospital    ,PDO::PARAM_STR);      //varchar(32)	
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
        $billing = new Billing();
        
        $billing->patient_id  =0;      //int(11)		
        $billing->number      ="";      //varchar(32)	patient pnum
        $billing->name        ="";      //varchar(32)	
        $billing->lastname    ="";      //varchar(32)	
        $billing->slide_type  =0;      //tinyint(4)
        $billing->code_description = "";
        $billing->description ="";      //text	        
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
        
        $sql = "DELETE FROM `billing` WHERE `billing`.`id` = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function update($conn)
    {

        $sql = "UPDATE billing
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
        $sql = "SELECT * FROM `billing` ";

        $sql = $sql . " WHERE id=$id;";
        
        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

}
