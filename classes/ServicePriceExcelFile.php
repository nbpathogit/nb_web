<?php

#[\AllowDynamicProperties]
class ServicePriceExcelFile {

    public $id;
    public $hospital_id;
    public $hospital_name;
    public $service_type_id;
    public $original_filename;
    public $saved_filename;
    public $file_path;
    public $row_count;
    public $upload_user_id;
    public $upload_user_name;
    public $upload_date;

    private static $tableChecked = false;
    private static $tableAvailable = false;

    private static function getFallbackJsonPath()
    {
        $dir = dirname(__DIR__) . '/nb_price_8c_uploads';
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        return $dir . '/history_index.json';
    }

    private static function readFallbackList()
    {
        $path = self::getFallbackJsonPath();
        if (!is_file($path)) {
            return [];
        }
        $raw = @file_get_contents($path);
        if (!$raw) {
            return [];
        }
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    private static function writeFallbackList($list)
    {
        $path = self::getFallbackJsonPath();
        @file_put_contents($path, json_encode(array_values($list), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    public static function ensureTable($conn)
    {
        if (self::$tableChecked) {
            return self::$tableAvailable;
        }
        self::$tableChecked = true;

        // First check if table already exists using SELECT (no CREATE privilege needed)
        try {
            $conn->query("SELECT 1 FROM `service_price_excel_file` LIMIT 1");
            self::$tableAvailable = true;
            return true;
        } catch (Exception $e) {
            // Table does not exist yet; try CREATE TABLE if user has privilege
        }

        $sql = "CREATE TABLE IF NOT EXISTS `service_price_excel_file` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `hospital_id` int(11) NOT NULL DEFAULT 0 COMMENT '0 = ราคามาตรฐาน, >0 = hospital.id',
            `hospital_name` varchar(255) DEFAULT NULL COMMENT 'ชื่อโรงพยาบาล',
            `service_type_id` int(11) NOT NULL DEFAULT 0 COMMENT '0 = ทุกชนิด, >0 = service_type.id',
            `original_filename` varchar(255) DEFAULT NULL COMMENT 'ชื่อไฟล์ต้นฉบับ',
            `saved_filename` varchar(255) DEFAULT NULL COMMENT 'ชื่อไฟล์ตามโรงพยาบาลและวันที่อัปโหลด',
            `file_path` varchar(500) DEFAULT NULL COMMENT 'พาธไฟล์ที่เก็บในโฟลเดอร์',
            `row_count` int(11) NOT NULL DEFAULT 0 COMMENT 'จำนวนรายการที่นำเข้า',
            `upload_user_id` int(11) DEFAULT NULL COMMENT 'user.id ผู้อัปโหลด',
            `upload_user_name` varchar(255) DEFAULT NULL COMMENT 'ชื่อผู้อัปโหลด',
            `upload_date` datetime DEFAULT NULL COMMENT 'วันที่และเวลาที่อัปโหลด',
            PRIMARY KEY (`id`),
            KEY `idx_hospital_id` (`hospital_id`),
            KEY `idx_upload_date` (`upload_date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        try {
            $conn->exec($sql);
            self::$tableAvailable = true;
        } catch (Exception $e) {
            // CREATE command denied (Error 1142) or table missing: fallback to JSON index gracefully
            self::$tableAvailable = false;
        }

        return self::$tableAvailable;
    }

    public function create($conn)
    {
        if (self::ensureTable($conn)) {
            try {
                $sql = "INSERT INTO `service_price_excel_file`
                        (`hospital_id`, `hospital_name`, `service_type_id`, `original_filename`, `saved_filename`, `file_path`, `row_count`, `upload_user_id`, `upload_user_name`, `upload_date`)
                        VALUES
                        (:hospital_id, :hospital_name, :service_type_id, :original_filename, :saved_filename, :file_path, :row_count, :upload_user_id, :upload_user_name, :upload_date)";

                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':hospital_id', (int)$this->hospital_id, PDO::PARAM_INT);
                $stmt->bindValue(':hospital_name', (string)$this->hospital_name, PDO::PARAM_STR);
                $stmt->bindValue(':service_type_id', (int)$this->service_type_id, PDO::PARAM_INT);
                $stmt->bindValue(':original_filename', (string)$this->original_filename, PDO::PARAM_STR);
                $stmt->bindValue(':saved_filename', (string)$this->saved_filename, PDO::PARAM_STR);
                $stmt->bindValue(':file_path', (string)$this->file_path, PDO::PARAM_STR);
                $stmt->bindValue(':row_count', (int)$this->row_count, PDO::PARAM_INT);
                $stmt->bindValue(':upload_user_id', (int)$this->upload_user_id, PDO::PARAM_INT);
                $stmt->bindValue(':upload_user_name', (string)$this->upload_user_name, PDO::PARAM_STR);
                $stmt->bindValue(':upload_date', (string)$this->upload_date, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $this->id = $conn->lastInsertId();
                    return true;
                }
            } catch (Exception $e) {
                // Fallback below
            }
        }

        // Fallback storage in nb_price_8c_uploads/history_index.json if DB table is not created yet
        $list = self::readFallbackList();
        $maxId = 0;
        foreach ($list as $item) {
            if (isset($item['id']) && (int)$item['id'] > $maxId) {
                $maxId = (int)$item['id'];
            }
        }
        $this->id = $maxId + 1;
        $record = [
            'id' => $this->id,
            'hospital_id' => (int)$this->hospital_id,
            'hospital_name' => (string)$this->hospital_name,
            'resolved_hospital_name' => (string)$this->hospital_name,
            'service_type_id' => (int)$this->service_type_id,
            'original_filename' => (string)$this->original_filename,
            'saved_filename' => (string)$this->saved_filename,
            'file_path' => (string)$this->file_path,
            'row_count' => (int)$this->row_count,
            'upload_user_id' => (int)$this->upload_user_id,
            'upload_user_name' => (string)$this->upload_user_name,
            'resolved_user_name' => (string)$this->upload_user_name,
            'upload_date' => (string)$this->upload_date
        ];
        array_unshift($list, $record);
        self::writeFallbackList($list);
        return true;
    }

    public static function getAll($conn, $hospital_id = -1)
    {
        if (self::ensureTable($conn)) {
            try {
                $sql = "SELECT f.*,
                               COALESCE(NULLIF(f.hospital_name, ''), CASE WHEN f.hospital_id = 0 THEN 'ราคามาตรฐาน' ELSE h.hospital END) AS resolved_hospital_name,
                               COALESCE(NULLIF(f.upload_user_name, ''), TRIM(CONCAT(COALESCE(u.name, ''), ' ', COALESCE(u.lastname, '')))) AS resolved_user_name
                        FROM `service_price_excel_file` AS f
                        LEFT JOIN `hospital` AS h ON h.id = f.hospital_id
                        LEFT JOIN `user` AS u ON u.id = f.upload_user_id";

                if ($hospital_id !== -1 && $hospital_id !== null && $hospital_id !== '') {
                    $sql .= " WHERE f.hospital_id = :hospital_id";
                }

                $sql .= " ORDER BY f.id DESC;";

                $stmt = $conn->prepare($sql);
                if ($hospital_id !== -1 && $hospital_id !== null && $hospital_id !== '') {
                    $stmt->bindValue(':hospital_id', (int)$hospital_id, PDO::PARAM_INT);
                }
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                // Fallback below
            }
        }

        $list = self::readFallbackList();
        if ($hospital_id !== -1 && $hospital_id !== null && $hospital_id !== '') {
            $filtered = [];
            foreach ($list as $item) {
                if ((int)($item['hospital_id'] ?? -1) === (int)$hospital_id) {
                    $filtered[] = $item;
                }
            }
            return $filtered;
        }
        return $list;
    }

    public static function getByID($conn, $id)
    {
        if (self::ensureTable($conn)) {
            try {
                $sql = "SELECT * FROM `service_price_excel_file` WHERE id = :id LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    return $row;
                }
            } catch (Exception $e) {
                // Fallback below
            }
        }

        $list = self::readFallbackList();
        foreach ($list as $item) {
            if ((int)($item['id'] ?? 0) === (int)$id) {
                return $item;
            }
        }
        return false;
    }
}
