<?php
require '../includes/init.php';
$conn = require '../includes/db.php';
require '../user_auth.php';

header('Content-Type: application/json; charset=utf-8');

if (!Auth::isLoggedIn() || $isCurUserClinicianCust || $isCurUserHospitalCust) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.'], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Fallback OpenXML (.xlsx) parser in case client-side parsed_rows is not sent
 */
function parseXlsxFile8c($filePath)
{
    if (!class_exists('ZipArchive')) {
        return [];
    }
    $zip = new ZipArchive();
    if ($zip->open($filePath) !== true) {
        return [];
    }

    $strings = [];
    $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
    if ($sharedStringsXml) {
        $xml = @simplexml_load_string($sharedStringsXml);
        if ($xml) {
            foreach ($xml->si as $si) {
                if (isset($si->t)) {
                    $strings[] = (string)$si->t;
                } elseif (isset($si->r)) {
                    $text = '';
                    foreach ($si->r as $r) {
                        $text .= (string)$r->t;
                    }
                    $strings[] = $text;
                } else {
                    $strings[] = '';
                }
            }
        }
    }

    $sheetXmlContent = $zip->getFromName('xl/worksheets/sheet1.xml');
    if (!$sheetXmlContent) {
        $zip->close();
        return [];
    }

    $sheetXml = @simplexml_load_string($sheetXmlContent);
    if (!$sheetXml || !isset($sheetXml->sheetData->row)) {
        $zip->close();
        return [];
    }

    $rows = [];
    foreach ($sheetXml->sheetData->row as $row) {
        $rowCells = [];
        $colIndex = 0;
        foreach ($row->c as $c) {
            $cellRef = (string)$c['r'];
            if (preg_match('/^([A-Z]+)(\d+)$/', $cellRef, $matches)) {
                $colLetters = $matches[1];
                $actualIndex = 0;
                for ($i = 0; $i < strlen($colLetters); $i++) {
                    $actualIndex = $actualIndex * 26 + (ord($colLetters[$i]) - ord('A') + 1);
                }
                $actualIndex -= 1;
                while ($colIndex < $actualIndex) {
                    $rowCells[$colIndex] = '';
                    $colIndex++;
                }
            }
            $type = (string)$c['t'];
            $val = '';
            if ($type === 's' && isset($c->v)) {
                $idx = (int)$c->v;
                $val = isset($strings[$idx]) ? $strings[$idx] : '';
            } elseif ($type === 'inlineStr' && isset($c->is)) {
                if (isset($c->is->t)) {
                    $val = (string)$c->is->t;
                } elseif (isset($c->is->r)) {
                    foreach ($c->is->r as $r) {
                        $val .= (string)$r->t;
                    }
                }
            } elseif (isset($c->v)) {
                $val = (string)$c->v;
            }
            $rowCells[$colIndex] = $val;
            $colIndex++;
        }
        $rows[] = $rowCells;
    }

    $zip->close();
    return $rows;
}

if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => 'ไม่พบไฟล์ที่อัปโหลด หรือเกิดข้อผิดพลาดในการอัปโหลดไฟล์'], JSON_UNESCAPED_UNICODE);
    exit;
}

$originalName = basename($_FILES['excel_file']['name']);
$ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
if (!in_array($ext, ['xlsx', 'xls'], true)) {
    echo json_encode(['status' => 'error', 'message' => 'รองรับเฉพาะไฟล์ Excel (.xlsx, .xls) เท่านั้น'], JSON_UNESCAPED_UNICODE);
    exit;
}

$hospital_id = isset($_POST['hospital_id']) ? (int)$_POST['hospital_id'] : 0;
$type_id = isset($_POST['type_id']) ? (int)$_POST['type_id'] : 0;
$replace_existing = !empty($_POST['replace_existing']) && $_POST['replace_existing'] == '1';

// Resolve Hospital Name
$hospital_name = isset($_POST['hospital_name']) ? trim($_POST['hospital_name']) : '';
if ($hospital_name === '') {
    if ($hospital_id === 0) {
        $hospital_name = 'ราคามาตรฐาน';
    } else {
        $hstmt = $conn->prepare("SELECT hospital FROM hospital WHERE id = :id LIMIT 1");
        $hstmt->execute([':id' => $hospital_id]);
        $hospital_name = (string)$hstmt->fetchColumn() ?: ('Hospital_' . $hospital_id);
    }
}

$cur_thai_time = Util::get_curreint_thai_date_time(); // YYYY-MM-DD HH:mm:ss
$dateForFilename = str_replace([':', ' '], ['-', '_'], trim($cur_thai_time));
$cleanHospForName = preg_replace('/[\\\\\/:*?"<>|\s]+/u', '_', $hospital_name);

// Friendly filename named after hospital and upload date
$savedDisplayFilename = $cleanHospForName . '_' . $dateForFilename . '.' . $ext;

// Physical path inside nb_price_8c_uploads/
$uploadFolderRel = 'nb_price_8c_uploads';
$uploadFolderAbs = dirname(__DIR__) . '/' . $uploadFolderRel;
if (!is_dir($uploadFolderAbs) && !mkdir($uploadFolderAbs, 0777, true)) {
    echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถสร้างโฟลเดอร์สำหรับเก็บไฟล์อัปโหลดได้'], JSON_UNESCAPED_UNICODE);
    exit;
}

$physicalFilename = 'hosp' . $hospital_id . '_' . preg_replace('/[^0-9_]/', '', $dateForFilename) . '_' . substr(md5(uniqid('', true)), 0, 6) . '.' . $ext;
$fileRelPath = $uploadFolderRel . '/' . $physicalFilename;
$fileAbsPath = $uploadFolderAbs . '/' . $physicalFilename;

if (!move_uploaded_file($_FILES['excel_file']['tmp_name'], $fileAbsPath)) {
    echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถบันทึกไฟล์ลงในโฟลเดอร์ได้'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Parse rows from client-side JSON or fallback to server-side XLSX parser
$rows = [];
if (!empty($_POST['parsed_rows'])) {
    $decoded = json_decode($_POST['parsed_rows'], true);
    if (is_array($decoded)) {
        $rows = $decoded;
    }
}
if (empty($rows)) {
    $rows = parseXlsxFile8c($fileAbsPath);
}

// Build lookup map of service_type (id <-> service_type)
$serviceTypes = ServiceType::getAll_v2($conn);
$typeIdToName = [];
$typeNameToId = [];
if (is_array($serviceTypes)) {
    foreach ($serviceTypes as $st) {
        $sid = (int)$st['id'];
        $sname = trim((string)$st['service_type']);
        $typeIdToName[$sid] = $sname;
        if ($sname !== '') {
            $typeNameToId[$sname] = $sid;
        }
    }
}

$upload_user_id = isset($_SESSION['user']->id) ? (int)$_SESSION['user']->id : (isset($_SESSION['userid']) ? (int)$_SESSION['userid'] : 0);
$upload_user_name = '';
if (!empty($_SESSION['log_name'])) {
    $upload_user_name = trim((string)$_SESSION['log_name']);
} elseif (isset($_SESSION['user']->name)) {
    $upload_user_name = trim((string)$_SESSION['user']->name . ' ' . (string)($_SESSION['user']->lastname ?? ''));
} elseif (!empty($_SESSION['username'])) {
    $upload_user_name = (string)$_SESSION['username'];
}

// Filter and validate data rows for service_price_list
$validRecords = [];
$validationErrors = [];
$allowedTypeIdsStr = implode(', ', array_keys($typeIdToName));

foreach ($rows as $rowIndex => $r) {
    if (!is_array($r)) {
        continue;
    }
    $excelRowNum = $rowIndex + 1;
    $col0 = isset($r[0]) ? trim((string)$r[0]) : '';
    $col1 = isset($r[1]) ? trim((string)$r[1]) : '';
    $col2 = isset($r[2]) ? trim((string)$r[2]) : '';
    $col3 = isset($r[3]) ? trim((string)$r[3]) : '';
    $col4 = isset($r[4]) ? trim((string)$r[4]) : '';
    $col5 = isset($r[5]) ? trim((string)$r[5]) : '';
    $col6 = isset($r[6]) ? trim((string)$r[6]) : '';
    $col7 = isset($r[7]) ? trim((string)$r[7]) : '';

    // Skip completely empty rows
    if ($col0 === '' && $col1 === '' && $col2 === '' && $col3 === '' && $col4 === '' && $col5 === '' && $col6 === '' && $col7 === '') {
        continue;
    }

    // Skip header row (only on first row if it matches header labels)
    if (
        $rowIndex === 0 && (
            strpos($col0, 'ลำดับ') !== false ||
            stripos($col0, 'row_id') !== false ||
            stripos($col0, 'number') !== false ||
            strpos($col1, 'โค้ด') !== false ||
            stripos($col1, 'code') !== false ||
            strpos($col2, 'รายการ') !== false
        )
    ) {
        continue;
    }

    $rowErrors = [];

    // 1. คอลัมน์ ลำดับ(number) / ไอดี ต้องเป็นตัวเลขเท่านั้น
    if ($col0 === '' || !preg_match('/^\d+$/', $col0)) {
        $rowErrors[] = "คอลัมน์ 'ลำดับ(number)' ต้องเป็นตัวเลขเท่านั้น (ค่าที่พบ: '{$col0}')";
    }

    // 2. คอลัมน์ โค้ด(code) ต้องเป็นตัวเลขเท่านั้น ห้ามเว้นว่างหรือมีอักขระอื่น
    if ($col1 === '' || !preg_match('/^\d+$/', $col1)) {
        $rowErrors[] = "คอลัมน์ 'โค้ด(code)' ต้องเป็นตัวเลขเท่านั้นและห้ามเว้นว่าง (ค่าที่พบ: '{$col1}')";
    }

    // 3. คอลัมน์ รายการ(detail) ต้องมีข้อมูล
    if ($col2 === '') {
        $rowErrors[] = "คอลัมน์ 'รายการ(detail)' ห้ามเว้นว่าง";
    }

    // 4. คอลัมน์ ราคา(price) ต้องเป็นตัวเลขเท่านั้น ห้ามไม่ใส่ค่า
    $cleanPrice = str_replace(',', '', $col4);
    if ($cleanPrice === '' || !preg_match('/^\d+(\.\d+)?$/', $cleanPrice)) {
        $rowErrors[] = "คอลัมน์ 'ราคา(price)' ต้องเป็นตัวเลขเท่านั้นและห้ามเว้นว่าง (ค่าที่พบ: '{$col4}')";
    }

    // 5. คอลัมน์ ไอดีชนิด(type) ต้องเป็นตัวเลข และต้องอยู่ในตาราง service_type เท่านั้น
    if ($col6 === '' || !preg_match('/^\d+$/', $col6)) {
        $rowErrors[] = "คอลัมน์ 'ไอดีชนิด(type)' ต้องเป็นตัวเลขเท่านั้นและห้ามเว้นว่าง (ค่าที่พบ: '{$col6}')";
    } else {
        $jobtypeInt = (int)$col6;
        if (!isset($typeIdToName[$jobtypeInt])) {
            $rowErrors[] = "คอลัมน์ 'ไอดีชนิด(type)' มีค่า '{$col6}' ซึ่งไม่อยู่ในตาราง service_type (ค่าที่ใช้ได้: {$allowedTypeIdsStr})";
        }
    }

    if (!empty($rowErrors)) {
        $validationErrors[] = "แถวที่ {$excelRowNum}: " . implode(' | ', $rowErrors);
        continue;
    }

    $number = (int)$col0;
    $speciment_num = (int)$col1;
    $specimen = $col2;
    $unit_count = $col3;
    $price = (int)round((float)$cleanPrice);
    $comment = $col5;
    $jobtype = (int)$col6;
    $service_des = ($col7 !== '') ? $col7 : $typeIdToName[$jobtype];

    $validRecords[] = [
        'number' => $number,
        'speciment_num' => $speciment_num,
        'jobtype' => $jobtype,
        'specimen' => $specimen,
        'hospital_id' => $hospital_id,
        'unit_count' => $unit_count,
        'price' => $price,
        'comment' => $comment,
        'create_date' => $cur_thai_time,
        'add_user_id' => $upload_user_id,
        'edit_user_id' => 0,
        'service_des' => $service_des
    ];
}

if (!empty($validationErrors)) {
    @unlink($fileAbsPath);
    $maxShow = 10;
    $shownErrors = array_slice($validationErrors, 0, $maxShow);
    $moreCount = count($validationErrors) - count($shownErrors);
    $errMsg = "พบข้อผิดพลาดของข้อมูลในไฟล์ Excel (ยกเลิกการนำเข้า):\n- " . implode("\n- ", $shownErrors);
    if ($moreCount > 0) {
        $errMsg .= "\n... และอีก {$moreCount} แถว";
    }
    echo json_encode(['status' => 'error', 'message' => $errMsg], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($validRecords)) {
    @unlink($fileAbsPath);
    echo json_encode(['status' => 'error', 'message' => 'ไม่พบข้อมูลรายการที่ถูกต้องในไฟล์ Excel'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Optionally replace existing price records for this hospital (and service type if selected)
if ($replace_existing && !empty($validRecords)) {
    $delSql = "DELETE FROM `service_price_list` WHERE hospital_id = :hospital_id";
    if ($type_id > 0) {
        $delSql .= " AND jobtype = :type_id";
    }
    $delStmt = $conn->prepare($delSql);
    $delStmt->bindValue(':hospital_id', $hospital_id, PDO::PARAM_INT);
    if ($type_id > 0) {
        $delStmt->bindValue(':type_id', $type_id, PDO::PARAM_INT);
    }
    $delStmt->execute();
}

// Insert valid records into service_price_list
$insertedCount = 0;
if (!empty($validRecords)) {
    $insSql = "INSERT INTO `service_price_list`
        (`number`, `speciment_num`, `jobtype`, `specimen`, `hospital_id`, `unit_count`, `price`, `comment`, `create_date`, `add_user_id`, `edit_user_id`, `service_des`)
        VALUES
        (:number, :speciment_num, :jobtype, :specimen, :hospital_id, :unit_count, :price, :comment, :create_date, :add_user_id, :edit_user_id, :service_des)";
    $insStmt = $conn->prepare($insSql);
    foreach ($validRecords as $rec) {
        $insStmt->execute([
            ':number' => $rec['number'],
            ':speciment_num' => $rec['speciment_num'],
            ':jobtype' => $rec['jobtype'],
            ':specimen' => $rec['specimen'],
            ':hospital_id' => $rec['hospital_id'],
            ':unit_count' => $rec['unit_count'],
            ':price' => $rec['price'],
            ':comment' => $rec['comment'],
            ':create_date' => $rec['create_date'],
            ':add_user_id' => $rec['add_user_id'],
            ':edit_user_id' => $rec['edit_user_id'],
            ':service_des' => $rec['service_des']
        ]);
        $insertedCount++;
    }
}

// Record the uploaded Excel file in service_price_excel_file
$excelFileRecord = new ServicePriceExcelFile();
$excelFileRecord->hospital_id = $hospital_id;
$excelFileRecord->hospital_name = $hospital_name;
$excelFileRecord->service_type_id = $type_id;
$excelFileRecord->original_filename = $originalName;
$excelFileRecord->saved_filename = $savedDisplayFilename;
$excelFileRecord->file_path = $fileRelPath;
$excelFileRecord->row_count = $insertedCount;
$excelFileRecord->upload_user_id = $upload_user_id;
$excelFileRecord->upload_user_name = $upload_user_name;
$excelFileRecord->upload_date = $cur_thai_time;
$excelFileRecord->create($conn);

Log::add(
    $conn,
    $_SESSION['log_username'] ?? '',
    $_SESSION['log_name'] ?? '',
    "upload excel nb_price_8c.php",
    "hospital_id={$hospital_id}, file={$savedDisplayFilename}, rows={$insertedCount}"
);

$specimens = ServicePriceList::getSpecimenByHospitalID($conn, $hospital_id, $type_id);
$history = ServicePriceExcelFile::getAll($conn, -1);

echo json_encode([
    'status' => 'success',
    'message' => "อัปโหลดไฟล์ {$savedDisplayFilename} และนำเข้าข้อมูล {$insertedCount} รายการเรียบร้อยแล้ว",
    'row_count' => $insertedCount,
    'saved_filename' => $savedDisplayFilename,
    'specimens' => $specimens,
    'history' => $history
], JSON_UNESCAPED_UNICODE);

