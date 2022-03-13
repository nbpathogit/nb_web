<?php

/**
 *Patient
 *
 *A piece of writing for publication
 */
class Hospital
{

    /**
     * Uniqure identifier
     * @var integer
     */
    public $id;
    public $hospital;
    // public $name;
    public $address;
    public $hdetail;


    /**
     * Validation errors
     * @var array
     */
    public $errors = [];


    /**
     *Get all the articles
     *
     *@param object $conn Connection to the database
     *
     *@return array An associative array of all the article records
     */
    public static function getAll($conn, $id = 0)
    {
        $sql = "SELECT *
                FROM hospital
                ";
        
        if ($id != 0) {
             $sql = $sql . " WHERE ";
            $sql = $sql . " id = " . $id;
        }
         $sql = $sql . " ORDER BY id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($conn)
    {

        $sql = "INSERT INTO hospital (hospital,address,hdetail)
                VALUES ( :hospital,:address,:hdetail)";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':hospital', $this->hospital, PDO::PARAM_STR);

        if ($this->address == '') {
            $stmt->bindValue(':address', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':address', $this->address, PDO::PARAM_STR);
        }

        $stmt->bindValue(':hdetail', $this->hdetail, PDO::PARAM_STR);

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
        return $conn->query("SELECT COUNT(*) FROM hospital")->fetchColumn();
    }

    public static function getPage($conn, $limit, $offset)
    {

        $sql = "SELECT *
                FROM hospital
                LIMIT :limit
                OFFSET :offset";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByID($conn, $id, $columns = '*')
    {
        $sql = "SELECT $columns
                FROM hospital
                WHERE id= :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Hospital');

        if ($stmt->execute()) {
            return $stmt->fetch();
        }
    }

    public function update($conn)
    {

        $sql = "UPDATE hospital
                SET hospital = :hospital,
                    address = :address,
                    hdetail = :hdetail
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':hospital', $this->hospital, PDO::PARAM_STR);

        if ($this->address == '') {
            $stmt->bindValue(':address', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':address', $this->address, PDO::PARAM_STR);
        }

        $stmt->bindValue(':hdetail', $this->hdetail, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function delete($conn)
    {
        $sql = "DELETE FROM hospital
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
