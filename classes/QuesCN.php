<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class QuesCN {

    /**
     * Unique identifier
     * @var integer
     */
    public $id;             
    public $patient_id;     
    public $patient_num;    
    public $score_specimen; 
    public $score_staining; 
    public $score_mounting; 
    public $score_labeling; 
    public $note;           

    public $errors = [];
    
    public static function getInitObj() {
        $quesCN = new QuesCN();

        $quesCN->id=NULL;             
        $quesCN->patient_id=NULL;     
        $quesCN->patient_num="";    
        $quesCN->score_specimen=5; 
        $quesCN->score_staining=5; 
        $quesCN->score_mounting=5; 
        $quesCN->score_labeling=5; 
        $quesCN->note=""; 

        return $quesCN;
    }

    public function create($conn) {

        $curDateTime = Util::get_curreint_thai_date_time();

        $sql = "INSERT INTO questionnaire_quality_cn ( id ,patient_id , patient_num, score_specimen, score_staining, score_mounting ,score_labeling, note) " .
               " VALUES                              ( null ,:patient_id , :patient_num, :score_specimen, :score_staining, :score_mounting ,:score_labeling, :note)";

        $stmt = $conn->prepare($sql);

        //var_dump($this->name);

        $stmt->bindValue(':patient_id', $this->patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':patient_num', $this->patient_num, PDO::PARAM_STR);
        $stmt->bindValue(':score_specimen', $this->score_specimen, PDO::PARAM_INT);
        $stmt->bindValue(':score_staining', $this->score_staining, PDO::PARAM_INT);
        $stmt->bindValue(':score_mounting', $this->score_mounting, PDO::PARAM_INT);
        $stmt->bindValue(':score_labeling', $this->score_labeling, PDO::PARAM_INT);
        $stmt->bindValue(':note', $this->note, PDO::PARAM_STR);


        //var_dump($stmt);
        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            return true;
        } else {
            $this->errors[] = "บันทึกข้อมูลไม่สำเร็จ";
            return false;
        }
    }
    
        public static function update($conn,$id,$patient_id,$a1,$a2,$a3,$a4,$qcomment) {

        $curDateTime = Util::get_curreint_thai_date_time();

        $sql = "UPDATE `questionnaire_quality_cn` SET "
                . "`score_specimen` = :score_specimen, "
                . "`score_staining` = :score_staining, "
                . "`score_mounting` = :score_mounting, "
                . "`score_labeling` = :score_labeling, "
                . "`note` = :note "
                . "WHERE `questionnaire_quality_cn`.`id` = :id";

        $stmt = $conn->prepare($sql);

        //var_dump($this->name);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->bindValue(':score_specimen', $a1, PDO::PARAM_INT);
        $stmt->bindValue(':score_staining', $a2, PDO::PARAM_INT);
        $stmt->bindValue(':score_mounting', $a3, PDO::PARAM_INT);
        $stmt->bindValue(':score_labeling', $a4, PDO::PARAM_INT);
        $stmt->bindValue(':note', $qcomment, PDO::PARAM_STR);

        //var_dump($stmt);
        if ($stmt->execute()) {
            return true;
        } else {
            $this->errors[] = "บันทึกข้อมูลไม่สำเร็จ";
            return false;
        }
    }
    
    

    public static function getAll($conn, $id = 0) {
        $sql = "SELECT * FROM `questionnaire_quality_cn` ";

        if ($id != 0) {
            $sql = $sql . " WHERE id = " . $id;
        }

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
        
    public static function getAllbyDaterange($conn,$range= '0'){
        $sql = "SELECT 
                #*,
                c.id as id, 
                c.patient_id AS patient_id, 
                p.pnum as patient_num ,
                p.date_1000 as accept_date,
                c.score_specimen AS score_thickness,
                c.score_staining AS score_staining,
                c.score_mounting AS score_mounting,
                c.score_labeling AS score_labeling,
                c.note AS note
                FROM `questionnaire_quality_cn` AS c
                JOIN patient as p ON p.id = c.patient_id and p.movetotrash = 0  ";

        
        if ($range != '0') {
            $sql .= " and date(p.date_1000) >= '{$range}' ";
        }

        if($GLOBALS['isSqlWriteFileForDBG']){
//        if(TRUE){
            Util::writeFile('QuesSN_getAllbydaterange.txt', $sql);   
        }
        
        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
        
    }


    public static function getAllbyPatientID($conn, $id) {
        $sql = "SELECT * FROM `questionnaire_quality_cn` ";

        if ($id != 0) {
            $sql = $sql . " WHERE patient_id = " . $id;
        }

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

}
