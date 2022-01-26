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
    public $specimen;

    public static function getAll($conn)
    {
        $sql = "SELECT *
                FROM specimen_list
                ORDER BY id;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($conn)
    {

        $sql = "INSERT INTO specimen_list ( specimen)
                VALUES ( :specimen)";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':specimen', $this->specimen, PDO::PARAM_STR);

        //var_dump($stmt);

        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public static function getTotal($conn) {
        return $conn->query("SELECT COUNT(*) FROM user")->fetchColumn();
    }

    public static function getPage($conn, $limit, $offset) {

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
}
