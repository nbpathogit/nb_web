<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InternalNote
 *
 * @author USER
 */
class InternalNote {
    //put your code here
    public $id;
    public $patient_id;
    public $note;
    public $creater;
    public $editer;
    public $created_date;
    public $edit_date;
    
        public static function getInitObj()
    {
        $iniNote = new InternalNote();

	$iniNote->id=NULL;
	$iniNote->patient_id=NULL;
	$iniNote->note=NULL;
	$iniNote->creater=NULL;
	$iniNote->editer=NULL;
	$iniNote->created_date=NULL;
	$iniNote->edit_date=NULL;
        
        return $iniNote;
    }

    public function create($conn)
    {

        $sql = "INSERT INTO `internal_note` (`id`, `patient_id`, `note`, `creater`, `editer`, `created_date`, `edit_date`) "
                . "VALUES                   (NULL, :patient_id,  :note,  :creater,  :editer,  :created_date,  :edit_date)";
  
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':patient_id', $this->patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':note', $this->note, PDO::PARAM_STR);
        $stmt->bindValue(':creater', $this->creater, PDO::PARAM_STR);
        $stmt->bindValue(':editer', $this->editer, PDO::PARAM_STR);
        $stmt->bindValue(':created_date', $this->created_date, PDO::PARAM_STR);
        $stmt->bindValue(':edit_date', $this->edit_date, PDO::PARAM_STR);


        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            return $this->id;
        } else {
            return false;
        }
    }

    public static function getAll($conn, $patient_id = 0)
    {
        $sql = "SELECT * FROM `internal_note` ";

        $sql = $sql . " WHERE 1 ";

        if ($patient_id != 0) {
            $sql = $sql . " and patient_id = " . $patient_id;
        }
        $sql = $sql . " ORDER BY id DESC";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    
    
}
