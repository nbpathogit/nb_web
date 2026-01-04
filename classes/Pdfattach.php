<?php
/**
 * User
 *
 * A person or entity that can log in to the site
 */
class Pdfattach
{

    /**
     * Unique identifier
     * @var integer
     */
    public $id;
    public $filelocation;
    public $rec_create_date;
    public $patient_id;
    public $fileName;

   



    public static function getAll($conn, $patient_id)
    {
        $sql = "SELECT *
                FROM pdf_attach
                WHERE patient_id  = ". $patient_id ;

        $sql = $sql . " ORDER BY id";

        $results = $conn->query($sql);

        return  $results->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public static function getInitObj() {
        $pdfat = new Pdfattach();
        
        $pdfat->id=0;
        $pdfat->filelocation="";
        $pdfat->rec_create_date="";
        $pdfat->patient_id=0;
        $pdfat->fileName="";

        return $pdfat;
    }

    /**
     * Insert a new user
     *
     * @param object $conn Connection to the database
     *
     * @return boolean True if the insert was successful, false otherwise
     */
    public function create($conn)
    {
        
        $curDateTime = Util::get_curreint_thai_date_time();
        Log::add($conn, $_SESSION['log_username'], $_SESSION['log_name'], "Call Pdfattach::create()", 'PatienID::' . $this->patient_id .' fileLocation::'.$this->filelocation .' fileName::'.$this->fileName , $curDateTime);

        $sql = "INSERT INTO pdf_attach ( id, filelocation,  rec_create_date, patient_id,fileName)
            VALUES (             null, :filelocation, :rec_create_date, :patient_id, :fileName)";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':filelocation', $this->filelocation, PDO::PARAM_STR);
        $stmt->bindValue(':rec_create_date', $curDateTime, PDO::PARAM_STR);
        $stmt->bindValue(':patient_id', $this->patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':fileName', $this->fileName, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            return true;
        } else {
            $this->errors[] = "บันทึกข้อมูลไม่สำเร็จ";
            return false;
        }
    }


}
