<?php
require 'includes/init.php';
Auth::requireLogin("dbtosqlfile.php");

$conn = require 'includes/db.php';
require 'user_auth.php';

// Backup directory path: C:\anuchit2\nb_web\dev3\db_bk_sql
$backupDir = __DIR__ . DIRECTORY_SEPARATOR . 'db_bk_sql';
if (!is_dir($backupDir)) {
    @mkdir($backupDir, 0777, true);
}

// Target max file size: ~10 MB (10 * 1024 * 1024 bytes)
define('DB_BK_MAX_FILE_BYTES', 10 * 1024 * 1024);

/**
 * Helper: Format bytes into human-readable KB / MB
 */
function dbBkFormatBytes($bytes)
{
    $bytes = (float)$bytes;
    if ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    }
    if ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    }
    return number_format($bytes, 0) . ' B';
}

/**
 * Helper: Safely quote value for SQL output
 */
function dbBkQuoteValue($conn, $val)
{
    if ($val === null) {
        return 'NULL';
    }
    return $conn->quote((string)$val);
}

/**
 * Helper: Get all tables that have stampdatecreate or stampdateedit
 */
function dbBkGetTablesMetadata($conn)
{
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $meta = [];
    foreach ($tables as $table) {
        $cols = $conn->query("SHOW COLUMNS FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
        $colNames = [];
        $pkCols = [];
        $uniCols = [];
        $hasCreate = false;
        $hasEdit = false;

        foreach ($cols as $c) {
            $field = $c['Field'];
            $colNames[] = $field;
            if ($field === 'stampdatecreate') {
                $hasCreate = true;
            }
            if ($field === 'stampdateedit') {
                $hasEdit = true;
            }
            if ($c['Key'] === 'PRI') {
                $pkCols[] = $field;
            } elseif ($c['Key'] === 'UNI') {
                $uniCols[] = $field;
            }
        }

        if ($hasCreate || $hasEdit) {
            $keyCols = !empty($pkCols) ? $pkCols : (!empty($uniCols) ? [$uniCols[0]] : [$colNames[0]]);
            $meta[$table] = [
                'columns' => $colNames,
                'pk' => $keyCols,
                'has_create' => $hasCreate,
                'has_edit' => $hasEdit,
                'has_int_id' => in_array('id', $pkCols, true),
            ];
        }
    }
    return $meta;
}

/**
 * Helper: Check if backup file(s) already exist for a given date (YYYY-MM-DD) and hour (HH)
 */
function dbBkGetExistingFilesForHour($backupDir, $dateStr, $hourStr)
{
    $patternNew = $backupDir . DIRECTORY_SEPARATOR . $dateStr . '_' . $hourStr . '0000_to_' . $hourStr . '5959_*.sql';
    $patternOld = $backupDir . DIRECTORY_SEPARATOR . $dateStr . '_' . $hourStr . '_*.sql';
    $matchesNew = glob($patternNew);
    $matchesOld = glob($patternOld);
    $matches = array_unique(array_merge(
        is_array($matchesNew) ? $matchesNew : [],
        is_array($matchesOld) ? $matchesOld : []
    ));
    $valid = [];
    foreach ($matches as $m) {
        if (is_file($m) && filesize($m) > 0) {
            $valid[] = basename($m);
        }
    }
    sort($valid);
    return $valid;
}

/**
 * Helper: Scan existing .sql files in db_bk_sql/ and return structured list
 */
function dbBkListAllFiles($backupDir)
{
    if (!is_dir($backupDir)) {
        return [];
    }
    $files = glob($backupDir . DIRECTORY_SEPARATOR . '*.sql');
    if (!is_array($files)) {
        return [];
    }

    $list = [];
    foreach ($files as $fullPath) {
        if (!is_file($fullPath)) {
            continue;
        }
        $fname = basename($fullPath);
        $size = @filesize($fullPath);
        $mtime = @filemtime($fullPath);

        $datePart = '-';
        $hourPart = '-';
        $hourRangeLabel = '-';
        $extraPart = '-';

        // Parse YYYY-MM-DD_HH0000_to_HH5959_??.sql -> e.g. 2026-11-08_110000_to_115959_01.sql
        if (preg_match('/^(\d{4}-\d{2}-\d{2})_(\d{2})0000_to_\d{2}5959_(.+)\.sql$/i', $fname, $m)) {
            $datePart = $m[1];
            $hourPart = $m[2];
            $extraPart = $m[3];
            $hourRangeLabel = $datePart . ' ' . $hourPart . ':00:00 - ' . $hourPart . ':59:59';
        } elseif (preg_match('/^(\d{4}-\d{2}-\d{2})_(\d{2})_(.+)\.sql$/i', $fname, $m)) {
            $datePart = $m[1];
            $hourPart = $m[2];
            $extraPart = $m[3];
            $hourRangeLabel = $datePart . ' ' . $hourPart . ':00:00 - ' . $hourPart . ':59:59';
        }

        $list[] = [
            'filename' => $fname,
            'date' => $datePart,
            'hour' => $hourPart,
            'hour_key' => ($datePart !== '-' && $hourPart !== '-') ? ($datePart . '_' . $hourPart) : $fname,
            'hour_range' => $hourRangeLabel,
            'extra' => $extraPart,
            'size_bytes' => (int)$size,
            'size_formatted' => dbBkFormatBytes($size),
            'mtime' => (int)$mtime,
            'mtime_formatted' => $mtime ? date('Y-m-d H:i:s', $mtime) : '-',
        ];
    }

    // Sort newest hour_key first, then filename ascending
    usort($list, function ($a, $b) {
        if ($a['hour_key'] !== $b['hour_key']) {
            return strcmp($b['hour_key'], $a['hour_key']);
        }
        return strcmp($a['filename'], $b['filename']);
    });

    return $list;
}

/**
 * Helper: Generate SQL statements for a single 1-hour slot (YYYY-MM-DD HH:00:00 to YYYY-MM-DD HH:59:59)
 * Splits into multiple files (YYYY-MM-DD_HH0000_to_HH5959_01.sql, 02.sql, ...) if size approaches 10 MB.
 */
function dbBkBackupSingleHour($conn, $backupDir, $dateStr, $hourStr, $tablesMeta = null, $activeTableFilter = null, $maxFileBytes = DB_BK_MAX_FILE_BYTES)
{
    // 1. Check if this hour already has backup file(s)
    $existing = dbBkGetExistingFilesForHour($backupDir, $dateStr, $hourStr);
    if (!empty($existing)) {
        return [
            'status' => 'skipped_existing',
            'hour_key' => $dateStr . '_' . $hourStr,
            'files' => $existing,
            'insert_rows' => 0,
            'update_rows' => 0,
        ];
    }

    if ($tablesMeta === null) {
        $tablesMeta = dbBkGetTablesMetadata($conn);
    }

    $hStart = $dateStr . ' ' . $hourStr . ':00:00';
    $hEnd   = $dateStr . ' ' . $hourStr . ':59:59';

    $partNum = 1;
    $fp = null;
    $currentFilePath = '';
    $currentFileName = '';
    $currentBytes = 0;
    $createdFiles = [];
    $totalInsertRows = 0;
    $totalUpdateRows = 0;

    $openNewPartFile = function () use (
        $backupDir,
        $dateStr,
        $hourStr,
        $hStart,
        $hEnd,
        &$partNum,
        &$fp,
        &$currentFilePath,
        &$currentFileName,
        &$currentBytes,
        &$createdFiles
    ) {
        if (is_resource($fp)) {
            $footer = "\nSET FOREIGN_KEY_CHECKS = 1;\n-- End of file {$currentFileName}\n";
            fwrite($fp, $footer);
            fclose($fp);
            $fp = null;
        }

        $partTag = str_pad((string)$partNum, 2, '0', STR_PAD_LEFT);
        $currentFileName = "{$dateStr}_{$hourStr}0000_to_{$hourStr}5959_{$partTag}.sql";
        $currentFilePath = $backupDir . DIRECTORY_SEPARATOR . $currentFileName;

        $fp = fopen($currentFilePath, 'wb');
        if (!$fp) {
            throw new RuntimeException("ไม่สามารถสร้างไฟล์แบคอัพได้: {$currentFileName}");
        }

        $genTime = date('Y-m-d H:i:s');
        $header = "-- ============================================================================\n"
                . "-- NB Patho Incremental Database Backup SQL\n"
                . "-- File Name    : {$currentFileName}\n"
                . "-- Hour Window  : {$hStart} to {$hEnd}\n"
                . "-- Generated At : {$genTime}\n"
                . "-- Part Number  : {$partNum}\n"
                . "-- ============================================================================\n"
                . "SET NAMES utf8mb4;\n"
                . "SET FOREIGN_KEY_CHECKS = 0;\n"
                . "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n\n";

        $written = fwrite($fp, $header);
        $currentBytes = (int)$written;
        $createdFiles[] = $currentFileName;
        $partNum++;
    };

    $writeStatement = function ($sqlLine) use (
        $maxFileBytes,
        &$fp,
        &$currentBytes,
        $openNewPartFile
    ) {
        $lineBytes = strlen($sqlLine);
        if (!is_resource($fp) || ($currentBytes > 1024 && ($currentBytes + $lineBytes + 128) > $maxFileBytes)) {
            $openNewPartFile();
        }
        $written = fwrite($fp, $sqlLine);
        $currentBytes += (int)$written;
    };

    foreach ($tablesMeta as $table => $meta) {
        $checkCreate = $meta['has_create'];
        $checkEdit   = $meta['has_edit'];

        if (is_array($activeTableFilter)) {
            $checkCreate = $checkCreate && !empty($activeTableFilter['create'][$table]);
            $checkEdit   = $checkEdit   && !empty($activeTableFilter['edit'][$table]);
        }

        if (!$checkCreate && !$checkEdit) {
            continue;
        }

        $cols = $meta['columns'];
        $pkCols = $meta['pk'];
        $hasIntId = $meta['has_int_id'];
        $quotedColsList = implode(', ', array_map(function ($c) {
            return "`{$c}`";
        }, $cols));

        // -----------------------------------------------------------------
        // 1. Rows created in this hour (stampdatecreate) -> INSERT SQL
        // -----------------------------------------------------------------
        if ($checkCreate) {
            $chunkSize = 2000;
            $lastId = -1;
            $offset = 0;
            $wroteCreateHeader = false;

            while (true) {
                if ($hasIntId) {
                    $sql = "SELECT * FROM `{$table}` WHERE `stampdatecreate` >= :hStart AND `stampdatecreate` <= :hEnd AND `stampdatecreate` != '0000-00-00 00:00:00' AND `id` > :lastId ORDER BY `id` ASC LIMIT {$chunkSize}";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue(':hStart', $hStart, PDO::PARAM_STR);
                    $stmt->bindValue(':hEnd', $hEnd, PDO::PARAM_STR);
                    $stmt->bindValue(':lastId', $lastId, PDO::PARAM_INT);
                } else {
                    $sql = "SELECT * FROM `{$table}` WHERE `stampdatecreate` >= :hStart AND `stampdatecreate` <= :hEnd AND `stampdatecreate` != '0000-00-00 00:00:00' LIMIT {$chunkSize} OFFSET {$offset}";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue(':hStart', $hStart, PDO::PARAM_STR);
                    $stmt->bindValue(':hEnd', $hEnd, PDO::PARAM_STR);
                }

                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (empty($rows)) {
                    break;
                }

                if (!$wroteCreateHeader) {
                    $writeStatement("\n-- [INSERT from stampdatecreate] Table `{$table}` ({$hStart} - {$hEnd})\n");
                    $wroteCreateHeader = true;
                }

                foreach ($rows as $row) {
                    if ($hasIntId && isset($row['id'])) {
                        $lastId = (int)$row['id'];
                    }
                    $vals = [];
                    $updatePairs = [];
                    foreach ($cols as $c) {
                        $qVal = dbBkQuoteValue($conn, array_key_exists($c, $row) ? $row[$c] : null);
                        $vals[] = $qVal;
                        if (!in_array($c, $pkCols, true)) {
                            $updatePairs[] = "`{$c}`=VALUES(`{$c}`)";
                        }
                    }

                    $valStr = implode(', ', $vals);
                    if (!empty($updatePairs)) {
                        $updStr = implode(', ', $updatePairs);
                        $stmtSql = "INSERT INTO `{$table}` ({$quotedColsList}) VALUES ({$valStr}) ON DUPLICATE KEY UPDATE {$updStr};\n";
                    } else {
                        $stmtSql = "INSERT IGNORE INTO `{$table}` ({$quotedColsList}) VALUES ({$valStr});\n";
                    }

                    $writeStatement($stmtSql);
                    $totalInsertRows++;
                }

                $offset += count($rows);
                if (count($rows) < $chunkSize) {
                    break;
                }
            }
        }

        // -----------------------------------------------------------------
        // 2. Rows updated in this hour (stampdateedit) -> UPDATE / REPLACE SQL
        // -----------------------------------------------------------------
        if ($checkEdit) {
            $chunkSize = 2000;
            $lastId = -1;
            $offset = 0;
            $wroteEditHeader = false;

            while (true) {
                if ($hasIntId) {
                    $sql = "SELECT * FROM `{$table}` WHERE `stampdateedit` >= :hStart AND `stampdateedit` <= :hEnd AND `stampdateedit` != '0000-00-00 00:00:00' AND `id` > :lastId ORDER BY `id` ASC LIMIT {$chunkSize}";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue(':hStart', $hStart, PDO::PARAM_STR);
                    $stmt->bindValue(':hEnd', $hEnd, PDO::PARAM_STR);
                    $stmt->bindValue(':lastId', $lastId, PDO::PARAM_INT);
                } else {
                    $sql = "SELECT * FROM `{$table}` WHERE `stampdateedit` >= :hStart AND `stampdateedit` <= :hEnd AND `stampdateedit` != '0000-00-00 00:00:00' LIMIT {$chunkSize} OFFSET {$offset}";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue(':hStart', $hStart, PDO::PARAM_STR);
                    $stmt->bindValue(':hEnd', $hEnd, PDO::PARAM_STR);
                }

                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (empty($rows)) {
                    break;
                }

                if (!$wroteEditHeader) {
                    $writeStatement("\n-- [UPDATE/REPLACE from stampdateedit] Table `{$table}` ({$hStart} - {$hEnd})\n");
                    $wroteEditHeader = true;
                }

                foreach ($rows as $row) {
                    if ($hasIntId && isset($row['id'])) {
                        $lastId = (int)$row['id'];
                    }

                    $setClauses = [];
                    $whereClauses = [];
                    $vals = [];
                    $updatePairs = [];

                    foreach ($cols as $c) {
                        $qVal = dbBkQuoteValue($conn, array_key_exists($c, $row) ? $row[$c] : null);
                        $vals[] = $qVal;
                        if (in_array($c, $pkCols, true)) {
                            $whereClauses[] = "`{$c}` = {$qVal}";
                        } else {
                            $setClauses[] = "`{$c}` = {$qVal}";
                            $updatePairs[] = "`{$c}`=VALUES(`{$c}`)";
                        }
                    }

                    if (!empty($setClauses) && !empty($whereClauses)) {
                        // Emit UPDATE statement + safe upsert fallback comment/statement
                        $setStr = implode(', ', $setClauses);
                        $whereStr = implode(' AND ', $whereClauses);
                        $stmtSql = "UPDATE `{$table}` SET {$setStr} WHERE {$whereStr};\n";
                    } else {
                        $valStr = implode(', ', $vals);
                        $stmtSql = "REPLACE INTO `{$table}` ({$quotedColsList}) VALUES ({$valStr});\n";
                    }

                    $writeStatement($stmtSql);
                    $totalUpdateRows++;
                }

                $offset += count($rows);
                if (count($rows) < $chunkSize) {
                    break;
                }
            }
        }
    }

    if (is_resource($fp)) {
        $footer = "\nSET FOREIGN_KEY_CHECKS = 1;\n-- End of file {$currentFileName} (INSERT rows: {$totalInsertRows}, UPDATE rows: {$totalUpdateRows})\n";
        fwrite($fp, $footer);
        fclose($fp);
        $fp = null;
    }

    if (empty($createdFiles)) {
        return [
            'status' => 'empty_hour',
            'hour_key' => $dateStr . '_' . $hourStr,
            'files' => [],
            'insert_rows' => 0,
            'update_rows' => 0,
        ];
    }

    return [
        'status' => 'created',
        'hour_key' => $dateStr . '_' . $hourStr,
        'files' => $createdFiles,
        'insert_rows' => $totalInsertRows,
        'update_rows' => $totalUpdateRows,
    ];
}

/**
 * Helper: Discover all active hours in the lookback range across all tables
 */
function dbBkDiscoverActiveHours($conn, $backupDir, $daysLookback)
{
    $daysLookback = max(1, min(365, (int)$daysLookback));
    $cutoffTime = date('Y-m-d 00:00:00', strtotime("-{$daysLookback} days"));
    $nowTime = date('Y-m-d H:59:59');

    $tablesMeta = dbBkGetTablesMetadata($conn);
    $hoursMap = [];

    foreach ($tablesMeta as $table => $meta) {
        if ($meta['has_create']) {
            $sql = "SELECT DATE_FORMAT(`stampdatecreate`, '%Y-%m-%d_%H') AS h, COUNT(*) AS cnt
                    FROM `{$table}`
                    WHERE `stampdatecreate` >= :cutoff
                      AND `stampdatecreate` <= :nowTime
                      AND `stampdatecreate` != '0000-00-00 00:00:00'
                    GROUP BY h";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':cutoff' => $cutoffTime, ':nowTime' => $nowTime]);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $h = $r['h'];
                if ($h && preg_match('/^\d{4}-\d{2}-\d{2}_\d{2}$/', $h)) {
                    $hoursMap[$h]['create'][$table] = (int)$r['cnt'];
                }
            }
        }

        if ($meta['has_edit']) {
            $sql = "SELECT DATE_FORMAT(`stampdateedit`, '%Y-%m-%d_%H') AS h, COUNT(*) AS cnt
                    FROM `{$table}`
                    WHERE `stampdateedit` >= :cutoff
                      AND `stampdateedit` <= :nowTime
                      AND `stampdateedit` != '0000-00-00 00:00:00'
                    GROUP BY h";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':cutoff' => $cutoffTime, ':nowTime' => $nowTime]);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $h = $r['h'];
                if ($h && preg_match('/^\d{4}-\d{2}-\d{2}_\d{2}$/', $h)) {
                    $hoursMap[$h]['edit'][$table] = (int)$r['cnt'];
                }
            }
        }
    }

    ksort($hoursMap);

    $plan = [];
    foreach ($hoursMap as $hKey => $tableCounts) {
        list($dStr, $hStr) = explode('_', $hKey);
        $existingFiles = dbBkGetExistingFilesForHour($backupDir, $dStr, $hStr);
        $createSum = isset($tableCounts['create']) ? array_sum($tableCounts['create']) : 0;
        $editSum   = isset($tableCounts['edit']) ? array_sum($tableCounts['edit']) : 0;

        $plan[] = [
            'hour_key' => $hKey,
            'date' => $dStr,
            'hour' => $hStr,
            'create_rows' => $createSum,
            'edit_rows' => $editSum,
            'already_backed_up' => !empty($existingFiles),
            'existing_files' => $existingFiles,
            'tables' => $tableCounts,
        ];
    }

    return [
        'days' => $daysLookback,
        'cutoff_time' => $cutoffTime,
        'now_time' => $nowTime,
        'total_active_hours' => count($plan),
        'plan' => $plan,
    ];
}

// ============================================================================
// Handle Download Create Table Script Action (?action=download_schema)
// ============================================================================
if (isset($_GET['action']) && $_GET['action'] === 'download_schema') {
    @ini_set('memory_limit', '512M');
    @set_time_limit(0);

    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $dbName = $conn->query("SELECT DATABASE()")->fetchColumn();
    $genTime = date('Y-m-d H:i:s');
    $schemaFileName = 'create_tables_schema_' . date('Y-m-d_His') . '.sql';

    $sqlOut = "-- ============================================================================\n"
            . "-- NB Patho Database Schema (CREATE TABLE Script)\n"
            . "-- Database     : {$dbName}\n"
            . "-- Total Tables : " . count($tables) . "\n"
            . "-- Generated At : {$genTime}\n"
            . "-- ============================================================================\n"
            . "SET NAMES utf8mb4;\n"
            . "SET FOREIGN_KEY_CHECKS = 0;\n"
            . "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n\n";

    foreach ($tables as $t) {
        $stmt = $conn->query("SHOW CREATE TABLE `{$t}`");
        $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
        if (!$row) {
            continue;
        }
        $createSql = isset($row['Create Table']) ? $row['Create Table'] : (isset($row['Create View']) ? $row['Create View'] : '');
        if ($createSql === '') {
            continue;
        }
        // Use CREATE TABLE IF NOT EXISTS for safe execution on backup server
        $createSql = preg_replace('/^CREATE TABLE\s+/i', 'CREATE TABLE IF NOT EXISTS ', $createSql, 1);

        $sqlOut .= "-- ----------------------------------------------------------------------------\n"
                 . "-- Table structure for `{$t}`\n"
                 . "-- ----------------------------------------------------------------------------\n"
                 . $createSql . ";\n\n";
    }

    $sqlOut .= "SET FOREIGN_KEY_CHECKS = 1;\n"
             . "-- End of CREATE TABLE schema script\n";

    if (ob_get_length()) {
        @ob_end_clean();
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/sql; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $schemaFileName . '"');
    header('Content-Length: ' . strlen($sqlOut));
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

    echo $sqlOut;
    exit;
}

// ============================================================================
// Handle Download All as ZIP Action (?action=download_zip)
// ============================================================================
if (isset($_GET['action']) && $_GET['action'] === 'download_zip') {
    @ini_set('memory_limit', '1024M');
    @set_time_limit(0);

    $sqlFiles = glob($backupDir . DIRECTORY_SEPARATOR . '*.sql');
    if (!is_array($sqlFiles) || empty($sqlFiles)) {
        http_response_code(404);
        echo "<script>alert('ยังไม่มีไฟล์ .sql ในโฟลเดอร์ db_bk_sql สำหรับดาวน์โหลด'); window.location.href='dbtosqlfile.php';</script>";
        exit;
    }

    sort($sqlFiles);
    $zipFileName = 'db_bk_sql_' . date('Y-m-d_His') . '.zip';
    $tmpZipPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('nb_db_bk_', true) . '.zip';

    $createdZip = false;

    if (class_exists('ZipArchive')) {
        $zip = new ZipArchive();
        if ($zip->open($tmpZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($sqlFiles as $fPath) {
                if (is_file($fPath)) {
                    $zip->addFile($fPath, basename($fPath));
                }
            }
            $zip->close();
            if (is_file($tmpZipPath) && filesize($tmpZipPath) > 0) {
                $createdZip = true;
            }
        }
    }

    // Pure-PHP ZIP builder fallback if ZipArchive extension is not enabled
    if (!$createdZip) {
        $fhZip = fopen($tmpZipPath, 'wb');
        if ($fhZip) {
            $centralDir = '';
            $offset = 0;
            $fileCount = 0;

            foreach ($sqlFiles as $fPath) {
                if (!is_file($fPath)) {
                    continue;
                }
                $entryName = basename($fPath);
                $data = file_get_contents($fPath);
                if ($data === false) {
                    continue;
                }

                $uncLen = strlen($data);
                $crc = crc32($data);
                $zdata = gzdeflate($data, 6);
                $compLen = strlen($zdata);

                $mtime = filemtime($fPath);
                $dtime = getdate($mtime ? $mtime : time());
                $dosTime = (($dtime['hours'] << 11) | ($dtime['minutes'] << 5) | ($dtime['seconds'] >> 1));
                $dosDate = ((($dtime['year'] - 1980) << 9) | ($dtime['mon'] << 5) | $dtime['mday']);

                $localHeader = "\x50\x4b\x03\x04"
                    . "\x14\x00"
                    . "\x00\x00"
                    . "\x08\x00"
                    . pack('v', $dosTime)
                    . pack('v', $dosDate)
                    . pack('V', $crc)
                    . pack('V', $compLen)
                    . pack('V', $uncLen)
                    . pack('v', strlen($entryName))
                    . pack('v', 0)
                    . $entryName;

                fwrite($fhZip, $localHeader);
                fwrite($fhZip, $zdata);

                $centralRecord = "\x50\x4b\x01\x02"
                    . "\x00\x00"
                    . "\x14\x00"
                    . "\x00\x00"
                    . "\x08\x00"
                    . pack('v', $dosTime)
                    . pack('v', $dosDate)
                    . pack('V', $crc)
                    . pack('V', $compLen)
                    . pack('V', $uncLen)
                    . pack('v', strlen($entryName))
                    . pack('v', 0)
                    . pack('v', 0)
                    . pack('v', 0)
                    . pack('v', 0)
                    . pack('V', 32)
                    . pack('V', $offset)
                    . $entryName;

                $centralDir .= $centralRecord;
                $offset += strlen($localHeader) + $compLen;
                $fileCount++;
                unset($data, $zdata);
            }

            $endOfCentral = "\x50\x4b\x05\x06"
                . "\x00\x00"
                . "\x00\x00"
                . pack('v', $fileCount)
                . pack('v', $fileCount)
                . pack('V', strlen($centralDir))
                . pack('V', $offset)
                . "\x00\x00";

            fwrite($fhZip, $centralDir . $endOfCentral);
            fclose($fhZip);
            $createdZip = is_file($tmpZipPath) && filesize($tmpZipPath) > 0;
        }
    }

    if (!$createdZip || !is_file($tmpZipPath)) {
        http_response_code(500);
        echo "ไม่สามารถสร้างไฟล์ ZIP ได้";
        exit;
    }

    if (ob_get_length()) {
        @ob_end_clean();
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
    header('Content-Length: ' . filesize($tmpZipPath));
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

    $fh = fopen($tmpZipPath, 'rb');
    if ($fh) {
        while (!feof($fh)) {
            echo fread($fh, 65536);
            flush();
        }
        fclose($fh);
    }
    @unlink($tmpZipPath);
    exit;
}

// ============================================================================
// Handle File Download Action (?action=download&file=...)
// ============================================================================
if (isset($_GET['action']) && $_GET['action'] === 'download' && !empty($_GET['file'])) {
    $reqFile = basename((string)$_GET['file']);
    $fullPath = $backupDir . DIRECTORY_SEPARATOR . $reqFile;

    if ($reqFile !== $_GET['file'] || !preg_match('/\.sql$/i', $reqFile) || !is_file($fullPath)) {
        http_response_code(404);
        echo "ไม่พบไฟล์แบคอัพที่ต้องการดาวน์โหลด";
        exit;
    }

    if (ob_get_length()) {
        @ob_end_clean();
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/sql; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $reqFile . '"');
    header('Content-Length: ' . filesize($fullPath));
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

    $fh = fopen($fullPath, 'rb');
    if ($fh) {
        while (!feof($fh)) {
            echo fread($fh, 65536);
            flush();
        }
        fclose($fh);
    }
    exit;
}

// ============================================================================
// Handle AJAX Requests (plan, backup_hour, list_files, delete_file)
// ============================================================================
if (isset($_REQUEST['ajax_action'])) {
    @ini_set('memory_limit', '1024M');
    @set_time_limit(0);
    header('Content-Type: application/json; charset=utf-8');

    $ajaxAction = (string)$_REQUEST['ajax_action'];

    try {
        if ($ajaxAction === 'plan') {
            $days = isset($_POST['days']) ? (int)$_POST['days'] : 10;
            $discovery = dbBkDiscoverActiveHours($conn, $backupDir, $days);
            echo json_encode([
                'ok' => true,
                'discovery' => $discovery,
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($ajaxAction === 'backup_hour') {
            $dateStr = isset($_POST['date']) ? trim((string)$_POST['date']) : '';
            $hourStr = isset($_POST['hour']) ? trim((string)$_POST['hour']) : '';
            $tablesJson = isset($_POST['tables_json']) ? (string)$_POST['tables_json'] : '';

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr) || !preg_match('/^\d{2}$/', $hourStr)) {
                throw new InvalidArgumentException("รูปแบบวันที่หรือชั่วโมงไม่ถูกต้อง");
            }

            $activeFilter = null;
            if ($tablesJson !== '') {
                $decoded = json_decode($tablesJson, true);
                if (is_array($decoded)) {
                    $activeFilter = $decoded;
                }
            }

            $result = dbBkBackupSingleHour($conn, $backupDir, $dateStr, $hourStr, null, $activeFilter);
            echo json_encode([
                'ok' => true,
                'result' => $result,
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($ajaxAction === 'list_files') {
            $files = dbBkListAllFiles($backupDir);
            $totalBytes = 0;
            $uniqueHours = [];
            foreach ($files as $f) {
                $totalBytes += $f['size_bytes'];
                if ($f['date'] !== '-') {
                    $uniqueHours[$f['hour_key']] = true;
                }
            }
            echo json_encode([
                'ok' => true,
                'files' => $files,
                'total_files' => count($files),
                'total_hours' => count($uniqueHours),
                'total_size_formatted' => dbBkFormatBytes($totalBytes),
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($ajaxAction === 'delete_file') {
            $reqFile = isset($_POST['file']) ? basename((string)$_POST['file']) : '';
            $fullPath = $backupDir . DIRECTORY_SEPARATOR . $reqFile;
            if ($reqFile === '' || !preg_match('/\.sql$/i', $reqFile) || !is_file($fullPath)) {
                throw new InvalidArgumentException("ไม่พบไฟล์ที่ต้องการลบ");
            }
            @unlink($fullPath);
            echo json_encode(['ok' => true, 'deleted' => $reqFile], JSON_UNESCAPED_UNICODE);
            exit;
        }

        throw new InvalidArgumentException("ไม่รู้จักคำสั่ง ajax_action");
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'ok' => false,
            'error' => $e->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// Log normal page view
Log::add($conn, $_SESSION['log_username'], $_SESSION['log_name'], "open dbtosqlfile.php", "");

// Initial file list on page load
$initialFiles = dbBkListAllFiles($backupDir);
$initialTotalBytes = 0;
$initialUniqueHours = [];
foreach ($initialFiles as $f) {
    $initialTotalBytes += $f['size_bytes'];
    if ($f['date'] !== '-') {
        $initialUniqueHours[$f['hour_key']] = true;
    }
}
?>
<?php require 'includes/header.php'; ?>

<style>
    .bk-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
        padding: 1.25rem;
    }
    .bk-stat-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #0d6efd;
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }
    .bk-stat-box.stat-emerald { border-left-color: #10b981; }
    .bk-stat-box.stat-amber   { border-left-color: #f59e0b; }
    .bk-stat-box.stat-indigo  { border-left-color: #4f46e5; }
    .bk-log-box {
        max-height: 220px;
        overflow-y: auto;
        background: #0f172a;
        color: #e2e8f0;
        font-family: Consolas, Monaco, monospace;
        font-size: 0.82rem;
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }
</style>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
        <div class="d-flex flex-wrap align-items-center justify-content-between w-100 gap-2">
            <div>
                <h2 class="mb-1"><i class="fa-solid fa-database text-primary me-2"></i>แบคอัพฐานข้อมูลเป็นไฟล์ SQL (Incremental Hourly Backup)</h2>
                <div class="text-muted small">
                    อ้างอิงเวลาจากคอลัมน์ <code>stampdatecreate</code> (สร้างคำสั่ง <code>INSERT</code>) และ <code>stampdateedit</code> (สร้างคำสั่ง <code>UPDATE / REPLACE</code>)
                    แยกไฟล์รายชั่วโมง (<code>YYYY-MM-DD_HH0000_to_HH5959_??.sql</code>) ขนาดไม่เกิน ~10 MB เก็บไว้ที่โฟลเดอร์ <code>db_bk_sql/</code>
                </div>
            </div>
            <span class="badge bg-primary fs-6">โฟลเดอร์: db_bk_sql/</span>
        </div>

        <hr class="my-3">

        <!-- Controls Row -->
        <div class="row g-3 align-items-end w-100">
            <div class="col-12 col-md-4 col-lg-3">
                <label for="bk_days_input" class="form-label fw-bold mb-1">จำนวนวันย้อนหลัง (Lookback Days):</label>
                <div class="input-group">
                    <input type="number" id="bk_days_input" class="form-control" value="10" min="1" max="365" step="1">
                    <span class="input-group-text">วัน</span>
                </div>
            </div>

            <div class="col-12 col-md-8 col-lg-5">
                <label class="form-label fw-bold mb-1">เลือกจำนวนวันด่วน:</label>
                <div class="d-flex flex-wrap gap-1">
                    <button type="button" class="btn btn-sm btn-outline-primary bk-quick-days" data-days="1">1 วัน</button>
                    <button type="button" class="btn btn-sm btn-outline-primary bk-quick-days" data-days="3">3 วัน</button>
                    <button type="button" class="btn btn-sm btn-outline-primary bk-quick-days" data-days="7">7 วัน</button>
                    <button type="button" class="btn btn-sm btn-primary bk-quick-days" data-days="10">10 วัน (ค่าเริ่มต้น)</button>
                    <button type="button" class="btn btn-sm btn-outline-primary bk-quick-days" data-days="15">15 วัน</button>
                    <button type="button" class="btn btn-sm btn-outline-primary bk-quick-days" data-days="30">30 วัน</button>
                </div>
            </div>

            <div class="col-12 col-lg-4 d-flex flex-wrap gap-2 justify-content-lg-end">
                <button type="button" id="btn_start_backup" class="btn btn-success px-3 fw-bold">
                    <i class="fa-solid fa-play me-2"></i>เริ่มแบคอัพฐานข้อมูล
                </button>
                <a href="dbtosqlfile.php?action=download_schema" id="btn_download_schema" class="btn btn-warning text-dark fw-bold" title="ดาวน์โหลดสคริปต์ CREATE TABLE ทั้งหมดของฐานข้อมูล">
                    <i class="fa-solid fa-table-list me-2"></i>Download Create Table (.sql)
                </a>
                <a href="dbtosqlfile.php?action=download_zip" id="btn_download_all_zip" class="btn btn-primary fw-bold <?= empty($initialFiles) ? 'disabled' : '' ?>">
                    <i class="fa-solid fa-file-zipper me-2"></i>Download All as ZIP
                </a>
                <button type="button" id="btn_refresh_files" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-rotate me-1"></i>รีเฟรช
                </button>
            </div>
        </div>

        <!-- Summary KPI Row -->
        <div class="row g-3 w-100 mt-2">
            <div class="col-6 col-md-3">
                <div class="bk-stat-box stat-indigo">
                    <div class="small text-muted fw-bold">จำนวนไฟล์แบคอัพทั้งหมด</div>
                    <div class="fs-4 fw-bold" id="stat_total_files"><?= number_format(count($initialFiles)) ?> ไฟล์</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bk-stat-box stat-emerald">
                    <div class="small text-muted fw-bold">จำนวนชั่วโมงที่มีไฟล์แล้ว</div>
                    <div class="fs-4 fw-bold" id="stat_total_hours"><?= number_format(count($initialUniqueHours)) ?> ชั่วโมง</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bk-stat-box stat-amber">
                    <div class="small text-muted fw-bold">ขนาดไฟล์แบคอัพสะสม</div>
                    <div class="fs-4 fw-bold" id="stat_total_size"><?= htmlspecialchars(dbBkFormatBytes($initialTotalBytes)) ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bk-stat-box">
                    <div class="small text-muted fw-bold">ขนาดสูงสุดต่อไฟล์</div>
                    <div class="fs-4 fw-bold">~10.00 MB</div>
                </div>
            </div>
        </div>

        <!-- Live Progress Section (hidden until backup starts) -->
        <div id="bk_progress_wrapper" class="w-100 mt-3" style="display:none;">
            <div class="bk-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-primary" id="bk_progress_title">กำลังตรวจสอบข้อมูลชั่วโมงที่ต้องแบคอัพ...</span>
                    <span class="badge bg-info text-dark" id="bk_progress_counter">0 / 0 ชั่วโมง</span>
                </div>
                <div class="progress mb-3" style="height: 22px;">
                    <div id="bk_progress_bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;">0%</div>
                </div>
                <div class="d-flex flex-wrap gap-3 mb-2 small fw-bold">
                    <span class="text-success"><i class="fa-solid fa-file-circle-plus me-1"></i>สร้างไฟล์ใหม่รอบนี้: <span id="bk_run_created_files">0</span> ไฟล์ (<span id="bk_run_created_hours">0</span> ชั่วโมง)</span>
                    <span class="text-secondary"><i class="fa-solid fa-forward-step me-1"></i>ข้ามชั่วโมงที่มีไฟล์แล้ว: <span id="bk_run_skipped_hours">0</span> ชั่วโมง</span>
                    <span class="text-primary"><i class="fa-solid fa-database me-1"></i>INSERT: <span id="bk_run_insert_rows">0</span> แถว | UPDATE: <span id="bk_run_update_rows">0</span> แถว</span>
                </div>
                <div class="bk-log-box" id="bk_log_box"></div>
            </div>
        </div>
    </div>
</div>

<!-- Backup Files Table -->
<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
        <div class="d-flex flex-wrap align-items-center justify-content-between w-100 mb-3 gap-2">
            <div>
                <h4 class="mb-0"><i class="fa-solid fa-folder-open text-warning me-2"></i>รายการไฟล์แบคอัพในโฟลเดอร์ <code>db_bk_sql/</code></h4>
                <span class="text-muted small">คลิกที่ชื่อไฟล์หรือปุ่มดาวน์โหลดเพื่อบันทึกไฟล์ <code>.sql</code> ลงเครื่อง</span>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="dbtosqlfile.php?action=download_schema" class="btn btn-sm btn-warning text-dark fw-bold" title="ดาวน์โหลดสคริปต์ CREATE TABLE ทั้งหมดของฐานข้อมูล">
                    <i class="fa-solid fa-table-list me-1"></i>Download Create Table (.sql)
                </a>
                <a href="dbtosqlfile.php?action=download_zip" id="btn_download_all_zip_table" class="btn btn-sm btn-primary fw-bold <?= empty($initialFiles) ? 'disabled' : '' ?>">
                    <i class="fa-solid fa-file-zipper me-1"></i>Download All as ZIP
                </a>
            </div>
        </div>

        <div class="table-responsive w-100">
            <table class="table table-hover table-striped align-middle text-center" id="bkFilesTable" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th class="text-start">ชื่อไฟล์แบคอัพ (คลิกเพื่อดาวน์โหลด)</th>
                        <th>วันที่ของข้อมูล</th>
                        <th>ชั่วโมง</th>
                        <th>ช่วงเวลาข้อมูลในไฟล์ (1 ชั่วโมง)</th>
                        <th>Part</th>
                        <th>ขนาดไฟล์</th>
                        <th>วันเวลาที่สร้างไฟล์แบคอัพ</th>
                        <th>ดาวน์โหลด / จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($initialFiles as $idx => $file): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td class="text-start fw-bold">
                                <a href="dbtosqlfile.php?action=download&amp;file=<?= urlencode($file['filename']) ?>" class="text-decoration-none">
                                    <i class="fa-solid fa-file-code text-primary me-1"></i><?= htmlspecialchars($file['filename']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($file['date']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($file['hour']) ?>:00</span></td>
                            <td><?= htmlspecialchars($file['hour_range']) ?></td>
                            <td><code><?= htmlspecialchars($file['extra']) ?></code></td>
                            <td><?= htmlspecialchars($file['size_formatted']) ?></td>
                            <td><span class="text-success fw-bold"><?= htmlspecialchars($file['mtime_formatted']) ?></span></td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="dbtosqlfile.php?action=download&amp;file=<?= urlencode($file['filename']) ?>" class="btn btn-sm btn-primary" title="ดาวน์โหลดไฟล์ SQL">
                                        <i class="fa-solid fa-download me-1"></i>ดาวน์โหลด
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-bk-file" data-file="<?= htmlspecialchars($file['filename']) ?>" title="ลบไฟล์นี้ (เพื่อให้แบคอัพซ้ำชั่วโมงนี้ได้)">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
<script type="text/javascript">
    $(document).ready(function () {
        var bkTable = $('#bkFilesTable').DataTable({
            responsive: true,
            pageLength: 50,
            order: [[2, 'desc'], [3, 'desc'], [1, 'asc']],
            language: {
                emptyTable: "ยังไม่มีไฟล์แบคอัพในโฟลเดอร์ db_bk_sql"
            }
        });

        // Quick lookback day buttons
        $('.bk-quick-days').on('click', function () {
            var d = $(this).data('days');
            $('#bk_days_input').val(d);
            $('.bk-quick-days').removeClass('btn-primary').addClass('btn-outline-primary');
            $(this).removeClass('btn-outline-primary').addClass('btn-primary');
        });

        $('#bk_days_input').on('input change', function () {
            var val = parseInt($(this).val(), 10);
            $('.bk-quick-days').each(function () {
                if (parseInt($(this).data('days'), 10) === val) {
                    $(this).removeClass('btn-outline-primary').addClass('btn-primary');
                } else {
                    $(this).removeClass('btn-primary').addClass('btn-outline-primary');
                }
            });
        });

        function appendBkLog(msg, colorClass) {
            var $box = $('#bk_log_box');
            var timeStr = new Date().toLocaleTimeString('th-TH', { hour12: false });
            var cls = colorClass || 'text-light';
            $box.append('<div class="' + cls + '">[' + timeStr + '] ' + msg + '</div>');
            $box.scrollTop($box[0].scrollHeight);
        }

        function refreshFilesTable() {
            $.post('dbtosqlfile.php', { ajax_action: 'list_files' }, function (res) {
                if (!res || !res.ok) return;
                $('#stat_total_files').text(res.total_files.toLocaleString() + ' ไฟล์');
                $('#stat_total_hours').text(res.total_hours.toLocaleString() + ' ชั่วโมง');
                $('#stat_total_size').text(res.total_size_formatted);

                bkTable.clear();
                var files = res.files || [];
                if (files.length > 0) {
                    $('#btn_download_all_zip, #btn_download_all_zip_table').removeClass('disabled');
                } else {
                    $('#btn_download_all_zip, #btn_download_all_zip_table').addClass('disabled');
                }
                for (var i = 0; i < files.length; i++) {
                    var f = files[i];
                    var dlUrl = 'dbtosqlfile.php?action=download&file=' + encodeURIComponent(f.filename);
                    var nameLink = '<a href="' + dlUrl + '" class="text-decoration-none fw-bold"><i class="fa-solid fa-file-code text-primary me-1"></i>' + $('<div>').text(f.filename).html() + '</a>';
                    var hourBadge = '<span class="badge bg-secondary">' + $('<div>').text(f.hour).html() + ':00</span>';
                    var partCode = '<code>' + $('<div>').text(f.extra).html() + '</code>';
                    var mtimeHtml = '<span class="text-success fw-bold">' + $('<div>').text(f.mtime_formatted).html() + '</span>';
                    var actionBtns = '<div class="d-flex gap-1 justify-content-center">'
                        + '<a href="' + dlUrl + '" class="btn btn-sm btn-primary" title="ดาวน์โหลดไฟล์ SQL"><i class="fa-solid fa-download me-1"></i>ดาวน์โหลด</a>'
                        + '<button type="button" class="btn btn-sm btn-outline-danger btn-delete-bk-file" data-file="' + $('<div>').text(f.filename).html() + '" title="ลบไฟล์นี้"><i class="fa-solid fa-trash"></i></button>'
                        + '</div>';

                    bkTable.row.add([
                        i + 1,
                        nameLink,
                        f.date,
                        hourBadge,
                        f.hour_range,
                        partCode,
                        f.size_formatted,
                        mtimeHtml,
                        actionBtns
                    ]);
                }
                bkTable.draw(false);
            }, 'json');
        }

        $('#btn_refresh_files').on('click', function () {
            refreshFilesTable();
        });

        // Delete individual backup file
        $('#bkFilesTable').on('click', '.btn-delete-bk-file', function () {
            var fileName = $(this).data('file');
            if (!fileName) return;
            if (!confirm('ต้องการลบไฟล์แบคอัพ ' + fileName + ' ใช่หรือไม่?\n(หากลบแล้ว เมื่อกดแบคอัพอีกครั้ง ระบบจะสร้างไฟล์ของชั่วโมงนี้ใหม่)')) {
                return;
            }
            $.post('dbtosqlfile.php', { ajax_action: 'delete_file', file: fileName }, function (res) {
                if (res && res.ok) {
                    refreshFilesTable();
                } else {
                    alert('ไม่สามารถลบไฟล์ได้: ' + (res && res.error ? res.error : 'Unknown error'));
                }
            }, 'json');
        });

        // Start Incremental Hourly Backup
        var isBackingUp = false;
        $('#btn_start_backup').on('click', function () {
            if (isBackingUp) return;
            var days = parseInt($('#bk_days_input').val(), 10);
            if (isNaN(days) || days < 1) {
                days = 10;
                $('#bk_days_input').val(10);
            }

            isBackingUp = true;
            var $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>กำลังแบคอัพ...');

            $('#bk_progress_wrapper').slideDown(150);
            $('#bk_log_box').empty();
            $('#bk_run_created_files').text('0');
            $('#bk_run_created_hours').text('0');
            $('#bk_run_skipped_hours').text('0');
            $('#bk_run_insert_rows').text('0');
            $('#bk_run_update_rows').text('0');
            $('#bk_progress_bar').css('width', '5%').text('กำลังสแกน...');
            $('#bk_progress_title').text('กำลังค้นหาชั่วโมงที่มีข้อมูลเปลี่ยนแปลงย้อนหลัง ' + days + ' วัน...');

            appendBkLog('เริ่มต้นตรวจสอบข้อมูลย้อนหลัง ' + days + ' วัน จากทุกตาราง...', 'text-info');

            $.ajax({
                url: 'dbtosqlfile.php',
                method: 'POST',
                dataType: 'json',
                data: { ajax_action: 'plan', days: days },
                success: function (res) {
                    if (!res || !res.ok || !res.discovery) {
                        appendBkLog('เกิดข้อผิดพลาดในการวางแผนแบคอัพ', 'text-danger');
                        finishBackupRun();
                        return;
                    }

                    var plan = res.discovery.plan || [];
                    var totalHours = plan.length;
                    appendBkLog('พบชั่วโมงที่มีข้อมูลทั้งหมด ' + totalHours + ' ชั่วโมง (ช่วง ' + res.discovery.cutoff_time + ' ถึง ' + res.discovery.now_time + ')', 'text-info');

                    if (totalHours === 0) {
                        $('#bk_progress_bar').css('width', '100%').text('100%');
                        $('#bk_progress_title').text('ไม่พบข้อมูลเปลี่ยนแปลงในช่วงเวลาที่เลือก');
                        finishBackupRun();
                        return;
                    }

                    var createdFilesCount = 0;
                    var createdHoursCount = 0;
                    var skippedHoursCount = 0;
                    var totalInsertRows = 0;
                    var totalUpdateRows = 0;
                    var idx = 0;

                    function processNextHour() {
                        if (idx >= totalHours) {
                            $('#bk_progress_bar').removeClass('progress-bar-animated').css('width', '100%').text('100% (เสร็จสมบูรณ์)');
                            $('#bk_progress_title').text('แบคอัพเสร็จสมบูรณ์! สร้างไฟล์ใหม่ ' + createdFilesCount + ' ไฟล์, ข้ามชั่วโมงที่มีแล้ว ' + skippedHoursCount + ' ชั่วโมง');
                            appendBkLog('=== แบคอัพเสร็จสมบูรณ์: สร้างไฟล์ใหม่ ' + createdFilesCount + ' ไฟล์ (' + createdHoursCount + ' ชั่วโมง), ข้าม ' + skippedHoursCount + ' ชั่วโมง ===', 'text-warning fw-bold');
                            refreshFilesTable();
                            finishBackupRun();
                            return;
                        }

                        var item = plan[idx];
                        var pct = Math.round(((idx + 1) / totalHours) * 100);
                        $('#bk_progress_counter').text((idx + 1) + ' / ' + totalHours + ' ชั่วโมง');
                        $('#bk_progress_bar').css('width', pct + '%').text(pct + '%');

                        // Fast-skip on client if already backed up from a previous run
                        if (item.already_backed_up) {
                            skippedHoursCount++;
                            $('#bk_run_skipped_hours').text(skippedHoursCount.toLocaleString());
                            appendBkLog('[ข้าม] ชั่วโมง ' + item.date + ' ' + item.hour + ':00 มีไฟล์แบคอัพแล้ว (' + item.existing_files.join(', ') + ')', 'text-secondary');
                            idx++;
                            setTimeout(processNextHour, 5);
                            return;
                        }

                        $('#bk_progress_title').text('กำลังสร้างไฟล์แบคอัพชั่วโมง ' + item.date + ' ' + item.hour + ':00 (INSERT ~' + item.create_rows.toLocaleString() + ', UPDATE ~' + item.edit_rows.toLocaleString() + ' แถว)...');

                        $.ajax({
                            url: 'dbtosqlfile.php',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                ajax_action: 'backup_hour',
                                date: item.date,
                                hour: item.hour,
                                tables_json: JSON.stringify(item.tables || {})
                            },
                            success: function (hrRes) {
                                if (hrRes && hrRes.ok && hrRes.result) {
                                    var r = hrRes.result;
                                    if (r.status === 'skipped_existing') {
                                        skippedHoursCount++;
                                        $('#bk_run_skipped_hours').text(skippedHoursCount.toLocaleString());
                                        appendBkLog('[ข้าม] ชั่วโมง ' + item.date + ' ' + item.hour + ':00 มีไฟล์แล้ว (' + (r.files || []).join(', ') + ')', 'text-secondary');
                                    } else if (r.status === 'created') {
                                        createdHoursCount++;
                                        createdFilesCount += (r.files ? r.files.length : 0);
                                        totalInsertRows += (r.insert_rows || 0);
                                        totalUpdateRows += (r.update_rows || 0);

                                        $('#bk_run_created_files').text(createdFilesCount.toLocaleString());
                                        $('#bk_run_created_hours').text(createdHoursCount.toLocaleString());
                                        $('#bk_run_insert_rows').text(totalInsertRows.toLocaleString());
                                        $('#bk_run_update_rows').text(totalUpdateRows.toLocaleString());

                                        appendBkLog('[สร้างสำเร็จ] ชั่วโมง ' + item.date + ' ' + item.hour + ':00 -> ' + (r.files || []).join(', ') + ' (INSERT: ' + (r.insert_rows || 0).toLocaleString() + ', UPDATE: ' + (r.update_rows || 0).toLocaleString() + ')', 'text-success');
                                    }
                                } else {
                                    appendBkLog('[ผิดพลาด] ชั่วโมง ' + item.date + ' ' + item.hour + ':00', 'text-danger');
                                }
                                idx++;
                                processNextHour();
                            },
                            error: function (xhr) {
                                var errMsg = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : (xhr.statusText || 'Error');
                                appendBkLog('[ผิดพลาด] ชั่วโมง ' + item.date + ' ' + item.hour + ':00 : ' + errMsg, 'text-danger');
                                idx++;
                                processNextHour();
                            }
                        });
                    }

                    processNextHour();
                },
                error: function (xhr) {
                    var errMsg = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้';
                    appendBkLog('เกิดข้อผิดพลาด: ' + errMsg, 'text-danger');
                    finishBackupRun();
                }
            });

            function finishBackupRun() {
                isBackingUp = false;
                $btn.prop('disabled', false).html('<i class="fa-solid fa-play me-2"></i>เริ่มแบคอัพฐานข้อมูล');
            }
        });

        // Set active sidebar menu item
        $("#admin_dropdown").addClass("show");
        $("#admin_tab").addClass("active");
        $("#dbtosqlfile_tab").addClass("active");
    });
</script>

