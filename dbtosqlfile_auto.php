<?php
/**
 * dbtosqlfile_auto.php
 *
 * ไฟล์สำหรับเรียกทำงานหลังบ้าน (Background / Cron Job / Windows Task Scheduler / HTTP API)
 * เพื่อทำการแบคอัพฐานข้อมูลรายชั่วโมงลงโฟลเดอร์ db_bk_sql/ อัตโนมัติ
 * เหมือนกับการกดปุ่ม "เริ่มแบคอัพฐานข้อมูล" บนหน้า dbtosqlfile.php
 *
 * วิธีเรียกใช้งาน:
 * 1. เรียกผ่าน URL / cURL (ค่าเริ่มต้นย้อนหลัง 10 วัน):
 *    http://localhost/dev3/dbtosqlfile_auto.php
 *    http://localhost/dev3/dbtosqlfile_auto.php?days=10
 *
 * 2. เรียกผ่าน Command Line (CLI / Task Scheduler / Cron):
 *    php C:\anuchit2\nb_web\dev3\dbtosqlfile_auto.php
 *    php C:\anuchit2\nb_web\dev3\dbtosqlfile_auto.php 10
 */

@ini_set('memory_limit', '1024M');
@set_time_limit(0);
@ignore_user_abort(true);

require __DIR__ . '/includes/init.php';

// Ensure HTTP_HOST and subfolder are populated when invoked via CLI / Cron
if (empty($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'localhost';
}
if (!isset($_SESSION['subfolder']) || $_SESSION['subfolder'] === '') {
    $folderName = basename(__DIR__);
    if (!in_array(strtolower($folderName), ['nb_web', 'htdocs', 'www', 'public_html'], true)) {
        $_SESSION['subfolder'] = $folderName;
    }
}

$conn = require __DIR__ . '/includes/db.php';

// Backup directory path: C:\anuchit2\nb_web\dev3\db_bk_sql
$backupDir = __DIR__ . DIRECTORY_SEPARATOR . 'db_bk_sql';
if (!is_dir($backupDir)) {
    @mkdir($backupDir, 0777, true);
}

// Target max file size: ~10 MB (10 * 1024 * 1024 bytes)
if (!defined('DB_BK_MAX_FILE_BYTES')) {
    define('DB_BK_MAX_FILE_BYTES', 10 * 1024 * 1024);
}

/**
 * Helper: Safely quote value for SQL output
 */
function dbBkAutoQuoteValue($conn, $val)
{
    if ($val === null) {
        return 'NULL';
    }
    return $conn->quote((string)$val);
}

/**
 * Helper: Get all tables that have stampdatecreate or stampdateedit
 */
function dbBkAutoGetTablesMetadata($conn)
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
function dbBkAutoGetExistingFilesForHour($backupDir, $dateStr, $hourStr)
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
 * Helper: Generate SQL statements for a single 1-hour slot (YYYY-MM-DD HH:00:00 to YYYY-MM-DD HH:59:59)
 * Splits into multiple files (YYYY-MM-DD_HH0000_to_HH5959_01.sql, 02.sql, ...) if size approaches 10 MB.
 */
function dbBkAutoBackupSingleHour($conn, $backupDir, $dateStr, $hourStr, $tablesMeta, $activeTableFilter = null, $maxFileBytes = DB_BK_MAX_FILE_BYTES)
{
    $existing = dbBkAutoGetExistingFilesForHour($backupDir, $dateStr, $hourStr);
    if (!empty($existing)) {
        return [
            'status' => 'skipped_existing',
            'hour_key' => $dateStr . '_' . $hourStr,
            'files' => $existing,
            'insert_rows' => 0,
            'update_rows' => 0,
        ];
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

        // 1. Rows created in this hour (stampdatecreate) -> INSERT SQL
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
                        $qVal = dbBkAutoQuoteValue($conn, array_key_exists($c, $row) ? $row[$c] : null);
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

        // 2. Rows updated in this hour (stampdateedit) -> UPDATE / REPLACE SQL
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

                    foreach ($cols as $c) {
                        $qVal = dbBkAutoQuoteValue($conn, array_key_exists($c, $row) ? $row[$c] : null);
                        $vals[] = $qVal;
                        if (in_array($c, $pkCols, true)) {
                            $whereClauses[] = "`{$c}` = {$qVal}";
                        } else {
                            $setClauses[] = "`{$c}` = {$qVal}";
                        }
                    }

                    if (!empty($setClauses) && !empty($whereClauses)) {
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

// ============================================================================
// Parse Days Parameter (default = 10 days, from GET/POST or CLI args)
// ============================================================================
$daysLookback = 10;
if (isset($_REQUEST['days']) && is_numeric($_REQUEST['days'])) {
    $daysLookback = (int)$_REQUEST['days'];
} elseif (PHP_SAPI === 'cli' && !empty($argv)) {
    foreach (array_slice($argv, 1) as $arg) {
        if (is_numeric($arg)) {
            $daysLookback = (int)$arg;
            break;
        }
        if (preg_match('/^days=(\d+)$/i', $arg, $m)) {
            $daysLookback = (int)$m[1];
            break;
        }
    }
}
$daysLookback = max(1, min(365, $daysLookback));

$startTimeExec = microtime(true);
$cutoffTime = date('Y-m-d 00:00:00', strtotime("-{$daysLookback} days"));
$nowTime    = date('Y-m-d H:59:59');

try {
    $tablesMeta = dbBkAutoGetTablesMetadata($conn);
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

    $createdHoursCount = 0;
    $skippedHoursCount = 0;
    $createdFilesList  = [];
    $skippedHoursList  = [];
    $totalInsertRows   = 0;
    $totalUpdateRows   = 0;

    foreach ($hoursMap as $hKey => $tableCounts) {
        list($dStr, $hStr) = explode('_', $hKey);
        $res = dbBkAutoBackupSingleHour($conn, $backupDir, $dStr, $hStr, $tablesMeta, $tableCounts);

        if ($res['status'] === 'skipped_existing') {
            $skippedHoursCount++;
            $skippedHoursList[] = $hKey;
        } elseif ($res['status'] === 'created') {
            $createdHoursCount++;
            foreach ($res['files'] as $fName) {
                $createdFilesList[] = $fName;
            }
            $totalInsertRows += (int)$res['insert_rows'];
            $totalUpdateRows += (int)$res['update_rows'];
        }
    }

    $elapsedSec = round(microtime(true) - $startTimeExec, 3);

    $logUser = isset($_SESSION['log_username']) ? $_SESSION['log_username'] : 'system_auto';
    $logName = isset($_SESSION['log_name']) ? $_SESSION['log_name'] : 'Background Auto Backup';
    $logDetail = "days={$daysLookback}, created_files=" . count($createdFilesList) . ", skipped_hours={$skippedHoursCount}, elapsed={$elapsedSec}s";
    Log::add($conn, $logUser, $logName, "run dbtosqlfile_auto.php", $logDetail);

    $response = [
        'ok' => true,
        'message' => 'Backup completed successfully',
        'days_lookback' => $daysLookback,
        'range_start' => $cutoffTime,
        'range_end' => $nowTime,
        'total_active_hours_found' => count($hoursMap),
        'created_hours_count' => $createdHoursCount,
        'created_files_count' => count($createdFilesList),
        'skipped_hours_count' => $skippedHoursCount,
        'total_insert_rows' => $totalInsertRows,
        'total_update_rows' => $totalUpdateRows,
        'elapsed_seconds' => $elapsedSec,
        'backup_folder' => $backupDir,
        'created_files' => $createdFilesList,
        'skipped_hours' => $skippedHoursList,
    ];

    if (PHP_SAPI !== 'cli') {
        header('Content-Type: application/json; charset=utf-8');
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
} catch (Exception $e) {
    if (PHP_SAPI !== 'cli') {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
    }
    echo json_encode([
        'ok' => false,
        'error' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
    exit(1);
}

