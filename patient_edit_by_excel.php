<?php
require 'includes/init.php';
Auth::requireLogin();
$conn = require 'includes/db.php';
require 'user_auth.php';

// Authorization check: Only authorized pathologists or system admin can access
// Group 1000: Admin, Group 1900: Pathologist Admin, Group 2000: Pathologist, or role_5 == 1, or role_a == 1
$isAuthorizedPatho = ($isCurUserAdmin || $isCurUserPathoAdmin || $isCurUserPatho || (isset($_SESSION['user']->role_5) && $_SESSION['user']->role_5 == 1) || (isset($_SESSION['user']->role_a) && $_SESSION['user']->role_a == 1));

// Handle sample file download
if (isset($_GET['action']) && $_GET['action'] === 'download_example') {
    $examplePath = __DIR__ . '/file/ตัวอย่างไฟล์uploadผลNB.xlsx';
    if (!file_exists($examplePath)) {
        $examplePath = 'C:/anuchit2/example upload result NB.xlsx';
    }
    if (file_exists($examplePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="ตัวอย่างไฟล์uploadผลNB.xlsx"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($examplePath));
        readfile($examplePath);
        exit;
    }
}

/**
 * OpenXML XLSX Parser without external libraries
 */
function parseExcelFile($filePath) {
    if (!class_exists('ZipArchive')) {
        return ['error' => 'ZipArchive PHP extension is not enabled on this server.'];
    }

    $zip = new ZipArchive();
    if ($zip->open($filePath) !== true) {
        return ['error' => 'Cannot open the uploaded file as an Excel (.xlsx) archive.'];
    }

    // Read shared strings
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

    // Read first worksheet
    $sheetXmlContent = $zip->getFromName('xl/worksheets/sheet1.xml');
    if (!$sheetXmlContent) {
        $zip->close();
        return ['error' => 'Sheet 1 not found in the uploaded Excel workbook.'];
    }

    $sheetXml = @simplexml_load_string($sheetXmlContent);
    if (!$sheetXml || !isset($sheetXml->sheetData->row)) {
        $zip->close();
        return ['error' => 'Failed to parse worksheet XML data or the sheet is completely empty.'];
    }

    $rows = [];
    foreach ($sheetXml->sheetData->row as $row) {
        $rowCells = [];
        $colIndex = 0;
        foreach ($row->c as $c) {
            $cellRef = (string)$c['r']; // e.g. A1, B1
            preg_match('/^([A-Z]+)(\d+)$/', $cellRef, $matches);
            if (!empty($matches[1])) {
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
            if (isset($c->v)) {
                $rawVal = (string)$c->v;
                if ($type === 's') {
                    $val = $strings[(int)$rawVal] ?? $rawVal;
                } elseif ($type === 'inlineStr' && isset($c->is->t)) {
                    $val = (string)$c->is->t;
                } else {
                    $val = $rawVal;
                }
            } elseif ($type === 'inlineStr' && isset($c->is->t)) {
                $val = (string)$c->is->t;
            }

            $rowCells[$colIndex] = $val;
            $colIndex++;
        }
        $rows[] = $rowCells;
    }

    $zip->close();
    return $rows;
}

/**
 * Intelligent matcher between Excel column header and uresult.result_type
 */
function matchExcelColumnToResultType($excelColName, $uresultType) {
    $c = strtolower(trim($excelColName));
    $r = strtolower(trim($uresultType));

    if ($c === '' || $r === '') {
        return false;
    }

    // Direct case-insensitive match
    if ($c === $r) {
        return true;
    }

    // Clinical Diagnosis: explicitly handle to prevent cross-matching with Diag
    if (in_array($c, ['clinical diagnosis', 'clinical diag', 'clinical', 'clin diag', 'clin diagnosis'])) {
        return ($r === 'clinical diagnosis' || strpos($r, 'clinical') !== false);
    }

    // Diag / Pathological Diagnosis: explicitly handle (must NOT match clinical diagnosis!)
    if (in_array($c, ['diag', 'diagnosis', 'pathological diagnosis', 'patho diagnosis', 'pathological diag', 'patho diag', 'pathology diagnosis'])) {
        if (strpos($r, 'clinical') !== false) {
            return false;
        }
        return ($r === 'pathological diagnosis' || $r === 'diagnosis' || strpos($r, 'pathological') !== false);
    }

    // Gross Description
    if (in_array($c, ['gross', 'gross description', 'gross desc', 'gross examination', 'gross exam', 'macroscopic'])) {
        return ($r === 'gross description' || strpos($r, 'gross') !== false);
    }

    // Microscopic Description
    if (in_array($c, ['micro', 'microscopic', 'microscopic description', 'micro desc', 'micro examination'])) {
        return ($r === 'microscopic description' || strpos($r, 'micro') !== false);
    }

    // Specimen
    if (in_array($c, ['specimen', 'speciment', 'specimen type', 'speciment type', 'sample'])) {
        return ($r === 'specimen' || $r === 'specimen type' || strpos($r, 'specimen') !== false);
    }

    // General fallback: match only if length >= 5 and no conflict
    if (strlen($c) >= 5 && (strpos($r, $c) !== false || strpos($c, $r) !== false)) {
        return true;
    }

    return false;
}

/**
 * Check if an Excel column header represents 'Diag' / Pathological Diagnosis
 */
function isDiagColumn($colName) {
    $c = strtolower(trim((string)$colName));
    if ($c === '') {
        return false;
    }
    // Clinical diagnosis must NOT match
    if (strpos($c, 'clinical') !== false) {
        return false;
    }
    return in_array($c, ['diag', 'diagnosis', 'pathological diagnosis', 'patho diagnosis', 'pathological diag', 'patho diag', 'pathology diagnosis']) || strpos($c, 'diag') !== false || strpos($c, 'pathological') !== false;
}

// Processing state
$processResults = null;
$uploadError = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    if (!$isAuthorizedPatho) {
        $uploadError = "ขออภัย ท่านไม่มีสิทธิ์เข้าถึงฟังก์ชันนี้ (Authorized Pathologists Only)";
    } elseif ($_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
        $uploadError = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์ (Error code: " . $_FILES['excel_file']['error'] . ")";
    } else {
        $tmpFilePath = $_FILES['excel_file']['tmp_name'];
        $origFileName = $_FILES['excel_file']['name'];
        $ext = strtolower(pathinfo($origFileName, PATHINFO_EXTENSION));

        if ($ext !== 'xlsx') {
            $uploadError = "รองรับเฉพาะไฟล์ Excel นามสกุล .xlsx เท่านั้น (Uploaded file is not .xlsx)";
        } else {
            $parsed = parseExcelFile($tmpFilePath);
            if (isset($parsed['error'])) {
                $uploadError = $parsed['error'];
            } elseif (empty($parsed)) {
                $uploadError = "ไม่พบข้อมูลในไฟล์ Excel ที่อัปโหลด (Excel sheet is empty)";
            } else {
                $isDryRun = isset($_POST['dry_run']) && $_POST['dry_run'] == '1';
                $allowAdminOverride = isset($_POST['admin_override']) && $_POST['admin_override'] == '1' && ($isCurUserAdmin || (isset($_SESSION['user']->role_a) && $_SESSION['user']->role_a == 1));

                // Header row
                $headerRow = $parsed[0];
                $snColIndex = -1;
                $colHeaders = [];

                foreach ($headerRow as $idx => $headerName) {
                    $cleaned = trim((string)$headerName);
                    $colHeaders[$idx] = $cleaned;
                    if (strtolower($cleaned) === 'sn') {
                        $snColIndex = $idx;
                    }
                }

                if ($snColIndex === -1) {
                    $uploadError = "ไม่พบคอลัมน์ 'sn' ในหัวตารางของไฟล์ Excel กรุณาตรวจสอบว่ามีคอลัมน์ชื่อ sn";
                } else {
                    $processResults = [
                        'filename' => $origFileName,
                        'is_dry_run' => $isDryRun,
                        'total_rows' => count($parsed) - 1,
                        'success_rows' => 0,
                        'skipped_rows' => 0,
                        'error_rows' => 0,
                        'updated_fields' => 0,
                        'rows' => []
                    ];

                    $curUserId = (int)$_SESSION['user']->id;
                    $curUserName = $_SESSION['user']->name . " " . $_SESSION['user']->lastname;

                    // Read row by row (starting from index 1)
                    for ($rowIdx = 1; $rowIdx < count($parsed); $rowIdx++) {
                        $row = $parsed[$rowIdx];
                        $snValue = isset($row[$snColIndex]) ? trim((string)$row[$snColIndex]) : '';

                        $rowReport = [
                            'row_num' => $rowIdx + 1,
                            'sn' => $snValue,
                            'status' => 'PENDING',
                            'patient_id' => null,
                            'patient_name' => '-',
                            'owner_name' => '-',
                            'owner_id' => 0,
                            'is_owner' => false,
                            'steps' => [],
                            'column_actions' => []
                        ];

                        // Empty SN check
                        if ($snValue === '') {
                            $rowReport['status'] = 'SKIPPED';
                            $rowReport['steps'][] = [
                                'step' => '1. ตรวจสอบหมายเลข SN',
                                'status' => 'SKIPPED',
                                'msg' => "แถวที่ " . ($rowIdx + 1) . ": ค่าในคอลัมน์ sn ว่างเปล่า ข้ามแถวนี้"
                            ];
                            $processResults['skipped_rows']++;
                            $processResults['rows'][] = $rowReport;
                            continue;
                        }

                        // STEP 1: Select SN from table patient
                        $stmtP = $conn->prepare("SELECT id, pnum, ppre_name, pname, plastname, ppathologist_id, status_id, movetotrash, phospital_id, reported_as FROM patient WHERE pnum = :sn LIMIT 1");
                        $stmtP->execute([':sn' => $snValue]);
                        $patient = $stmtP->fetch(PDO::FETCH_ASSOC);

                        if (!$patient) {
                            $rowReport['status'] = 'NOT_FOUND';
                            $rowReport['steps'][] = [
                                'step' => '1. ตรวจสอบหมายเลข SN ในฐานข้อมูล',
                                'status' => 'ERROR',
                                'msg' => "ไม่พบเลขที่สิ่งส่งตรวจ '{$snValue}' ในตาราง patient"
                            ];
                            $processResults['error_rows']++;
                            $processResults['rows'][] = $rowReport;
                            continue;
                        }

                        $patientId = (int)$patient['id'];
                        $patientFullName = trim($patient['ppre_name'] . $patient['pname'] . ' ' . $patient['plastname']);
                        $patientReportedAs = trim((string)($patient['reported_as'] ?? ''));
                        $patientStatusId = (int)($patient['status_id'] ?? 0);
                        $rowReport['patient_id'] = $patientId;
                        $rowReport['patient_name'] = $patientFullName;

                        if ((int)$patient['movetotrash'] === 1) {
                            $rowReport['status'] = 'SKIPPED';
                            $rowReport['steps'][] = [
                                'step' => '1. ตรวจสอบสถานะเคส',
                                'status' => 'SKIPPED',
                                'msg' => "เคส SN: '{$snValue}' (ID: {$patientId}) อยู่ในถังขยะ (movetotrash = 1) ข้ามการประมวลผล"
                            ];
                            $processResults['skipped_rows']++;
                            $processResults['rows'][] = $rowReport;
                            continue;
                        }

                        $reportedAsText = $patientReportedAs ?: 'ยังไม่ออกผล';
                        $rowReport['steps'][] = [
                            'step' => '1. ตรวจสอบหมายเลข SN ในฐานข้อมูล',
                            'status' => 'SUCCESS',
                            'msg' => "พบข้อมูลผู้ป่วย ID: {$patientId}, ชื่อ: {$patientFullName}, สถานะปัจจุบัน: {$patient['status_id']}, การออกผล: {$reportedAsText}"
                        ];

                        // STEP 2: Check Pathologist Ownership
                        $assignedPathoId = (int)$patient['ppathologist_id'];
                        $assignedPathoName = "";

                        // Also verify with Job 5 (Pathologist)
                        $stmtJob = $conn->prepare("SELECT user_id, pre_name, name, lastname FROM job WHERE patient_id = :p_id AND job_role_id = 5 LIMIT 1");
                        $stmtJob->execute([':p_id' => $patientId]);
                        $job5 = $stmtJob->fetch(PDO::FETCH_ASSOC);

                        if ($assignedPathoId > 0) {
                            $ownerUser = User::getByID($conn, $assignedPathoId);
                            if ($ownerUser) {
                                if (is_object($ownerUser)) {
                                    $assignedPathoName = trim($ownerUser->pre_name . $ownerUser->name . ' ' . $ownerUser->lastname);
                                } elseif (is_array($ownerUser)) {
                                    $assignedPathoName = trim(($ownerUser['pre_name'] ?? '') . ($ownerUser['name'] ?? '') . ' ' . ($ownerUser['lastname'] ?? ''));
                                }
                            }
                        }
                        if (!$assignedPathoName && !empty($job5['user_id'])) {
                            $assignedPathoId = (int)$job5['user_id'];
                            $assignedPathoName = $job5['pre_name'] . $job5['name'] . ' ' . $job5['lastname'];
                        }

                        $rowReport['owner_id'] = $assignedPathoId;
                        $rowReport['owner_name'] = $assignedPathoName ?: "ยังไม่ได้ระบุพยาธิแพทย์";

                        $isOwner = ($curUserId === $assignedPathoId || (!empty($job5['user_id']) && $curUserId === (int)$job5['user_id']));
                        $rowReport['is_owner'] = $isOwner;

                        if (!$isOwner) {
                            if ($allowAdminOverride) {
                                $rowReport['steps'][] = [
                                    'step' => '2. ตรวจสอบสิทธิ์พยาธิแพทย์เจ้าของเคส',
                                    'status' => 'WARNING',
                                    'msg' => "ท่านไม่ใช่เจ้าของเคสนี้ (เจ้าของเคสคือ: {$rowReport['owner_name']} [ID: {$assignedPathoId}]) แต่ได้รับสิทธิ์ผู้ดูแลระบบ (Admin Override) ให้อัปเดตแทนได้"
                                ];
                            } else {
                                $rowReport['status'] = 'NOT_OWNER_SKIPPED';
                                $rowReport['steps'][] = [
                                    'step' => '2. ตรวจสอบสิทธิ์พยาธิแพทย์เจ้าของเคส',
                                    'status' => 'SKIPPED',
                                    'msg' => "ท่าน ({$curUserName}) ไม่ใช่พยาธิแพทย์เจ้าของเคสนี้ เจ้าของเคสคือ: {$rowReport['owner_name']} (ID: {$assignedPathoId}) -> ข้ามการบันทึกแถวนี้ตามเงื่อนไขความปลอดภัย"
                                ];
                                $processResults['skipped_rows']++;
                                $processResults['rows'][] = $rowReport;
                                continue;
                            }
                        } else {
                            $rowReport['steps'][] = [
                                'step' => '2. ตรวจสอบสิทธิ์พยาธิแพทย์เจ้าของเคส',
                                'status' => 'SUCCESS',
                                'msg' => "ตรวจสอบสิทธิ์สำเร็จ: ท่านเป็นพยาธิแพทย์เจ้าของเคสนี้ ({$assignedPathoName})"
                            ];
                        }

                        // STEP 3: Fetch uresult (presultupdate) for this patient_id
                        $stmtU = $conn->prepare("SELECT id, group_type, result_type, result_type_id, result_message, release_time, release_type, pathologist_id, pathologist2_id FROM presultupdate WHERE patient_id = :p_id ORDER BY id ASC");
                        $stmtU->execute([':p_id' => $patientId]);
                        $uresultRows = $stmtU->fetchAll(PDO::FETCH_ASSOC);

                        if (empty($uresultRows)) {
                            // Check if Excel has Diag column to create
                            $hasDiagHeader = false;
                            foreach ($colHeaders as $cIdx => $cHName) {
                                if ($cIdx !== $snColIndex && isDiagColumn($cHName)) {
                                    $hasDiagHeader = true;
                                    break;
                                }
                            }
                            if ($hasDiagHeader) {
                                $rowReport['steps'][] = [
                                    'step' => '3. ดึงข้อมูลตาราง uresult (presultupdate)',
                                    'status' => 'WARNING',
                                    'msg' => "ไม่พบรายการผลตรวจเดิมในระบบ แต่พบคอลัมน์ Diag ในไฟล์ Excel ระบบจะเตรียมสร้างหัวข้อใหม่ให้อัตโนมัติ"
                                ];
                            } else {
                                $rowReport['status'] = 'NO_URESULT';
                                $rowReport['steps'][] = [
                                    'step' => '3. ดึงข้อมูลตาราง uresult (presultupdate)',
                                    'status' => 'ERROR',
                                    'msg' => "ไม่พบรายการหัวข้อผลตรวจ (uresult) ของผู้ป่วย ID: {$patientId} ในฐานข้อมูล"
                                ];
                                $processResults['error_rows']++;
                                $processResults['rows'][] = $rowReport;
                                continue;
                            }
                        } else {
                            $availableTypes = array_map(function($r) {
                                return $r['result_type'] . " (ID: " . $r['id'] . ")";
                            }, $uresultRows);

                            $rowReport['steps'][] = [
                                'step' => '3. ดึงข้อมูลตาราง uresult (presultupdate)',
                                'status' => 'SUCCESS',
                                'msg' => "ดึงข้อมูล uresult สำเร็จ พบ " . count($uresultRows) . " รายการ: [" . implode(', ', $availableTypes) . "]"
                            ];
                        }

                        // STEP 4: Loop check columns from Excel against uresult rows
                        $hasAnyUpdate = false;
                        $hasAnyMatch = false;

                        foreach ($colHeaders as $colIdx => $colName) {
                            if ($colIdx === $snColIndex || $colName === '') {
                                continue; // Skip the 'sn' column itself
                            }

                            $excelCellText = isset($row[$colIdx]) ? (string)$row[$colIdx] : '';
                            $excelTrimmed = trim($excelCellText);

                            $colAction = [
                                'column' => $colName,
                                'excel_text_len' => mb_strlen($excelTrimmed, 'UTF-8'),
                                'matched_uresult_id' => null,
                                'matched_result_type' => null,
                                'status' => 'PENDING',
                                'message' => ''
                            ];

                            // Find matching uresult row
                            $matchedURow = null;
                            foreach ($uresultRows as $uRow) {
                                if (matchExcelColumnToResultType($colName, $uRow['result_type'])) {
                                    $matchedURow = $uRow;
                                    break;
                                }
                            }

                            // If not matched, check if it's 'Diag' to create new result
                            if ($matchedURow === null) {
                                if (isDiagColumn($colName)) {
                                    // Check if case is already released / provisional
                                    if ($patientReportedAs === 'ออกผลเบื้องต้น' || $patientReportedAs === 'ออกผลแล้ว' || $patientStatusId == 20000) {
                                        $colAction['status'] = 'RELEASED_SKIPPED';
                                        $colAction['matched_result_type'] = 'Pathological Diagnosis (สร้างใหม่)';
                                        $colAction['message'] = "เคสนี้มีสถานะเป็น '" . ($patientReportedAs ?: 'ออกผลแล้ว') . "' ไม่สามารถสร้างหรือใส่ข้อมูล 'Diag' ได้ (ข้ามการใส่ข้อมูล)";
                                        $rowReport['column_actions'][] = $colAction;
                                        continue;
                                    }

                                    // If Excel text is empty
                                    if ($excelTrimmed === '') {
                                        $colAction['status'] = 'EMPTY_SKIPPED';
                                        $colAction['matched_result_type'] = 'Pathological Diagnosis (สร้างใหม่)';
                                        $colAction['message'] = "ไม่พบหัวข้อ Diag เดิมในระบบ และค่าใน Excel ว่างเปล่า จึงข้ามการสร้าง";
                                        $rowReport['column_actions'][] = $colAction;
                                        continue;
                                    }

                                    $hasAnyMatch = true;

                                    if ($isDryRun) {
                                        $colAction['status'] = 'SIMULATED';
                                        $colAction['matched_result_type'] = 'Pathological Diagnosis (สร้างใหม่)';
                                        $colAction['message'] = "[จำลอง] ไม่พบหัวข้อ Diag เดิม -> พร้อมสร้างหัวข้อใหม่ 'Pathological Diagnosis' (Group 2, ID: 12) และใส่ค่าข้อความ (จำนวน " . mb_strlen($excelTrimmed, 'UTF-8') . " ตัวอักษร)";
                                        $hasAnyUpdate = true;
                                        $processResults['updated_fields']++;
                                    } else {
                                        // Retrieve report_type info for Pathological Diagnosis
                                        $stmtRT = $conn->prepare("SELECT id, name, group_type, release_type FROM report_type WHERE (name = 'Pathological Diagnosis' OR id = 12) AND group_type = 2 LIMIT 1");
                                        $stmtRT->execute();
                                        $diagReportType = $stmtRT->fetch(PDO::FETCH_ASSOC);

                                        $diagTypeId = $diagReportType ? (int)$diagReportType['id'] : 12;
                                        $diagTypeName = $diagReportType ? $diagReportType['name'] : 'Pathological Diagnosis';
                                        $diagReleaseType = $diagReportType ? $diagReportType['release_type'] : 'ออกผลแล้ว';

                                        // Create new presultupdate record
                                        $newPrsu = Presultupdate::getInitObj();
                                        $newPrsu->group_type = 2;
                                        $newPrsu->patient_id = $patientId;
                                        $newPrsu->result_type = $diagTypeName;
                                        $newPrsu->result_type_id = $diagTypeId;
                                        $newPrsu->release_type = $diagReleaseType;

                                        if ($newPrsu->create($conn)) {
                                            $newRsuId = (int)$newPrsu->id;
                                            Presultupdate::setTxtResult($conn, $newRsuId, $excelTrimmed);

                                            if ($assignedPathoId > 0) {
                                                $conn->query("UPDATE presultupdate SET pathologist_id = " . (int)$assignedPathoId . " WHERE id = " . (int)$newRsuId);
                                            }

                                            $curDateTime = Util::get_curreint_thai_date_time();
                                            Log::add(
                                                $conn,
                                                $_SESSION['username'] ?? '',
                                                $_SESSION['user']->name ?? '',
                                                "patient_edit_by_excel",
                                                "Created new Diag (uresult ID {$newRsuId}, {$diagTypeName}) for SN: {$snValue} (Patient ID: {$patientId}) with text from Excel",
                                                $curDateTime
                                            );

                                            // Add to in-memory list
                                            $uresultRows[] = [
                                                'id' => $newRsuId,
                                                'group_type' => 2,
                                                'result_type' => $diagTypeName,
                                                'result_type_id' => $diagTypeId,
                                                'result_message' => $excelTrimmed,
                                                'release_time' => null,
                                                'release_type' => $diagReleaseType,
                                                'pathologist_id' => $assignedPathoId,
                                                'pathologist2_id' => 0
                                            ];

                                            $colAction['matched_uresult_id'] = $newRsuId;
                                            $colAction['matched_result_type'] = $diagTypeName . ' (สร้างใหม่)';
                                            $colAction['status'] = 'UPDATED';
                                            $colAction['message'] = "สร้างหัวข้อใหม่ '{$diagTypeName}' (ID: {$newRsuId}) และใส่ค่าข้อความสำเร็จ (จำนวน " . mb_strlen($excelTrimmed, 'UTF-8') . " ตัวอักษร)";
                                            $hasAnyUpdate = true;
                                            $processResults['updated_fields']++;
                                        } else {
                                            $colAction['status'] = 'DB_ERROR';
                                            $colAction['message'] = "เกิดข้อผิดพลาดในการสร้างหัวข้อ Diag ใหม่ในฐานข้อมูล";
                                        }
                                    }

                                    $rowReport['column_actions'][] = $colAction;
                                    continue;
                                }

                                // Explain why cannot insert non-Diag column
                                $colAction['status'] = 'NO_MATCH';
                                $colAction['message'] = "ไม่พบคอลัมน์/หัวข้อที่ตรงกันในตาราง uresult ของผู้ป่วยรายนี้ (หัวข้อที่มีในระบบ: " . implode(', ', array_column($uresultRows, 'result_type')) . ")";
                                $rowReport['column_actions'][] = $colAction;
                                continue;
                            }

                            // Matched an existing uresult row
                            $hasAnyMatch = true;
                            $rsuId = (int)$matchedURow['id'];
                            $rsuType = $matchedURow['result_type'];
                            $colAction['matched_uresult_id'] = $rsuId;
                            $colAction['matched_result_type'] = $rsuType;

                            // CHECK STATUS: If 'ออกผลเบื้องต้น' or 'ออกผลแล้ว' -> SKIP!
                            $isItemReleased = false;
                            $releaseReason = '';

                            $uReleaseTime = $matchedURow['release_time'] ?? null;
                            $uReleaseType = trim((string)($matchedURow['release_type'] ?? ''));

                            if (!empty($uReleaseTime)) {
                                $isItemReleased = true;
                                $statusText = ($uReleaseType !== '' && $uReleaseType !== 'ยังไม่ออกผล') ? $uReleaseType : ($patientReportedAs ?: 'ออกผลแล้ว');
                                $releaseReason = "สถานะเป็น '{$statusText}' (ออกผลแล้วเมื่อ [{$uReleaseTime}]) ไม่สามารถใส่หรือแก้ไขข้อมูลได้ (ข้ามการใส่ข้อมูล)";
                            } elseif ($patientReportedAs === 'ออกผลเบื้องต้น' || $patientReportedAs === 'ออกผลแล้ว') {
                                $isItemReleased = true;
                                $releaseReason = "เคสนี้มีสถานะเป็น '{$patientReportedAs}' ไม่สามารถใส่หรือแก้ไขข้อมูลได้ (ข้ามการใส่ข้อมูล)";
                            } elseif ($uReleaseType === 'ออกผลเบื้องต้น') {
                                $isItemReleased = true;
                                $releaseReason = "หัวข้อนี้มีสถานะเป็น 'ออกผลเบื้องต้น' ไม่สามารถใส่หรือแก้ไขข้อมูลได้ (ข้ามการใส่ข้อมูล)";
                            } elseif ($patientStatusId == 20000) {
                                $isItemReleased = true;
                                $releaseReason = "เคสนี้เสร็จสิ้นการออกผลแล้ว (status_id: 20000) ไม่สามารถใส่หรือแก้ไขข้อมูลได้ (ข้ามการใส่ข้อมูล)";
                            }

                            if ($isItemReleased) {
                                $colAction['status'] = 'RELEASED_SKIPPED';
                                $colAction['message'] = $releaseReason;
                                $rowReport['column_actions'][] = $colAction;
                                continue;
                            }

                            // If Excel text is empty
                            if ($excelTrimmed === '') {
                                $colAction['status'] = 'EMPTY_SKIPPED';
                                $colAction['message'] = "ค่าใน Excel ว่างเปล่า จึงคงข้อมูลเดิมไว้ ไม่บันทึกทับ";
                                $rowReport['column_actions'][] = $colAction;
                                continue;
                            }

                            // Check if content is already identical
                            $currentDbText = trim((string)$matchedURow['result_message']);
                            if ($currentDbText === $excelTrimmed) {
                                $colAction['status'] = 'UNCHANGED';
                                $colAction['message'] = "ข้อความตรงกับในระบบแล้ว ไม่จำเป็นต้องอัปเดต";
                                $rowReport['column_actions'][] = $colAction;
                                continue;
                            }

                            // Perform update or Dry Run
                            if ($isDryRun) {
                                $colAction['status'] = 'SIMULATED';
                                $colAction['message'] = "[จำลอง] พร้อมอัปเดตข้อมูลลง uresult ID: {$rsuId} ('{$rsuType}') (จำนวน " . mb_strlen($excelTrimmed, 'UTF-8') . " ตัวอักษร)";
                                $hasAnyUpdate = true;
                                $processResults['updated_fields']++;
                            } else {
                                $updateOk = Presultupdate::setTxtResult($conn, $rsuId, $excelTrimmed);
                                if ($updateOk) {
                                    // Set pathologist_id if not set yet
                                    if (empty($matchedURow['pathologist_id']) || $matchedURow['pathologist_id'] == 0) {
                                        $conn->query("UPDATE presultupdate SET pathologist_id = " . (int)$assignedPathoId . " WHERE id = " . (int)$rsuId);
                                    }

                                    $curDateTime = Util::get_curreint_thai_date_time();
                                    Log::add(
                                        $conn,
                                        $_SESSION['username'],
                                        $_SESSION['user']->name,
                                        "patient_edit_by_excel",
                                        "Updated uresult ID {$rsuId} ({$rsuType}) for SN: {$snValue} (Patient ID: {$patientId})",
                                        $curDateTime
                                    );

                                    $colAction['status'] = 'UPDATED';
                                    $colAction['message'] = "บันทึกข้อมูลลง uresult ID: {$rsuId} ('{$rsuType}') สำเร็จ (จำนวน " . mb_strlen($excelTrimmed, 'UTF-8') . " ตัวอักษร)";
                                    $hasAnyUpdate = true;
                                    $processResults['updated_fields']++;
                                } else {
                                    $colAction['status'] = 'DB_ERROR';
                                    $colAction['message'] = "เกิดข้อผิดพลาดในการเขียนลงฐานข้อมูล uresult ID: {$rsuId}";
                                }
                            }

                            $rowReport['column_actions'][] = $colAction;
                        }

                        if ($hasAnyUpdate) {
                            $rowReport['status'] = $isDryRun ? 'SIMULATION_OK' : 'SUCCESS';
                            $processResults['success_rows']++;
                        } elseif ($hasAnyMatch) {
                            // Check if all actions were skipped due to release
                            $allReleased = true;
                            foreach ($rowReport['column_actions'] as $ca) {
                                if ($ca['status'] !== 'RELEASED_SKIPPED' && $ca['status'] !== 'EMPTY_SKIPPED') {
                                    $allReleased = false;
                                    break;
                                }
                            }
                            if ($allReleased && !empty($rowReport['column_actions'])) {
                                $rowReport['status'] = 'RELEASED_SKIPPED';
                            } else {
                                $rowReport['status'] = 'NO_CHANGES_NEEDED';
                            }
                            $processResults['skipped_rows']++;
                        } else {
                            $rowReport['status'] = 'NO_COLUMNS_MATCHED';
                            $processResults['error_rows']++;
                        }

                        $processResults['rows'][] = $rowReport;
                    }
                }
            }
        }
    }
}
?>

<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <!-- Breadcrumb & Header Title -->
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-between p-3 mx-1 mb-4 addsolidborder">
        <div class="col-md-8">
            <h3 class="mb-1 text-primary"><i class="fa-solid fa-file-excel me-2 text-success"></i>อัปโหลดผลการวินิจฉัยจาก Excel (Batch Update by Excel)</h3>
            <p class="mb-0 text-muted">
                หน้าสำหรับพยาธิแพทย์ในการนำเข้าผลตรวจ เช่น <strong>Specimen, Clinical diagnosis, Gross, Micro, Diag</strong> จากไฟล์ Excel (.xlsx) เข้าสู่ฐานข้อมูลระบบ NB Patho อัตโนมัติ
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="patient.php" class="btn btn-outline-secondary me-2"><i class="fa-solid fa-arrow-left me-1"></i>กลับหน้ารายชื่อผู้ป่วย</a>
            <a href="file/ตัวอย่างไฟล์uploadผลNB.xlsx" class="btn btn-outline-success" download><i class="fa-solid fa-download me-1"></i>ดาวน์โหลดไฟล์ตัวอย่าง</a>
        </div>
    </div>

    <!-- Authorization Notice -->
    <?php if (!$isAuthorizedPatho): ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="fa-solid fa-triangle-exclamation fa-2x me-3"></i>
            <div>
                <h5 class="alert-heading mb-1">ไม่สามารถเข้าถึงหน้านี้ได้</h5>
                หน้านี้อนุญาตเฉพาะพยาธิแพทย์ (Pathologist) และผู้ดูแลระบบเท่านั้น กรุณาติดต่อผู้ดูแลระบบหากต้องการสิทธิ์เข้าใช้งาน
            </div>
        </div>
    <?php else: ?>

        <!-- Pathologist Info Card -->
        <div class="card mb-4 border-primary shadow-sm">
            <div class="card-body bg-light rounded py-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-user-doctor fa-2x text-primary me-3"></i>
                            <div>
                                <strong>พยาธิแพทย์ผู้ใช้งานปัจจุบัน:</strong> <?= htmlspecialchars($_SESSION['user']->pre_name . $_SESSION['user']->name . " " . $_SESSION['user']->lastname) ?>
                                <span class="badge bg-primary ms-2"><?= htmlspecialchars($_SESSION['usergroup']->ugroup ?? 'Pathologist') ?></span>
                                <?php if ($isCurUserAdmin): ?>
                                    <span class="badge bg-danger ms-1">Administrator</span>
                                <?php endif; ?>
                                <div class="text-muted small mt-1">
                                    ระบบจะตรวจสอบสิทธิ์ความเป็นเจ้าของเคส (ppathologist_id) ในแต่ละแถวของไฟล์ Excel หากไม่ใช่เจ้าของเคส ระบบจะข้ามแถวนั้นอัตโนมัติ
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="text-muted small">รูปแบบหัวตารางที่รองรับ:</span><br>
                        <code>sn | Specimen | Clinical diagnosis | Gross | Micro | Diag</code>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($uploadError)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-xmark me-2"></i>
                <strong>เกิดข้อผิดพลาด:</strong> <?= htmlspecialchars($uploadError) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Upload Form Card -->
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="card-title mb-0"><i class="fa-solid fa-cloud-arrow-up me-2"></i>อัปโหลดไฟล์ Excel (.xlsx) เพื่อนำเข้าผลตรวจ</h5>
            </div>
            <div class="card-body p-4">
                <form action="patient_edit_by_excel.php" method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <label for="excel_file" class="form-label fw-bold">เลือกไฟล์ Excel (.xlsx) ที่มีคอลัมน์ผลตรวจ:</label>
                            <input class="form-control form-control-lg" type="file" id="excel_file" name="excel_file" accept=".xlsx" required>
                            <div class="form-text">
                                ตัวอย่างโครงสร้างไฟล์: <code>file/ตัวอย่างไฟล์uploadผลNB.xlsx</code> (ระบบรองรับ multiline, text carriage returns ในแต่ละช่อง)
                            </div>
                        </div>

                        <div class="col-lg-4 d-flex flex-column justify-content-center">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="dry_run" name="dry_run" value="1">
                                <label class="form-check-label fw-bold" for="dry_run">
                                    <i class="fa-solid fa-vial-circle-check text-warning me-1"></i>ตรวจสอบจำลองก่อน (Dry Run - ไม่บันทึกจริง)
                                </label>
                            </div>

                            <?php if ($isCurUserAdmin || (isset($_SESSION['user']->role_a) && $_SESSION['user']->role_a == 1)): ?>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="admin_override" name="admin_override" value="1">
                                    <label class="form-check-label text-danger fw-bold" for="admin_override">
                                        <i class="fa-solid fa-shield-halved me-1"></i>Admin Override (อนุญาตให้อัปเดตแม้ไม่ใช่เจ้าของเคส)
                                    </label>
                                </div>
                            <?php endif; ?>

                            <button type="submit" class="btn btn-success btn-lg mt-2 shadow">
                                <i class="fa-solid fa-file-import me-2"></i>เริ่มอ่านไฟล์และนำเข้าผลตรวจ
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Format Instruction & Mapping Guide Accordion -->
        <div class="accordion mb-4 shadow-sm" id="guideAccordion">
            <div class="accordion-item border-0">
                <h2 class="accordion-header" id="headingGuide">
                    <button class="accordion-button collapsed bg-light text-dark fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGuide" aria-expanded="false" aria-controls="collapseGuide">
                        <i class="fa-solid fa-circle-info text-info me-2"></i>คลิกเพื่อดูคำอธิบายขั้นตอนการทำงาน และการจับคู่คอลัมน์ (Column Mapping Guide)
                    </button>
                </h2>
                <div id="collapseGuide" class="accordion-collapse collapse" aria-labelledby="headingGuide" data-bs-parent="#guideAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary fw-bold"><i class="fa-solid fa-list-ol me-2"></i>ขั้นตอนการทำงานในแต่ละแถว (Execution Pipeline):</h6>
                                <ol class="small text-muted ps-3">
                                    <li><strong>อ่านค่า sn จาก Excel:</strong> ค้นหาในตาราง <code>patient</code> ด้วยฟิลด์ <code>pnum = :sn</code></li>
                                    <li><strong>ดึงข้อมูลผู้ป่วย (patient_id):</strong> ตรวจสอบว่ามีเคสจริง และไม่ได้อยู่ในถังขยะ</li>
                                    <li><strong>ตรวจสอบสิทธิ์พยาธิแพทย์:</strong> ตรวจสอบว่าพยาธิแพทย์ที่ล็อกอินปัจจุบันเป็นเจ้าของเคสหรือไม่ (<code>ppathologist_id</code> / <code>job_role_id = 5</code>) ถ้าไม่ใช่จะ <strong>ข้ามแถวทันที (SKIP)</strong></li>
                                    <li><strong>ดึงรายการผลตรวจ (uresult):</strong> ค้นหาทุกแถวในตาราง <code>presultupdate</code> ที่มี <code>patient_id = :id</code></li>
                                    <li><strong>วนลูปตรวจสอบคอลัมน์:</strong> เปรียบเทียบชื่อคอลัมน์ใน Excel กับ <code>result_type</code> ในตาราง <code>uresult</code>
                                        <ul>
                                            <li><strong>ตรวจสอบสถานะการออกผล:</strong> หากหัวข้อหรือเคสมีสถานะเป็น <code>ออกผลเบื้องต้น</code> หรือ <code>ออกผลแล้ว</code> จะไม่สามารถใส่ข้อมูลได้ ระบบจะ <strong>ข้ามการใส่ข้อมูลทันที</strong> เพื่อรักษาความปลอดภัยของเวชระเบียน</li>
                                            <li><strong>คอลัมน์ Diag (สร้างใหม่อัตโนมัติ):</strong> หากในระบบยังไม่มีหัวข้อ Diag (ไม่เจอคู่) ระบบจะ <strong>สร้างหัวข้อ Pathological Diagnosis (Group 2, ID: 12) ใหม่ขึ้นมาให้อัตโนมัติ</strong> แล้วใส่ค่าผลตรวจจาก Excel</li>
                                            <li>หากตรงกันและยังไม่ออกผล -> ทำการบันทึกข้อความจาก Excel ลงฐานข้อมูล</li>
                                        </ul>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary fw-bold"><i class="fa-solid fa-table-columns me-2"></i>ตารางการจับคู่คอลัมน์ (Excel Header Mapping):</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered small">
                                        <thead class="table-light">
                                            <tr>
                                                <th>คอลัมน์ใน Excel</th>
                                                <th>จับคู่กับ uresult.result_type</th>
                                                <th>หมายเหตุ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><code>sn</code></td>
                                                <td><code>patient.pnum</code></td>
                                                <td>กุญแจหลักในการค้นหาเคส</td>
                                            </tr>
                                            <tr>
                                                <td><code>Specimen</code></td>
                                                <td><code>Specimen</code></td>
                                                <td>Group 1 (id: 1)</td>
                                            </tr>
                                            <tr>
                                                <td><code>Clinical diagnosis</code></td>
                                                <td><code>Clinical Diagnosis</code></td>
                                                <td>Group 1 (id: 2)</td>
                                            </tr>
                                            <tr>
                                                <td><code>Gross</code></td>
                                                <td><code>Gross Description</code></td>
                                                <td>Group 1 (id: 3)</td>
                                            </tr>
                                            <tr>
                                                <td><code>Micro</code></td>
                                                <td><code>Microscopic Description</code></td>
                                                <td>Group 1 (id: 4)</td>
                                            </tr>
                                            <tr>
                                                <td><code>Diag</code></td>
                                                <td><code>Pathological Diagnosis</code></td>
                                                <td>Group 2 (id: 12) *หากไม่พบคู่จะสร้างใหม่อัตโนมัติ*</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Processing Report Section -->
        <?php if ($processResults !== null): ?>
            <div class="card mb-4 shadow border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-clipboard-check me-2 text-warning"></i>ผลการประมวลผลไฟล์:
                        <span class="text-info"><?= htmlspecialchars($processResults['filename']) ?></span>
                        <?php if ($processResults['is_dry_run']): ?>
                            <span class="badge bg-warning text-dark ms-2"><i class="fa-solid fa-vial me-1"></i>โหมดจำลอง (DRY RUN - ไม่มีการแก้ไข DB)</span>
                        <?php else: ?>
                            <span class="badge bg-success ms-2"><i class="fa-solid fa-database me-1"></i>โหมดบันทึกจริง (REAL UPDATE)</span>
                        <?php endif; ?>
                    </h5>
                    <div>
                        <button class="btn btn-sm btn-outline-light" onclick="window.print()"><i class="fa-solid fa-print me-1"></i>พิมพ์รายงาน</button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Summary Stats Badges -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="p-3 bg-light border rounded text-center">
                                <div class="text-muted small">จำนวนแถวทั้งหมดใน Excel</div>
                                <div class="fs-3 fw-bold text-primary"><?= $processResults['total_rows'] ?></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light border border-success rounded text-center">
                                <div class="text-muted small">ประมวลผลสำเร็จ (Success)</div>
                                <div class="fs-3 fw-bold text-success"><?= $processResults['success_rows'] ?></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light border border-warning rounded text-center">
                                <div class="text-muted small">ข้าม (Skipped / Not Owner / Empty)</div>
                                <div class="fs-3 fw-bold text-warning"><?= $processResults['skipped_rows'] ?></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light border border-info rounded text-center">
                                <div class="text-muted small">จำนวนช่องที่อัปเดต (Fields Updated)</div>
                                <div class="fs-3 fw-bold text-info"><?= $processResults['updated_fields'] ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step-by-Step Execution Table -->
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-shoe-prints me-2 text-primary"></i>รายละเอียดขั้นตอนการประมวลผลทีละแถว (Step-by-Step Row Report):</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">แถว</th>
                                    <th style="width: 140px;">Surgical No. (SN)</th>
                                    <th style="width: 200px;">ข้อมูลผู้ป่วย & เจ้าของเคส</th>
                                    <th style="width: 130px;" class="text-center">สถานะแถว</th>
                                    <th>บันทึกขั้นตอนการทำงาน (Step Log) และผลการตรวจสอบคอลัมน์</th>
                                    <th style="width: 110px;" class="text-center">ลิงก์เคส</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($processResults['rows'] as $r): ?>
                                    <?php
                                    $rowBadgeClass = 'bg-secondary';
                                    $rowStatusLabel = $r['status'];
                                    if ($r['status'] === 'SUCCESS') {
                                        $rowBadgeClass = 'bg-success';
                                        $rowStatusLabel = 'สำเร็จ';
                                    } elseif ($r['status'] === 'SIMULATION_OK') {
                                        $rowBadgeClass = 'bg-info text-dark';
                                        $rowStatusLabel = 'จำลองสำเร็จ';
                                    } elseif ($r['status'] === 'NOT_OWNER_SKIPPED') {
                                        $rowBadgeClass = 'bg-warning text-dark';
                                        $rowStatusLabel = 'ข้าม (ไม่ใช่เจ้าของ)';
                                    } elseif ($r['status'] === 'RELEASED_SKIPPED') {
                                        $rowBadgeClass = 'bg-danger text-white';
                                        $rowStatusLabel = 'ออกผลแล้ว (ข้าม)';
                                    } elseif ($r['status'] === 'SKIPPED') {
                                        $rowBadgeClass = 'bg-secondary';
                                        $rowStatusLabel = 'ข้าม';
                                    } elseif ($r['status'] === 'NOT_FOUND') {
                                        $rowBadgeClass = 'bg-danger';
                                        $rowStatusLabel = 'ไม่พบ SN';
                                    } elseif ($r['status'] === 'NO_CHANGES_NEEDED') {
                                        $rowBadgeClass = 'bg-primary';
                                        $rowStatusLabel = 'ข้อมูลตรงแล้ว';
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?= $r['row_num'] ?></td>
                                        <td>
                                            <span class="fw-bold text-primary fs-6"><?= htmlspecialchars($r['sn']) ?></span>
                                            <?php if ($r['patient_id']): ?>
                                                <div class="small text-muted">ID: <?= $r['patient_id'] ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($r['patient_name']) ?></div>
                                            <div class="small text-muted">
                                                <i class="fa-solid fa-user-tag me-1"></i>เจ้าของ: <?= htmlspecialchars($r['owner_name']) ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge <?= $rowBadgeClass ?> px-2 py-1"><?= $rowStatusLabel ?></span>
                                        </td>
                                        <td>
                                            <!-- Step Log List -->
                                            <div class="mb-2">
                                                <?php foreach ($r['steps'] as $st): ?>
                                                    <?php
                                                    $stIcon = 'fa-check text-success';
                                                    if ($st['status'] === 'ERROR') $stIcon = 'fa-xmark text-danger';
                                                    if ($st['status'] === 'SKIPPED' || $st['status'] === 'WARNING') $stIcon = 'fa-triangle-exclamation text-warning';
                                                    ?>
                                                    <div class="small mb-1">
                                                        <i class="fa-solid <?= $stIcon ?> me-1"></i>
                                                        <strong><?= htmlspecialchars($st['step']) ?>:</strong> <?= htmlspecialchars($st['msg']) ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                            <!-- Column Actions Table -->
                                            <?php if (!empty($r['column_actions'])): ?>
                                                <div class="bg-light p-2 rounded border">
                                                    <div class="fw-bold small mb-1 text-secondary"><i class="fa-solid fa-table-cells me-1"></i>ผลการจับคู่และบันทึกคอลัมน์:</div>
                                                    <table class="table table-sm table-bordered bg-white mb-0 small">
                                                        <thead>
                                                            <tr class="table-light">
                                                                <th>คอลัมน์ Excel</th>
                                                                <th>หัวข้อ uresult ที่ตรงกัน</th>
                                                                <th>สถานะ</th>
                                                                <th>รายละเอียด / สาเหตุ</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($r['column_actions'] as $ca): ?>
                                                                <?php
                                                                $caBadge = 'bg-secondary';
                                                                if ($ca['status'] === 'UPDATED') $caBadge = 'bg-success';
                                                                if ($ca['status'] === 'SIMULATED') $caBadge = 'bg-info text-dark';
                                                                if ($ca['status'] === 'UNCHANGED') $caBadge = 'bg-primary';
                                                                if ($ca['status'] === 'EMPTY_SKIPPED') $caBadge = 'bg-secondary';
                                                                if ($ca['status'] === 'NO_MATCH') $caBadge = 'bg-danger';
                                                                if ($ca['status'] === 'RELEASED_SKIPPED') $caBadge = 'bg-warning text-dark';
                                                                ?>
                                                                <tr>
                                                                    <td class="fw-bold"><?= htmlspecialchars($ca['column']) ?></td>
                                                                    <td>
                                                                        <?php if ($ca['matched_uresult_id']): ?>
                                                                            <span class="text-success"><i class="fa-solid fa-link me-1"></i><?= htmlspecialchars($ca['matched_result_type']) ?></span>
                                                                            <span class="text-muted">(ID: <?= $ca['matched_uresult_id'] ?>)</span>
                                                                        <?php else: ?>
                                                                            <span class="text-danger"><i class="fa-solid fa-link-slash me-1"></i>ไม่มี</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td><span class="badge <?= $caBadge ?>"><?= $ca['status'] ?></span></td>
                                                                    <td><?= htmlspecialchars($ca['message']) ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($r['patient_id']): ?>
                                                <a href="patient_edit.php?id=<?= $r['patient_id'] ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="เปิดหน้าแก้ไขเคสนี้">
                                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>ดูเคส
                                                </a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>

<?php require 'includes/footer.php'; ?>
