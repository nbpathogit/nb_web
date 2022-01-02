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
    public $import_date;
    public $report_date;
    public $status;
    public $priority_id;
    public $phospital_id;
    public $phospital_num;
    public $ppathologist_id;
    public $pspecimen_id;
    public $pclinician_id;
    public $pprice;
    public $pspprice;
    public $p_rs_specimen;
    public $p_rs_clinical_diag;
    public $p_rs_gross_desc;
    public $p_rs_microscopic_desc;
    public $p_rs_diagnosis;

    /**
     * Validation errors
     * @var array
     */
    public $errors = [];

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
                WHERE p.ppathologist_id = u.id
                and p.phospital_id = h.id
                and p.priority_id = pri.id";

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
     * @return mixed An object of this class, or null if not found
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

        $sql = "INSERT INTO `patient` (`id`, `pnum`, `plabnum`, `pname`, `pgender`, `plastname`, `pedge`, `import_date`, `report_date`, `status`, `priority_id`, `phospital_id`, `phospital_num`, `ppathologist_id`, `pspecimen_id`, `pclinician_id`, `pprice`, `pspprice`, `p_rs_specimen`, `p_rs_clinical_diag`, `p_rs_gross_desc`, `p_rs_microscopic_desc`, `p_rs_diagnosis`) "
                . "            VALUES (NULL,   :pnum,  :plabnum,   :pname,   :pgender, :plastname, :pedge, :import_date, :report_date,  :status, :priority_id, :phospital_id, :phospital_num,     :ppathologist_id,   :pspecimen_id, :pclinician_id,   :pprice, :pspprice,  :p_rs_specimen, :p_rs_clinical_diag,    :p_rs_gross_desc, :p_rs_microscopic_desc, :p_rs_diagnosis);";

        $stmt = $conn->prepare($sql);

        //var_dump( $this->name);

        $stmt->bindValue(':pnum', $this->pnum, PDO::PARAM_STR);
        $stmt->bindValue(':plabnum', $this->plabnum, PDO::PARAM_STR);
        $stmt->bindValue(':pname', $this->pname, PDO::PARAM_STR);
        $stmt->bindValue(':pgender', $this->pgender, PDO::PARAM_STR);
        $stmt->bindValue(':plastname', $this->plastname, PDO::PARAM_STR);
        $stmt->bindValue(':pedge', $this->pedge, PDO::PARAM_STR);
        $stmt->bindValue(':import_date', $this->import_date, PDO::PARAM_STR);
        $stmt->bindValue(':report_date', $this->report_date, PDO::PARAM_STR);
        $stmt->bindValue(':status', $this->status, PDO::PARAM_STR);
        $stmt->bindValue(':priority_id', $this->priority_id, PDO::PARAM_INT);
        $stmt->bindValue(':phospital_id', $this->phospital_id, PDO::PARAM_STR);
        $stmt->bindValue(':phospital_num', $this->phospital_num, PDO::PARAM_STR);
        $stmt->bindValue(':ppathologist_id', $this->ppathologist_id, PDO::PARAM_STR);
        
        
        
        
        
        $stmt->bindValue(':pspecimen_id', $this->pspecimen_id, PDO::PARAM_STR);
        $stmt->bindValue(':pclinician_id', $this->pclinician_id, PDO::PARAM_STR);
        $stmt->bindValue(':pprice', $this->pprice, PDO::PARAM_STR);
        $stmt->bindValue(':pspprice', $this->pspprice, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_specimen', $this->p_rs_specimen, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_clinical_diag', $this->p_rs_clinical_diag, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_gross_desc', $this->p_rs_gross_desc, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_microscopic_desc', $this->p_rs_microscopic_desc, PDO::PARAM_STR);
        $stmt->bindValue(':p_rs_diagnosis', $this->p_rs_diagnosis, PDO::PARAM_STR);
        //var_dump($stmt);

        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            return true;
        } else {
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
                "import_date" => "",
                "report_date" => "",
                "status" => "รับเข้า",
                "status_id" => 2000,
                "priority_id" => 5,
                "phospital_id" => 0,
                "phospital_num" => "",
                "ppathologist_id" => 0,
                "pspecimen_id" => 0,
                "pclinician_id" => 0,
                "pclinician2_id" => 0,
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
                "p_rs_diagnosis" => ""
        ]];
    }
    
    
    public static function updateStatus($conn,$id,$status_id)
    {
        $sql = "UPDATE patient
                SET status_id = :status_id
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':status_id', $status_id, PDO::PARAM_INT);

        return $stmt->execute();
        
    }

}
