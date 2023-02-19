<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JobRole
 *
 * @author 2444536
 */
class JobRole {

    public $id;
    public $name;
    public $name_e;
    public $cost_per_job;
    public $cost_count_per_day;

    public static function getAll($conn, $id = 0) {
        $sql = "SELECT * FROM `job_role` ";

        if ($id != 0) {
            $sql = $sql . " WHERE id = " . $id;
        }

        $sql = $sql . " ORDER BY id";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

}
