<?php

/**
 * User
 *
 * A person or entity that can log in to the site
 */
class Log
{

    /**
     * Unique identifier
     * @var integer
     */
    public $id;
    public $username;
    public $name;
    public $event;
    public $detail;
    public $event_date;
    
    public $message = [];
 

    public static function setuser($conn, $username_s, $name_s)
    {
        Log::$username_s = $username_s;
        Log::$name_s = $name_s;
    }

    public static function getAll($conn, $id = 0)
    {
        $sql = "SELECT * FROM `log`";


        if ($id != 0) {
            $sql = $sql . " and id = " . $id;
        }


        $results = $conn->query($sql);

        return  $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($conn)
    {
        
        $curDateTime = Util::get_curreint_thai_date_time();
        // INSERT INTO user (id, name, lastname, username, password, ugroup_id, uhospital_id) VALUES (NULL, 'อนุชิกก', 'ยุงทอม', 'aaaa', 'aaaa', '4', '2');

        $sql = "INSERT INTO `log` (`id`, `username`, `name`, `event`, `detail`, `event_date`) "
                . "VALUES (NULL, :username, :name, :event, :detail, :event_date)";
        
        
        $stmt = $conn->prepare($sql);

        //var_dump($this->name);

        $stmt->bindValue(':username', $_SESSION['log_username'], PDO::PARAM_STR);
        $stmt->bindValue(':name', $_SESSION['name'], PDO::PARAM_STR);
        $stmt->bindValue(':event', $this->event, PDO::PARAM_STR);
        $stmt->bindValue(':detail', $this->detail, PDO::PARAM_STR);
        $stmt->bindValue(':event_date', $curDateTime, PDO::PARAM_STR);
        //var_dump($stmt);

        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            $this->message[] = "บันทึกข้อมูลสำเร็จ";
            return true;
        } else {
            $this->message[] = "บันทึกข้อมูลไม่สำเร็จ";
            return false;
        }
    }
    
    public static function add($conn,$username,$name,$event,$detail,$event_date="")
    {
        if (!($conn instanceof PDO)) {
            $conn = (new Database())->getConn();
        }
        if($event_date == ""){
           $event_date = Util::get_curreint_thai_date_time();
        }
        $sql = "INSERT INTO `log` (`id`, `username`, `name`, `event`, `detail`, `event_date`) "
                . "VALUES (NULL, :username, :name, :event, :detail, :event_date)";
        
        
        $stmt = $conn->prepare($sql);



        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':event', $event, PDO::PARAM_STR);
        $stmt->bindValue(':detail', $detail, PDO::PARAM_STR);
        $stmt->bindValue(':event_date', $event_date, PDO::PARAM_STR);
        //var_dump($stmt);

        if ($stmt->execute()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public static function getAllLogInLogOut($conn, $range = "0", $start_date = "", $end_date = "")
    {
        $where = " WHERE (`event`= 'login' or `event`= 'logout') ";
        $params = [];

        if ($start_date !== "" && $end_date !== "") {
            $where .= " AND `event_date` >= :start_date AND `event_date` <= :end_date ";
            $params[':start_date'] = $start_date;
            $params[':end_date'] = $end_date;
        } elseif ($range !== "0" && $range !== "") {
            if (preg_match('/^\d{4}$/', $range)) {
                $where .= " AND YEAR(`event_date`) = :yr ";
                $params[':yr'] = (int)$range;
            } else {
                $where .= " AND `event_date` >= :range_date ";
                $params[':range_date'] = $range . " 00:00:00";
            }
        }

        $sql = "SELECT "
                . " `id`,`username`,`name`,`event`,`detail`,`event_date` "
                . " FROM `log` "
                . $where
                . " ORDER BY id DESC ";

        $stmt = $conn->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    

}








