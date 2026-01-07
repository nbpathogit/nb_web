<?php

class LabelPrint {

    //put your code here
    public $id;
    public $userid;
    public $sn_num;
    public $hn_num;
    public $patho_abbreviation;
    public $speciment_abbreviation;
    public $accept_date;
    public $company_name;

     public function create($conn) {

        $sql = "INSERT INTO `labelprint_tmp_a`(`id`, `userid`, `sn_num`, `hn_num`, `patho_abbreviation`, `speciment_abbreviation`, `accept_date`, `company_name`) "
               ."                      VALUES (NULL, :userid, :sn_num,  :hn_num,  :patho_abbreviation,  :speciment_abbreviation,   :accept_date,  :company_name );";


        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':userid', $this->userid, PDO::PARAM_INT);
        $stmt->bindValue(':sn_num', $this->sn_num, PDO::PARAM_STR);
        $stmt->bindValue(':hn_num', $this->hn_num, PDO::PARAM_STR);
        $stmt->bindValue(':patho_abbreviation', $this->patho_abbreviation, PDO::PARAM_STR);
        $stmt->bindValue(':speciment_abbreviation', $this->speciment_abbreviation, PDO::PARAM_STR);
        $stmt->bindValue(':accept_date', $this->accept_date, PDO::PARAM_STR);
        $stmt->bindValue(':company_name', "N.B.Pathology", PDO::PARAM_STR);

        //var_dump($stmt);

        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            //            $stmt->debugDumpParams();
            return true;
        } else {
            //            $stmt->debugDumpParams();
            return false;
        }
    }
    
    public static function createByLoopNum($conn,$userid,$sn_num,$hn_num,$patho_abbreviation,$accept_date,$company_name,$letter,$start_num,$end_num) {
        $result = TRUE;
        try {
            for ($i = $start_num; $i <= $end_num; $i++) { 
                $speciment_abbreviation = $letter . $i;
                $sql = "INSERT INTO `labelprint_tmp_a`(`id`, `userid`, `sn_num`, `hn_num`, `patho_abbreviation`, `speciment_abbreviation`, `accept_date`, `company_name`) "
                   ."                          VALUES (NULL, '{$userid}','{$sn_num}','{$hn_num}', '{$patho_abbreviation}',  '{$speciment_abbreviation}',   '{$accept_date}',  '{$company_name}' );";
                //Util::writeFile("aaa.txt", $sql);
                   
                $stmt = $conn->prepare($sql);
                $stmt->execute();
            }
        } catch (Exception $e) {
            
            $result = "Caught exception: " . $e->getMessage();
            //Util::writeFile("bbb.txt", $result);
        }
        return $result;
    }
    
    public static function deleteAllbyUserID($conn, $userid)
    {
        $sql = "DELETE FROM labelprint_tmp_a
                WHERE userid = :userid";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);

        return $stmt->execute();
        
        
    }
    
    public static function getAllbyUserID($conn, $userid) {
        $sql = "SELECT * 
                FROM labelprint_tmp_a ";

        
        $sql = $sql . " WHERE userid = " . $userid;
        

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

}
