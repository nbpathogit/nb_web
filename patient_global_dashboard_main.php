<?php
/**
 * Patient Global Dashboard Main (1-Year Executive Overview)
 * File: patient_global_dashboard_main.php
 * Path: C:\anuchit2\nb_web\dev3\patient_global_dashboard_main.php
 *
 * Description:
 * Executive 1-Year Fixed Overview Dashboard (2 charts per row, plot by month).
 * 1. Monthly Turn Around Time (TAT) - Default colored by Hospital
 * 2. Monthly Incoming Cases Count - Default colored by Hospital
 * 3. Monthly Reported Cases Count - Default colored by Hospital
 * 4. Pathologist Caseload Distribution (1 bar per pathologist)
 *
 * Self-contained backend functions with `pgdm_` prefix.
 */

require 'includes/init.php';
$conn = require 'includes/db.php';
Auth::requireLogin();
require 'user_auth.php';
require_once 'classes/Patientdash.php';

// ============================================================================
// STANDALONE BACKEND FUNCTIONS (Self-Contained with pgdm_ prefix)
// ============================================================================

if (!function_exists('pgdm_get_user_hospital_filter')) {
    /**
     * Determine if user is restricted to their customer hospital or has full NB pathology view
     * Returns: null if unrestricted (NB Pathology / Admin / Internal Staff), or integer hospital_id if customer
     */
    function pgdm_get_user_hospital_filter($conn) {
        $cur_user = isset($_SESSION['user']) ? $_SESSION['user'] : Auth::getUser();
        $uhosp_id = isset($cur_user->uhospital_id) ? intval($cur_user->uhospital_id) : 0;
        $ugroup_id = isset($cur_user->ugroup_id) ? intval($cur_user->ugroup_id) : 0;
        
        // If currently active user is Admin (group 1000) and NOT simulating another user:
        if ($ugroup_id === 1000 && !isset($_SESSION['adminusername'])) {
            return null; // Full admin sees all hospitals
        }
        
        // If user belongs to NB Pathology Center (hospital id 15):
        if ($uhosp_id === 15) {
            return null; // NB Pathology Center sees all hospitals
        }
        
        // If user belongs to NB internal groups (1000, 1900, 2000, 2100, 2200, 2500) and hospital is 15 or 0:
        if (in_array($ugroup_id, [1000, 1900, 2000, 2100, 2200, 2500]) && ($uhosp_id === 0 || $uhosp_id === 15)) {
            return null;
        }
        
        // Customer hospital user (groups 5000, 5100, or any user with uhospital_id > 0 and != 15):
        // Strict filter to only their own hospital ID
        if ($uhosp_id > 0) {
            return $uhosp_id;
        }
        
        return null;
    }
}

if (!function_exists('pgdm_generate_12_month_buckets')) {
    /**
     * Generate exactly 12 calendar month buckets for the past 1 year
     */
    function pgdm_generate_12_month_buckets() {
        $buckets = [];
        $thai_months = [
            1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.',
            5 => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.',
            9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
        ];

        $now = new DateTime('now');

        for ($i = 11; $i >= 0; $i--) {
            $dt = (clone $now)->modify("-$i month");
            $key = $dt->format('Y-m');
            $m = intval($dt->format('n'));
            $y = intval($dt->format('Y'));
            $label = $thai_months[$m] . ' ' . ($y + 543);
            $start = (clone $dt)->modify('first day of this month')->format('Y-m-d 00:00:00');
            $end = (clone $dt)->modify('last day of this month')->format('Y-m-d 23:59:59');
            $buckets[] = [
                'key' => $key,
                'label' => $label,
                'start' => $start,
                'end' => $end
            ];
        }

        return $buckets;
    }
}

if (!function_exists('pgdm_fetch_patient_records')) {
    /**
     * Fetch patient records for the 1-year period using user's base SQL
     */
    function pgdm_fetch_patient_records($conn, $start_date, $end_date, $filter_hospital_id = null) {
        $params = [
            ':start_date' => $start_date,
            ':end_date' => $end_date
        ];

        $hosp_clause = "";
        if (!empty($filter_hospital_id)) {
            $hosp_clause = " AND p.phospital_id = :filter_hospital_id AND (p.sn_type NOT IN ('IN', 'ZN') OR p.sn_type IS NULL)";
            $params[':filter_hospital_id'] = intval($filter_hospital_id);
        }

        $sql = "SELECT 
            p.id AS pid
            ,p.sn_type AS p_sn_type
            ,p.pnum AS p_pnum
            ,p.phospital_num AS p_phospital_num
            ,p.pname AS p_pname
            ,p.plastname AS p_plastname
            ,COALESCE(NULLIF(TRIM(h.hospital), ''), 'ไม่ระบุโรงพยาบาล') AS h_hospitial
            ,COALESCE(NULLIF(TRIM(CONCAT(COALESCE(upatho.name, ''), ' ', COALESCE(upatho.lastname, ''))), ''), 'ไม่ระบุพยาธิ') AS name_patho
            ,DATE(p.date_1000) AS p_date_1000
            ,DATE(p.date_first_report) AS p_date_first_report
            ,s.des AS s_des
            ,p.reported_as AS p_reported_as
            ,pri.priority AS pri_priority
            ,p.second_patho_review AS p_second_patho_review
            ,p.request_sp_slide AS p_request_sp_slide
            ,p.tr_time AS p_tr_time
            ,COALESCE(NULLIF(TRIM(p.create_by), ''), 'ไม่ระบุผู้รับเข้า') AS p_create_by
            ,COALESCE(NULLIF(TRIM(CONCAT(COALESCE(uclinician.name, ''), ' ', COALESCE(uclinician.lastname, ''))), ''), 'ไม่ระบุแพทย์ผู้ส่ง') AS u_clinician
        FROM patient AS p
        LEFT JOIN user AS upatho ON p.ppathologist_id = upatho.id
        LEFT JOIN user AS uclinician ON p.pclinician_id = uclinician.id
        LEFT JOIN hospital AS h ON p.phospital_id = h.id
        LEFT JOIN priority AS pri ON p.priority_id = pri.id
        LEFT JOIN status AS s ON p.status_id = s.id
        WHERE (p.movetotrash = 0 OR p.movetotrash IS NULL)
          AND (
              (p.date_1000 >= :start_date AND p.date_1000 <= :end_date)
              OR
              (p.date_first_report >= :start_date AND p.date_first_report <= :end_date)
          )
          $hosp_clause";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Check hospital scope: NB Pathology vs Customer Hospital
$scoped_hospital_id = pgdm_get_user_hospital_filter($conn);
$scoped_hospital_name = null;
if (!empty($scoped_hospital_id)) {
    $hstmt = $conn->prepare("SELECT hospital FROM hospital WHERE id = :id LIMIT 1");
    $hstmt->execute([':id' => $scoped_hospital_id]);
    $scoped_hospital_name = $hstmt->fetchColumn() ?: "โรงพยาบาลของท่าน";
}

// Generate Fixed 12-Month Period
$month_buckets = pgdm_generate_12_month_buckets();
$start_date = $month_buckets[0]['start'];
$end_date = $month_buckets[11]['end'];

// Fetch Records (filtered to customer hospital if non-NB)
$patient_records = pgdm_fetch_patient_records($conn, $start_date, $end_date, $scoped_hospital_id);

// Package Data Payload for Client-side JS
$payload_data = [
    'period_name' => '1 ปีย้อนหลัง (12 เดือน)',
    'start_date' => $start_date,
    'end_date' => $end_date,
    'scoped_hospital_id' => $scoped_hospital_id,
    'scoped_hospital_name' => $scoped_hospital_name,
    'is_hospital_scoped' => !empty($scoped_hospital_id),
    'buckets' => $month_buckets,
    'records_count' => count($patient_records),
    'records' => $patient_records
];

require 'includes/header.php';
?>

<!-- ==========================================================================
     PAGE CSS STYLING: Premium Modern Dark/Light Dual Theme
     ========================================================================== -->
<style>
:root {
    --pgdm-bg: #0b0f19;
    --pgdm-surface: #111827;
    --pgdm-surface-elevated: #1f2937;
    --pgdm-surface-card: #151d30;
    --pgdm-border: rgba(255, 255, 255, 0.08);
    --pgdm-border-highlight: rgba(99, 102, 241, 0.35);
    --pgdm-text-main: #f8fafc;
    --pgdm-text-muted: #94a3b8;
    --pgdm-primary: #6366f1;
    --pgdm-primary-hover: #4f46e5;
    --pgdm-accent-cyan: #06b6d4;
    --pgdm-accent-emerald: #10b981;
    --pgdm-accent-amber: #f59e0b;
    --pgdm-accent-purple: #a855f7;
    --pgdm-kpi-bg: rgba(255, 255, 255, 0.035);
}

body.pgdm-theme-light {
    --pgdm-bg: #f8fafc;
    --pgdm-surface: #ffffff;
    --pgdm-surface-elevated: #f1f5f9;
    --pgdm-surface-card: #ffffff;
    --pgdm-border: #e2e8f0;
    --pgdm-border-highlight: rgba(99, 102, 241, 0.4);
    --pgdm-text-main: #0f172a;
    --pgdm-text-muted: #64748b;
    --pgdm-primary: #4f46e5;
    --pgdm-primary-hover: #4338ca;
    --pgdm-kpi-bg: #f8fafc;
}

#pgdm-app {
    background-color: var(--pgdm-bg);
    color: var(--pgdm-text-main);
    min-height: 100vh;
    padding: 1.5rem 1rem 3rem 1rem;
    font-family: 'Heebo', system-ui, -apple-system, sans-serif;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* Header Card */
.pgdm-header-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.85) 0%, rgba(15, 23, 42, 0.98) 100%);
    border: 1px solid var(--pgdm-border);
    border-radius: 1rem;
    padding: 1.25rem 1.75rem;
    backdrop-filter: blur(12px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
    margin-bottom: 1.5rem;
}

body.pgdm-theme-light .pgdm-header-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.pgdm-header-title {
    font-size: 1.6rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.65rem;
    color: var(--pgdm-text-main);
    margin: 0;
}

.pgdm-header-subtitle {
    color: var(--pgdm-text-muted);
    font-size: 0.9rem;
    margin-top: 0.25rem;
}

/* Buttons & Controls */
.pgdm-btn {
    border-radius: 0.5rem;
    padding: 0.45rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid transparent;
    text-decoration: none;
}

.pgdm-btn-primary {
    background-color: var(--pgdm-primary);
    color: #ffffff !important;
}

.pgdm-btn-primary:hover {
    background-color: var(--pgdm-primary-hover);
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.4);
}

.pgdm-btn-outline {
    background: var(--pgdm-surface-elevated);
    border: 1px solid var(--pgdm-border);
    color: var(--pgdm-text-main) !important;
}

.pgdm-btn-outline:hover {
    border-color: var(--pgdm-primary);
    color: var(--pgdm-primary) !important;
}

.pgdm-select {
    background: var(--pgdm-surface-elevated);
    border: 1px solid var(--pgdm-border);
    color: var(--pgdm-text-main);
    border-radius: 0.5rem;
    padding: 0.35rem 0.75rem;
    font-size: 0.825rem;
    font-weight: 500;
    outline: none;
    transition: all 0.2s ease;
}

.pgdm-select:focus {
    border-color: var(--pgdm-primary);
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25);
}

/* 2-Chart Per Row Grid Cards */
.pgdm-card {
    background: var(--pgdm-surface-card);
    border: 1px solid var(--pgdm-border);
    border-radius: 1rem;
    padding: 1.25rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.15);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

body.pgdm-theme-light .pgdm-card {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.pgdm-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
    padding-bottom: 0.85rem;
    border-bottom: 1px solid var(--pgdm-border);
    margin-bottom: 1rem;
}

.pgdm-card-title-box {
    display: flex;
    align-items: center;
    gap: 0.65rem;
}

.pgdm-card-icon {
    width: 36px;
    height: 36px;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.pgdm-icon-cyan {
    background: rgba(6, 182, 212, 0.15);
    color: var(--pgdm-accent-cyan);
    border: 1px solid rgba(6, 182, 212, 0.3);
}

.pgdm-icon-emerald {
    background: rgba(16, 185, 129, 0.15);
    color: var(--pgdm-accent-emerald);
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.pgdm-icon-amber {
    background: rgba(245, 158, 11, 0.15);
    color: var(--pgdm-accent-amber);
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.pgdm-icon-purple {
    background: rgba(168, 85, 247, 0.15);
    color: var(--pgdm-accent-purple);
    border: 1px solid rgba(168, 85, 247, 0.3);
}

.pgdm-card-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--pgdm-text-main);
    margin: 0;
}

.pgdm-card-desc {
    font-size: 0.775rem;
    color: var(--pgdm-text-muted);
    margin: 0;
}

/* Compact KPI Badges */
.pgdm-kpi-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.5rem;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .pgdm-kpi-row {
        grid-template-columns: repeat(2, 1fr);
    }
}

.pgdm-kpi-item {
    background: var(--pgdm-kpi-bg);
    border: 1px solid var(--pgdm-border);
    border-radius: 0.6rem;
    padding: 0.55rem 0.75rem;
    text-align: center;
}

.pgdm-kpi-title {
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--pgdm-text-muted);
    margin-bottom: 0.15rem;
}

.pgdm-kpi-value {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--pgdm-text-main);
    line-height: 1.2;
}

/* Compact Chart Canvas Container (280px) */
.pgdm-chart-container {
    position: relative;
    width: 100%;
    height: 290px;
    flex-grow: 1;
}

.pgdm-badge-pill {
    padding: 0.2rem 0.55rem;
    border-radius: 9999px;
    font-size: 0.725rem;
    font-weight: 600;
    background: rgba(99, 102, 241, 0.15);
    color: #818cf8;
    border: 1px solid rgba(99, 102, 241, 0.3);
}

/* Fullscreen styles for Main Dashboard container */
#pgdm-app:fullscreen,
#pgdm-app:-webkit-full-screen {
    width: 100vw !important;
    height: 100vh !important;
    overflow-y: auto !important;
    padding: 1.5rem 1.5rem 3rem 1.5rem !important;
    background: var(--pgdm-bg) !important;
}

#pgdm-app.pgdm-fallback-fullscreen {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 99999 !important;
    overflow-y: auto !important;
    padding: 1.5rem 1.5rem 3rem 1.5rem !important;
    background: var(--pgdm-bg) !important;
}

/* Card Maximize Overlay */
.pgdm-card.pgdm-card-maximized {
    position: fixed !important;
    top: 15px !important;
    left: 15px !important;
    right: 15px !important;
    bottom: 15px !important;
    z-index: 100000 !important;
    overflow-y: auto !important;
    box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.75), 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
}
.pgdm-card.pgdm-card-maximized .pgdm-chart-container {
    height: 65vh !important;
}
</style>

<!-- ==========================================================================
     PAGE MARKUP & HTML STRUCTURE (1 Row 2 Charts)
     ========================================================================== -->
<div id="pgdm-app" class="pgdm-theme-dark">
    <div class="container-fluid px-md-3">

        <!-- HEADER BANNER -->
        <div class="pgdm-header-card">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h1 class="pgdm-header-title">
                        <i class="fa-solid fa-gauge-high text-primary"></i>
                        Main Executive Dashboard (1 ปีย้อนหลัง รายเดือน)
                    </h1>
                    <div class="pgdm-header-subtitle">
                        สรุปภาพรวมผู้บริหาร 12 เดือนย้อนหลัง (<?= $month_buckets[0]['label'] ?> - <?= $month_buckets[11]['label'] ?>) รวม <?= number_format(count($patient_records)) ?> เคส
                    </div>
                    <?php if (!empty($scoped_hospital_name)): ?>
                        <div class="mt-2">
                            <span class="badge bg-info text-dark px-3 py-1" style="font-size: 0.85rem;">
                                <i class="fa-solid fa-hospital me-1"></i> แสดงข้อมูลเฉพาะ: <?= htmlspecialchars($scoped_hospital_name) ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <!-- LINK TO FULL DASHBOARD -->
                    <a href="patient_global_dashboard.php" class="pgdm-btn pgdm-btn-primary" title="เปิดแดชบอร์ดแบบละเอียด สามารถปรับช่วงเวลาและแสดงตาราง">
                        <i class="fa-solid fa-chart-pie"></i> แดชบอร์ดแบบละเอียด (Full Dashboard)
                        <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                    </a>
                    <!-- FULLSCREEN BUTTON -->
                    <button id="pgdm-fullscreen-btn" class="pgdm-btn pgdm-btn-outline" type="button" title="สลับเต็มหน้าจอ (Fullscreen)" onclick="pgdmToggleFullscreen()">
                        <i id="pgdm-fullscreen-icon" class="fa-solid fa-expand"></i>
                        <span id="pgdm-fullscreen-text" class="d-none d-sm-inline">เต็มจอ</span>
                    </button>
                    <!-- THEME TOGGLE -->
                    <button id="pgdm-theme-toggle-btn" class="pgdm-btn pgdm-btn-outline" type="button" title="สลับธีม มืด/สว่าง">
                        <i id="pgdm-theme-icon" class="fa-solid fa-moon"></i>
                        <span id="pgdm-theme-text">โหมดมืด</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- 2 CHARTS PER ROW GRID -->
        <div class="row g-4">

            <!-- ==============================================================
                 CHART 1: TURN AROUND TIME (TAT รายเดือน 1 ปีย้อนหลัง)
                 ============================================================== -->
            <div class="col-12 col-xl-6">
                <div class="pgdm-card" id="card-tat">
                    <div class="pgdm-card-header">
                        <div class="pgdm-card-title-box">
                            <div class="pgdm-card-icon pgdm-icon-cyan">
                                <i class="fa-solid fa-stopwatch"></i>
                            </div>
                            <div>
                                <h3 class="pgdm-card-title">Turn Around Time รายเดือน</h3>
                                <p class="pgdm-card-desc">ระยะเวลาออกผลตรวจเฉลี่ย (วัน) 1 ปีย้อนหลัง</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <label class="small text-muted me-1">ใส่สีตาม:</label>
                            <!-- DEFAULT: แยกสีตามโรงพยาบาล -->
                            <select id="pgdm_dim_tat" class="pgdm-select" onchange="PGDM.updateTatChart()">
                                <?php if (empty($scoped_hospital_id)): ?>
                                <option value="h_hospitial" selected>โรงพยาบาล (Default)</option>
                                <?php else: ?>
                                <option value="none" selected>ไม่แยกสี (สีเดียว Default)</option>
                                <?php endif; ?>
                                <option value="name_patho">ชื่อพยาธิผู้ออกผล</option>
                                <option value="u_clinician">แพทย์ผู้ส่ง</option>
                                <option value="p_create_by">คนรับเข้า</option>
                                <?php if (empty($scoped_hospital_id)): ?>
                                <option value="none">ไม่แยกสี (สีเดียว)</option>
                                <?php endif; ?>
                            </select>
                            <button type="button" class="pgdm-btn pgdm-btn-outline py-1 px-2 pgdm-card-max-btn" title="ขยายกราฟนี้เต็มจอ" onclick="pgdmToggleCardFullscreen('card-tat')">
                                <i class="fa-solid fa-maximize"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Compact KPIs -->
                    <div class="pgdm-kpi-row">
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">ค่าเฉลี่ย (&mu;)</div>
                            <div class="pgdm-kpi-value text-info" id="kpi-tat-mean">-</div>
                        </div>
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">Std Dev (&sigma;)</div>
                            <div class="pgdm-kpi-value text-warning" id="kpi-tat-std">-</div>
                        </div>
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">ต่ำสุด (Min)</div>
                            <div class="pgdm-kpi-value text-success" id="kpi-tat-min">-</div>
                        </div>
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">สูงสุด (Max)</div>
                            <div class="pgdm-kpi-value text-danger" id="kpi-tat-max">-</div>
                        </div>
                    </div>

                    <!-- Canvas -->
                    <div class="pgdm-chart-container">
                        <canvas id="chart-main-tat"></canvas>
                    </div>
                </div>
            </div>

            <!-- ==============================================================
                 CHART 2: INCOMING CASES (เคสรับเข้า รายเดือน 1 ปีย้อนหลัง)
                 ============================================================== -->
            <div class="col-12 col-xl-6">
                <div class="pgdm-card" id="card-incoming">
                    <div class="pgdm-card-header">
                        <div class="pgdm-card-title-box">
                            <div class="pgdm-card-icon pgdm-icon-emerald">
                                <i class="fa-solid fa-inbox"></i>
                            </div>
                            <div>
                                <h3 class="pgdm-card-title">จำนวนเคสรับเข้า รายเดือน</h3>
                                <p class="pgdm-card-desc">ยอดบันทึกรับสิ่งส่งตรวจ (date_1000) 1 ปีย้อนหลัง</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <label class="small text-muted me-1">ใส่สีตาม:</label>
                            <!-- DEFAULT: แยกสีตามโรงพยาบาล -->
                            <select id="pgdm_dim_incoming" class="pgdm-select" onchange="PGDM.updateIncomingChart()">
                                <?php if (empty($scoped_hospital_id)): ?>
                                <option value="h_hospitial" selected>โรงพยาบาล (Default)</option>
                                <?php else: ?>
                                <option value="none" selected>ไม่แยกสี (สีเดียว Default)</option>
                                <?php endif; ?>
                                <option value="name_patho">ชื่อพยาธิผู้ออกผล</option>
                                <option value="u_clinician">แพทย์ผู้ส่ง</option>
                                <option value="p_create_by">คนรับเข้า</option>
                                <?php if (empty($scoped_hospital_id)): ?>
                                <option value="none">ไม่แยกสี (สีเดียว)</option>
                                <?php endif; ?>
                            </select>
                            <button type="button" class="pgdm-btn pgdm-btn-outline py-1 px-2 pgdm-card-max-btn" title="ขยายกราฟนี้เต็มจอ" onclick="pgdmToggleCardFullscreen('card-incoming')">
                                <i class="fa-solid fa-maximize"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Compact KPIs -->
                    <div class="pgdm-kpi-row">
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">รับเข้ารวม</div>
                            <div class="pgdm-kpi-value text-success" id="kpi-in-total">-</div>
                        </div>
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">เฉลี่ย/เดือน (&mu;)</div>
                            <div class="pgdm-kpi-value text-info" id="kpi-in-mean">-</div>
                        </div>
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">Std Dev (&sigma;)</div>
                            <div class="pgdm-kpi-value text-warning" id="kpi-in-std">-</div>
                        </div>
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">เดือนสูงสุด</div>
                            <div class="pgdm-kpi-value text-danger" id="kpi-in-peak">-</div>
                        </div>
                    </div>

                    <!-- Canvas -->
                    <div class="pgdm-chart-container">
                        <canvas id="chart-main-incoming"></canvas>
                    </div>
                </div>
            </div>

            <!-- ==============================================================
                 CHART 3: REPORTED CASES (จำนวนการออกผล รายเดือน 1 ปีย้อนหลัง)
                 ============================================================== -->
            <div class="col-12 col-xl-6">
                <div class="pgdm-card" id="card-reported">
                    <div class="pgdm-card-header">
                        <div class="pgdm-card-title-box">
                            <div class="pgdm-card-icon pgdm-icon-amber">
                                <i class="fa-solid fa-file-circle-check"></i>
                            </div>
                            <div>
                                <h3 class="pgdm-card-title">จำนวนการออกผล รายเดือน</h3>
                                <p class="pgdm-card-desc">ยอดออกผลตรวจพยาธิวิทยา (date_first_report) 1 ปีย้อนหลัง</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <label class="small text-muted me-1">ใส่สีตาม:</label>
                            <!-- DEFAULT: แยกสีตามโรงพยาบาล -->
                            <select id="pgdm_dim_reported" class="pgdm-select" onchange="PGDM.updateReportedChart()">
                                <?php if (empty($scoped_hospital_id)): ?>
                                <option value="h_hospitial" selected>โรงพยาบาล (Default)</option>
                                <?php else: ?>
                                <option value="none" selected>ไม่แยกสี (สีเดียว Default)</option>
                                <?php endif; ?>
                                <option value="name_patho">ชื่อพยาธิผู้ออกผล</option>
                                <option value="u_clinician">แพทย์ผู้ส่ง</option>
                                <option value="p_create_by">คนรับเข้า</option>
                                <?php if (empty($scoped_hospital_id)): ?>
                                <option value="none">ไม่แยกสี (สีเดียว)</option>
                                <?php endif; ?>
                            </select>
                            <button type="button" class="pgdm-btn pgdm-btn-outline py-1 px-2 pgdm-card-max-btn" title="ขยายกราฟนี้เต็มจอ" onclick="pgdmToggleCardFullscreen('card-reported')">
                                <i class="fa-solid fa-maximize"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Compact KPIs -->
                    <div class="pgdm-kpi-row">
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">ออกผลรวม</div>
                            <div class="pgdm-kpi-value text-warning" id="kpi-rep-total">-</div>
                        </div>
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">เฉลี่ย/เดือน (&mu;)</div>
                            <div class="pgdm-kpi-value text-info" id="kpi-rep-mean">-</div>
                        </div>
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">Std Dev (&sigma;)</div>
                            <div class="pgdm-kpi-value text-warning" id="kpi-rep-std">-</div>
                        </div>
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">เดือนสูงสุด</div>
                            <div class="pgdm-kpi-value text-danger" id="kpi-rep-peak">-</div>
                        </div>
                    </div>

                    <!-- Canvas -->
                    <div class="pgdm-chart-container">
                        <canvas id="chart-main-reported"></canvas>
                    </div>
                </div>
            </div>

            <!-- ==============================================================
                 CHART 4: PATHOLOGIST CASELOAD (หนึ่งท่านหนึ่งแท่ง 1 ปีย้อนหลัง)
                 ============================================================== -->
            <div class="col-12 col-xl-6">
                <div class="pgdm-card" id="card-patho">
                    <div class="pgdm-card-header">
                        <div class="pgdm-card-title-box">
                            <div class="pgdm-card-icon pgdm-icon-purple">
                                <i class="fa-solid fa-user-doctor"></i>
                            </div>
                            <div>
                                <h3 class="pgdm-card-title">จำนวนการออกผล ของ พยาธิแต่ละท่าน</h3>
                                <p class="pgdm-card-desc">ภาระงานออกผลตรวจ 1 ปีย้อนหลัง หนึ่งท่านหนึ่งแท่ง</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="pgdm-badge-pill">
                                <i class="fa-solid fa-arrow-down-wide-short me-1"></i> เรียงจากมากไปน้อย
                            </span>
                            <button type="button" class="pgdm-btn pgdm-btn-outline py-1 px-2 pgdm-card-max-btn" title="ขยายกราฟนี้เต็มจอ" onclick="pgdmToggleCardFullscreen('card-patho')">
                                <i class="fa-solid fa-maximize"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Compact KPIs -->
                    <div class="pgdm-kpi-row">
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">จำนวนพยาธิ</div>
                            <div class="pgdm-kpi-value text-purple" id="kpi-pat-count">-</div>
                        </div>
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">เฉลี่ย/ท่าน (&mu;)</div>
                            <div class="pgdm-kpi-value text-info" id="kpi-pat-mean">-</div>
                        </div>
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">Std Dev (&sigma;)</div>
                            <div class="pgdm-kpi-value text-warning" id="kpi-pat-std">-</div>
                        </div>
                        <div class="pgdm-kpi-item">
                            <div class="pgdm-kpi-title">สูงสุด (Top)</div>
                            <div class="pgdm-kpi-value text-danger" id="kpi-pat-top" style="font-size:0.95rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">-</div>
                        </div>
                    </div>

                    <!-- Canvas -->
                    <div class="pgdm-chart-container">
                        <canvas id="chart-main-patho"></canvas>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- ==========================================================================
     SCRIPTS: Chart.js Library + Standalone Engine
     ========================================================================== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
if (typeof Chart === 'undefined') {
    document.write('<script src="lib/chart/chart.min.js"><\/script>');
}
</script>

<script>
const PGDM_PAYLOAD = <?= json_encode($payload_data, JSON_UNESCAPED_UNICODE) ?>;
</script>

<script>
/**
 * Standalone Dashboard Manager for patient_global_dashboard_main.php
 */
const PGDM = {
    isDark: true,
    chartTat: null,
    chartIncoming: null,
    chartReported: null,
    chartPatho: null,

    palette: [
        '#06b6d4', '#6366f1', '#10b981', '#f59e0b', '#ec4899',
        '#8b5cf6', '#14b8a6', '#f97316', '#3b82f6', '#84cc16',
        '#d946ef', '#0ea5e9', '#eab308', '#a855f7', '#64748b'
    ],

    init: function() {
        this.initTheme();
        this.renderAllCharts();
    },

    initTheme: function() {
        const saved = localStorage.getItem('pgdm_theme') || localStorage.getItem('pgd_theme');
        this.isDark = (saved !== 'light');
        this.applyTheme();

        const btn = document.getElementById('pgdm-theme-toggle-btn');
        if (btn) {
            btn.addEventListener('click', () => {
                this.isDark = !this.isDark;
                localStorage.setItem('pgdm_theme', this.isDark ? 'dark' : 'light');
                this.applyTheme();
                this.updateAllChartsTheme();
            });
        }
    },

    applyTheme: function() {
        const app = document.getElementById('pgdm-app');
        const icon = document.getElementById('pgdm-theme-icon');
        const text = document.getElementById('pgdm-theme-text');
        if (this.isDark) {
            document.body.classList.remove('pgdm-theme-light');
            document.body.classList.add('pgdm-theme-dark');
            app.classList.remove('pgdm-theme-light');
            app.classList.add('pgdm-theme-dark');
            if (icon) icon.className = 'fa-solid fa-moon';
            if (text) text.textContent = 'โหมดมืด';
        } else {
            document.body.classList.remove('pgdm-theme-dark');
            document.body.classList.add('pgdm-theme-light');
            app.classList.remove('pgdm-theme-dark');
            app.classList.add('pgdm-theme-light');
            if (icon) icon.className = 'fa-solid fa-sun';
            if (text) text.textContent = 'โหมดสว่าง';
        }
    },

    getThemeColors: function() {
        if (this.isDark) {
            return {
                text: '#cbd5e1',
                textMuted: '#94a3b8',
                grid: 'rgba(255, 255, 255, 0.08)',
                tooltipBg: '#1e293b',
                tooltipBorder: 'rgba(255, 255, 255, 0.15)'
            };
        } else {
            return {
                text: '#334155',
                textMuted: '#64748b',
                grid: 'rgba(0, 0, 0, 0.06)',
                tooltipBg: '#ffffff',
                tooltipBorder: 'rgba(0, 0, 0, 0.1)'
            };
        }
    },

    updateAllChartsTheme: function() {
        if (this.chartTat) this.updateTatChart();
        if (this.chartIncoming) this.updateIncomingChart();
        if (this.chartReported) this.updateReportedChart();
        if (this.chartPatho) this.updatePathoChart();
    },

    calcMean: function(arr) {
        if (!arr || arr.length === 0) return 0;
        const sum = arr.reduce((acc, v) => acc + (parseFloat(v) || 0), 0);
        return sum / arr.length;
    },

    calcStdDev: function(arr) {
        if (!arr || arr.length <= 1) return 0;
        const mean = this.calcMean(arr);
        const variance = arr.reduce((acc, v) => acc + Math.pow((parseFloat(v) || 0) - mean, 2), 0) / (arr.length - 1);
        return Math.sqrt(variance);
    },

    calcMinMax: function(arr) {
        if (!arr || arr.length === 0) return { min: 0, max: 0 };
        const valid = arr.map(v => parseFloat(v)).filter(v => !isNaN(v));
        if (valid.length === 0) return { min: 0, max: 0 };
        return { min: Math.min(...valid), max: Math.max(...valid) };
    },

    renderAllCharts: function() {
        this.updateTatChart();
        this.updateIncomingChart();
        this.updateReportedChart();
        this.updatePathoChart();
    },

    // =========================================================================
    // 1. CHART: TURN AROUND TIME (TAT รายเดือน 1 ปีย้อนหลัง)
    // =========================================================================
    updateTatChart: function() {
        const dim = document.getElementById('pgdm_dim_tat').value;
        const buckets = PGDM_PAYLOAD.buckets;
        const records = PGDM_PAYLOAD.records;
        const theme = this.getThemeColors();

        const tatRecords = records.filter(r => r.p_date_first_report && (parseFloat(r.p_tr_time) > 0));

        const bucketTatValues = buckets.map(() => []);
        const bucketDimGroups = buckets.map(() => ({}));
        const dimTotalCounts = {};

        tatRecords.forEach(r => {
            const repDate = r.p_date_first_report;
            const tr = parseFloat(r.p_tr_time);
            const category = r[dim] || 'ไม่ระบุ';

            for (let i = 0; i < buckets.length; i++) {
                const b = buckets[i];
                if (repDate >= b.start.substring(0, 10) && repDate <= b.end.substring(0, 10)) {
                    bucketTatValues[i].push(tr);
                    if (dim !== 'none') {
                        if (!bucketDimGroups[i][category]) bucketDimGroups[i][category] = [];
                        bucketDimGroups[i][category].push(tr);
                        dimTotalCounts[category] = (dimTotalCounts[category] || 0) + 1;
                    }
                    break;
                }
            }
        });

        const allTats = tatRecords.map(r => parseFloat(r.p_tr_time));
        const overallMean = this.calcMean(allTats);
        const overallStd = this.calcStdDev(allTats);
        const { min, max } = this.calcMinMax(allTats);

        document.getElementById('kpi-tat-mean').textContent = overallMean > 0 ? overallMean.toFixed(2) + ' วัน' : '-';
        document.getElementById('kpi-tat-std').textContent = overallStd > 0 ? '± ' + overallStd.toFixed(2) : '-';
        document.getElementById('kpi-tat-min').textContent = min > 0 ? min.toFixed(2) : '-';
        document.getElementById('kpi-tat-max').textContent = max > 0 ? max.toFixed(2) : '-';

        const labels = buckets.map(b => b.label);
        let datasets = [];

        if (dim === 'none') {
            const dataMeans = bucketTatValues.map(arr => {
                const m = this.calcMean(arr);
                return m > 0 ? Number(m.toFixed(2)) : 0;
            });
            datasets.push({
                type: 'bar',
                label: 'TAT เฉลี่ย (วัน)',
                data: dataMeans,
                backgroundColor: 'rgba(6, 182, 212, 0.75)',
                borderColor: '#06b6d4',
                borderWidth: 1.5,
                borderRadius: 5
            });
        } else {
            const sortedDims = Object.keys(dimTotalCounts).sort((a, b) => dimTotalCounts[b] - dimTotalCounts[a]);
            const topDims = sortedDims.slice(0, 6);
            const hasOthers = sortedDims.length > 6;

            topDims.forEach((cat, idx) => {
                const color = this.palette[idx % this.palette.length];
                const catData = buckets.map((b, bIdx) => {
                    const vals = bucketDimGroups[bIdx][cat] || [];
                    const m = this.calcMean(vals);
                    return m > 0 ? Number(m.toFixed(2)) : null;
                });
                datasets.push({
                    type: 'bar',
                    label: cat,
                    data: catData,
                    backgroundColor: color,
                    borderRadius: 4
                });
            });

            if (hasOthers) {
                const otherData = buckets.map((b, bIdx) => {
                    let otherVals = [];
                    Object.keys(bucketDimGroups[bIdx]).forEach(c => {
                        if (!topDims.includes(c)) otherVals = otherVals.concat(bucketDimGroups[bIdx][c]);
                    });
                    const m = this.calcMean(otherVals);
                    return m > 0 ? Number(m.toFixed(2)) : null;
                });
                datasets.push({
                    type: 'bar',
                    label: 'อื่นๆ',
                    data: otherData,
                    backgroundColor: '#64748b',
                    borderRadius: 4
                });
            }
        }

        if (overallMean > 0) {
            datasets.push({
                type: 'line',
                label: 'ค่าเฉลี่ยรวม (Mean: ' + overallMean.toFixed(2) + ' วัน)',
                data: Array(buckets.length).fill(Number(overallMean.toFixed(2))),
                borderColor: '#ef4444',
                borderWidth: 2,
                borderDash: [5, 4],
                pointRadius: 0,
                fill: false,
                order: 0
            });
        }

        const ctx = document.getElementById('chart-main-tat').getContext('2d');
        if (this.chartTat) this.chartTat.destroy();

        this.chartTat = new Chart(ctx, {
            data: { labels, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { color: theme.text, font: { family: 'Heebo', size: 11 }, boxWidth: 12 }
                    },
                    tooltip: {
                        backgroundColor: theme.tooltipBg,
                        titleColor: theme.text,
                        bodyColor: theme.text,
                        borderColor: theme.tooltipBorder,
                        borderWidth: 1,
                        callbacks: {
                            label: function(c) {
                                return c.dataset.label + ': ' + (c.raw !== null && c.raw !== undefined ? c.raw + ' วัน' : '-');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: theme.grid },
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 10 } }
                    },
                    y: {
                        title: { display: true, text: 'TAT เฉลี่ย (วัน)', color: theme.textMuted },
                        grid: { color: theme.grid },
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 10 } },
                        beginAtZero: true
                    }
                }
            }
        });
    },

    // =========================================================================
    // 2. CHART: INCOMING CASES (เคสรับเข้า รายเดือน 1 ปีย้อนหลัง)
    // =========================================================================
    updateIncomingChart: function() {
        const dim = document.getElementById('pgdm_dim_incoming').value;
        const buckets = PGDM_PAYLOAD.buckets;
        const records = PGDM_PAYLOAD.records;
        const theme = this.getThemeColors();

        const inRecords = records.filter(r => r.p_date_1000);

        const bucketCounts = buckets.map(() => 0);
        const bucketDimCounts = buckets.map(() => ({}));
        const dimTotalCounts = {};

        inRecords.forEach(r => {
            const inDate = r.p_date_1000;
            const category = r[dim] || 'ไม่ระบุ';

            for (let i = 0; i < buckets.length; i++) {
                const b = buckets[i];
                if (inDate >= b.start.substring(0, 10) && inDate <= b.end.substring(0, 10)) {
                    bucketCounts[i]++;
                    if (dim !== 'none') {
                        bucketDimCounts[i][category] = (bucketDimCounts[i][category] || 0) + 1;
                        dimTotalCounts[category] = (dimTotalCounts[category] || 0) + 1;
                    }
                    break;
                }
            }
        });

        const totalIn = bucketCounts.reduce((a, b) => a + b, 0);
        const meanIn = this.calcMean(bucketCounts);
        const stdIn = this.calcStdDev(bucketCounts);
        const maxIn = Math.max(...bucketCounts);
        const maxIdx = bucketCounts.indexOf(maxIn);
        const peakLabel = maxIdx >= 0 ? buckets[maxIdx].label : '-';

        document.getElementById('kpi-in-total').textContent = totalIn.toLocaleString();
        document.getElementById('kpi-in-mean').textContent = meanIn.toFixed(0);
        document.getElementById('kpi-in-std').textContent = '± ' + stdIn.toFixed(0);
        document.getElementById('kpi-in-peak').textContent = peakLabel.split(' ')[0] + ' (' + maxIn.toLocaleString() + ')';

        const labels = buckets.map(b => b.label);
        let datasets = [];

        if (dim === 'none') {
            datasets.push({
                type: 'bar',
                label: 'เคสรับเข้า (เคส)',
                data: bucketCounts,
                backgroundColor: 'rgba(16, 185, 129, 0.75)',
                borderColor: '#10b981',
                borderWidth: 1.5,
                borderRadius: 5
            });
        } else {
            const sortedDims = Object.keys(dimTotalCounts).sort((a, b) => dimTotalCounts[b] - dimTotalCounts[a]);
            const topDims = sortedDims.slice(0, 6);
            const hasOthers = sortedDims.length > 6;

            topDims.forEach((cat, idx) => {
                const color = this.palette[idx % this.palette.length];
                const data = buckets.map((b, bIdx) => bucketDimCounts[bIdx][cat] || 0);
                datasets.push({
                    type: 'bar',
                    label: cat,
                    data: data,
                    backgroundColor: color,
                    borderRadius: 3
                });
            });

            if (hasOthers) {
                const otherData = buckets.map((b, bIdx) => {
                    let sum = 0;
                    Object.keys(bucketDimCounts[bIdx]).forEach(c => {
                        if (!topDims.includes(c)) sum += bucketDimCounts[bIdx][c];
                    });
                    return sum;
                });
                datasets.push({
                    type: 'bar',
                    label: 'อื่นๆ',
                    data: otherData,
                    backgroundColor: '#64748b',
                    borderRadius: 3
                });
            }
        }

        if (meanIn > 0) {
            datasets.push({
                type: 'line',
                label: 'เฉลี่ยต่อเดือน (Mean: ' + meanIn.toFixed(0) + ' เคส)',
                data: Array(buckets.length).fill(Number(meanIn.toFixed(0))),
                borderColor: '#f59e0b',
                borderWidth: 2,
                borderDash: [5, 4],
                pointRadius: 0,
                fill: false,
                order: 0
            });
        }

        const ctx = document.getElementById('chart-main-incoming').getContext('2d');
        if (this.chartIncoming) this.chartIncoming.destroy();

        this.chartIncoming = new Chart(ctx, {
            data: { labels, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { color: theme.text, font: { family: 'Heebo', size: 11 }, boxWidth: 12 }
                    },
                    tooltip: {
                        backgroundColor: theme.tooltipBg,
                        titleColor: theme.text,
                        bodyColor: theme.text,
                        borderColor: theme.tooltipBorder,
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        stacked: (dim !== 'none'),
                        grid: { color: theme.grid },
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 10 } }
                    },
                    y: {
                        stacked: (dim !== 'none'),
                        title: { display: true, text: 'จำนวนเคสรับเข้า (เคส)', color: theme.textMuted },
                        grid: { color: theme.grid },
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 10 } },
                        beginAtZero: true
                    }
                }
            }
        });
    },

    // =========================================================================
    // 3. CHART: REPORTED CASES (จำนวนการออกผล รายเดือน 1 ปีย้อนหลัง)
    // =========================================================================
    updateReportedChart: function() {
        const dim = document.getElementById('pgdm_dim_reported').value;
        const buckets = PGDM_PAYLOAD.buckets;
        const records = PGDM_PAYLOAD.records;
        const theme = this.getThemeColors();

        const repRecords = records.filter(r => r.p_date_first_report);

        const bucketCounts = buckets.map(() => 0);
        const bucketDimCounts = buckets.map(() => ({}));
        const dimTotalCounts = {};

        repRecords.forEach(r => {
            const repDate = r.p_date_first_report;
            const category = r[dim] || 'ไม่ระบุ';

            for (let i = 0; i < buckets.length; i++) {
                const b = buckets[i];
                if (repDate >= b.start.substring(0, 10) && repDate <= b.end.substring(0, 10)) {
                    bucketCounts[i]++;
                    if (dim !== 'none') {
                        bucketDimCounts[i][category] = (bucketDimCounts[i][category] || 0) + 1;
                        dimTotalCounts[category] = (dimTotalCounts[category] || 0) + 1;
                    }
                    break;
                }
            }
        });

        const totalRep = bucketCounts.reduce((a, b) => a + b, 0);
        const meanRep = this.calcMean(bucketCounts);
        const stdRep = this.calcStdDev(bucketCounts);
        const maxRep = Math.max(...bucketCounts);
        const maxIdx = bucketCounts.indexOf(maxRep);
        const peakLabel = maxIdx >= 0 ? buckets[maxIdx].label : '-';

        document.getElementById('kpi-rep-total').textContent = totalRep.toLocaleString();
        document.getElementById('kpi-rep-mean').textContent = meanRep.toFixed(0);
        document.getElementById('kpi-rep-std').textContent = '± ' + stdRep.toFixed(0);
        document.getElementById('kpi-rep-peak').textContent = peakLabel.split(' ')[0] + ' (' + maxRep.toLocaleString() + ')';

        const labels = buckets.map(b => b.label);
        let datasets = [];

        if (dim === 'none') {
            datasets.push({
                type: 'bar',
                label: 'เคสออกผลแล้ว (เคส)',
                data: bucketCounts,
                backgroundColor: 'rgba(245, 158, 11, 0.75)',
                borderColor: '#f59e0b',
                borderWidth: 1.5,
                borderRadius: 5
            });
        } else {
            const sortedDims = Object.keys(dimTotalCounts).sort((a, b) => dimTotalCounts[b] - dimTotalCounts[a]);
            const topDims = sortedDims.slice(0, 6);
            const hasOthers = sortedDims.length > 6;

            topDims.forEach((cat, idx) => {
                const color = this.palette[idx % this.palette.length];
                const data = buckets.map((b, bIdx) => bucketDimCounts[bIdx][cat] || 0);
                datasets.push({
                    type: 'bar',
                    label: cat,
                    data: data,
                    backgroundColor: color,
                    borderRadius: 3
                });
            });

            if (hasOthers) {
                const otherData = buckets.map((b, bIdx) => {
                    let sum = 0;
                    Object.keys(bucketDimCounts[bIdx]).forEach(c => {
                        if (!topDims.includes(c)) sum += bucketDimCounts[bIdx][c];
                    });
                    return sum;
                });
                datasets.push({
                    type: 'bar',
                    label: 'อื่นๆ',
                    data: otherData,
                    backgroundColor: '#64748b',
                    borderRadius: 3
                });
            }
        }

        if (meanRep > 0) {
            datasets.push({
                type: 'line',
                label: 'เฉลี่ยต่อเดือน (Mean: ' + meanRep.toFixed(0) + ' เคส)',
                data: Array(buckets.length).fill(Number(meanRep.toFixed(0))),
                borderColor: '#6366f1',
                borderWidth: 2,
                borderDash: [5, 4],
                pointRadius: 0,
                fill: false,
                order: 0
            });
        }

        const ctx = document.getElementById('chart-main-reported').getContext('2d');
        if (this.chartReported) this.chartReported.destroy();

        this.chartReported = new Chart(ctx, {
            data: { labels, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { color: theme.text, font: { family: 'Heebo', size: 11 }, boxWidth: 12 }
                    },
                    tooltip: {
                        backgroundColor: theme.tooltipBg,
                        titleColor: theme.text,
                        bodyColor: theme.text,
                        borderColor: theme.tooltipBorder,
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        stacked: (dim !== 'none'),
                        grid: { color: theme.grid },
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 10 } }
                    },
                    y: {
                        stacked: (dim !== 'none'),
                        title: { display: true, text: 'จำนวนเคสออกผล (เคส)', color: theme.textMuted },
                        grid: { color: theme.grid },
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 10 } },
                        beginAtZero: true
                    }
                }
            }
        });
    },

    // =========================================================================
    // 4. CHART: PATHOLOGIST CASELOAD (หนึ่งท่านหนึ่งแท่ง 1 ปีย้อนหลัง)
    // =========================================================================
    updatePathoChart: function() {
        const records = PGDM_PAYLOAD.records;
        const theme = this.getThemeColors();

        const pathoCounts = {};
        const pathoTats = {};

        records.forEach(r => {
            if (r.p_date_first_report && r.name_patho && r.name_patho !== 'ไม่ระบุพยาธิ') {
                const p = r.name_patho;
                pathoCounts[p] = (pathoCounts[p] || 0) + 1;
                if (!pathoTats[p]) pathoTats[p] = [];
                if (parseFloat(r.p_tr_time) > 0) {
                    pathoTats[p].push(parseFloat(r.p_tr_time));
                }
            }
        });

        const sortedPathos = Object.keys(pathoCounts).sort((a, b) => pathoCounts[b] - pathoCounts[a]);
        const counts = sortedPathos.map(p => pathoCounts[p]);

        const meanCases = this.calcMean(counts);
        const stdCases = this.calcStdDev(counts);
        const topPathoName = sortedPathos.length > 0 ? sortedPathos[0] : '-';
        const topPathoCount = sortedPathos.length > 0 ? pathoCounts[topPathoName] : 0;

        document.getElementById('kpi-pat-count').textContent = sortedPathos.length + ' ท่าน';
        document.getElementById('kpi-pat-mean').textContent = meanCases.toFixed(0);
        document.getElementById('kpi-pat-std').textContent = '± ' + stdCases.toFixed(0);
        document.getElementById('kpi-pat-top').textContent = topPathoName + ' (' + topPathoCount.toLocaleString() + ')';

        const barColors = sortedPathos.map((p, idx) => this.palette[idx % this.palette.length]);

        const datasets = [
            {
                type: 'bar',
                label: 'จำนวนเคสที่ออกผล (เคส)',
                data: counts,
                backgroundColor: barColors,
                borderRadius: 5
            }
        ];

        if (meanCases > 0) {
            datasets.push({
                type: 'line',
                label: 'เฉลี่ยต่อท่าน (Mean: ' + meanCases.toFixed(0) + ' เคส)',
                data: Array(sortedPathos.length).fill(Number(meanCases.toFixed(0))),
                borderColor: '#ec4899',
                borderWidth: 2,
                borderDash: [5, 4],
                pointRadius: 0,
                fill: false,
                order: 0
            });
        }

        const ctx = document.getElementById('chart-main-patho').getContext('2d');
        if (this.chartPatho) this.chartPatho.destroy();

        this.chartPatho = new Chart(ctx, {
            data: { labels: sortedPathos, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { color: theme.text, font: { family: 'Heebo', size: 11 }, boxWidth: 12 }
                    },
                    tooltip: {
                        backgroundColor: theme.tooltipBg,
                        titleColor: theme.text,
                        bodyColor: theme.text,
                        borderColor: theme.tooltipBorder,
                        borderWidth: 1,
                        callbacks: {
                            afterLabel: function(c) {
                                const p = sortedPathos[c.dataIndex];
                                const tats = pathoTats[p] || [];
                                const avgTat = tats.length > 0 ? (tats.reduce((a, b) => a + b, 0) / tats.length).toFixed(2) : '-';
                                return 'TAT เฉลี่ย: ' + avgTat + ' วัน';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: theme.grid },
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 10 }, maxRotation: 45, minRotation: 20 }
                    },
                    y: {
                        title: { display: true, text: 'จำนวนเคสออกผล (เคส)', color: theme.textMuted },
                        grid: { color: theme.grid },
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 10 } },
                        beginAtZero: true
                    }
                }
            }
        });
    }
};

// =========================================================================
// FULLSCREEN TOGGLE HANDLERS
// =========================================================================
function pgdmToggleFullscreen() {
    const elem = document.getElementById('pgdm-app');
    const icon = document.getElementById('pgdm-fullscreen-icon');
    const text = document.getElementById('pgdm-fullscreen-text');

    if (!document.fullscreenElement && !document.webkitFullscreenElement && !elem.classList.contains('pgdm-fallback-fullscreen')) {
        if (elem.requestFullscreen) {
            elem.requestFullscreen().catch(() => {
                elem.classList.add('pgdm-fallback-fullscreen');
                updateBtn(true);
            });
        } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen();
        } else {
            elem.classList.add('pgdm-fallback-fullscreen');
            updateBtn(true);
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen().catch(() => {});
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
        elem.classList.remove('pgdm-fallback-fullscreen');
        updateBtn(false);
    }

    function updateBtn(isFull) {
        if (icon) {
            icon.className = isFull ? 'fa-solid fa-compress' : 'fa-solid fa-expand';
        }
        if (text) {
            text.textContent = isFull ? 'ย่อจอ' : 'เต็มจอ';
        }
    }
}

document.addEventListener('fullscreenchange', function() {
    const elem = document.getElementById('pgdm-app');
    const icon = document.getElementById('pgdm-fullscreen-icon');
    const text = document.getElementById('pgdm-fullscreen-text');
    const isFull = !!document.fullscreenElement;
    if (icon) icon.className = isFull ? 'fa-solid fa-compress' : 'fa-solid fa-expand';
    if (text) text.textContent = isFull ? 'ย่อจอ' : 'เต็มจอ';
    if (!isFull && elem) elem.classList.remove('pgdm-fallback-fullscreen');
    setTimeout(() => {
        if (window.PGDM && PGDM.charts) {
            Object.values(PGDM.charts).forEach(c => c && c.resize());
        }
    }, 150);
});

function pgdmToggleCardFullscreen(cardId) {
    const card = document.getElementById(cardId);
    if (!card) return;
    card.classList.toggle('pgdm-card-maximized');
    const isMax = card.classList.contains('pgdm-card-maximized');
    const btn = card.querySelector('.pgdm-card-max-btn i');
    if (btn) {
        btn.className = isMax ? 'fa-solid fa-compress' : 'fa-solid fa-maximize';
    }
    setTimeout(() => {
        if (window.PGDM && PGDM.charts) {
            Object.values(PGDM.charts).forEach(c => c && c.resize());
        }
    }, 150);
}

document.addEventListener('DOMContentLoaded', function() {
    PGDM.init();
});
</script>

<?php require 'includes/footer.php'; ?>
