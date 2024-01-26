<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Specimen
 *
 * @author 2444536
 */
class ServicePriceList {

    public $id;
    public $speciment_num;
    public $jobtype;
    
    public $specimen;
    public $hospital_id;
    public $price;
    public $comment;
    public $create_date;

    public static function getAll($conn,$id= -1)
    {
        $sql = "SELECT *
                FROM service_price_list";
        if ($id != -1) {
            $sql = $sql . " WHERE id = $id";
        }

        $sql = $sql . " ORDER BY id;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getAll_v2($conn,$id= -1)
    {
        $sql = "SELECT spl.id as spl_id
                    ,h.id as h_id
                    ,st.id as st_id
                    ,spl.number as spl_number
                    ,h.hospital as h_hospital
                    ,st.service_type as st_service_type
                    ,spl.speciment_num as spl_speciment_num
                    ,spl.specimen as spl_specimen
                    ,spl.price as spl_price
                    ,spl.unit_count as spl_unit_count
                    ,DATE(spl.create_date) as spl_create_date
                    ,CONCAT(u_add.name , ' ' , u_add.lastname) as user_add
                    ,CONCAT(u_edit.name , ' ' , u_edit.lastname) as user_edit
                FROM `service_price_list` as spl 
                JOIN hospital as h ON h.id = spl.hospital_id 
                JOIN service_type as st ON st.id = spl.jobtype 
                LEFT JOIN user as u_add ON u_add.id = spl.add_user_id 
                LEFT JOIN user as u_edit ON u_edit.id = spl.edit_user_id ";
        if ($id != -1) {
            $sql = $sql . " WHERE id = $id ";
        }else{
            $sql = $sql . " WHERE 1 ";
        }

        $sql = $sql . " ORDER BY spl.id;";
        
//        Util::writeFile('sql.txt', $sql);

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getSpecimen($conn)
    {
        $sql = "SELECT *
                FROM service_price_list
                WHERE jobtype = 1
                ORDER BY id;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getSpecimenByHospitalID($conn,$hospital_id,$type=0)
    {
        $sql = "SELECT *
                FROM service_price_list";
        $sql = $sql." WHERE hospital_id = :hospital_id ";
        if($type !=0){
            $sql = $sql." and jobtype = :type ";
        }
        $sql = $sql." ORDER BY id;";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':hospital_id', $hospital_id, PDO::PARAM_INT);
        $stmt->bindValue(':type', $type, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getSpecimenByHospitalIDJoptypeSpecimen($conn,$hospital_id)
    {
        $sql = "SELECT *
                FROM service_price_list";
        $sql = $sql." WHERE hospital_id = :hospital_id ";
        $sql = $sql." and (jobtype = 1 or jobtype = 4 or jobtype = 5)  "; // service id 1 ,4, 5
        $sql = $sql." ORDER BY id;";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':hospital_id', $hospital_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getSpecimenByHospitalIDJoptypeSpecialStainging($conn,$hospital_id=0,$service_id=0)
    {
        $sql = "SELECT *
                FROM service_price_list";
        $sql = $sql." WHERE hospital_id = :hospital_id ";
        if($service_id == 0){
            $sql = $sql." and (jobtype = 2 or jobtype = 3 or jobtype = 6 or jobtype = 7)  "; // service id 2, 3, 6, 7
        }else{
            $sql = $sql." and (jobtype = $service_id )";
        }

        $sql = $sql." ORDER BY id;";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':hospital_id', $hospital_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function delSpecimenByHospitalID($conn,$hospital_id,$type=0)
    {
        $sql = "DELETE FROM `service_price_list`";

        $sql = $sql." WHERE hospital_id = :hospital_id ";
        if($type !=0){
            $sql = $sql." and jobtype = :type ";
        }
        $sql = $sql." ORDER BY id;";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':hospital_id', $hospital_id, PDO::PARAM_INT);
        $stmt->bindValue(':type', $type, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getSpecialSlide($conn)
    {
        $sql = "SELECT *
                FROM service_price_list
                WHERE jobtype = 2
                ORDER BY id;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($conn)
    {

        $sql = "INSERT INTO service_price_list (speciment_num, specimen, price)
                VALUES (:speciment_num, :specimen, :price)";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':speciment_num', $this->speciment_num, PDO::PARAM_INT);
        $stmt->bindValue(':specimen', $this->specimen, PDO::PARAM_STR);
        $stmt->bindValue(':price', $this->price, PDO::PARAM_INT);

        //var_dump($stmt);

        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }
    
    public function createBySQL($conn,$sql)
    {

        $stmt = $conn->prepare($sql);

        if ($stmt->execute()) {

            return true;
        } else {
            return false;
        }
    }

    public static function getTotal($conn)
    {
        return $conn->query("SELECT COUNT(*) FROM service_price_list")->fetchColumn();
    }

    public static function getPage($conn, $limit, $offset)
    {

        $sql = "SELECT *
                FROM service_price_list
                LIMIT :limit
                OFFSET :offset";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getSearch($conn, $search)
    {

        $sql = "SELECT *
                FROM service_price_list
                WHERE specimen LIKE '%$search%';";

        // var_dump($sql);

        $stmt = $conn->prepare($sql);

        // $stmt->bindValue(':search', $search, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByID($conn, $id, $columns = '*')
    {
        $sql = "SELECT $columns
                FROM service_price_list
                WHERE id= :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Specimen');

        if ($stmt->execute()) {
            return $stmt->fetch();
        }
    }

    public function update($conn)
    {

        $sql = "UPDATE service_price_list
                    SET specimen = :specimen,
                        speciment_num =:speciment_num,
                        price =:price
                    WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':speciment_num', $this->speciment_num, PDO::PARAM_INT);
        $stmt->bindValue(':specimen', $this->specimen, PDO::PARAM_STR);
        $stmt->bindValue(':price', $this->price, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($conn)
    {
        $sql = "DELETE FROM service_price_list
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
