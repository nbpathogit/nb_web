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
    public $sn_type;
    public $sn_year;
    public $sn_run;
    public $plabnum;
    public $ppre_name;
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
    public $p_speciment_type;
    public $p_slide_lab_id;
    public $p_slide_lab_price;
    public $reported_as;
    public $isautoeditmode;
    public $pautoscroll;
    public $p_sp_patho_comment;
    public $second_patho_review;
    public $request_sp_slide;

    /**
     * Validation errors
     * @var array
     */
    public $errors = [];
    public $create_date;
    public $create_by;

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
            $sql = $sql . " WHERE id = " . $id . " and movetotrash = 0";
        } else {
            $sql = $sql . " WHERE movetotrash = 0";
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
    public static function getAllJoin($conn, $id = 0, $start = '0') {
        $sql = "SELECT * ,p.id as pid
                FROM patient as p
                JOIN user as u
                JOIN hospital as h
                JOIN priority as pri
                JOIN status as s
                WHERE p.ppathologist_id = u.id
                and p.phospital_id = h.id
                and p.priority_id = pri.id
                and p.status_id = s.id";


        if ($id != 0) {
            $sql = $sql . " and p.id = " . $id;
        }

        if ($start != '0') {
            $sql .= " and date(p.date_1000) >= '{$start}'";
        }

        $sql .= " and p.movetotrash = 0";
        $sql .= " ORDER BY  p.id DESC;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllJoinWithReported($conn, $id = 0, $start = '0') {
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
                and p.reported_as != ''";

        if ($id != 0) {
            $sql = $sql . " and p.id = " . $id;
        }

        if ($start != '0') {
            $sql .= " and date(p.date_1000) >= '{$start}'";
        }
        $sql .= " and p.movetotrash = 0";
        $sql .= " ORDER BY  p.id DESC;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllJoinID8000($conn, $id = 0, $start = '0') {
        $sql = "SELECT * ,p.id as pid
                FROM patient as p
                JOIN user as u
                JOIN hospital as h
                JOIN priority as pri
                JOIN status as s
                WHERE p.ppathologist_id = u.id
                and p.phospital_id = h.id
                and p.priority_id = pri.id
                and p.request_sp_slide > 0
                and p.status_id = s.id";

        if ($id != 0) {
            $sql = $sql . " and p.id = " . $id;
        }

        if ($start != '0') {
            $sql .= " and date(p.date_1000) >= '{$start}'";
        }
        $sql .= " and p.movetotrash = 0";
        $sql .= " ORDER BY  p.id DESC;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllConfirm($conn, $id = 0, $start = '0') {
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
                and p.second_patho_review > 0";


        if ($id != 0) {
            $sql = $sql . " and p.id = " . $id;
        }

        if ($start != '0') {
            $sql .= " and date(p.date_1000) >= '{$start}'";
        }

        $sql .= " and p.movetotrash = 0";
        $sql .= " ORDER BY  p.id DESC;";

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

        $curDateTime = Util::get_curreint_thai_date_time();

        $sql = "INSERT INTO `patient` (`id`,  `sn_type`    ,  `sn_year`, `sn_run`, `pnum`, `plabnum`, `ppre_name` ,  `pname`,  `pgender`, `plastname`, `pedge`,`status_id`,  `date_1000`,   `priority_id`, `phospital_id`, `phospital_num`,  `ppathologist_id`,  `pspecimen_id`, `pclinician_id`,`p_cross_section_id`,`p_cross_section_ass_id`,`p_slide_prep_id`, `p_slide_prep_sp_id`,  `pprice`, `pspprice`, `p_rs_specimen`, `p_rs_clinical_diag`, `p_rs_gross_desc`, `p_rs_microscopic_desc`,   `p_speciment_type`,  `p_slide_lab_id`,  `p_slide_lab_price`,  `isautoeditmode`, `pautoscroll`,   create_date,       create_by)"
                . "            VALUES     (NULL,  :sn_type     ,  :sn_year,  :sn_run,  :pnum,  :plabnum , :ppre_name  ,  :pname,   :pgender,  :plastname,   :pedge ,:status_id,  :date_1000,   :priority_id,  :phospital_id,  :phospital_num,   :ppathologist_id,   :pspecimen_id,  :pclinician_id, :p_cross_section_id, :p_cross_section_ass_id, :p_slide_prep_id,  :p_slide_prep_sp_id,   :pprice,  :pspprice,  :p_rs_specimen, :p_rs_clinical_diag,    :p_rs_gross_desc,  :p_rs_microscopic_desc,   :p_speciment_type,   :p_slide_lab_id,   :p_slide_lab_price ,  :isautoeditmode , :pautoscroll,   :create_date,     :create_by)";




        $stmt = $conn->prepare($sql);

        //var_dump( $this->name);

        $stmt->bindValue(':pnum', $this->pnum, PDO::PARAM_STR);

        $stmt->bindValue(':sn_type', $this->sn_type, PDO::PARAM_STR);
        $stmt->bindValue(':sn_year', $this->sn_year, PDO::PARAM_STR);
        $stmt->bindValue(':sn_run', $this->sn_run, PDO::PARAM_INT);

        $stmt->bindValue(':plabnum', $this->plabnum, PDO::PARAM_STR);
        $stmt->bindValue(':ppre_name', $this->ppre_name, PDO::PARAM_STR);
        $stmt->bindValue(':pname', $this->pname, PDO::PARAM_STR);
        $stmt->bindValue(':plastname', $this->plastname, PDO::PARAM_STR);
        $stmt->bindValue(':pgender', $this->pgender, PDO::PARAM_STR);
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


        $stmt->bindValue(':p_speciment_type', $this->p_speciment_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_slide_lab_id', $this->p_slide_lab_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_slide_lab_price', $this->p_slide_lab_price, PDO::PARAM_INT);

        $stmt->bindValue(':isautoeditmode', $this->isautoeditmode, PDO::PARAM_STR);
        $stmt->bindValue(':pautoscroll', $this->pautoscroll, PDO::PARAM_STR);

        $stmt->bindValue(':create_date', $curDateTime, PDO::PARAM_STR);
        $stmt->bindValue(':date_1000', $curDateTime, PDO::PARAM_STR);
        $stmt->bindValue(':create_by', $this->create_by, PDO::PARAM_STR);

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
                "sn_year" => "0",
                "sn_run" => 0,
                "pnum" => "",
                "plabnum" => "",
                "ppre_name" => "",
                "pname" => "",
                "pgender" => "",
                "plastname" => "",
                "pedge" => 0,
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
                "p_cross_section_id" => 0,
                "p_cross_section_ass_id" => 0,
                "p_slide_prep_id" => 0,
                "p_slide_prep_sp_id" => 0,
                "pprice" => 0,
                "pspprice" => 0,
                "p_rs_specimen" => "",
                "p_rs_clinical_diag" => "",
                "p_rs_gross_desc" => "",
                "p_rs_microscopic_desc" => "",
                "p_speciment_type" => "lump",
                "p_slide_lab_id" => 0,
                "p_slide_lab_price" => 0,
                "reported_as" => "",
                "isautoeditmode" => "patient_detail_section",
                "pautoscroll" => "patient_detail_section",
                "create_date" => "",
                "create_by" => ""
            ]
        ];
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
        $curDateTime = Util::get_curreint_thai_date_time();
        $sql = "UPDATE patient
                SET status_id = :status_id";

        //Update current status as finished date.
        if ($cur_status_id > 0) {
            $sql = $sql . ", date_" . $cur_status_id . " = '$curDateTime' ";
        }
        if ($isset_date_first_report == "0" && $next_status_id == "20000") {
            $sql = $sql . ", date_first_report = '$curDateTime' ";
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
                    ppre_name=:ppre_name,
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

                   
                    
                    p_speciment_type=:p_speciment_type,
                    p_slide_lab_id=:p_slide_lab_id,
                    p_slide_lab_price=:p_slide_lab_price
                    
                    
                    WHERE id = :id";

        $stmt = $conn->prepare($sql);


        //var_dump( $this->name);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':pnum', $this->pnum, PDO::PARAM_STR);
        $stmt->bindValue(':plabnum', $this->plabnum, PDO::PARAM_STR);
        $stmt->bindValue(':ppre_name', $this->ppre_name, PDO::PARAM_STR);
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

    public function updatePatientDetail($conn, $id) {
        /* array(12) { 
         * ["pnum"]=> string(8) "sc657766" 
         * ["plabnum"]=> string(2) "cc" 
         * ["pgender"]=> string(12) "หญิง" 
         * ["pname"]=> string(18) "กกกกกก" 
         * ["plastname"]=> string(18) "ขขขขขข" 
         * ["pedge"]=> string(1) "9" 
         * ["phospital_num"]=> string(2) "cc" 
         * ["phospital_id"]=> string(1) "0" 
         * ["pclinician_id"]=> string(2) "35" 
         * ["pspecimen_id"]=> string(2) "74" 
         * ["priority_id"]=> string(1) "5" 
         * ["save_patient_detail"]=> string(0) "" }
         */
        $sql = "UPDATE `patient` 
                 SET pnum=:pnum,
                    plabnum=:plabnum,
                    ppre_name=:ppre_name,
                    pname=:pname,
                    pgender=:pgender,
                    plastname=:plastname,
                    pedge=:pedge,
                    phospital_num=:phospital_num,
                    phospital_id=:phospital_id,
                    pclinician_id=:pclinician_id,
                    pspecimen_id=:pspecimen_id,
                    priority_id=:priority_id,
                    status_id=:status_id,
                    isautoeditmode=:isautoeditmode,
                    pautoscroll=:pautoscroll

                    WHERE id = :id";

        $stmt = $conn->prepare($sql);


        //var_dump( $this->name);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':pnum', $this->pnum, PDO::PARAM_STR);
        $stmt->bindValue(':plabnum', $this->plabnum, PDO::PARAM_STR);
        $stmt->bindValue(':ppre_name', $this->ppre_name, PDO::PARAM_STR);
        $stmt->bindValue(':pname', $this->pname, PDO::PARAM_STR);
        $stmt->bindValue(':pgender', $this->pgender, PDO::PARAM_STR);
        $stmt->bindValue(':plastname', $this->plastname, PDO::PARAM_STR);
        $stmt->bindValue(':pedge', $this->pedge, PDO::PARAM_STR);
        $stmt->bindValue(':phospital_num', $this->phospital_num, PDO::PARAM_STR);
        $stmt->bindValue(':phospital_id', $this->phospital_id, PDO::PARAM_INT);
        $stmt->bindValue(':pclinician_id', $this->pclinician_id, PDO::PARAM_INT);
        $stmt->bindValue(':pspecimen_id', $this->pspecimen_id, PDO::PARAM_INT);
        $stmt->bindValue(':priority_id', $this->priority_id, PDO::PARAM_INT);
        $stmt->bindValue(':status_id', $this->status_id, PDO::PARAM_INT);
        $stmt->bindValue(':isautoeditmode', $this->isautoeditmode, PDO::PARAM_STR);
        $stmt->bindValue(':pautoscroll', $this->pautoscroll, PDO::PARAM_STR);

        //        var_dump($stmt);
        //die();
        if ($stmt->execute()) {
            //            $this->id = $conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public function updatePatientPlan($conn, $id) {


        $sql = "UPDATE `patient` 
                 SET p_speciment_type=:p_speciment_type,
                    p_cross_section_id=:p_cross_section_id,
                    p_cross_section_ass_id=:p_cross_section_ass_id,
                    p_slide_prep_id=:p_slide_prep_id,
                    pprice=:pprice,
                    
                    p_slide_lab_id=:p_slide_lab_id,
                    p_slide_lab_price=:p_slide_lab_price,
                    ppathologist_id=:ppathologist_id,
                    isautoeditmode=:isautoeditmode,
                    pautoscroll=:pautoscroll
        
                    WHERE id = :id";

        $stmt = $conn->prepare($sql);


        //var_dump( $this->name);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':p_speciment_type', $this->p_speciment_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_cross_section_id', $this->p_cross_section_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_cross_section_ass_id', $this->p_cross_section_ass_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_slide_prep_id', $this->p_slide_prep_id, PDO::PARAM_INT);
        $stmt->bindValue(':pprice', $this->pprice, PDO::PARAM_STR);

        $stmt->bindValue(':p_slide_lab_id', $this->p_slide_lab_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_slide_lab_price', $this->p_slide_lab_price, PDO::PARAM_INT);
        $stmt->bindValue(':ppathologist_id', $this->ppathologist_id, PDO::PARAM_INT);
        $stmt->bindValue(':isautoeditmode', $this->isautoeditmode, PDO::PARAM_STR);
        $stmt->bindValue(':pautoscroll', $this->pautoscroll, PDO::PARAM_STR);

        //        var_dump($stmt);
        //die();
        if ($stmt->execute()) {
            //            $this->id = $conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public function updateSpcialSlide($conn, $id) {

        $sql = "UPDATE `patient` 
                 SET p_slide_prep_sp_id=:p_slide_prep_sp_id,
                    pspprice=:pspprice,
                    p_sp_patho_comment=:p_sp_patho_comment,

                    isautoeditmode=:isautoeditmode,
                    pautoscroll=:pautoscroll
        
                    WHERE id = :id";

        $stmt = $conn->prepare($sql);


        //var_dump( $this->name);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        $stmt->bindValue(':p_slide_prep_sp_id', $this->p_slide_prep_sp_id, PDO::PARAM_INT);
        $stmt->bindValue(':pspprice', $this->pspprice, PDO::PARAM_STR);

        $stmt->bindValue(':isautoeditmode', $this->isautoeditmode, PDO::PARAM_STR);
        $stmt->bindValue(':pautoscroll', $this->pautoscroll, PDO::PARAM_STR);

        $stmt->bindValue(':p_sp_patho_comment', $this->p_sp_patho_comment, PDO::PARAM_STR);

        //        var_dump($stmt);
        //die();
        if ($stmt->execute()) {
            //            $this->id = $conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public function updateInterimResult($conn, $id) {
        /* array(5) { 
         * ["p_rs_specimen"]=> string(3) "aaa" 
         * ["p_rs_clinical_diag"]=> string(3) "bbb" 
         * ["p_rs_gross_desc"]=> string(3) "ccc" 
         * ["p_rs_microscopic_desc"]=> string(3) "ddd" 
         * ["save_interim_result"]=> string(0) "" }
         */
        $sql = "UPDATE `patient` 
                 SET     p_rs_specimen=:p_rs_specimen,
                    p_rs_clinical_diag=:p_rs_clinical_diag,
                    p_rs_gross_desc=:p_rs_gross_desc,
                    p_rs_microscopic_desc=:p_rs_microscopic_desc,
                    isautoeditmode=:isautoeditmode,
                    pautoscroll=:pautoscroll
                 
                    WHERE id = :id";

        $stmt = $conn->prepare($sql);


        //var_dump( $this->name);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':p_rs_specimen', $this->p_rs_specimen, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_clinical_diag', $this->p_rs_clinical_diag, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_gross_desc', $this->p_rs_gross_desc, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_microscopic_desc', $this->p_rs_microscopic_desc, PDO::PARAM_STR);
        $stmt->bindValue(':isautoeditmode', $this->isautoeditmode, PDO::PARAM_STR);
        $stmt->bindValue(':pautoscroll', $this->pautoscroll, PDO::PARAM_STR);

        //        var_dump($stmt);
        //die();
        if ($stmt->execute()) {
            //            $this->id = $conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public static function updateReportTypeName($conn, $id, $resultname = "") {

        $sql = "UPDATE patient
                SET reported_name = :resultname
                    WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':resultname', $resultname, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function updateReportAs($conn, $id, $resultname = "") {

        $sql = "UPDATE patient
                SET reported_as = :resultname
                    WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':resultname', $resultname, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($conn) {
        $sql = "DELETE FROM patient
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function setAutoScroll($conn, $id, $pautoscroll) {
        $sql = "UPDATE `patient` 
                 SET 
                 pautoscroll=:pautoscroll
                 WHERE id = :id";

        $stmt = $conn->prepare($sql);

        //var_dump( $this->name);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':pautoscroll', $pautoscroll, PDO::PARAM_STR);

        //var_dump($stmt);
        //die();
        if ($stmt->execute()) {
            //            $this->id = $conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public static function clearAutoScroll($conn, $id) {
        $sql = "UPDATE patient
                SET pautoscroll = :pautoscroll
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':pautoscroll', "NA", PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function setisautoeditmode($conn, $id, $isautoeditmode) {
        $sql = "UPDATE `patient` 
                 SET 
                 isautoeditmode=:isautoeditmode
                 WHERE id = :id";

        $stmt = $conn->prepare($sql);

        //var_dump( $this->name);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':isautoeditmode', $isautoeditmode, PDO::PARAM_STR);

        //var_dump($stmt);
        //die();
        if ($stmt->execute()) {
            //            $this->id = $conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public static function clearisautoeditmode($conn, $id) {
        $sql = "UPDATE patient
                SET isautoeditmode = :isautoeditmode
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':isautoeditmode', "NA", PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function movetotrash($conn, $id) {
        $thai_date = Util::get_curreint_thai_date_time();
        Log::add($conn, $_SESSION['log_username'], $_SESSION['log_name'], "Patient::movetotrash(id)", $id, $thai_date);
        $sql = "UPDATE patient
                SET movetotrash = 1
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function delete2($conn, $id) {
        $thai_date = Util::get_curreint_thai_date_time();
        Log::add($conn, $_SESSION['log_username'], $_SESSION['log_name'], "Patient::delete2(id)", $id, $thai_date);
        $sql = "DELETE FROM patient
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    //    ++Example table input
    //    call  get_max_sn_run_by_year($conn,22");
    //    
    //    ++Example table output++
    //    |sn_year |max_sn_run
    //    |22      |2
    //
    public static function get_max_sn_run_by_year($conn, $sn_year, $sn_type) {
        $sql = "SELECT `sn_year`, MAX(`sn_run`) as max_sn_run FROM `patient` as p WHERE sn_year = :sn_year and sn_type = :sn_type GROUP BY `sn_year` ORDER BY `sn_year`;";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':sn_year', $sn_year, PDO::PARAM_STR);
        $stmt->bindValue(':sn_type', $sn_type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Print for debug
    //$count_sn_year = Patient::get_count_sn_year($conn, "22");
    //var_dump($count_sn_year);
    //echo '<br>';
    //echo $count_sn_year[0]['count'];
    //echo '<br>';
    //die();
    public static function get_count_sn_year($conn, $sn_year) {
        $sql = "SELECT COUNT(*) as count FROM patient WHERE `sn_year` = :sn_year";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':sn_year', $sn_year, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addCriticalReport($conn, $id, $critical_report) {
        $sql = "UPDATE patient
                SET iscritical = :iscritical
                WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':iscritical', $critical_report, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function setSpComment($conn, $id, $p_sp_patho_comment) {
        $sql = "UPDATE patient" .
                " SET p_sp_patho_comment = '" . $p_sp_patho_comment . "' " .
                " WHERE id = " . $id;

        $results = $conn->query($sql);


        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getSpComment($conn, $id) {
        $sql = "SELECT p_sp_patho_comment FROM patient WHERE id = $id";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function setPathoID($conn, $id, $ppathologist_id) {
        $sql = "UPDATE patient
                SET ppathologist_id = :ppathologist_id
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':ppathologist_id', $ppathologist_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    //setSecondPathoReview($conn, $_POST['patient_id'], $_POST['state']);
    public static function setSecondPathoReview($conn, $id, $second_patho_review) {
        $sql = "UPDATE patient
                SET second_patho_review = :second_patho_review
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':second_patho_review', $second_patho_review, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function set_request_sp_slide($conn, $id, $request_sp_slide) {
        $sql = "UPDATE patient
                SET request_sp_slide = :request_sp_slide
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':request_sp_slide', $request_sp_slide, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function set_update_released_time($conn, $id, $date_20000) {
        $sql = "UPDATE patient
                SET date_20000 = :date_20000
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':date_20000', $date_20000, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function set_update_first_released_time_if_null($conn, $id, $date_first_report, $importdate) {
 
        $tr_time = Util::get_diff_time_in_days($date_first_report, $importdate);
        //return $tr_time;
        
        
        $thai_date = Util::get_curreint_thai_date_time();
        
        Log::add($conn, $_SESSION['log_username'], $_SESSION['log_name'], "Patient::set_update_first_released_time_if_null(conn,id,date_first_report,importdate)",$date_first_report." ".$importdate." ".$tr_time, $thai_date);
        
        $sql = "UPDATE patient " .
                " SET date_first_report = :date_first_report , tr_time = $tr_time " .
                " WHERE id = :id and `date_first_report` IS NULL";

        $stmt = $conn->prepare($sql);


        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
//        $stmt->bindValue(':tr_time', $tr_time, PDO::PARAM_STR);
        $stmt->bindValue(':date_first_report', $date_first_report, PDO::PARAM_STR);

        return $stmt->execute();
    }

}
