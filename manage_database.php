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
        // Stream HTML-Excel (.xls) which Microsoft Excel opens natively without memory limits
        $xlsFilename = "NB_Patho_{$selectedTable}_ALL_{$filenameDate}.xls";
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $xlsFilename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>';
        echo '<body><table border="1">';
        echo '<tr style="background-color: #0284c7; color: #ffffff; font-weight: bold;">';
        foreach ($exportCols as $col) {
            echo '<th>' . htmlspecialchars($col, ENT_QUOTES, 'UTF-8') . '</th>';
        }
        echo '</tr>';

        $cursor = $conn->query("SELECT * FROM `{$selectedTable}`");
        while ($row = $cursor->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            foreach ($exportCols as $col) {
                $val = isset($row[$col]) ? $row[$col] : '';
                // Format numbers as string if they look like large numeric IDs to prevent scientific notation
                $style = is_numeric($val) && strlen((string)$val) > 9 ? ' style="mso-number-format:\'\@\';"' : '';
                echo '<td' . $style . '>' . htmlspecialchars((string)$val, ENT_QUOTES, 'UTF-8') . '</td>';
            }
            echo '</tr>';
        }
        echo '</table></body></html>';
        exit;
    }
}

// If a table is selected, get its columns and data
$columns = [];
$primaryKey = '';
$tableData = [];
$tableTotalRows = 0;
$queryLimit = 2000; // safe max limit for browser view
$isLimited = false;

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

    // 3. Fetch rows
    $orderBy = $primaryKey !== '' ? "ORDER BY `{$primaryKey}` DESC" : "";
    $dataStmt = $conn->query("SELECT * FROM `{$selectedTable}` {$orderBy} LIMIT {$queryLimit}");
    $tableData = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

    if ($tableTotalRows > $queryLimit) {
        $isLimited = true;
    }
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
                                    <th class="text-center" style="width: 120px;">การจัดการ</th>
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
                                        <td class="text-center">
                                            <a href="manage_database.php?table=<?= urlencode($t['TABLE_NAME']) ?>" class="btn btn-sm btn-outline-primary" title="เปิดดูข้อมูลและส่งออก Excel">
                                                <i class="fa-solid fa-eye me-1"></i>เปิดดู
                                            </a>
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
                        <?php if ($isLimited): ?>
                            <span class="badge bg-warning text-dark" title="ระบบโหลด 2,000 แถวบนหน้าเว็บเพื่อความรวดเร็วของเบราว์เซอร์">
                                <i class="fa-solid fa-circle-info me-1"></i>หน้าเว็บแสดง: <?= number_format($queryLimit) ?> แถวล่าสุด
                            </span>
                        <?php endif; ?>

                        <!-- Full Database Export Buttons (Streams all 80,000+ rows directly from DB) -->
                        <div class="btn-group btn-group-sm ms-2">
                            <a href="manage_database.php?table=<?= urlencode($selectedTable) ?>&action=export_excel_all" class="btn btn-success fw-bold" title="ดาวน์โหลดข้อมูลทั้งหมดทุกแถวในฐานข้อมูลเป็นไฟล์ Excel (.xls)">
                                <i class="fa-solid fa-file-excel me-1"></i>ส่งออกทั้งหมดเป็น Excel (<?= number_format($tableTotalRows) ?> แถว)
                            </a>
                            <a href="manage_database.php?table=<?= urlencode($selectedTable) ?>&action=export_csv_all" class="btn btn-outline-success fw-bold" title="ดาวน์โหลดข้อมูลทั้งหมดเป็น CSV (UTF-8)">
                                <i class="fa-solid fa-file-csv me-1"></i>CSV ทั้งหมด
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Column Structure Collapsible -->
                    <div class="mb-3">
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

                    <!-- DataTable for Selected Table Rows -->
                    <div class="table-responsive">
                        <table id="tbl_single_data" class="table table-bordered table-hover table-striped align-middle" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <?php foreach ($columns as $c): ?>
                                        <th class="text-nowrap" data-name="<?= htmlspecialchars($c['COLUMN_NAME']) ?>" title="<?= htmlspecialchars($c['COLUMN_COMMENT'] ?: $c['COLUMN_NAME']) ?>"><?= htmlspecialchars($c['COLUMN_NAME']) ?><?php if ($c['COLUMN_KEY'] === 'PRI'): ?> <i class="fa-solid fa-key text-warning ms-1" title="Primary Key"></i><?php endif; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tableData as $row): ?>
                                    <tr>
                                        <?php foreach ($columns as $c): 
                                            $colName = $c['COLUMN_NAME'];
                                            $val = isset($row[$colName]) ? $row[$colName] : null;
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
                </div>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<!-- DataTables Buttons & Excel Export Scripts -->
<script>
    $(document).ready(function() {
        // Highlight Admin Sidebar Menu
        $("#admin_dropdown").addClass("show");
        $("#admin_tab").addClass("active");
        $("#manage_database_tab").addClass("active");

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

        // 2. Initialize Single Table Data DataTable
        if ($('#tbl_single_data').length) {
            var currentTableName = "<?= htmlspecialchars($selectedTable) ?>";
            var colDefs = [];
            $('#tbl_single_data thead th').each(function(idx) {
                var colName = $(this).attr('data-name') || $(this).text().trim();
                // แสดง SearchPane เฉพาะ 12 คอลัมน์แรกเพื่อประสิทธิภาพและความสวยงาม
                if (idx < 12) {
                    colDefs.push({
                        targets: idx,
                        searchPanes: {
                            show: true,
                            header: colName // กำหนดชื่อหัวข้อของแต่ละฟิลเตอร์ให้ตรงกับชื่อคอลัมน์ชัดเจน 100%
                        }
                    });
                } else {
                    colDefs.push({
                        targets: idx,
                        searchPanes: {
                            show: false
                        }
                    });
                }
            });

            $('#tbl_single_data').DataTable({
                responsive: false,
                scrollX: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "ทั้งหมด"]],
                dom: 'PBflrtip',
                searchPanes: {
                    layout: 'columns-4',
                    initCollapsed: false,
                    cascadePanes: true,
                    viewTotal: true,
                    i18n: {
                        collapse: { 0: 'ตัวกรอง (SearchPanes)', _: 'ตัวกรอง (%d ตัวทำงาน)' },
                        clearMessage: 'Clear All',
                        collapseMessage: 'Collapse All',
                        showMessage: 'Show All',
                        title: 'Filters Active - %d',
                        emptyPanes: 'ไม่มีตัวกรองสำหรับฟิลด์นี้',
                        count: '{total}',
                        countFiltered: '{shown} ({total})'
                    }
                },
                columnDefs: colDefs,
                buttons: [
                    {
                        text: 'export excel',
                        extend: 'excel',
                        filename: 'NB_Patho_' + currentTableName + '_' + new Date().toISOString().slice(0, 10)
                    },
                    {
                        extend: 'csv',
                        text: 'export csv',
                        charset: 'utf-8',
                        extension: '.csv',
                        fieldSeparator: ',',
                        fieldBoundary: '',
                        filename: 'export_' + currentTableName,
                        bom: true
                    }
                ],
                language: {
                    search: "ค้นหาข้อมูลในตาราง:",
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
    });
</script>
