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
    public $patient_id;  //int(11)		
    public $number;      //varchar(32)	
    public $name;        //varchar(32)	
    public $lastname;    //varchar(32)	
    public $slide_type;  //tinyint(4)	
    public $description; //text	        
    public $import_date; //date			
    public $report_date; //varchar(16)	
    public $hospital;    //varchar(32)	
    public $hn;          //varchar(32)	
    public $send_doctor; //varchar(32)	
    public $pathologist; //varchar(32)	
    public $cost;        //int(11)		
    public $comment;     //text	        

    //put your code here

    public static function getAll($conn, $id = 0) {
        $sql = "SELECT *
                FROM billing
                ";

        if ($id != 0) {
            $sql = $sql . " WHERE ";
            $sql = $sql . " patient_id = " . $id;
        }
        $sql = $sql . " ORDER BY id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($conn) {

        $sql = "INSERT INTO hospital (hospital,address,hdetail)
                VALUES ( :hospital,:address,:hdetail)";
        
        $sql = "INSERT INTO `billing` (`id`, `patient_id`, `number`, `name`, `lastname`, `slide_type`, `description`, `import_date`, `report_date`, `hospital`, `hn`, `send_doctor`, `pathologist`, `cost`, `comment`) "
        . "VALUES                     (NULL, :patient_id,  :number , :name , :lastname,  :slide_type , :description , :import_date ,  :report_date, :hospital,  :hn,  :send_doctor , :pathologist , :cost, :comment)";


        $stmt = $conn->prepare($sql);

    $stmt->bindValue(':patient_id' ,$this->patient_id  ,PDO::PARAM_INT);      //int(11)		
    $stmt->bindValue(':number'     ,$this->number      ,PDO::PARAM_STR);      //varchar(32)	patient pnum
    $stmt->bindValue(':name'       ,$this->name        ,PDO::PARAM_STR);      //varchar(32)	
    $stmt->bindValue(':lastname'   ,$this->lastname    ,PDO::PARAM_STR);      //varchar(32)	
    $stmt->bindValue(':slide_type' ,$this->slide_type  ,PDO::PARAM_STR);      //tinyint(4)	
    $stmt->bindValue(':description',$this->description ,PDO::PARAM_STR);      //text	        
    $stmt->bindValue(':import_date',$this->import_date ,PDO::PARAM_STR);      //date			
    $stmt->bindValue(':report_date',$this->report_date ,PDO::PARAM_STR);      //varchar(16)	
    $stmt->bindValue(':hospital'   ,$this->hospital    ,PDO::PARAM_STR);      //varchar(32)	
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
        $billing->description ="";      //text	        
        $billing->import_date ="0000-00-00 00:00:00";      //datetime			
        $billing->report_date =null;      //datetime	
        $billing->hospital    ="";      //varchar(32)	
        $billing->hn          ="";      //varchar(32)	
        $billing->send_doctor ="";      //varchar(32)	
        $billing->pathologist ="";      //varchar(32)	
        $billing->cost        =0;      //int(11)		
        $billing->comment     ="";      //text
        
        return $billing;
       
    }


}
