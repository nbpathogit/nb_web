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
class Specimen
{

    public $id;
    public $speciment_num;
    public $specimen;
    public $price;

    public static function getAll($conn)
    {
        $sql = "SELECT *
                FROM specimen_list
                ORDER BY id;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getSpecimen($conn)
    {
        $sql = "SELECT *
                FROM specimen_list
                WHERE jobtype = 1
                ORDER BY id;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getSpecialSlide($conn)
    {
        $sql = "SELECT *
                FROM specimen_list
                WHERE jobtype = 2
                ORDER BY id;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($conn)
    {

        $sql = "INSERT INTO specimen_list (speciment_num, specimen, price)
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

    public static function getTotal($conn)
    {
        return $conn->query("SELECT COUNT(*) FROM specimen_list")->fetchColumn();
    }

    public static function getPage($conn, $limit, $offset)
    {

        $sql = "SELECT *
                FROM specimen_list
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
                FROM specimen_list
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
                FROM specimen_list
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

        $sql = "UPDATE specimen_list
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
        $sql = "DELETE FROM specimen_list
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
