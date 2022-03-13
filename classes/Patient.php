<?php

/**
 * Patient
 *
 * A piece of writing for publication
 */
class Patient {

    /**
     * Uniqure identifier
     * @var integer
     */
    public $id;

    /**
     * The patient number
     * @var string
     */
    public $pnum;
    public $plabnum;
    public $pname;
    public $pgender;
    public $plastname;
    public $pedge;
    public $date_1000;
    public $date_2000;
    public $date_3000;
    public $date_6000;
    public $date_8000;
    public $date_10000;
    public $date_12000;
    public $date_13000;
    public $date_14000;
    public $date_20000;
    public $date_first_report;
    public $status_id;
    public $priority_id;
    public $phospital_id;
    public $phospital_num;
    public $ppathologist_id;
    public $pspecimen_id;
    public $pclinician_id;
    public $ppathologist2_id;
    public $p_cross_section_id;
    public $p_cross_section_ass_id;
    public $p_slide_prep_id;
    public $p_slide_prep_sp_id;
    public $pprice;
    public $pspprice;
    public $p_rs_specimen;
    public $p_rs_clinical_diag;
    public $p_rs_gross_desc;
    public $p_rs_microscopic_desc;
    public $p_rs_diagnosis;
    public $p_uresult_id;
    public $p_speciment_type;
    public $p_slide_lab_id;
    public $p_slide_lab_price;

    /**
     * Validation errors
     * @var array
     */
    public $errors = [];

    public static function getTotal($conn, $user_group = "*") {
        return $conn->query("SELECT COUNT(*) FROM patient")->fetchColumn();
    }

    public static function getPage($conn, $limit, $offset) {

        $sql = "SELECT * ,p.id as pid
                FROM patient as p
                JOIN user as u
                JOIN hospital as h
                JOIN priority as pri
                JOIN status as s
                WHERE p.ppathologist_id = u.id
                and p.phospital_id = h.id
                and p.priority_id = pri.id
                and p.status_id = s.id
                ORDER BY  p.id DESC
                

                LIMIT :limit
                OFFSET :offset";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all the articles
     *
     * @param object $conn Connection to the database
     *
     * @return array An associative array of all the article records
     */
    public static function getAll($conn, $id = 0) {
        $sql = "SELECT * 
                FROM patient ";

        if ($id != 0) {
            $sql = $sql . " WHERE id = " . $id;
        }

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all the articles
     *
     * @param object $conn Connection to the database
     *
     * @return array An associative array of all the article records
     */
    public static function getAllJoin($conn, $id = 0) {
        $sql = "SELECT * ,p.id as pid
                FROM patient as p
                JOIN user as u
                JOIN hospital as h
                JOIN priority as pri
                JOIN status as s
                WHERE p.ppathologist_id = u.id
                and p.phospital_id = h.id
                and p.priority_id = pri.id
                and p.status_id = s.id
                ORDER BY  p.id DESC;";

        if ($id != 0) {
            $sql = $sql . " and p.id = " . $id;
        }

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the article record based on the ID
     *
     * @param object $conn Connection to the database
     * $param integer $id article ID
     * @param String $columns Optional list of columns foe thr select, defaults to *
     *
     * @return An object of this class, or null if not found
     */
    public static function getByID($conn, $id, $columns = '*') {
        $sql = "SELECT $columns
                FROM patient
                WHERE id= :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Patient');

        if ($stmt->execute()) {
            return $stmt->fetch();
        }
    }

    /**
     * Insert a new user
     *
     * @param object $conn Connection to the database
     *
     * @return boolean True if the insert was successful, false otherwise
     */
    public function create($conn) {

//        $sql = "INSERT INTO `patient` (`id`,   `pnum`, `plabnum`,  `pname`,  `pgender`, `plastname`, `pedge`, `date_1000`   ,`date_2000`  ,`date_3000`  ,`date_6000` ,`date_8000` ,`date_10000` ,`date_12000` ,`date_13000` ,`date_14000` ,`date_20000` ,`date_first_report`  ,`status_id`,   `priority_id`, `phospital_id`, `phospital_num`,  `ppathologist_id`,  `pspecimen_id`, `pclinician_id`, `ppathologist2_id`,`p_cross_section_id`,`p_cross_section_ass_id`,`p_slide_prep_id`, `p_slide_prep_sp_id`,  `pprice`, `pspprice`, `p_rs_specimen`, `p_rs_clinical_diag`, `p_rs_gross_desc`, `p_rs_microscopic_desc`, `p_rs_diagnosis`, `p_uresult_id`) "
//                . "            VALUES (NULL,   :pnum,  :plabnum,   :pname,   :pgender,  :plastname,   :pedge,  NULL,        ,NULL         ,NULL         ,NULL        ,NULL        ,NULL         ,NULL         ,NULL         ,NULL         ,NULL         ,NULL                 ,:status_id,    :priority_id,  :phospital_id,  :phospital_num,   :ppathologist_id,   :pspecimen_id,  :pclinician_id,  :ppathologist2_id, :p_cross_section_id, :p_cross_section_ass_id, :p_slide_prep_id,  :p_slide_prep_sp_id,   :pprice,  :pspprice,  :p_rs_specimen, :p_rs_clinical_diag,    :p_rs_gross_desc,  :p_rs_microscopic_desc,  :p_rs_diagnosis , :p_uresult_id);";

        $sql = "INSERT INTO `patient` (`id`,   `pnum`, `plabnum`,  `pname`,  `pgender`, `plastname`, `pedge`,`status_id`,   `priority_id`, `phospital_id`, `phospital_num`,  `ppathologist_id`,  `pspecimen_id`, `pclinician_id`, `ppathologist2_id`,`p_cross_section_id`,`p_cross_section_ass_id`,`p_slide_prep_id`, `p_slide_prep_sp_id`,  `pprice`, `pspprice`, `p_rs_specimen`, `p_rs_clinical_diag`, `p_rs_gross_desc`, `p_rs_microscopic_desc`, `p_rs_diagnosis`, `p_uresult_id`,  `p_speciment_type`,  `p_slide_lab_id`,  `p_slide_lab_price`) "
                . "            VALUES (NULL,   :pnum,  :plabnum,   :pname,   :pgender,  :plastname,   :pedge ,:status_id,    :priority_id,  :phospital_id,  :phospital_num,   :ppathologist_id,   :pspecimen_id,  :pclinician_id,  :ppathologist2_id, :p_cross_section_id, :p_cross_section_ass_id, :p_slide_prep_id,  :p_slide_prep_sp_id,   :pprice,  :pspprice,  :p_rs_specimen, :p_rs_clinical_diag,    :p_rs_gross_desc,  :p_rs_microscopic_desc,  :p_rs_diagnosis , :p_uresult_id  ,:p_speciment_type,  :p_slide_lab_id,  :p_slide_lab_price);";   

        



        $stmt = $conn->prepare($sql);

        //var_dump( $this->name);

        $stmt->bindValue(':pnum', $this->pnum, PDO::PARAM_STR);
        $stmt->bindValue(':plabnum', $this->plabnum, PDO::PARAM_STR);
        $stmt->bindValue(':pname', $this->pname, PDO::PARAM_STR);
        $stmt->bindValue(':pgender', $this->pgender, PDO::PARAM_STR);
        $stmt->bindValue(':plastname', $this->plastname, PDO::PARAM_STR);
        $stmt->bindValue(':pedge', $this->pedge, PDO::PARAM_STR);

//        $stmt->bindValue(':date_1000', $this->date_1000, PDO::PARAM_STR);
//        $stmt->bindValue(':date_2000', $this->date_2000, PDO::PARAM_STR);
//        $stmt->bindValue(':date_3000', $this->date_3000, PDO::PARAM_STR);
//        $stmt->bindValue(':date_6000', $this->date_6000, PDO::PARAM_STR);
//        $stmt->bindValue(':date_8000', $this->date_8000, PDO::PARAM_STR);
//        $stmt->bindValue(':date_10000', $this->date_10000, PDO::PARAM_STR);
//        $stmt->bindValue(':date_12000', $this->date_12000, PDO::PARAM_STR);
//        $stmt->bindValue(':date_13000', $this->date_13000, PDO::PARAM_STR);
//        $stmt->bindValue(':date_14000', $this->date_14000, PDO::PARAM_STR);
//        $stmt->bindValue(':date_20000', $this->date_20000, PDO::PARAM_STR);
//        $stmt->bindValue(':date_first_report', $this->date_first_report, PDO::PARAM_STR);



        $stmt->bindValue(':status_id', $this->status_id, PDO::PARAM_INT);
        $stmt->bindValue(':priority_id', $this->priority_id, PDO::PARAM_INT);
        $stmt->bindValue(':phospital_id', $this->phospital_id, PDO::PARAM_INT);
        $stmt->bindValue(':phospital_num', $this->phospital_num, PDO::PARAM_STR);
        $stmt->bindValue(':ppathologist_id', $this->ppathologist_id, PDO::PARAM_INT);
        $stmt->bindValue(':pspecimen_id', $this->pspecimen_id, PDO::PARAM_INT);
        $stmt->bindValue(':pclinician_id', $this->pclinician_id, PDO::PARAM_INT);
        $stmt->bindValue(':ppathologist2_id', $this->ppathologist2_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_cross_section_id', $this->p_cross_section_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_cross_section_ass_id', $this->p_cross_section_ass_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_slide_prep_id', $this->p_slide_prep_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_slide_prep_sp_id', $this->p_slide_prep_sp_id, PDO::PARAM_INT);
        $stmt->bindValue(':pprice', $this->pprice, PDO::PARAM_STR);
        $stmt->bindValue(':pspprice', $this->pspprice, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_specimen', $this->p_rs_specimen, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_clinical_diag', $this->p_rs_clinical_diag, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_gross_desc', $this->p_rs_gross_desc, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_microscopic_desc', $this->p_rs_microscopic_desc, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_diagnosis', $this->p_rs_diagnosis, PDO::PARAM_STR);
        
        $stmt->bindValue(':p_speciment_type', $this->p_speciment_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_slide_lab_id', $this->p_slide_lab_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_slide_lab_price', $this->p_slide_lab_price, PDO::PARAM_INT);
        
        

        isset($this->p_uresult_id) ? $stmt->bindValue(':p_uresult_id', $this->p_uresult_id, PDO::PARAM_INT) : $stmt->bindValue(':p_uresult_id', 0, PDO::PARAM_INT);
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

    public static function getInit() {
        return [
            [
                "id" => "",
                "pnum" => "",
                "plabnum" => "",
                "pname" => "",
                "pgender" => "",
                "plastname" => "",
                "pedge" => "",
                "date_1000" => null,
                "date_2000" => "",
                "date_3000" => "",
                "date_6000" => "",
                "date_8000" => "",
                "date_10000" => "",
                "date_12000" => "",
                "date_13000" => "",
                "date_14000" => "",
                "date_20000" => "",
                "date_first_report" => "",
                "status_id" => 1000,
                "priority_id" => 5,
                "phospital_id" => 0,
                "phospital_num" => "",
                "ppathologist_id" => 0,
                "pspecimen_id" => 0,
                "pclinician_id" => 0,
                "ppathologist2_id" => 0,
                "p_cross_section_id" => 0,
                "p_cross_section_ass_id" => 0,
                "p_slide_prep_id" => 0,
                "p_slide_prep_sp_id" => 0,
                "pprice" => "",
                "pspprice" => "",
                "p_rs_specimen" => "",
                "p_rs_clinical_diag" => "",
                "p_rs_gross_desc" => "",
                "p_rs_microscopic_desc" => "",
                "p_rs_diagnosis" => "",
                "p_uresult_id" => 0,
                "p_speciment_type" => "lump",
                "p_slide_lab_id" => 0, 
                "p_slide_lab_price" => 0
        ]];
    }

    public static function updateStatus($conn, $id, $status_id) {
        $sql = "UPDATE patient
                SET status_id = :status_id
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':status_id', $status_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function updateStatusWithMoveDATE($conn, $id, $cur_status_id, $next_status_id, $isset_date_first_report) {
        $sql = "UPDATE patient
                SET status_id = :status_id";

        //Update current status as finished date.
        if ($cur_status_id > 0) {
            $sql = $sql . ", date_" . $cur_status_id . " = NOW() ";
        }
        if ($isset_date_first_report == "0" && $next_status_id == "20000") {
            $sql = $sql . ", date_first_report = NOW() ";
        }

        $sql = $sql . " WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':status_id', $next_status_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function update($conn, $id) {

//                            date_1000=:date_1000,
//                    
//                    date_2000=:date_2000,
//                    date_3000=:date_3000,
//                    date_6000=:date_6000,
//                    date_8000=:date_8000,
//                    date_10000=:date_10000,
//
//                    date_13000=:date_13000,
//                    
//                    date_14000=:date_14000,

        $sql = "UPDATE `patient` 
                 SET pnum=:pnum,
                    plabnum=:plabnum,
                    pname=:pname,
                    pgender=:pgender,
                    plastname=:plastname,
                    pedge=:pedge,
                    


                    status_id=:status_id,
                    priority_id=:priority_id,
                    phospital_id=:phospital_id,
                    phospital_num=:phospital_num,
                    ppathologist_id=:ppathologist_id,
                    pspecimen_id=:pspecimen_id,
                    pclinician_id=:pclinician_id,
                    ppathologist2_id=:ppathologist2_id,
                    p_cross_section_id=:p_cross_section_id,
                    p_cross_section_ass_id=:p_cross_section_ass_id,
                    p_slide_prep_id=:p_slide_prep_id,
                    p_slide_prep_sp_id=:p_slide_prep_sp_id,
                    pprice=:pprice,
                    pspprice=:pspprice,
                    p_rs_specimen=:p_rs_specimen,
                    p_rs_clinical_diag=:p_rs_clinical_diag,
                    p_rs_gross_desc=:p_rs_gross_desc,
                    p_rs_microscopic_desc=:p_rs_microscopic_desc,
                    p_rs_diagnosis=:p_rs_diagnosis,
                    p_uresult_id=:p_uresult_id,
                    
                    p_speciment_type=:p_speciment_type,
                    p_slide_lab_id=:p_slide_lab_id,
                    p_slide_lab_price=:p_slide_lab_price
                    
                    
                    WHERE id = :id";

        $stmt = $conn->prepare($sql);


        //var_dump( $this->name);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':pnum', $this->pnum, PDO::PARAM_STR);
        $stmt->bindValue(':plabnum', $this->plabnum, PDO::PARAM_STR);
        $stmt->bindValue(':pname', $this->pname, PDO::PARAM_STR);
        $stmt->bindValue(':pgender', $this->pgender, PDO::PARAM_STR);
        $stmt->bindValue(':plastname', $this->plastname, PDO::PARAM_STR);
        $stmt->bindValue(':pedge', $this->pedge, PDO::PARAM_STR);

//        $stmt->bindValue(':date_1000', $this->date_1000, PDO::PARAM_STR);
//        $stmt->bindValue(':date_13000', $this->date_13000, PDO::PARAM_STR);

        $stmt->bindValue(':status_id', $this->status_id, PDO::PARAM_INT);
        $stmt->bindValue(':priority_id', $this->priority_id, PDO::PARAM_INT);
        $stmt->bindValue(':phospital_id', $this->phospital_id, PDO::PARAM_INT);
        $stmt->bindValue(':phospital_num', $this->phospital_num, PDO::PARAM_STR);
        $stmt->bindValue(':ppathologist_id', $this->ppathologist_id, PDO::PARAM_INT);
        $stmt->bindValue(':pspecimen_id', $this->pspecimen_id, PDO::PARAM_INT);
        $stmt->bindValue(':pclinician_id', $this->pclinician_id, PDO::PARAM_INT);
        $stmt->bindValue(':ppathologist2_id', $this->ppathologist2_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_cross_section_id', $this->p_cross_section_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_cross_section_ass_id', $this->p_cross_section_ass_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_slide_prep_id', $this->p_slide_prep_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_slide_prep_sp_id', $this->p_slide_prep_sp_id, PDO::PARAM_INT);
        $stmt->bindValue(':pprice', $this->pprice, PDO::PARAM_STR);
        $stmt->bindValue(':pspprice', $this->pspprice, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_specimen', $this->p_rs_specimen, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_clinical_diag', $this->p_rs_clinical_diag, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_gross_desc', $this->p_rs_gross_desc, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_microscopic_desc', $this->p_rs_microscopic_desc, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_diagnosis', $this->p_rs_diagnosis, PDO::PARAM_STR);
        $stmt->bindValue(':p_uresult_id', $this->p_uresult_id, PDO::PARAM_INT);
        
        $stmt->bindValue(':p_speciment_type', $this->p_speciment_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_slide_lab_id', $this->p_slide_lab_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_slide_lab_price', $this->p_slide_lab_price, PDO::PARAM_INT);



//        var_dump($stmt);
//die();
        if ($stmt->execute()) {
//            $this->id = $conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

}
