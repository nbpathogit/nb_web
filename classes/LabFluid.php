<?php

/**
 * LabFluid
 *
 * A person or entity that can log in to the site
 */
class LabFluid {

    /**
     * Unique identifier
     * @var integer
     */
    public $id;
    public $labname;
    public $lab_des;
    

    public static function getAll($conn) {
        $sql = "SELECT * FROM lab_fluid;";

        $results = $conn->query($sql);

        return  $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByID($conn, $id, $columns = '*') {
        $sql = "SELECT $columns
                FROM lab_fluid
                WHERE id= :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'LabFluid');

        if ($stmt->execute()) {
            return $stmt->fetch();
        }
    }

    public function create($conn)
    {

        $sql = "INSERT INTO lab_fluid ( labname,lab_des)
                VALUES ( :labname,:lab_des)";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':labname', $this->labname, PDO::PARAM_STR);
        $stmt->bindValue(':lab_des', $this->lab_des, PDO::PARAM_STR);

        //var_dump($stmt);

        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public function update($conn)
    {

        $sql = "UPDATE lab_fluid
                    SET labname = :labname,
                        lab_des= :lab_des
                    WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':labname', $this->labname, PDO::PARAM_STR);
        $stmt->bindValue(':lab_des', $this->lab_des, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function delete($conn)
    {
        $sql = "DELETE FROM lab_fluid
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
    