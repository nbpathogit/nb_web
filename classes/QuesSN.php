<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class QuesSN {

    /**
     * Unique identifier
     * @var integer
     */
    public $id;
    public $patient_id;
    public $patient_num;
    public $score_thickness;
    public $score_staining;
    public $score_mounting;
    public $score_labeling;
    public $score_contaminate;
    public $note;
    public $errors = [];
    
    public static function getInitObj() {
        $quesSN = new QuesSN();

        $quesSN->id=NULL;
        $quesSN->patient_id=NULL;
        $quesSN->patient_num="";
        $quesSN->score_thickness=5;
        $quesSN->score_staining=5;
        $quesSN->score_mounting=5;
        $quesSN->score_labeling=5;
        $quesSN->score_contaminate=5;
        $quesSN->note="";   

        return $quesSN;
    }

    public function create($conn) {

        $curDateTime = Util::get_curreint_thai_date_time();

        $sql = "INSERT INTO questionnaire_quality_sn ( patient_id, patient_num, score_thickness, score_staining, score_mounting, score_labeling, score_contaminate, note) " .
               " VALUES                              ( :patient_id, :patient_num, :score_thickness, :score_staining, :score_mounting, :score_labeling, :score_contaminate, :note)";

        $stmt = $conn->prepare($sql);

        //var_dump($this->name);

        $stmt->bindValue(':patient_id', $this->patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':patient_num', $this->patient_num, PDO::PARAM_STR);
        $stmt->bindValue(':score_thickness', $this->score_thickness, PDO::PARAM_INT);
        $stmt->bindValue(':score_staining', $this->score_staining, PDO::PARAM_INT);
        $stmt->bindValue(':score_mounting', $this->score_mounting, PDO::PARAM_INT);
        $stmt->bindValue(':score_labeling', $this->score_labeling, PDO::PARAM_INT);
        $stmt->bindValue(':score_contaminate', $this->score_contaminate, PDO::PARAM_INT);
        $stmt->bindValue(':note', $this->note, PDO::PARAM_STR);
        
        if($GLOBALS['isSqlWriteFileForDBG']){
            Util::writeFile('QuesSN.txt', $sql);   
        }

        //var_dump($stmt);
        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            return true;
        } else {
            $this->errors[] = "บันทึกข้อมูลไม่สำเร็จ";
            return false;
        }
    }
    
    public function update($conn) {

        $curDateTime = Util::get_curreint_thai_date_time();

        $sql = "UPDATE `questionnaire_quality_sn` "
                . "SET "
                . "`score_thickness` = :score_thickness, "
                . "`score_staining` = :score_staining, "
                . "`score_mounting` = :score_mounting, "
                . "`score_labeling` = :score_labeling, "
                . "`score_contaminate` = :score_contaminate, "
                . "`note` = :note "
                . "WHERE `questionnaire_quality_sn`.`id` = :id";

        $stmt = $conn->prepare($sql);

        //var_dump($this->name);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':score_thickness', $this->score_thickness, PDO::PARAM_INT);
        $stmt->bindValue(':score_staining', $this->score_staining, PDO::PARAM_INT);
        $stmt->bindValue(':score_mounting', $this->score_mounting, PDO::PARAM_INT);
        $stmt->bindValue(':score_labeling', $this->score_labeling, PDO::PARAM_INT);
        $stmt->bindValue(':score_contaminate', $this->score_contaminate, PDO::PARAM_INT);
        $stmt->bindValue(':note', $this->note, PDO::PARAM_STR);

        //var_dump($stmt);
        if ($stmt->execute()) {
            return true;
        } else {
            $this->errors[] = "บันทึกข้อมูลไม่สำเร็จ";
            return false;
        }
    }
    
    

    public static function getAll($conn, $id = 0) {
        $sql = "SELECT * FROM `questionnaire_quality_sn` ";

        if ($id != 0) {
            $sql = $sql . " WHERE id = " . $id;
        }

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllbyPatientID($conn, $id) {
        $sql = "SELECT * FROM `questionnaire_quality_sn` ";

        if ($id != 0) {
            $sql = $sql . " WHERE patient_id = " . $id;
        }

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

}
