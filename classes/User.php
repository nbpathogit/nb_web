<?php

/**
 * User
 *
 * A person or entity that can log in to the site
 */
class User
{

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
    public $short_name;
    public $educational_bf;
    public $role;
    
    public $role_1;
    public $role_2;
    public $role_3;
    public $role_4;
    public $role_5;
    public $role_6;
    public $role_7;
    public $can_manaage_job;
    
    public $udetail;
    public $umobile;
    public $uemail;
    public $username;
    public $password;
    public $ugroup_id;
    public $uhospital_id;
    public $signature_file;
    public $profile_file;
    public $errors = [];
    public $create_date;
    public $create_by;

    /**
     * Authenticate a user by username and password
     *
     * @param object $conn Connection to the database
     * @param string $username Username
     * @param string $password Password
     *
     * @return boolean True if the credentials are correct, false otherwise
     */
    public static function authenticate($conn, $username, $password)
    {

        $sql = "SELECT *
                FROM user
                WHERE username= :username";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

        $stmt->execute();

        if ($user = $stmt->fetch()) {
            //function password_verify(string $password, string $hash): bool {}
            return password_verify($password, $user->password);
        }
    }
    
    public static function isPasswordWasReset($conn, $username, $password)
    {

        $sql = "SELECT *
                FROM user
                WHERE username= :username";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

        $stmt->execute();

        if ($user = $stmt->fetch()) {
            //function password_verify(string $password, string $hash): bool {}
            return password_verify("", $user->password);
        }
    }
    
    public static function isUserNameActive($conn, $username)
    {

        $sql = "SELECT *
                FROM user
                WHERE username= :username";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

        $stmt->execute();

        if ($user = $stmt->fetch()) {
            return $user->user_status == 1;
        }
    }

    public static function getAll($conn, $id = 0)
    {
        $sql = "SELECT *, U.id as uid, G.id as gid, H.id as hid
                FROM user U
                JOIN user_groups G
                JOIN hospital H
                WHERE U.ugroup_id  = G.id
                and U.uhospital_id  = H.id ";


        if ($id != 0) {
            $sql = $sql . " and U.id = " . $id;
        }
        $sql = $sql . " and U.movetotrash = 0 ";

        $sql = $sql . " ORDER BY U.id";

        $results = $conn->query($sql);

        return  $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllbyPathologis($conn, $id = 0)
    {
        $sql = "SELECT *, U.id as uid, G.id as gid, H.id as hid
                FROM user U
                JOIN user_groups G
                JOIN hospital H
                WHERE (U.ugroup_id  = G.id
                and U.uhospital_id  = H.id
                and (U.ugroup_id = 2000 or U.id =0)
                )";

        if ($id != 0) {
            $sql = $sql . " and U.id = " . $id;
        }
        $sql = $sql . " and U.movetotrash = 0 ";

        $sql = $sql . " ORDER BY U.id";

        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }

    //2000 2100 2200
    public static function getAllbyTeachien($conn)
    {
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
                or U.id = 0 )
                and U.movetotrash = 0 
                ORDER BY U.id ASC";


        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getAllbyNB($conn)
    {
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
                or U.ugroup_id = 2500
                or U.id = 0 )
                and U.movetotrash = 0 
                ORDER BY U.id ASC";


        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getAllbyCytologist($conn)
    {
        $sql = "SELECT * FROM `user`  "
                . "WHERE (`role_7` = 1 or id = 0) "
                . " and movetotrash = 0  "
                . " ORDER BY id ASC";


        $results = $conn->query($sql);

        return  $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllbyClinicians($conn)
    {
        $sql = "SELECT *, U.id as uid, G.id as gid, H.id as hid
                FROM user U
                JOIN user_groups G
                JOIN hospital H
                WHERE U.ugroup_id  = G.id
                and U.uhospital_id  = H.id
                and 
                (U.ugroup_id = 5000 
                or U.id = 0 )
                and U.movetotrash = 0";


        $results = $conn->query($sql);

        return $articles = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getAllbyCliniciansbyHospitalID($conn,$hospital_id)
    {
        $sql = "SELECT *, U.id as uid, G.id as gid, H.id as hid
                FROM user U
                JOIN user_groups G
                JOIN hospital H
                WHERE U.ugroup_id  = G.id
                and U.uhospital_id  = H.id
                and U.uhospital_id  =  ".$hospital_id. "  
                and
                (U.ugroup_id = 5000 
                or U.id = 0 )
                and U.movetotrash = 0  
                ORDER BY U.name ASC";


        $results = $conn->query($sql);

        return  $results->fetchAll(PDO::FETCH_ASSOC);
//        return $sql;
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
    public static function getByID($conn, $id, $columns = '*')
    {
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
    public static function getByUserName($conn, $username, $columns = '*')
    {
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

    
    public static function getInit() {
        return [
            [
            'id' => '',
            'username' => NULL,
            'password' => '',
            'pre_name' => '',
            'name' => '',
            'lastname' => '',
            'pre_name_e' => '',
            'name_e' => '',
            'lastname_e' => '',
            'short_name' => '',
            'educational_bf' => '',
            'role' => '',
            'udetail' => '',
            'umobile' => '',
            'uemail' => '',
            'ugroup_id' => '0',
            'uhospital_id' => '0',
            'signature_file' => '',
            'profile_file' => '',
            'user_status' => 1
                

            ]
        ];
    }
    
    public static function getInitObj() {
        $user = new User();
           
        $user->pre_name = "";
        $user->name = "";
        $user->lastname = "";
        $user->pre_name_e = "";
        $user->name_e = "";
        $user->lastname_e = "";
        $user->short_name = "";
        $user->educational_bf = "";
        $user->role = "";
        $user->udetail = "";
        $user->umobile = "";
        $user->uemail = "";
        $user->username = NULL;
        $user->password = "";
        $user->ugroup_id = 0;
        $user->uhospital_id = 0;
        $user->signature_file = "";
        $user->profile_file = "";
        $user->errors = NULL;
        $user->create_date = "";
        $user->create_by = "";


        return $user;
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
        // INSERT INTO user (id, name, lastname, username, password, ugroup_id, uhospital_id) VALUES (NULL, 'อนุชิกก', 'ยุงทอม', 'aaaa', 'aaaa', '4', '2');

        $sql = "INSERT INTO user ( pre_name, name, lastname, pre_name_e, name_e, lastname_e, short_name, educational_bf, role, udetail, umobile, uemail, username, password, ugroup_id, uhospital_id, create_date,   create_by )
            VALUES (:pre_name, :name, :lastname, :pre_name_e, :name_e, :lastname_e, :short_name, :educational_bf, :role, :udetail, :umobile, :uemail, :username, :password, :ugroupid, :uhospitalid, :create_date,  :create_by)";

        $stmt = $conn->prepare($sql);

        //var_dump($this->name);

        $stmt->bindValue(':pre_name', $this->pre_name, PDO::PARAM_STR);
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $stmt->bindValue(':pre_name_e', $this->pre_name_e, PDO::PARAM_STR);
        $stmt->bindValue(':name_e', $this->name_e, PDO::PARAM_STR);
        $stmt->bindValue(':lastname_e', $this->lastname_e, PDO::PARAM_STR);
        $stmt->bindValue(':short_name', $this->short_name, PDO::PARAM_STR);
        $stmt->bindValue(':educational_bf', $this->educational_bf, PDO::PARAM_STR);
        $stmt->bindValue(':role', $this->role, PDO::PARAM_STR);
        $stmt->bindValue(':udetail', $this->udetail, PDO::PARAM_STR);
        $stmt->bindValue(':umobile', $this->umobile, PDO::PARAM_STR);
        $stmt->bindValue(':uemail', $this->uemail, PDO::PARAM_STR);
        $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->password, PDO::PARAM_STR);
        $stmt->bindValue(':ugroupid', $this->ugroup_id, PDO::PARAM_INT);
        $stmt->bindValue(':uhospitalid', $this->uhospital_id, PDO::PARAM_INT);
        $stmt->bindValue(':create_date', $curDateTime, PDO::PARAM_STR);
        $stmt->bindValue(':create_by', $this->create_by, PDO::PARAM_STR);

        //var_dump($stmt);

        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            return true;
        } else {
            $this->errors[] = "บันทึกข้อมูลไม่สำเร็จ";
            return false;
        }
    }

    public static function getTotal($conn, $user_group = "*")
    {
        return $conn->query("SELECT COUNT(*) FROM user")->fetchColumn();
    }

    public static function getPage($conn, $limit, $offset, $only_published = false)
    {

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

    public function update($conn)
    {
        // need update
        $sql = "UPDATE user
                SET pre_name=:pre_name, name= :name,lastname= :lastname, pre_name_e=:pre_name_e, name_e= :name_e,lastname_e= :lastname_e, short_name=:short_name, educational_bf=:educational_bf,role=:role, udetail= :udetail,umobile= :umobile,uemail= :uemail, username=:username, password=:password, ugroup_id=:ugroupid, uhospital_id=:uhospitalid
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':pre_name', $this->pre_name, PDO::PARAM_STR);
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $stmt->bindValue(':pre_name_e', $this->pre_name_e, PDO::PARAM_STR);
        $stmt->bindValue(':name_e', $this->name_e, PDO::PARAM_STR);
        $stmt->bindValue(':lastname_e', $this->lastname_e, PDO::PARAM_STR);
        $stmt->bindValue(':short_name', $this->short_name, PDO::PARAM_STR);
        $stmt->bindValue(':educational_bf', $this->educational_bf, PDO::PARAM_STR);
        $stmt->bindValue(':role', $this->role, PDO::PARAM_STR);
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
    
    public function updateUserProfile($conn)
    {
        // need update
        $sql = "UPDATE user
                SET pre_name=:pre_name, name= :name,lastname= :lastname, pre_name_e=:pre_name_e, name_e= :name_e,lastname_e= :lastname_e, short_name=:short_name, educational_bf=:educational_bf,role=:role, udetail= :udetail,umobile= :umobile,uemail= :uemail, ugroup_id=:ugroupid, uhospital_id=:uhospitalid
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':pre_name', $this->pre_name, PDO::PARAM_STR);
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $stmt->bindValue(':pre_name_e', $this->pre_name_e, PDO::PARAM_STR);
        $stmt->bindValue(':name_e', $this->name_e, PDO::PARAM_STR);
        $stmt->bindValue(':lastname_e', $this->lastname_e, PDO::PARAM_STR);
        $stmt->bindValue(':short_name', $this->short_name, PDO::PARAM_STR);
        $stmt->bindValue(':educational_bf', $this->educational_bf, PDO::PARAM_STR);
        $stmt->bindValue(':role', $this->role, PDO::PARAM_STR);
        $stmt->bindValue(':udetail', $this->udetail, PDO::PARAM_STR);
        $stmt->bindValue(':umobile', $this->umobile, PDO::PARAM_STR);
        $stmt->bindValue(':uemail', $this->uemail, PDO::PARAM_STR);
        $stmt->bindValue(':ugroupid', $this->ugroup_id, PDO::PARAM_INT);
        $stmt->bindValue(':uhospitalid', $this->uhospital_id, PDO::PARAM_INT);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }
    
        public function updateUserPass($conn)
    {
        // need update
        $sql = "UPDATE user
                SET username=:username, password=:password
                WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->password, PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($conn)
    {
//        $sql = "DELETE FROM user
//                WHERE id = :id";
//
//        $stmt = $conn->prepare($sql);
//
//        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
//
//        return $stmt->execute();
        
        return false; //not allow to delete user, Let put to in-active instead
    }

    public function setSignatureFile($conn)
    {
        $sql = "UPDATE user
                SET signature_file = :signature_file
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':signature_file', $this->signature_file, $this->signature_file == null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        return $stmt->execute();
    }
    
    public static function isPasswordCorrect($conn, $username,$password)
    {
        $user = User::getByUserName($conn, $username);

        return true;
    }
    
    public static function movetotrash($conn, $id)
    {
        $sql = "UPDATE `user` SET `movetotrash` = 1 WHERE `user`.`id` = $id"; 
        $stmt = $conn->prepare($sql);
        return $stmt->execute();
    }
    
    public static function setUserStatus($conn, $id, $status)
    {
        $sql = "UPDATE `user` SET `user_status` = $status WHERE `user`.`id` = $id"; 
        $stmt = $conn->prepare($sql);
        return $stmt->execute();
    }
    
    public static function setUsernamePassword($conn, $id, $username, $password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE `user` SET `username` = '$username', `password` = '$password' WHERE `user`.`id` = $id"; 
        $stmt = $conn->prepare($sql);
        return $stmt->execute();
    }
    
}
