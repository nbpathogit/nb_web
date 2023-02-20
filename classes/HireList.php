<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HireList
 *
 * @author 
 */
class HireList {

    public $id; //          int(11)		
    public $patient_id; //          int(11)		
    public $patient_number; //          varchar(10)	
    public $name; //          varchar(50)	
    public $cost; //          float		
    public $comment; //         	varchar(70)	
    public $accept_date;
    public $insert_time;
    public $finish_time;

    public static function getInitObj() {
        $hirelist = new HireList();

        $hirelist->id = NULL; //          int(11)		
        $hirelist->patient_id = 0; //          int(11)		
        $hirelist->patient_number = ""; //          varchar(10)	
        $hirelist->name = ""; //          varchar(50)	
        $hirelist->cost = 0.0; //          float		
        $hirelist->comment = ""; //         	varchar(70)	
        $hirelist->accept_time = NULL;
        $hirelist->insert_time = NULL;
        $hirelist->finish_time = NULL;
        return $hirelist;
    }

    public static function getAll($conn, $patient_id = 0) {

        $sql = "SELECT * FROM `hire_list` ";



        if ($patient_id != 0) {
            $sql = $sql . " WHERE patient_id = " . $patient_id;
        }

        $sql = $sql . " ORDER BY id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($conn) {


        $sql = "INSERT INTO `hire_list` (`id`, `outside_id`, `patient_id`, `patient_number`, `name`, `cost`, `comment`, `accept_time`, `finish_time`) "
                . "VALUES               (NULL, :outside_id , :patient_id , :patient_number , :name , :cost , :comment , :accept_time ,     NULL)";

        $stmt = $conn->prepare($sql);

        //$stmt->bindValue(':id'            ,$this->id            ,PDO::PARAM_STR);           
        $stmt->bindValue(':outside_id', $this->outside_id, PDO::PARAM_INT);
        $stmt->bindValue(':patient_id', $this->patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':patient_number', $this->patient_number, PDO::PARAM_STR);
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':cost', $this->cost, PDO::PARAM_STR);
        $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);
        $stmt->bindValue(':accept_time', $this->accept_time, PDO::PARAM_STR);
//        $stmt->bindValue(':insert_time', $this->insert_time, PDO::PARAM_STR);
//        $stmt->bindValue(':finish_time', $this->finish_time, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public static function delete($conn,$id)
    {
        

        $sql = "DELETE FROM `hire_list` WHERE `hire_list`.`id` = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

}
