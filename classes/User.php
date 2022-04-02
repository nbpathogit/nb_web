<?php

/**
 * User
 *
 * A person or entity that can log in to the site
 */
class User {

    /**
     * Unique identifier
     * @var integer
     */
    public $id;
    public $pre_name;
    public $name;
    public $lastname;
    public $pre_name_e;
    public $name_e;
    public $lastname_e;
    public $educational_bf;
    public $role;
    public $udetail;
    public $umobile;
    public $uemail;
    public $username;
    public $password;
    public $ugroup_id;
    public $uhospital_id;
    public $signature_file;
    public $profile_file;

    /**
     * Authenticate a user by username and password
     *
     * @param object $conn Connection to the database
     * @param string $username Username
     * @param string $password Password
     *
     * @return boolean True if the credentials are correct, false otherwise
     */
    public static function authenticate($conn, $username, $password) {

        $sql = "SELECT *
                FROM user
                WHERE username= :username";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

        $stmt->execute();

        if ($user = $stmt->fetch()) {
            return password_verify($password, $user->password);
        }
    }

    public static function getAll($conn, $id = 0) {
        $sql = "SELECT *, U.id as uid, G.id as gid, H.id as hid
                FROM user U
                JOIN user_groups G
                JOIN hospital H
                WHERE U.ugroup_id  = G.id
                and U.uhospital_id  = H.id ";


        if ($id != 0) {
            $sql = $sql . " and U.id = " . $id;
        }

        $sql = $sql . " ORDER BY U.id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllbyPathologis($conn, $id = 0) {
        $sql = "SELECT *, U.id as uid, G.id as gid, H.id as hid
                FROM user U
                JOIN user_groups G
                JOIN hospital H
                WHERE (U.ugroup_id  = G.id
                and U.uhospital_id  = H.id
                and (U.ugroup_id = 2000 ))";
        
        if ($id != 0) {
            $sql = $sql . " and U.id = " . $id;
        }

        $sql = $sql . " ORDER BY U.id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllbyTeachien($conn) {
        $sql = "SELECT *, U.id as uid, G.id as gid, H.id as hid
                FROM user U
                JOIN user_groups G
                JOIN hospital H
                WHERE U.ugroup_id  = G.id
                and U.uhospital_id  = H.id
                and 
                (U.ugroup_id = 2000 
                or U.ugroup_id = 2100
                or U.ugroup_id = 2200
                or U.id = 0 )";


        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllbyClinicians($conn) {
        $sql = "SELECT *, U.id as uid, G.id as gid, H.id as hid
                FROM user U
                JOIN user_groups G
                JOIN hospital H
                WHERE U.ugroup_id  = G.id
                and U.uhospital_id  = H.id
                and 
                (U.ugroup_id = 5000 
                or U.id = 0 )";


        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
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
                FROM user
                WHERE id= :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

        if ($stmt->execute()) {
            return $stmt->fetch();
        }
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
    public static function getByUserName($conn, $username, $columns = '*') {
        $sql = "SELECT $columns
                FROM user
                WHERE username = :username";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

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
        // INSERT INTO user (id, name, lastname, username, password, ugroup_id, uhospital_id) VALUES (NULL, 'อนุชิกก', 'ยุงทอม', 'aaaa', 'aaaa', '4', '2');

        $sql = "INSERT INTO user ( name, lastname, udetail, umobile, uemail, username, password, ugroup_id, uhospital_id)
            VALUES ( :name, :lastname, :udetail, :umobile, :uemail, :username, :password, :ugroupid, :uhospitalid)";

        $stmt = $conn->prepare($sql);

        //var_dump($this->name);

        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $stmt->bindValue(':udetail', $this->udetail, PDO::PARAM_STR);
        $stmt->bindValue(':umobile', $this->umobile, PDO::PARAM_STR);
        $stmt->bindValue(':uemail', $this->uemail, PDO::PARAM_STR);
        $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->password, PDO::PARAM_STR);
        $stmt->bindValue(':ugroupid', $this->ugroup_id, PDO::PARAM_INT);
        $stmt->bindValue(':uhospitalid', $this->uhospital_id, PDO::PARAM_INT);

        //var_dump($stmt);

        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();

            return true;
        } else {
            return false;
        }
    }

    public static function getTotal($conn, $user_group = "*") {
        return $conn->query("SELECT COUNT(*) FROM user")->fetchColumn();
    }

    public static function getPage($conn, $limit, $offset, $only_published = false) {

        $sql = "SELECT *, U.id as uid, G.id as gid, H.id as hid
                FROM user U
                JOIN user_groups G
                JOIN hospital H
                WHERE U.ugroup_id  = G.id
                and U.uhospital_id  = H.id
                ORDER BY U.id
                LIMIT :limit
                OFFSET :offset";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($conn) {
        // need update
        $sql = "UPDATE user
                SET name= :name,lastname= :lastname,udetail= :udetail,umobile= :umobile,uemail= :uemail, username=:username, password=:password, ugroup_id=:ugroupid, uhospital_id=:uhospitalid
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $stmt->bindValue(':udetail', $this->udetail, PDO::PARAM_STR);
        $stmt->bindValue(':umobile', $this->umobile, PDO::PARAM_STR);
        $stmt->bindValue(':uemail', $this->uemail, PDO::PARAM_STR);
        $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->password, PDO::PARAM_STR);
        $stmt->bindValue(':ugroupid', $this->ugroup_id, PDO::PARAM_INT);
        $stmt->bindValue(':uhospitalid', $this->uhospital_id, PDO::PARAM_INT);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($conn) {
        $sql = "DELETE FROM user
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

}
