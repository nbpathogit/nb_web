<?php
require 'includes/init.php';
Auth::requireLogin("manage_database.php");
require 'user_auth.php';

if (!$isCurUserAdmin) {
    header("Location: index.php");
    exit;
}

$conn = require 'includes/db.php';

// Fetch all database tables and metadata
$tablesQuery = $conn->query("
    SELECT 
        TABLE_NAME, 
        ENGINE, 
        TABLE_ROWS, 
        DATA_LENGTH, 
        INDEX_LENGTH, 
        AUTO_INCREMENT, 
        TABLE_COMMENT,
        UPDATE_TIME
    FROM information_schema.TABLES 
    WHERE TABLE_SCHEMA = DATABASE() 
    ORDER BY TABLE_NAME ASC
");
$allTablesInfo = $tablesQuery->fetchAll(PDO::FETCH_ASSOC);

// Fetch EXACT row counts using SELECT COUNT(*) for each table (InnoDB TABLE_ROWS in information_schema is only a statistical estimate)
$tableMetaMap = [];
$tableNames = [];
foreach ($allTablesInfo as &$info) {
    $tName = $info['TABLE_NAME'];
    try {
        $exactCount = (int)$conn->query("SELECT COUNT(*) FROM `{$tName}`")->fetchColumn();
        $info['EXACT_ROWS'] = $exactCount;
    } catch (Exception $e) {
        $info['EXACT_ROWS'] = (int)$info['TABLE_ROWS'];
    }
    $tableMetaMap[$tName] = $info;
    $tableNames[] = $tName;
}
unset($info);

// Current selected table
$selectedTable = isset($_GET['table']) ? trim($_GET['table']) : '';
if ($selectedTable !== '' && !in_array($selectedTable, $tableNames, true)) {
    $selectedTable = '';
}

// ================= SAVED SQL QUERIES DATABASE STORAGE & AJAX API =================
// Ensure the table exists in database
$conn->exec("
    CREATE TABLE IF NOT EXISTS `saved_sql_query` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `title` VARCHAR(255) NOT NULL COMMENT 'ชื่อหรือคำอธิบายคำสั่ง SQL',
        `sql_query` MEDIUMTEXT NOT NULL COMMENT 'คำสั่ง SQL',
        `created_by` INT NULL DEFAULT NULL COMMENT 'รหัสผู้ใช้งานที่สร้าง',
        `stampdatecreate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่สร้าง',
        `stampdateedit` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่แก้ไขล่าสุด',
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บคำสั่ง SQL ที่บันทึกไว้สำหรับเรียกใช้ใน manage_database.php';
");

// AJAX Handlers for saving, updating, and deleting SQL queries in database
$actionAjax = isset($_GET['action_ajax']) ? trim($_GET['action_ajax']) : (isset($_POST['action_ajax']) ? trim($_POST['action_ajax']) : '');
if ($actionAjax !== '') {
    header('Content-Type: application/json; charset=utf-8');
    
    if ($actionAjax === 'save_query') {
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $sql = isset($_POST['sql']) ? trim($_POST['sql']) : '';
        
        if ($title === '' || $sql === '') {
            echo json_encode(['status' => 0, 'message' => 'กรุณากรอกชื่อและคำสั่ง SQL ให้ครบถ้วน']);
            exit;
        }

        try {
            $curUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
            $insertStmt = $conn->prepare("
                INSERT INTO `saved_sql_query` (`title`, `sql_query`, `created_by`, `stampdatecreate`)
                VALUES (:title, :sql_query, :created_by, NOW())
            ");
            $insertStmt->execute([
                ':title' => $title,
                ':sql_query' => $sql,
                ':created_by' => $curUserId
            ]);
            $newId = (int)$conn->lastInsertId();

            $newItem = [
                'id' => (string)$newId,
                'title' => $title,
                'sql' => $sql,
                'stampdatecreate' => date('Y-m-d H:i:s'),
                'stampdateedit' => null
            ];

            echo json_encode(['status' => 1, 'message' => 'บันทึกคำสั่ง SQL ลงฐานข้อมูลเรียบร้อยแล้ว', 'item' => $newItem]);
        } catch (Exception $e) {
            echo json_encode(['status' => 0, 'message' => 'เกิดข้อผิดพลาดในการบันทึกลงฐานข้อมูล: ' . $e->getMessage()]);
        }
        exit;
    }

    if ($actionAjax === 'update_query') {
        $id = isset($_POST['id']) ? trim($_POST['id']) : '';
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $sql = isset($_POST['sql']) ? trim($_POST['sql']) : '';

        if ($id === '' || $title === '' || $sql === '') {
            echo json_encode(['status' => 0, 'message' => 'ข้อมูลไม่ครบถ้วนสำหรับการอัพเดท']);
            exit;
        }

        try {
            $updateStmt = $conn->prepare("
                UPDATE `saved_sql_query`
                SET `title` = :title, `sql_query` = :sql_query, `stampdateedit` = NOW()
                WHERE `id` = :id
            ");
            $updateStmt->execute([
                ':title' => $title,
                ':sql_query' => $sql,
                ':id' => (int)$id
            ]);

            $updatedItem = [
                'id' => (string)$id,
                'title' => $title,
                'sql' => $sql,
                'stampdateedit' => date('Y-m-d H:i:s')
            ];

            echo json_encode(['status' => 1, 'message' => 'อัพเดทคำสั่ง SQL ในฐานข้อมูลเรียบร้อยแล้ว', 'item' => $updatedItem]);
        } catch (Exception $e) {
            echo json_encode(['status' => 0, 'message' => 'เกิดข้อผิดพลาดในการอัพเดทฐานข้อมูล: ' . $e->getMessage()]);
        }
        exit;
    }

    if ($actionAjax === 'delete_query') {
        $id = isset($_POST['id']) ? trim($_POST['id']) : '';
        if ($id === '') {
            echo json_encode(['status' => 0, 'message' => 'ไม่พบรหัสคำสั่ง SQL ที่ต้องการลบ']);
            exit;
        }

        try {
            $deleteStmt = $conn->prepare("DELETE FROM `saved_sql_query` WHERE `id` = :id");
            $deleteStmt->execute([':id' => (int)$id]);

            echo json_encode(['status' => 1, 'message' => 'ลบคำสั่ง SQL ออกจากฐานข้อมูลเรียบร้อยแล้ว']);
        } catch (Exception $e) {
            echo json_encode(['status' => 0, 'message' => 'เกิดข้อผิดพลาดในการลบจากฐานข้อมูล: ' . $e->getMessage()]);
        }
        exit;
    }

    echo json_encode(['status' => 0, 'message' => 'Invalid action']);
    exit;
}

// Load saved queries from database for page rendering
try {
    $savedQueriesStmt = $conn->query("
        SELECT `id`, `title`, `sql_query` AS `sql`, `stampdatecreate`, `stampdateedit` 
        FROM `saved_sql_query` 
        ORDER BY `id` DESC
    ");
    $savedQueries = $savedQueriesStmt->fetchAll(PDO::FETCH_ASSOC);
    // Ensure id is string to maintain consistent comparison in JS and HTML
    foreach ($savedQueries as &$sq) {
        $sq['id'] = (string)$sq['id'];
    }
    unset($sq);
} catch (Exception $e) {
    $savedQueries = [];
}

// ================= CUSTOM SQL QUERY PROCESSING =================
$customSql = isset($_POST['custom_sql']) ? trim($_POST['custom_sql']) : (isset($_GET['custom_sql']) ? trim($_GET['custom_sql']) : '');
$loadedSqlId = isset($_POST['loaded_sql_id']) ? trim($_POST['loaded_sql_id']) : (isset($_GET['loaded_sql_id']) ? trim($_GET['loaded_sql_id']) : '');
$sqlAction = isset($_POST['sql_action']) ? trim($_POST['sql_action']) : (isset($_GET['sql_action']) ? trim($_GET['sql_action']) : 'run');

// If loadedSqlId is empty but customSql matches one of the saved queries, auto-link it
if ($loadedSqlId === '' && $customSql !== '') {
    foreach ($savedQueries as $sq) {
        if (trim($sq['sql']) === trim($customSql)) {
            $loadedSqlId = $sq['id'];
            break;
        }
    }
}
$customSqlError = '';
$customSqlSuccess = false;
$customSqlCols = [];
$customSqlRows = [];
$customSqlCount = 0;
$customSqlExecTime = 0;

if ($customSql !== '') {
    // Safety check: allow SELECT, SHOW, DESCRIBE, EXPLAIN queries
    $trimmedUpper = strtoupper(ltrim($customSql));
    $isAllowedSelect = strpos($trimmedUpper, 'SELECT') === 0 
                    || strpos($trimmedUpper, 'SHOW') === 0 
                    || strpos($trimmedUpper, 'DESC') === 0 
                    || strpos($trimmedUpper, 'EXPLAIN') === 0;

    if (!$isAllowedSelect) {
        $customSqlError = 'ระบบอนุญาตเฉพาะคำสั่ง SELECT, SHOW, หรือ EXPLAIN เท่านั้น เพื่อความปลอดภัยของข้อมูล (Only SELECT queries are allowed)';
    } else {
        // Direct Download without plotting on the web page (Fastest)
        if ($sqlAction === 'export_excel' || $sqlAction === 'export_csv') {
            @set_time_limit(600);
            @ini_set('memory_limit', '1024M');
            try {
                $sqlStmt = $conn->query($customSql);
                if ($sqlStmt) {
                    $firstRow = $sqlStmt->fetch(PDO::FETCH_ASSOC);
                    if (!$firstRow) {
                        $customSqlError = 'คำสั่ง SQL ดำเนินการสำเร็จ แต่ไม่พบแถวข้อมูลที่ตรงตามเงื่อนไข (0 rows returned) จึงไม่มีข้อมูลสำหรับดาวน์โหลดเป็นไฟล์ Excel/CSV กรุณาตรวจสอบเงื่อนไขคำสั่ง SQL เช่น เงื่อนไข WHERE หรือชื่อฟิลด์';
                    } else {
                        while (ob_get_level()) {
                            ob_end_clean();
                        }
                        $filenameDate = date('Ymd_His');

                        if ($sqlAction === 'export_csv') {
                            $csvFilename = "SQL_Export_{$filenameDate}.csv";
                            header('Content-Type: text/csv; charset=utf-8');
                            header('Content-Disposition: attachment; filename="' . $csvFilename . '"');
                            header('Pragma: no-cache');
                            header('Expires: 0');

                            $out = fopen('php://output', 'w');
                            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
                            $cols = array_keys($firstRow);
                            fputcsv($out, $cols);
                            fputcsv($out, $firstRow);

                            while ($row = $sqlStmt->fetch(PDO::FETCH_ASSOC)) {
                                fputcsv($out, $row);
                            }
                            fclose($out);
                            exit;
                        }

                        if ($sqlAction === 'export_excel') {
                            $xlsFilename = "SQL_Export_{$filenameDate}.xls";
                            header('Content-Type: application/vnd.ms-excel; charset=utf-8');
                            header('Content-Disposition: attachment; filename="' . $xlsFilename . '"');
                            header('Pragma: no-cache');
                            header('Expires: 0');

                            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
                            echo '<head>';
                            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
                            echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>SQL_Export</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
                            echo '<style>';
                            echo 'table { border-collapse: collapse; }';
                            echo 'th { background-color: #0284c7; color: #ffffff; font-weight: bold; border: 1px solid #94a3b8; padding: 4px 8px; }';
                            echo 'td { border: 1px solid #cbd5e1; padding: 3px 6px; mso-number-format:\@; }';
                            echo '</style>';
                            echo '</head>';
                            echo '<body><table border="1">';

                            $cols = array_keys($firstRow);
                            echo '<tr>';
                            foreach ($cols as $col) {
                                echo '<th>' . htmlspecialchars((string)$col, ENT_QUOTES, 'UTF-8') . '</th>';
                            }
                            echo '</tr>';

                            echo '<tr>';
                            foreach ($cols as $col) {
                                $val = isset($firstRow[$col]) ? $firstRow[$col] : '';
                                echo '<td>' . htmlspecialchars((string)$val, ENT_QUOTES, 'UTF-8') . '</td>';
                            }
                            echo '</tr>';

                            while ($row = $sqlStmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>';
                                foreach ($cols as $col) {
                                    $val = isset($row[$col]) ? $row[$col] : '';
                                    echo '<td>' . htmlspecialchars((string)$val, ENT_QUOTES, 'UTF-8') . '</td>';
                                }
                                echo '</tr>';
                            }
                            echo '</table></body></html>';
                            exit;
                        }
                    }
                }
            } catch (Exception $e) {
                $customSqlError = 'SQL Error: ' . $e->getMessage();
            }
        } else {
            // Normal Run query for web display
            $startTime = microtime(true);
            try {
                $sqlStmt = $conn->query($customSql);
                if ($sqlStmt) {
                    $customSqlRows = $sqlStmt->fetchAll(PDO::FETCH_ASSOC);
                    $customSqlCount = count($customSqlRows);
                    $customSqlExecTime = round((microtime(true) - $startTime) * 1000, 2);
                    $customSqlSuccess = true;
                    if ($customSqlCount > 0) {
                        $customSqlCols = array_keys($customSqlRows[0]);
                    }
                }
            } catch (Exception $e) {
                $customSqlError = 'SQL Error: ' . $e->getMessage();
            }
        }
    }
}

// ================= ACTION: DIRECT FULL EXPORT ALL RECORDS =================
$action = isset($_GET['action']) ? trim($_GET['action']) : '';
if ($selectedTable !== '' && ($action === 'export_excel_all' || $action === 'export_csv_all')) {
    // Prevent script timeout and memory overflow for large tables (e.g. 80,000+ rows)
    @set_time_limit(600);
    @ini_set('memory_limit', '1024M');

    // 1. Fetch column headers
    $colStmt = $conn->prepare("
        SELECT COLUMN_NAME 
        FROM information_schema.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :tbl 
        ORDER BY ORDINAL_POSITION ASC
    ");
    $colStmt->execute([':tbl' => $selectedTable]);
    $exportCols = $colStmt->fetchAll(PDO::FETCH_COLUMN);

    $filenameDate = date('Ymd_His');

    if ($action === 'export_csv_all') {
        while (ob_get_level()) {
            ob_end_clean();
        }
        // Stream CSV with UTF-8 BOM
        $csvFilename = "NB_Patho_{$selectedTable}_ALL_{$filenameDate}.csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $csvFilename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        // UTF-8 BOM for Thai language compatibility in Excel
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($out, $exportCols);

        $cursor = $conn->query("SELECT * FROM `{$selectedTable}`");
        while ($row = $cursor->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($out, $row);
        }
        fclose($out);
        exit;
    }

    if ($action === 'export_excel_all') {
        while (ob_get_level()) {
            ob_end_clean();
        }
        // Stream HTML-Excel (.xls) which Microsoft Excel opens natively without memory limits
        $xlsFilename = "NB_Patho_{$selectedTable}_ALL_{$filenameDate}.xls";
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $xlsFilename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>' . htmlspecialchars($selectedTable) . '</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
        echo '<style>';
        echo 'table { border-collapse: collapse; }';
        echo 'th { background-color: #0284c7; color: #ffffff; font-weight: bold; border: 1px solid #94a3b8; padding: 4px 8px; }';
        echo 'td { border: 1px solid #cbd5e1; padding: 3px 6px; mso-number-format:\@; }';
        echo '</style>';
        echo '</head>';
        echo '<body><table border="1">';
        echo '<tr>';
        foreach ($exportCols as $col) {
            echo '<th>' . htmlspecialchars($col, ENT_QUOTES, 'UTF-8') . '</th>';
        }
        echo '</tr>';

        $cursor = $conn->query("SELECT * FROM `{$selectedTable}`");
        while ($row = $cursor->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            foreach ($exportCols as $col) {
                $val = isset($row[$col]) ? $row[$col] : '';
                echo '<td>' . htmlspecialchars((string)$val, ENT_QUOTES, 'UTF-8') . '</td>';
            }
            echo '</tr>';
        }
        echo '</table></body></html>';
        exit;
    }
}

// If a table is selected, get its columns and count
$columns = [];
$primaryKey = '';
$tableTotalRows = 0;

if ($selectedTable !== '') {
    // 1. Get Column details
    $colStmt = $conn->prepare("
        SELECT 
            COLUMN_NAME, 
            COLUMN_TYPE, 
            IS_NULLABLE, 
            COLUMN_KEY, 
            COLUMN_DEFAULT, 
            EXTRA, 
            COLUMN_COMMENT
        FROM information_schema.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :tbl 
        ORDER BY ORDINAL_POSITION ASC
    ");
    $colStmt->execute([':tbl' => $selectedTable]);
    $columns = $colStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($columns as $c) {
        if ($c['COLUMN_KEY'] === 'PRI') {
            $primaryKey = $c['COLUMN_NAME'];
            break;
        }
    }

    // 2. Count actual rows
    $cntStmt = $conn->query("SELECT COUNT(*) FROM `{$selectedTable}`");
    $tableTotalRows = (int)$cntStmt->fetchColumn();
}

require 'includes/header.php';
?>
<!-- DataTables SearchPanes & Select CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.0.0/css/searchPanes.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.4/css/select.bootstrap5.min.css">
<style>
    /* SearchPanes Layout & Style Matching Theme */
    .dtsp-panesContainer {
        background-color: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }
    .dtsp-panesContainer .dtsp-title {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.75rem;
    }
    .dtsp-pane {
        border: 1px solid #94a3b8 !important;
        border-radius: 6px !important;
        background: #ffffff !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .dtsp-searchPanes .dtsp-titleRow {
        padding: 0.35rem 0.5rem !important;
        font-weight: 600;
        color: #1e293b;
        background: #e2e8f0;
        border-bottom: 1px solid #cbd5e1;
    }
    .dtsp-search {
        font-size: 0.85rem !important;
    }
</style>
<?php
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-4">

        <!-- Header Title Section -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h3 class="text-primary mb-1">
                    <i class="fa-solid fa-database me-2"></i>จัดการฐานข้อมูล (Database Management)
                </h3>
                <p class="text-muted mb-0 small">
                    ตรวจสอบตาราง ค้นหาข้อมูล ส่งออก Excel และดูโครงสร้างฐานข้อมูลของระบบ NB Patho
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="dbtosqlfile.php" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-file-export me-1"></i>แบคอัพ SQL
                </a>
                <a href="manage_database.php" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-list me-1"></i>รายการตารางทั้งหมด
                </a>
            </div>
        </div>

        <!-- ================= TOP SECTION: Custom SQL Query Box (Available on Every Page) ================= -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fa-solid fa-terminal me-2 text-warning"></i>SQL Query Console (รันคำสั่ง SELECT กำหนดเองได้ทุกหน้า)</span>
                <span class="badge bg-secondary">MySQL 8.x / MariaDB</span>
            </div>
            <div class="card-body bg-light p-3">
                <form method="POST" action="manage_database.php<?= $selectedTable !== '' ? '?table=' . urlencode($selectedTable) : '' ?>" class="mb-0">
                    <input type="hidden" name="loaded_sql_id" id="loaded_sql_id" value="<?= htmlspecialchars($loadedSqlId) ?>">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2 flex-grow-1 flex-wrap" style="max-width: 680px;">
                            <label for="saved_sql_select" class="form-label mb-0 small text-nowrap fw-bold text-secondary">
                                <i class="fa-solid fa-bookmark text-primary me-1"></i>SQL ที่บันทึกไว้:
                            </label>
                            <select id="saved_sql_select" class="form-select form-select-sm" style="max-width: 380px;" onchange="onSelectSavedQuery(this.value, true)">
                                <option value="">-- เลือกคำสั่ง SQL ที่บันทึกไว้ --</option>
                                <?php foreach ($savedQueries as $sq): ?>
                                    <option value="<?= htmlspecialchars($sq['id']) ?>" <?= ($loadedSqlId === $sq['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($sq['title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span id="saved_sql_status_badge" class="badge" style="display:none; font-size: 0.8rem;"></span>
                            <button type="button" id="btn_delete_saved_sql" class="btn btn-outline-danger btn-sm text-nowrap" style="display:none;" onclick="deleteSelectedSavedQuery()" title="ลบคำสั่ง SQL นี้ออกจากรายการบันทึก">
                                <i class="fa-solid fa-trash-can"></i> ลบ
                            </button>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Update button (shown when a saved query is loaded and can be overwritten) -->
                            <button type="button" id="btn_update_saved_sql" class="btn btn-warning btn-sm fw-bold text-dark" style="display:none;" onclick="openSaveSqlModal('update')" title="บันทึกทับคำสั่งเดิมที่กำลังแก้ไข">
                                <i class="fa-solid fa-arrows-rotate me-1"></i>อัพเดท (เซฟทับ)
                            </button>
                            <!-- Save as new button -->
                            <button type="button" id="btn_save_new_sql" class="btn btn-outline-primary btn-sm fw-bold" onclick="openSaveSqlModal('new')" title="บันทึกเป็นคำสั่ง SQL รายการใหม่">
                                <i class="fa-solid fa-floppy-disk me-1"></i><span id="lbl_save_btn">บันทึก SQL นี้</span>
                            </button>
                        </div>
                    </div>
                    <div class="mb-2">
                        <textarea class="form-control font-monospace text-dark" name="custom_sql" id="custom_sql_input" rows="3" placeholder="พิมพ์คำสั่ง SQL เช่น: SELECT * FROM <?= $selectedTable !== '' ? $selectedTable : 'service_billing' ?> WHERE ... ORDER BY id DESC LIMIT 100" style="font-size: 0.92rem; background: #ffffff;"><?= htmlspecialchars($customSql !== '' ? $customSql : ($selectedTable !== '' ? "SELECT * FROM `{$selectedTable}` ORDER BY 1 DESC LIMIT 100" : "")) ?></textarea>
                    </div>
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex gap-1 flex-wrap">
                            <span class="text-muted small align-self-center me-2">ตัวอย่างคำสั่งด่วน:</span>
                            <?php if ($selectedTable !== ''): ?>
                                <button type="button" class="btn btn-outline-secondary btn-sm py-0" onclick="$('#custom_sql_input').val('SELECT * FROM `<?= $selectedTable ?>` LIMIT 50'); $('#loaded_sql_id').val(''); $('#saved_sql_select').val(''); updateToolbarState();">LIMIT 50</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm py-0" onclick="$('#custom_sql_input').val('SELECT COUNT(*) AS total_count FROM `<?= $selectedTable ?>`'); $('#loaded_sql_id').val(''); $('#saved_sql_select').val(''); updateToolbarState();">COUNT(*)</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-outline-secondary btn-sm py-0" onclick="$('#custom_sql_input').val('SELECT * FROM `service_billing` ORDER BY id DESC LIMIT 100'); $('#loaded_sql_id').val(''); $('#saved_sql_select').val(''); updateToolbarState();">service_billing</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm py-0" onclick="$('#custom_sql_input').val('SELECT * FROM `patient` ORDER BY id DESC LIMIT 50'); $('#loaded_sql_id').val(''); $('#saved_sql_select').val(''); updateToolbarState();">patient</button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-outline-danger btn-sm py-0" onclick="$('#custom_sql_input').val(''); $('#loaded_sql_id').val(''); $('#saved_sql_select').val(''); updateToolbarState();">ล้างข้อความ</button>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" name="sql_action" value="run" class="btn btn-primary btn-sm px-3 fw-bold" title="ประมวลผลคำสั่ง SQL และพล็อตแสดงผลบนหน้าเว็บ">
                                <i class="fa-solid fa-play me-1"></i>Run SQL (แสดงบนเว็บ)
                            </button>
                            <button type="submit" name="sql_action" value="export_excel" class="btn btn-success btn-sm px-3 fw-bold" title="ดาวน์โหลดผลลัพธ์เป็นไฟล์ Excel ทันทีโดยไม่ต้องพล็อตหน้าเว็บ เพื่อความรวดเร็ว">
                                <i class="fa-solid fa-file-excel me-1"></i>ดาวน์โหลดเป็น Excel โดยตรง (ไม่ต้องพล็อตหน้าเว็บ)
                            </button>
                            <button type="submit" name="sql_action" value="export_csv" class="btn btn-outline-success btn-sm px-2 fw-bold" title="ดาวน์โหลดผลลัพธ์เป็นไฟล์ CSV โดยตรง">
                                <i class="fa-solid fa-file-csv me-1"></i>CSV
                            </button>
                        </div>
                    </div>
                </form>

                <?php if ($customSqlError !== ''): ?>
                    <div class="alert alert-danger mt-3 mb-0 py-2 small">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i><?= htmlspecialchars($customSqlError) ?>
                    </div>
                <?php endif; ?>

                <?php if ($customSqlSuccess): ?>
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                            <div>
                                <span class="badge bg-success me-2"><i class="fa-solid fa-check me-1"></i>สำเร็จ: <?= number_format($customSqlCount) ?> แถว</span>
                                <span class="text-muted small">เวลาที่ใช้: <?= $customSqlExecTime ?> ms</span>
                            </div>
                            <span class="text-muted small font-monospace text-truncate" style="max-width: 500px;" title="<?= htmlspecialchars($customSql) ?>">
                                <?= htmlspecialchars($customSql) ?>
                            </span>
                        </div>

                        <?php if ($customSqlCount > 0): ?>
                            <div class="table-responsive bg-white rounded p-2 border">
                                <table id="tbl_custom_sql_result" class="table table-bordered table-hover table-striped align-middle mb-0" style="width:100%">
                                    <thead class="table-dark">
                                        <tr>
                                            <?php foreach ($customSqlCols as $col): ?>
                                                <th class="text-nowrap" data-name="<?= htmlspecialchars($col) ?>"><?= htmlspecialchars($col) ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($customSqlRows as $r): ?>
                                            <tr>
                                                <?php foreach ($customSqlCols as $col): 
                                                    $val = isset($r[$col]) ? $r[$col] : null;
                                                ?>
                                                    <td class="text-truncate" style="max-width: 250px;" title="<?= htmlspecialchars((string)$val) ?>">
                                                        <?php if ($val === null): ?>
                                                            <span class="text-muted fst-italic">NULL</span>
                                                        <?php elseif ($val === ''): ?>
                                                            <span class="text-muted fst-italic">&lt;empty&gt;</span>
                                                        <?php else: ?>
                                                            <?= htmlspecialchars((string)$val) ?>
                                                        <?php endif; ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info py-2 small mb-0">ไม่พบแถวข้อมูลที่ตรงตามเงื่อนไข (0 rows returned)</div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($selectedTable === ''): ?>
            <!-- ================= VIEW 1: All Tables Overview ================= -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fa-solid fa-table-cells me-2"></i>ตารางทั้งหมดในฐานข้อมูล (<?= count($tableNames) ?> ตาราง)</span>
                    <span class="badge bg-light text-primary">Database: <?= htmlspecialchars($conn->query("SELECT DATABASE()")->fetchColumn()) ?></span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl_all_tables" class="table table-hover table-striped align-middle" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>ชื่อตาราง (Table Name)</th>
                                    <th class="text-end">จำนวนแถว (Rows)</th>
                                    <th class="text-end">ขนาดข้อมูล (Data Size)</th>
                                    <th class="text-end">ขนาด Index</th>
                                    <th>Engine</th>
                                    <th>คำอธิบาย (Comment)</th>
                                    <th class="text-center" style="width: 160px;">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allTablesInfo as $idx => $t): 
                                    $dataKb = round($t['DATA_LENGTH'] / 1024, 1);
                                    $indexKb = round($t['INDEX_LENGTH'] / 1024, 1);
                                ?>
                                    <tr>
                                        <td><?= $idx + 1 ?></td>
                                        <td>
                                            <a href="manage_database.php?table=<?= urlencode($t['TABLE_NAME']) ?>" class="fw-bold text-decoration-none">
                                                <i class="fa-solid fa-table me-1 text-primary"></i><?= htmlspecialchars($t['TABLE_NAME']) ?>
                                            </a>
                                        </td>
                                        <td class="text-end fw-semibold">
                                            <?= number_format(isset($t['EXACT_ROWS']) ? $t['EXACT_ROWS'] : $t['TABLE_ROWS']) ?>
                                        </td>
                                        <td class="text-end text-muted small"><?= number_format($dataKb, 1) ?> KB</td>
                                        <td class="text-end text-muted small"><?= number_format($indexKb, 1) ?> KB</td>
                                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($t['ENGINE']) ?></span></td>
                                        <td class="text-muted small"><?= htmlspecialchars($t['TABLE_COMMENT']) ?></td>
                                        <td class="text-center text-nowrap">
                                            <div class="btn-group btn-group-sm">
                                                <a href="manage_database.php?table=<?= urlencode($t['TABLE_NAME']) ?>" class="btn btn-outline-primary" title="เปิดดูโครงสร้างและสลับดูตาราง">
                                                    <i class="fa-solid fa-eye me-1"></i>เปิดดู
                                                </a>
                                                <a href="manage_database.php?table=<?= urlencode($t['TABLE_NAME']) ?>&action=export_excel_all" class="btn btn-success" title="ดาวน์โหลดตารางนี้เป็น Excel ทั้งหมดโดยตรง (ไม่ต้องพล็อตหน้าเว็บ)">
                                                    <i class="fa-solid fa-file-excel me-1"></i>Excel
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- ================= VIEW 2: Single Table Data Viewer ================= -->
            
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-light p-2 rounded">
                    <li class="breadcrumb-item"><a href="manage_database.php"><i class="fa-solid fa-database me-1"></i>ตารางทั้งหมด</a></li>
                    <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">
                        <i class="fa-solid fa-table me-1"></i><?= htmlspecialchars($selectedTable) ?>
                    </li>
                </ol>
            </nav>

            <!-- Table Quick Selector & Filter Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <label for="table_selector" class="fw-bold mb-0 text-secondary"><i class="fa-solid fa-filter me-1"></i>สลับดูตารางอื่น:</label>
                        <select id="table_selector" class="form-select form-select-sm" style="width: auto; min-width: 260px;" onchange="if(this.value) location.href='manage_database.php?table=' + encodeURIComponent(this.value);">
                            <?php foreach ($tableNames as $tName): ?>
                                <option value="<?= htmlspecialchars($tName) ?>" <?= $tName === $selectedTable ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tName) ?> (<?= number_format(isset($tableMetaMap[$tName]['EXACT_ROWS']) ? $tableMetaMap[$tName]['EXACT_ROWS'] : 0) ?> rows)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="badge bg-primary fs-6">จำนวนทั้งหมด: <?= number_format($tableTotalRows) ?> รายการ</span>

                        <!-- Full Database Export Buttons (Streams all rows directly from DB without web plotting) -->
                        <div class="btn-group btn-group-sm ms-2">
                            <a href="manage_database.php?table=<?= urlencode($selectedTable) ?>&action=export_excel_all" class="btn btn-success fw-bold" title="ดาวน์โหลดข้อมูลทั้งหมดทุกแถวในฐานข้อมูลเป็นไฟล์ Excel (.xls) ทันทีโดยไม่ต้องพล็อตหน้าเว็บ เพื่อความรวดเร็ว">
                                <i class="fa-solid fa-file-excel me-1"></i>ดาวน์โหลด Excel ทั้งหมด (<?= number_format($tableTotalRows) ?> แถว)
                            </a>
                            <a href="manage_database.php?table=<?= urlencode($selectedTable) ?>&action=export_csv_all" class="btn btn-outline-success fw-bold" title="ดาวน์โหลดข้อมูลทั้งหมดเป็น CSV (UTF-8) โดยตรง">
                                <i class="fa-solid fa-file-csv me-1"></i>CSV ทั้งหมด
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Column Structure Collapsible -->
                    <div class="mb-0">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#columnStructureCollapse" aria-expanded="false" aria-controls="columnStructureCollapse">
                            <i class="fa-solid fa-columns me-1"></i>ดูโครงสร้างฟิลด์ (Columns Schema: <?= count($columns) ?> คอลัมน์)
                        </button>
                        <div class="collapse mt-2" id="columnStructureCollapse">
                            <div class="card card-body bg-light border-0 p-3">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered bg-white mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Field</th>
                                                <th>Type</th>
                                                <th>Null</th>
                                                <th>Key</th>
                                                <th>Default</th>
                                                <th>Extra</th>
                                                <th>Comment</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($columns as $c): ?>
                                                <tr>
                                                    <td class="fw-bold"><?= htmlspecialchars($c['COLUMN_NAME']) ?></td>
                                                    <td><code><?= htmlspecialchars($c['COLUMN_TYPE']) ?></code></td>
                                                    <td><?= $c['IS_NULLABLE'] === 'YES' ? '<span class="text-success">YES</span>' : '<span class="text-muted">NO</span>' ?></td>
                                                    <td>
                                                        <?php if ($c['COLUMN_KEY'] === 'PRI'): ?>
                                                            <span class="badge bg-danger">PRI</span>
                                                        <?php elseif ($c['COLUMN_KEY'] === 'UNI'): ?>
                                                            <span class="badge bg-info text-dark">UNI</span>
                                                        <?php elseif ($c['COLUMN_KEY'] === 'MUL'): ?>
                                                            <span class="badge bg-secondary">MUL</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-muted small"><?= $c['COLUMN_DEFAULT'] !== null ? htmlspecialchars($c['COLUMN_DEFAULT']) : '<em>NULL</em>' ?></td>
                                                    <td class="text-muted small"><?= htmlspecialchars($c['EXTRA']) ?></td>
                                                    <td class="text-muted small"><?= htmlspecialchars($c['COLUMN_COMMENT']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>
</div>

<!-- Modal Save / Update SQL -->
<div class="modal fade" id="modalSaveSql" tabindex="-1" aria-labelledby="modalSaveSqlLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2" id="modal_header_save">
                <h5 class="modal-title fs-6" id="modalSaveSqlLabel"><i class="fa-solid fa-floppy-disk me-2"></i>บันทึกคำสั่ง SQL</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Choice Container (shown when an existing query is currently loaded) -->
                <div class="mb-3 p-2 bg-light rounded border" id="save_mode_container" style="display:none;">
                    <label class="form-label fw-bold mb-1 small text-secondary">เลือกรูปแบบการบันทึก:</label>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="radio" name="save_action_type" id="mode_update" value="update" onchange="onSaveModeChange()">
                        <label class="form-check-label fw-bold text-dark" for="mode_update">
                            <i class="fa-solid fa-arrows-rotate text-warning me-1"></i>อัพเดท (บันทึกทับรายการเดิม)
                        </label>
                        <div class="text-muted small ps-1">จะเซฟทับรายการ <span id="mode_update_name" class="fw-bold text-primary"></span></div>
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="radio" name="save_action_type" id="mode_new" value="new" onchange="onSaveModeChange()">
                        <label class="form-check-label fw-bold text-dark" for="mode_new">
                            <i class="fa-solid fa-plus text-success me-1"></i>บันทึกเป็นรายการใหม่
                        </label>
                        <div class="text-muted small ps-1">สร้างรายการ SQL ใหม่เพิ่มอีก 1 รายการโดยไม่ทับรายการเดิม</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="save_sql_title" class="form-label fw-bold">ชื่อหรือคำอธิบายคำสั่ง SQL <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="save_sql_title" placeholder="เช่น สรุปยอดคนไข้และสไลด์พิเศษปี 2026" maxlength="150">
                </div>
                <div class="mb-2">
                    <label for="save_sql_preview" class="form-label text-muted small fw-bold">คำสั่ง SQL ที่จะบันทึก</label>
                    <textarea class="form-control font-monospace bg-light" id="save_sql_preview" rows="4" readonly style="font-size: 0.85rem;"></textarea>
                </div>
                <div id="save_sql_alert" class="alert alert-danger py-1 px-2 small mb-0" style="display:none;"></div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary btn-sm px-3 fw-bold" id="btn_confirm_save_sql" onclick="submitSaveSql()">
                    <i class="fa-solid fa-check me-1"></i>บันทึก
                </button>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>

<!-- DataTables Buttons & Excel Export Scripts -->
<script>
    // Saved SQL queries memory cache
    var savedQueriesData = <?= json_encode($savedQueries, JSON_UNESCAPED_UNICODE) ?>;
    var currentLoadedQueryId = null;
    var originalQuerySql = '';
    var originalQueryTitle = '';

    function updateToolbarState() {
        var curSql = $('#custom_sql_input').val();
        if (currentLoadedQueryId) {
            var found = savedQueriesData.find(function(item) {
                return item.id === currentLoadedQueryId;
            });
            if (found) {
                var isModified = (curSql.trim() !== (found.sql || '').trim());
                if (isModified) {
                    $('#saved_sql_status_badge').show().removeClass('bg-info text-white bg-success').addClass('bg-warning text-dark')
                        .html('<i class="fa-solid fa-pen me-1"></i>มีการแก้ไข');
                    $('#btn_update_saved_sql').show().html('<i class="fa-solid fa-arrows-rotate me-1"></i>อัพเดท (เซฟทับ)');
                } else {
                    $('#saved_sql_status_badge').show().removeClass('bg-warning text-dark bg-success').addClass('bg-info text-white')
                        .html('<i class="fa-solid fa-check me-1"></i>โหลดแล้ว');
                    $('#btn_update_saved_sql').show().html('<i class="fa-solid fa-arrows-rotate me-1"></i>เซฟทับเดิม');
                }
                $('#btn_delete_saved_sql').show();
                $('#lbl_save_btn').text('เซฟเป็นอันใหม่');
                return;
            }
        }
        // No query loaded
        $('#saved_sql_status_badge').hide();
        $('#btn_update_saved_sql').hide();
        $('#btn_delete_saved_sql').hide();
        $('#lbl_save_btn').text('บันทึก SQL นี้');
    }

    function onSelectSavedQuery(selectedId, shouldSetTextarea) {
        if (typeof shouldSetTextarea === 'undefined') {
            shouldSetTextarea = true;
        }
        $('#loaded_sql_id').val(selectedId || '');
        if (!selectedId) {
            currentLoadedQueryId = null;
            originalQuerySql = '';
            originalQueryTitle = '';
            updateToolbarState();
            return;
        }
        var found = savedQueriesData.find(function(item) {
            return item.id === selectedId;
        });
        if (found) {
            currentLoadedQueryId = found.id;
            originalQuerySql = found.sql;
            originalQueryTitle = found.title;
            if (shouldSetTextarea) {
                $('#custom_sql_input').val(found.sql);
            }
            updateToolbarState();
        } else {
            currentLoadedQueryId = null;
            updateToolbarState();
        }
    }

    function openSaveSqlModal(preferredMode) {
        var sqlText = $('#custom_sql_input').val().trim();
        if (!sqlText) {
            alert('กรุณาพิมพ์คำสั่ง SQL ในช่องข้อความก่อนบันทึก');
            $('#custom_sql_input').focus();
            return;
        }
        $('#save_sql_preview').val(sqlText);
        $('#save_sql_alert').hide().text('');
        
        var loadedItem = null;
        if (currentLoadedQueryId) {
            loadedItem = savedQueriesData.find(function(item) {
                return item.id === currentLoadedQueryId;
            });
        }

        if (loadedItem) {
            $('#save_mode_container').show();
            $('#mode_update_name').text('"' + loadedItem.title + '"');
            
            if (preferredMode === 'new') {
                $('#mode_new').prop('checked', true);
                $('#save_sql_title').val(loadedItem.title + ' (คัดลอก)');
                $('#btn_confirm_save_sql').removeClass('btn-warning text-dark').addClass('btn-primary')
                    .html('<i class="fa-solid fa-plus me-1"></i>บันทึกเป็นรายการใหม่');
                $('#modalSaveSqlLabel').html('<i class="fa-solid fa-plus me-2"></i>บันทึกเป็นรายการใหม่');
            } else {
                $('#mode_update').prop('checked', true);
                $('#save_sql_title').val(loadedItem.title);
                $('#btn_confirm_save_sql').removeClass('btn-primary').addClass('btn-warning text-dark')
                    .html('<i class="fa-solid fa-arrows-rotate me-1"></i>อัพเดท (บันทึกทับ)');
                $('#modalSaveSqlLabel').html('<i class="fa-solid fa-arrows-rotate me-2"></i>อัพเดทคำสั่ง SQL (บันทึกทับ)');
            }
        } else {
            $('#save_mode_container').hide();
            $('#mode_new').prop('checked', true);
            $('#save_sql_title').val('');
            $('#btn_confirm_save_sql').removeClass('btn-warning text-dark').addClass('btn-primary')
                .html('<i class="fa-solid fa-check me-1"></i>บันทึก');
            $('#modalSaveSqlLabel').html('<i class="fa-solid fa-floppy-disk me-2"></i>บันทึกคำสั่ง SQL ไว้ใช้ภายหลัง');
        }

        var modalEl = document.getElementById('modalSaveSql');
        var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
        setTimeout(function() {
            if (preferredMode === 'update') {
                $('#btn_confirm_save_sql').focus();
            } else {
                $('#save_sql_title').focus();
            }
        }, 400);
    }

    function onSaveModeChange() {
        var mode = $('input[name="save_action_type"]:checked').val();
        var loadedItem = currentLoadedQueryId ? savedQueriesData.find(function(item) { return item.id === currentLoadedQueryId; }) : null;
        
        if (mode === 'update' && loadedItem) {
            $('#save_sql_title').val(loadedItem.title);
            $('#btn_confirm_save_sql').removeClass('btn-primary').addClass('btn-warning text-dark')
                .html('<i class="fa-solid fa-arrows-rotate me-1"></i>อัพเดท (บันทึกทับ)');
            $('#modalSaveSqlLabel').html('<i class="fa-solid fa-arrows-rotate me-2"></i>อัพเดทคำสั่ง SQL (บันทึกทับ)');
        } else {
            if (loadedItem) {
                $('#save_sql_title').val(loadedItem.title + ' (คัดลอก)');
            }
            $('#btn_confirm_save_sql').removeClass('btn-warning text-dark').addClass('btn-primary')
                .html('<i class="fa-solid fa-plus me-1"></i>บันทึกเป็นรายการใหม่');
            $('#modalSaveSqlLabel').html('<i class="fa-solid fa-plus me-2"></i>บันทึกเป็นรายการใหม่');
        }
    }

    function submitSaveSql() {
        var title = $('#save_sql_title').val().trim();
        var sqlText = $('#save_sql_preview').val().trim();
        var mode = $('input[name="save_action_type"]:checked').val() || 'new';
        
        if (!title) {
            $('#save_sql_alert').text('กรุณากรอกชื่อหรือคำอธิบายคำสั่ง SQL').show();
            $('#save_sql_title').focus();
            return;
        }
        if (!sqlText) {
            $('#save_sql_alert').text('ไม่พบคำสั่ง SQL ที่จะบันทึก').show();
            return;
        }
        
        $('#btn_confirm_save_sql').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i>กำลังบันทึก...');

        if (mode === 'update' && currentLoadedQueryId) {
            $.ajax({
                url: 'manage_database.php?action_ajax=update_query',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: currentLoadedQueryId,
                    title: title,
                    sql: sqlText
                },
                success: function(res) {
                    $('#btn_confirm_save_sql').prop('disabled', false);
                    if (res && res.status === 1 && res.item) {
                        var idx = savedQueriesData.findIndex(function(it) { return it.id === currentLoadedQueryId; });
                        if (idx !== -1) {
                            savedQueriesData[idx] = res.item;
                        }
                        originalQuerySql = res.item.sql;
                        originalQueryTitle = res.item.title;

                        $('#saved_sql_select option[value="' + currentLoadedQueryId + '"]').text(res.item.title);
                        $('#loaded_sql_id').val(currentLoadedQueryId);
                        $('#saved_sql_select').val(currentLoadedQueryId);
                        
                        var modalEl = document.getElementById('modalSaveSql');
                        var modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();

                        updateToolbarState();
                        alert('อัพเดทคำสั่ง SQL (บันทึกทับ) เรียบร้อยแล้ว');
                    } else {
                        $('#save_sql_alert').text((res && res.message) ? res.message : 'เกิดข้อผิดพลาดในการอัพเดท').show();
                    }
                },
                error: function(xhr, status, err) {
                    $('#btn_confirm_save_sql').prop('disabled', false);
                    $('#save_sql_alert').text('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์: ' + err).show();
                }
            });
        } else {
            $.ajax({
                url: 'manage_database.php?action_ajax=save_query',
                type: 'POST',
                dataType: 'json',
                data: {
                    title: title,
                    sql: sqlText
                },
                success: function(res) {
                    $('#btn_confirm_save_sql').prop('disabled', false);
                    if (res && res.status === 1 && res.item) {
                        savedQueriesData.unshift(res.item);
                        currentLoadedQueryId = res.item.id;
                        originalQuerySql = res.item.sql;
                        originalQueryTitle = res.item.title;

                        var newOption = $('<option>', {
                            value: res.item.id,
                            text: res.item.title
                        });
                        $('#saved_sql_select option:first').after(newOption);
                        $('#saved_sql_select').val(res.item.id);
                        $('#loaded_sql_id').val(res.item.id);

                        var modalEl = document.getElementById('modalSaveSql');
                        var modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();

                        updateToolbarState();
                        alert('บันทึกคำสั่ง SQL เป็นรายการใหม่เรียบร้อยแล้ว');
                    } else {
                        $('#save_sql_alert').text((res && res.message) ? res.message : 'เกิดข้อผิดพลาดในการบันทึก').show();
                    }
                },
                error: function(xhr, status, err) {
                    $('#btn_confirm_save_sql').prop('disabled', false);
                    $('#save_sql_alert').text('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์: ' + err).show();
                }
            });
        }
    }

    function deleteSelectedSavedQuery() {
        var selectedId = $('#saved_sql_select').val();
        if (!selectedId) return;
        
        var found = savedQueriesData.find(function(item) {
            return item.id === selectedId;
        });
        var titleName = found ? found.title : 'คำสั่งนี้';
        
        if (!confirm('คุณต้องการลบ "' + titleName + '" ออกจากรายการบันทึกใช่หรือไม่?')) {
            return;
        }
        
        $.ajax({
            url: 'manage_database.php?action_ajax=delete_query',
            type: 'POST',
            dataType: 'json',
            data: {
                id: selectedId
            },
            success: function(res) {
                if (res && res.status === 1) {
                    savedQueriesData = savedQueriesData.filter(function(item) {
                        return item.id !== selectedId;
                    });
                    
                    $('#saved_sql_select option[value="' + selectedId + '"]').remove();
                    $('#saved_sql_select').val('');
                    $('#loaded_sql_id').val('');
                    currentLoadedQueryId = null;
                    originalQuerySql = '';
                    originalQueryTitle = '';
                    updateToolbarState();
                    
                    alert('ลบคำสั่ง SQL เรียบร้อยแล้ว');
                } else {
                    alert((res && res.message) ? res.message : 'เกิดข้อผิดพลาดในการลบ');
                }
            },
            error: function(xhr, status, err) {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์: ' + err);
            }
        });
    }

    $(document).ready(function() {
        // Trigger save on Enter key in title input
        $(document).on('keypress', '#save_sql_title', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                submitSaveSql();
            }
        });

        // Monitor changes to SQL text to update toolbar status badge & buttons
        $('#custom_sql_input').on('input', function() {
            updateToolbarState();
        });

        // Initialize state on page load (preserves loaded query across Run SQL form submission)
        var initSqlId = $('#loaded_sql_id').val() || $('#saved_sql_select').val();
        if (initSqlId) {
            $('#saved_sql_select').val(initSqlId);
            onSelectSavedQuery(initSqlId, false);
        } else {
            updateToolbarState();
        }

        // Highlight Admin Sidebar Menu
        $("#admin_dropdown").addClass("show");
        $("#admin_tab").addClass("active");
        $("#manage_database_tab").addClass("active");

        // 0. Initialize Custom SQL Result DataTable (with SearchPanes & Excel Export)
        if ($('#tbl_custom_sql_result').length) {
            var sqlColDefs = [];
            $('#tbl_custom_sql_result thead th').each(function(idx) {
                var colName = $(this).attr('data-name') || $(this).text().trim();
                if (idx < 10) {
                    sqlColDefs.push({
                        targets: idx,
                        searchPanes: {
                            show: true,
                            header: colName
                        }
                    });
                } else {
                    sqlColDefs.push({
                        targets: idx,
                        searchPanes: {
                            show: false
                        }
                    });
                }
            });

            $('#tbl_custom_sql_result').DataTable({
                responsive: false,
                scrollX: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "ทั้งหมด"]],
                dom: 'PBflrtip',
                searchPanes: {
                    layout: 'columns-5',
                    initCollapsed: false,
                    cascadePanes: true,
                    viewTotal: true,
                    i18n: {
                        collapse: { 0: 'ตัวกรอง (SearchPanes)', _: 'ตัวกรอง (%d ตัวทำงาน)' },
                        clearMessage: 'Clear All',
                        collapseMessage: 'Collapse All',
                        showMessage: 'Show All',
                        title: 'Filters Active - %d',
                        emptyPanes: 'ไม่มีตัวกรอง',
                        count: '{total}',
                        countFiltered: '{shown} ({total})'
                    }
                },
                columnDefs: sqlColDefs,
                buttons: [
                    {
                        text: 'export excel',
                        extend: 'excel',
                        filename: 'SQL_Result_' + new Date().toISOString().slice(0, 10)
                    },
                    {
                        extend: 'csv',
                        text: 'export csv',
                        charset: 'utf-8',
                        extension: '.csv',
                        fieldSeparator: ',',
                        fieldBoundary: '',
                        filename: 'SQL_Result_' + new Date().toISOString().slice(0, 10),
                        bom: true
                    }
                ],
                language: {
                    search: "ค้นหาในผลลัพธ์ SQL:",
                    lengthMenu: "แสดง _MENU_ แถว",
                    info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ แถว",
                    infoEmpty: "ไม่มีข้อมูล",
                    paginate: {
                        first: "หน้าแรก",
                        last: "หน้าสุดท้าย",
                        next: "ถัดไป",
                        previous: "ก่อนหน้า"
                    }
                }
            });
        }

        // 1. Initialize All Tables DataTable
        if ($('#tbl_all_tables').length) {
            $('#tbl_all_tables').DataTable({
                responsive: true,
                pageLength: 50,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "ทั้งหมด"]],
                dom: 'Bflrtip',
                buttons: [
                    {
                        text: 'export excel',
                        extend: 'excel',
                    },
                    {
                        extend: 'csv',
                        text: 'export csv',
                        charset: 'utf-8',
                        extension: '.csv',
                        fieldSeparator: ',',
                        fieldBoundary: '',
                        filename: 'export_tables',
                        bom: true
                    }
                ],
                language: {
                    search: "ค้นหาตาราง:",
                    lengthMenu: "แสดง _MENU_ ตาราง",
                    info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ ตาราง",
                    infoEmpty: "ไม่มีข้อมูลตาราง",
                    paginate: {
                        first: "หน้าแรก",
                        last: "หน้าสุดท้าย",
                        next: "ถัดไป",
                        previous: "ก่อนหน้า"
                    }
                }
            });
        }
    });
</script>
