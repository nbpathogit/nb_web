<?php

class Gross_examination_record {
    private $conn;
    private $table = 'gross_examination_record';
    
    private $id;
    private $sub_id;
    private $description;
    private $content;
    private $create_date;
    private $rsv;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getSubId() {
        return $this->sub_id;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    public function getContent() {
        return $this->content;
    }
    
    public function getCreateDate() {
        return $this->create_date;
    }
    
    public function getRsv() {
        return $this->rsv;
    }
    
    // Setters
    public function setSubId($sub_id) {
        $this->sub_id = $sub_id;
    }
    
    public function setDescription($description) {
        $this->description = $description;
    }
    
    public function setContent($content) {
        $this->content = $content;
    }
    
    public function setRsv($rsv) {
        $this->rsv = $rsv;
    }
    
    // CREATE - Insert new record
    public function create() {
        $stmt = $this->conn->prepare("INSERT INTO `$this->table` (`sub_id`, `description`, `content`, `create_date`, `rsv`) VALUES (?, ?, ?, current_timestamp(), ?)");
        
        if (!$stmt) {
            return array('success' => false, 'error' => 'Prepare failed: ' . $this->conn->error);
        }
        
        $stmt->bind_param("isss", $this->sub_id, $this->description, $this->content, $this->rsv);
        
        if (!$stmt->execute()) {
            return array('success' => false, 'error' => 'Execute failed: ' . $stmt->error);
        }
        
        $this->id = $stmt->insert_id;
        $stmt->close();
        
        return array('success' => true, 'message' => 'Record created successfully', 'id' => $this->id);
    }
    
    // READ - Get record by ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM `$this->table` WHERE id = ?");
        
        if (!$stmt) {
            return array('success' => false, 'error' => 'Prepare failed: ' . $this->conn->error);
        }
        
        $stmt->bind_param("i", $id);
        
        if (!$stmt->execute()) {
            return array('success' => false, 'error' => 'Execute failed: ' . $stmt->error);
        }
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->id = $row['id'];
            $this->sub_id = $row['sub_id'];
            $this->content = $row['content'];
            $this->create_date = $row['create_date'];
            $this->rsv = $row['rsv'];
            
            $stmt->close();
            return array('success' => true, 'data' => $row);
        }
        
        $stmt->close();
        return array('success' => false, 'error' => 'Record not found');
    }
    
    // READ - Get latest record by sub_id
    public function getLatestBySubId($sub_id) {
        $stmt = $this->conn->prepare("SELECT * FROM `$this->table` WHERE sub_id = ? ORDER BY id DESC LIMIT 1");
        
        if (!$stmt) {
            return array('success' => false, 'error' => 'Prepare failed: ' . $this->conn->error);
        }
        
        $stmt->bind_param("i", $sub_id);
        
        if (!$stmt->execute()) {
            return array('success' => false, 'error' => 'Execute failed: ' . $stmt->error);
        }
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stmt->close();
            return array('success' => true, 'data' => $row);
        }
        
        $stmt->close();
        return array('success' => false, 'error' => 'No records found');
    }
    
    // READ - Get latest record grouped by sub_id
    /**
     * Retrieves the latest record for each unique sub_id
     * Groups all records by sub_id and returns only the most recent one (highest id) for each group
     * 
     * @return array Returns array with keys:
     *               - 'success' (bool): true if records found, false otherwise
     *               - 'data' (array): array of records, each containing latest record per sub_id
     *               - 'error' (string): error message if query fails
     *               
     * Returns array with latest record for each unique sub_id
     */
    public function getBySubId() {
        $sql = "SELECT * FROM `$this->table` t1 
                WHERE id = (
                    SELECT MAX(id) FROM `$this->table` t2 WHERE t2.sub_id = t1.sub_id
                )
                ORDER BY sub_id ASC";
        
        $result = $this->conn->query($sql);
        
        if (!$result) {
            return array('success' => false, 'error' => 'Query failed: ' . $this->conn->error);
        }
        
        if ($result->num_rows > 0) {
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return array('success' => true, 'data' => $rows);
        }
        
        return array('success' => false, 'error' => 'No records found');
    }
    
    // UPDATE - Update existing record
    public function update($id) {
        $stmt = $this->conn->prepare("UPDATE `$this->table` SET `sub_id` = ?, `description` = ?, `content` = ?, `rsv` = ? WHERE `id` = ?");
        
        if (!$stmt) {
            return array('success' => false, 'error' => 'Prepare failed: ' . $this->conn->error);
        }
        
        $stmt->bind_param("isssi", $this->sub_id, $this->description, $this->content, $this->rsv, $id);
        
        if (!$stmt->execute()) {
            return array('success' => false, 'error' => 'Execute failed: ' . $stmt->error);
        }
        
        $stmt->close();
        return array('success' => true, 'message' => 'Record updated successfully');
    }
    
    // DELETE - Delete record by ID
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM `$this->table` WHERE `id` = ?");
        
        if (!$stmt) {
            return array('success' => false, 'error' => 'Prepare failed: ' . $this->conn->error);
        }
        
        $stmt->bind_param("i", $id);
        
        if (!$stmt->execute()) {
            return array('success' => false, 'error' => 'Execute failed: ' . $stmt->error);
        }
        
        $stmt->close();
        return array('success' => true, 'message' => 'Record deleted successfully');
    }
    
    // GET ALL - Get all records with optional limit
    public function getAll($limit = null) {
        $sql = "SELECT * FROM `$this->table` ORDER BY id DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        $result = $this->conn->query($sql);
        
        if (!$result) {
            return array('success' => false, 'error' => 'Query failed: ' . $this->conn->error);
        }
        
        if ($result->num_rows > 0) {
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return array('success' => true, 'data' => $rows);
        }
        
        return array('success' => false, 'error' => 'No records found');
    }
    
    // GET NEXT SUB ID - Get the next sub_id (max sub_id + 1)
    public function getNextSubId() {
        $sql = "SELECT MAX(sub_id) as max_subid FROM `$this->table`";
        $result = $this->conn->query($sql);
        
        if (!$result) {
            return array('success' => false, 'error' => 'Query failed: ' . $this->conn->error);
        }
        
        $row = $result->fetch_assoc();
        $maxSubId = $row['max_subid'];
        $nextSubId = ($maxSubId === NULL) ? 1 : $maxSubId + 1;
        
        return array('success' => true, 'next_subid' => $nextSubId);
    }
}

?>
