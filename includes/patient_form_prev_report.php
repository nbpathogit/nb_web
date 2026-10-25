<?php
/**
 * Previous report section for patient_edit.php
 * Displays previous cases matching the current patient's first name and surname.
 */

$prev_target_pname = trim($patient[0]['pname'] ?? '');
$prev_target_plastname = trim($patient[0]['plastname'] ?? '');
$current_patient_id = (int)($patient[0]['id'] ?? 0);

$prevCases = [];
if ($prev_target_pname !== '' && $prev_target_plastname !== '') {
    $prevSql = "SELECT p.id, p.pnum, p.sn_type, p.date_1000, p.date_20000, p.status_id, p.phospital_num,
                       p.p_rs_specimen,
                       h.hospital as hospital_name,
                       s.des as status_des,
                       u.pre_name as patho_pre, u.name as patho_name, u.lastname as patho_lastname
                FROM patient p
                LEFT JOIN hospital h ON p.phospital_id = h.id
                LEFT JOIN status s ON p.status_id = s.id
                LEFT JOIN user u ON p.ppathologist_id = u.id
                WHERE p.movetotrash = 0
                  AND TRIM(p.pname) = :pname 
                  AND TRIM(p.plastname) = :plastname
                  AND p.id != :current_id";

    $prevParams = [
        ':pname' => $prev_target_pname,
        ':plastname' => $prev_target_plastname,
        ':current_id' => $current_patient_id
    ];

    // If customer user, restrict to their hospital for data privacy
    if (isset($isCurUserCust) && $isCurUserCust && !empty($_SESSION['user']->uhospital_id)) {
        $prevSql .= " AND p.phospital_id = :cust_hosp_id";
        $prevParams[':cust_hosp_id'] = $_SESSION['user']->uhospital_id;
    }

    $prevSql .= " ORDER BY p.id DESC";

    $prevStmt = $conn->prepare($prevSql);
    $prevStmt->execute($prevParams);
    $prevCases = $prevStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch diagnosis snippet in batch for found cases
    if (!empty($prevCases)) {
        $caseIds = array_column($prevCases, 'id');
        $inPlaceholders = implode(',', array_fill(0, count($caseIds), '?'));
        $sqlDiag = "SELECT p1.patient_id, p1.result_message 
                    FROM presultupdate p1
                    INNER JOIN (
                        SELECT patient_id, MAX(id) as max_id 
                        FROM presultupdate 
                        WHERE patient_id IN ($inPlaceholders)
                          AND (result_type = 'Pathological Diagnosis' OR group_type = 2)
                          AND result_message IS NOT NULL
                          AND result_message != ''
                        GROUP BY patient_id
                    ) p2 ON p1.id = p2.max_id";
        $diagStmt = $conn->prepare($sqlDiag);
        $diagStmt->execute($caseIds);
        $diagMap = [];
        while ($row = $diagStmt->fetch(PDO::FETCH_ASSOC)) {
            $diagMap[$row['patient_id']] = $row['result_message'];
        }
        foreach ($prevCases as &$c) {
            $c['diag_preview'] = $diagMap[$c['id']] ?? '';
        }
        unset($c);
    }
}
?>

<div id="history_report_section" class="container-fluid pt-4 px-4">
    <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
            <h4 class="mb-0">
                <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i><b>History report</b>
                <?php if (!empty($prevCases)): ?>
                    <span class="badge bg-primary ms-2"><?= count($prevCases) ?> รายการ</span>
                <?php else: ?>
                    <span class="badge bg-secondary ms-2">0 รายการ</span>
                <?php endif; ?>
            </h4>
            <div class="text-muted small">
                ประวัติผลการตรวจของผู้ป่วย: <b><?= htmlspecialchars(trim(($patient[0]['ppre_name'] ?? '') . ' ' . $prev_target_pname . ' ' . $prev_target_plastname)) ?></b>
            </div>
        </div>
        <hr class="mt-2 mb-3">

        <?php if ($prev_target_pname === '' || $prev_target_plastname === ''): ?>
            <div class="alert alert-secondary text-center mb-0" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>ไม่สามารถค้นหาประวัติได้เนื่องจากยังไม่ได้ระบุชื่อหรือนามสกุลผู้ป่วย (Patient name or surname is not specified)
            </div>
        <?php elseif (empty($prevCases)): ?>
            <div class="alert alert-info text-center mb-0" role="alert">
                <i class="fa-solid fa-circle-info me-2"></i>ไม่พบประวัติผลการตรวจสำหรับผู้ป่วยท่านนี้ (No history report found for this patient)
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered border-secondary bg-white align-middle mb-0" id="previous_report_table">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th style="min-width: 140px;">Surgical Number (SN)</th>
                            <th style="min-width: 120px;">วันที่รับเข้า</th>
                            <th style="min-width: 110px;">วันที่ออกผล</th>
                            <th style="min-width: 160px;">โรงพยาบาล / HN</th>
                            <th style="min-width: 140px;">พยาธิแพทย์</th>
                            <th style="min-width: 250px;">ผลการวินิจฉัย (Diagnosis)</th>
                            <th style="min-width: 110px;">สถานะ</th>
                            <th style="min-width: 90px;">เปิดดู</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rowNum = 1; foreach ($prevCases as $case): ?>
                            <?php
                            // Check if intake date of this history case is earlier than the currently active case
                            $cur_intake_ts = !empty($patient[0]['date_1000']) ? strtotime($patient[0]['date_1000']) : 0;
                            $case_intake_ts = !empty($case['date_1000']) ? strtotime($case['date_1000']) : 0;
                            $is_previous_case = ($cur_intake_ts > 0 && $case_intake_ts > 0 && $case_intake_ts < $cur_intake_ts);
                            ?>
                            <tr>
                                <td class="text-center text-muted"><?= $rowNum++ ?></td>
                                <td class="text-center">
                                    <a href="patient_edit.php?id=<?= $case['id']; ?>" target="_blank" class="fw-bold text-primary text-decoration-underline" title="คลิกเพื่อเปิดดูเคสนี้ในแท็บใหม่">
                                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i><?= htmlspecialchars($case['pnum']); ?>
                                    </a>
                                </td>
                                <td class="text-center small">
                                    <div><?= !empty($case['date_1000']) ? htmlspecialchars(substr($case['date_1000'], 0, 10)) : '-' ?></div>
                                    <?php if ($is_previous_case): ?>
                                        <div class="mt-1"><span class="badge bg-primary" title="เคสนี้รับเข้าก่อนเคสปัจจุบัน">previous report</span></div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center small">
                                    <?= !empty($case['date_20000']) ? htmlspecialchars(substr($case['date_20000'], 0, 10)) : '<span class="text-muted">-</span>' ?>
                                </td>
                                <td>
                                    <div class="fw-bold small"><?= htmlspecialchars($case['hospital_name'] ?? '-') ?></div>
                                    <?php if (!empty($case['phospital_num'])): ?>
                                        <div class="text-muted small">HN: <?= htmlspecialchars($case['phospital_num']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="small">
                                    <?= htmlspecialchars(trim(($case['patho_pre'] ?? '') . $case['patho_name'] . ' ' . ($case['patho_lastname'] ?? ''))) ?: '<span class="text-muted">-</span>' ?>
                                </td>
                                <td class="small">
                                    <?php 
                                    $diagText = trim(strip_tags($case['diag_preview'] ?? ''));
                                    if (!empty($diagText)): 
                                        $shortDiag = mb_substr($diagText, 0, 120);
                                    ?>
                                        <div class="text-dark" title="<?= htmlspecialchars($diagText) ?>">
                                            <?= nl2br(htmlspecialchars($shortDiag)) ?>
                                            <?= (mb_strlen($diagText) > 120) ? '...' : '' ?>
                                        </div>
                                    <?php elseif (!empty($case['p_rs_specimen'])): ?>
                                        <div class="text-muted fst-italic" title="สิ่งส่งตรวจ">
                                            <?= htmlspecialchars(mb_substr(trim(strip_tags($case['p_rs_specimen'])), 0, 80)) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($case['status_id'] == 20000): ?>
                                        <span class="badge bg-success"><?= htmlspecialchars($case['status_des'] ?? 'เสร็จสิ้น') ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><?= htmlspecialchars($case['status_des'] ?? 'กำลังดำเนินการ') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="patient_edit.php?id=<?= $case['id']; ?>" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-2" title="เปิดดูเคสนี้ในแท็บใหม่">
                                        <i class="fa-solid fa-folder-open me-1"></i>เปิดดู
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
