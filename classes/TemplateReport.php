<?php


class TemplateReport
{

    /**
     * Unique identifier
     * @var integer
     */
    public $id;
    public $user_id;
    public $reporttype_id;
    public $name;
    public $description;
    
    public static function getInit()
    {
        return [
            [
                "id" => 0,
                "user_id" => 0,
                "reporttype_id" => 0,
                "name" => "",
                "description" => ""

            ]
        ];
    }

    
    public function update($conn)
    {
        // need update
        $sql = "UPDATE user
                SET pre_name=:pre_name, name= :name,lastname= :lastname, pre_name_e=:pre_name_e, name_e= :name_e,lastname_e= :lastname_e, short_name=:short_name, educational_bf=:educational_bf,role=:role, udetail= :udetail,umobile= :umobile,uemail= :uemail, username=:username, password=:password, ugroup_id=:ugroupid, uhospital_id=:uhospitalid
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':pre_name', $this->pre_name, PDO::PARAM_STR);
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);


        return $stmt->execute();
    }
    
    public static function updatebyArray($conn,$tps)
    {
        // need update
        $sql = "UPDATE template_report
                SET  user_id= :user_id,reporttype_id= :reporttype_id, name=:name, description= :description
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $tps[0]['id'], PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $tps[0]['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':reporttype_id', $tps[0]['reporttype_id'], PDO::PARAM_INT);
        $stmt->bindValue(':name', $tps[0]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $tps[0]['description'], PDO::PARAM_STR);

        return $stmt->execute();
    }
    
        
    public static function getAllbyid($conn, $id = 0) {

        $sql = "SELECT t.id as tid, r.id as rid, u.id as uid, u.name as uname, u.lastname as ulastname, r.name as rname, t.name as tname, t.description as tdescription FROM `template_report` as t JOIN report_type as r JOIN user as u WHERE t.reporttype_id = r.id AND t.user_id = u.id";
        if ($id != 0) {
            $sql = $sql . " and t.id = $id";
        }
        $stmt = $conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
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
