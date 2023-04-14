<?php


class TemplateReport
{

    /**
     * Unique identifier
     * @var integer
     */
    public $id;
        
    
    public static function getTemplateByUser($conn, $user_id = 0) {

        $sql = "SELECT t.id as tid, r.id as rid, u.id as uid, u.name as uname, u.lastname as ulastname, r.name as rname, t.name as tname, t.description as tdescription FROM `template_report` as t JOIN report_type as r JOIN user as u WHERE t.reporttype_id = r.id AND t.user_id = u.id";
        if ($user_id != 0) {
            $sql = $sql . " and t.user_id = $user_id";
        }
        $stmt = $conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getTemplateByUserByType($conn, $user_id = 0, $type_id = 0) {

        $sql = "SELECT t.id as tid, r.id as rid, u.id as uid, u.name as uname, u.lastname as ulastname, r.name as rname, t.name as tname, t.description as tdescription FROM `template_report` as t JOIN report_type as r JOIN user as u WHERE t.reporttype_id = r.id AND t.user_id = u.id";
         if ($user_id != 0) {
            $sql = $sql . " and t.user_id = $user_id";
        }
        if ($type_id != 0) {
            $sql = $sql . " and t.reporttype_id = $type_id";
        }
        $stmt = $conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
