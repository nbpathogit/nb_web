<?php
/**
 * Patient Global Dashboard
 * File: patient_global_dashboard.php
 * Path: C:\anuchit2\nb_web\dev3\patient_global_dashboard.php
 *
 * Description:
 * Executive analytics dashboard for NB Patho providing:
 * 1. Weekly/Monthly Turn Around Time (TAT) with multi-dimensional breakdowns
 * 2. Weekly/Monthly Incoming Case Volume
 * 3. Weekly/Monthly Reported/Released Case Volume
 * 4. Pathologist Caseload Distribution (1 bar per pathologist)
 *
 * All functions are isolated and standalone with the `pgd_` prefix.
 */

require 'includes/init.php';
$conn = require 'includes/db.php';
Auth::requireLogin();
require 'user_auth.php';

// ============================================================================
// DEDICATED DASHBOARD BACKEND MODULE (Keeps patient.php completely untouched)
// ============================================================================
require_once 'classes/Patientdash.php';

if (!function_exists('pgd_get_available_years')) {
    /**
     * Fetch distinct years available in the patient database (with optional hospital filter)
     */
    function pgd_get_available_years($conn, $filter_hospital_id = null) {
        try {
            $params = [];
            $hosp_clause = "";
            if (!empty($filter_hospital_id)) {
                $hosp_clause = " AND phospital_id = :filter_hospital_id";
                $params[':filter_hospital_id'] = intval($filter_hospital_id);
            }
            $sql = "SELECT DISTINCT YEAR(date_1000) AS yr 
                    FROM patient 
                    WHERE date_1000 IS NOT NULL 
                      AND (movetotrash = 0 OR movetotrash IS NULL)
                      $hosp_clause
                    ORDER BY yr DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $years = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return !empty($years) ? $years : [date('Y')];
        } catch (Exception $e) {
            return [date('Y')];
        }
    }
}

if (!function_exists('pgd_generate_period_buckets')) {
    /**
     * Generate period buckets (Weeks or Months) with boundaries and Thai labels
     */
    function pgd_generate_period_buckets($time_unit, $period_count, $year_scope) {
        $buckets = [];
        $thai_months = [
            1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.',
            5 => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.',
            9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
        ];

        $now = new DateTime('now');

        if ($year_scope === 'rolling' || empty($year_scope)) {
            if ($time_unit === 'month') {
                for ($i = $period_count - 1; $i >= 0; $i--) {
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
            } else {
                // Weeks: ending at the end of current week (Sunday)
                $end_of_current_week = (clone $now)->modify('Sunday this week');
                for ($i = $period_count - 1; $i >= 0; $i--) {
                    $w_sunday = (clone $end_of_current_week)->modify("-$i week");
                    $w_monday = (clone $w_sunday)->modify('-6 days');
                    $key = $w_monday->format('o-\WW');
                    $label = 'W' . $w_monday->format('W') . ' (' . $w_monday->format('j ') . $thai_months[intval($w_monday->format('n'))] . ' - ' . $w_sunday->format('j ') . $thai_months[intval($w_sunday->format('n'))] . ')';
                    $start = $w_monday->format('Y-m-d 00:00:00');
                    $end = $w_sunday->format('Y-m-d 23:59:59');
                    $buckets[] = [
                        'key' => $key,
                        'label' => $label,
                        'start' => $start,
                        'end' => $end
                    ];
                }
            }
        } else {
            // Scoped to a specific calendar year (e.g. 2026, 2025)
            $yr = intval($year_scope);
            if ($time_unit === 'month') {
                $num_months = ($period_count == 6) ? 6 : 12;
                $start_m = ($num_months == 6) ? 7 : 1;
                for ($m = $start_m; $m <= 12; $m++) {
                    $dt = new DateTime(sprintf('%04d-%02d-01', $yr, $m));
                    $key = $dt->format('Y-m');
                    $label = $thai_months[$m] . ' ' . ($yr + 543);
                    $start = $dt->format('Y-m-01 00:00:00');
                    $end = (clone $dt)->modify('last day of this month')->format('Y-m-d 23:59:59');
                    $buckets[] = [
                        'key' => $key,
                        'label' => $label,
                        'start' => $start,
                        'end' => $end
                    ];
                }
            } else {
                // Weeks within the specified year
                $dec31 = new DateTime("$yr-12-31");
                $end_week = (clone $dec31)->modify('Sunday this week');
                if ($yr == intval($now->format('Y'))) {
                    $end_week = (clone $now)->modify('Sunday this week');
                }
                for ($i = $period_count - 1; $i >= 0; $i--) {
                    $w_sunday = (clone $end_week)->modify("-$i week");
                    $w_monday = (clone $w_sunday)->modify('-6 days');
                    $key = $w_monday->format('o-\WW');
                    $label = 'W' . $w_monday->format('W') . ' (' . $w_monday->format('j ') . $thai_months[intval($w_monday->format('n'))] . ' - ' . $w_sunday->format('j ') . $thai_months[intval($w_sunday->format('n'))] . ')';
                    $start = $w_monday->format('Y-m-d 00:00:00');
                    $end = $w_sunday->format('Y-m-d 23:59:59');
                    $buckets[] = [
                        'key' => $key,
                        'label' => $label,
                        'start' => $start,
                        'end' => $end
                    ];
                }
            }
        }

        return $buckets;
    }
}

if (!function_exists('pgd_fetch_patient_records')) {
    /**
     * Fetch patient records for the dashboard using user-specified query structure
     */
    function pgd_fetch_patient_records($conn, $start_date, $end_date, $filter_hospital_id = null) {
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

// ============================================================================
// REQUEST PARAMETERS & DATA RETRIEVAL
// ============================================================================

// Check hospital scope: NB Pathology vs Customer Hospital
$scoped_hospital_id = pgd_get_user_hospital_filter($conn);
$scoped_hospital_name = null;
if (!empty($scoped_hospital_id)) {
    $hstmt = $conn->prepare("SELECT hospital FROM hospital WHERE id = :id LIMIT 1");
    $hstmt->execute([':id' => $scoped_hospital_id]);
    $scoped_hospital_name = $hstmt->fetchColumn() ?: "โรงพยาบาลของท่าน";
}

$available_years = pgd_get_available_years($conn, $scoped_hospital_id);

$time_unit = isset($_GET['time_unit']) && in_array($_GET['time_unit'], ['weeks', 'month']) ? $_GET['time_unit'] : 'weeks';
$year_scope = isset($_GET['year_scope']) ? trim($_GET['year_scope']) : 'rolling';

if ($time_unit === 'weeks') {
    $allowed_periods = [6, 12, 24, 32];
    $period_count = isset($_GET['period_count']) ? intval($_GET['period_count']) : 6;
    if (!in_array($period_count, $allowed_periods)) {
        $period_count = 6;
    }
} else {
    $allowed_periods = [6, 12];
    $period_count = isset($_GET['period_count']) ? intval($_GET['period_count']) : 6;
    if (!in_array($period_count, $allowed_periods)) {
        $period_count = 6;
    }
}

// Generate Period Buckets
$period_buckets = pgd_generate_period_buckets($time_unit, $period_count, $year_scope);

$overall_start = !empty($period_buckets) ? $period_buckets[0]['start'] : date('Y-m-d 00:00:00', strtotime('-6 weeks'));
$overall_end = !empty($period_buckets) ? $period_buckets[count($period_buckets) - 1]['end'] : date('Y-m-d 23:59:59');

// Fetch patient records (filtered by hospital if customer)
$patient_records = pgd_fetch_patient_records($conn, $overall_start, $overall_end, $scoped_hospital_id);

// Package data payload for JavaScript
$payload_data = [
    'time_unit' => $time_unit,
    'period_count' => $period_count,
    'year_scope' => $year_scope,
    'scoped_hospital_id' => $scoped_hospital_id,
    'scoped_hospital_name' => $scoped_hospital_name,
    'is_hospital_scoped' => !empty($scoped_hospital_id),
    'buckets' => $period_buckets,
    'records_count' => count($patient_records),
    'records' => $patient_records
];

require 'includes/header.php';
?>

<!-- ==========================================================================
     PAGE CSS STYLING: Premium Modern Dark/Light Dual Theme
     ========================================================================== -->
<style>
/* CSS Custom Variables for Theme */
:root {
    --pgd-bg: #090d16;
    --pgd-surface: #111827;
    --pgd-surface-elevated: #1f2937;
    --pgd-surface-card: #151d30;
    --pgd-border: rgba(255, 255, 255, 0.08);
    --pgd-border-highlight: rgba(99, 102, 241, 0.3);
    --pgd-text-main: #f8fafc;
    --pgd-text-muted: #94a3b8;
    --pgd-primary: #6366f1;
    --pgd-primary-hover: #4f46e5;
    --pgd-accent-cyan: #06b6d4;
    --pgd-accent-emerald: #10b981;
    --pgd-accent-amber: #f59e0b;
    --pgd-accent-rose: #f43f5e;
    --pgd-accent-purple: #a855f7;
    --pgd-kpi-bg: rgba(255, 255, 255, 0.03);
    --pgd-table-header-bg: #1e293b;
    --pgd-table-row-hover: rgba(255, 255, 255, 0.04);
    --pgd-table-row-stripe: rgba(255, 255, 255, 0.02);
}

body.pgd-theme-light {
    --pgd-bg: #f8fafc;
    --pgd-surface: #ffffff;
    --pgd-surface-elevated: #f1f5f9;
    --pgd-surface-card: #ffffff;
    --pgd-border: #e2e8f0;
    --pgd-border-highlight: rgba(99, 102, 241, 0.4);
    --pgd-text-main: #0f172a;
    --pgd-text-muted: #64748b;
    --pgd-primary: #4f46e5;
    --pgd-primary-hover: #4338ca;
    --pgd-kpi-bg: #f8fafc;
    --pgd-table-header-bg: #f1f5f9;
    --pgd-table-row-hover: #f8fafc;
    --pgd-table-row-stripe: #ffffff;
}

/* Dashboard App Wrapper */
#pgd-app {
    background-color: var(--pgd-bg);
    color: var(--pgd-text-main);
    min-height: 100vh;
    padding: 1.5rem 1rem 3rem 1rem;
    font-family: 'Heebo', system-ui, -apple-system, sans-serif;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* Top Banner Header */
.pgd-header-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.95) 100%);
    border: 1px solid var(--pgd-border);
    border-radius: 1rem;
    padding: 1.5rem 2rem;
    backdrop-filter: blur(12px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
    margin-bottom: 1.5rem;
}

body.pgd-theme-light .pgd-header-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
}

.pgd-header-title {
    font-size: 1.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--pgd-text-main);
    margin: 0;
}

.pgd-header-subtitle {
    color: var(--pgd-text-muted);
    font-size: 0.95rem;
    margin-top: 0.25rem;
}

/* Filter Controls Bar */
.pgd-filter-bar {
    background: var(--pgd-surface);
    border: 1px solid var(--pgd-border);
    border-radius: 0.875rem;
    padding: 1rem 1.25rem;
    margin-bottom: 2rem;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.pgd-filter-group {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.pgd-filter-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--pgd-text-muted);
    margin: 0;
}

.pgd-select, .pgd-input {
    background: var(--pgd-surface-elevated);
    border: 1px solid var(--pgd-border);
    color: var(--pgd-text-main);
    border-radius: 0.5rem;
    padding: 0.45rem 0.85rem;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.pgd-select:focus, .pgd-input:focus {
    border-color: var(--pgd-primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
}

.pgd-btn {
    border-radius: 0.5rem;
    padding: 0.45rem 1rem;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.pgd-btn-primary {
    background-color: var(--pgd-primary);
    color: #ffffff;
}

.pgd-btn-primary:hover {
    background-color: var(--pgd-primary-hover);
    color: #ffffff;
}

.pgd-btn-theme-toggle {
    background: var(--pgd-surface-elevated);
    border: 1px solid var(--pgd-border);
    color: var(--pgd-text-main);
}

.pgd-btn-theme-toggle:hover {
    border-color: var(--pgd-primary);
    color: var(--pgd-primary);
}

/* Radio Pill Selector for Time Unit */
.pgd-pill-nav {
    display: inline-flex;
    background: var(--pgd-surface-elevated);
    border: 1px solid var(--pgd-border);
    border-radius: 0.5rem;
    padding: 0.25rem;
}

.pgd-pill-btn {
    padding: 0.35rem 0.85rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--pgd-text-muted);
    background: transparent;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.pgd-pill-btn.active {
    background: var(--pgd-primary);
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(99, 102, 241, 0.4);
}

/* Dashboard Chart Card */
.pgd-card {
    background: var(--pgd-surface-card);
    border: 1px solid var(--pgd-border);
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.15);
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}

body.pgd-theme-light .pgd-card {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.pgd-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--pgd-border);
    margin-bottom: 1.25rem;
}

.pgd-card-title-box {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.pgd-card-icon {
    width: 42px;
    height: 42px;
    border-radius: 0.625rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.pgd-icon-cyan {
    background: rgba(6, 182, 212, 0.15);
    color: var(--pgd-accent-cyan);
    border: 1px solid rgba(6, 182, 212, 0.3);
}

.pgd-icon-emerald {
    background: rgba(16, 185, 129, 0.15);
    color: var(--pgd-accent-emerald);
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.pgd-icon-amber {
    background: rgba(245, 158, 11, 0.15);
    color: var(--pgd-accent-amber);
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.pgd-icon-purple {
    background: rgba(168, 85, 247, 0.15);
    color: var(--pgd-accent-purple);
    border: 1px solid rgba(168, 85, 247, 0.3);
}

.pgd-card-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--pgd-text-main);
    margin: 0;
}

.pgd-card-desc {
    font-size: 0.825rem;
    color: var(--pgd-text-muted);
    margin: 0;
}

/* KPI Stat Badges Container */
.pgd-kpi-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 0.85rem;
    margin-bottom: 1.5rem;
}

.pgd-kpi-item {
    background: var(--pgd-kpi-bg);
    border: 1px solid var(--pgd-border);
    border-radius: 0.75rem;
    padding: 0.85rem 1rem;
    transition: all 0.2s ease;
}

.pgd-kpi-item:hover {
    border-color: var(--pgd-border-highlight);
    transform: translateY(-2px);
}

.pgd-kpi-title {
    font-size: 0.78rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--pgd-text-muted);
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.pgd-kpi-value {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--pgd-text-main);
    line-height: 1.2;
}

.pgd-kpi-sub {
    font-size: 0.75rem;
    color: var(--pgd-text-muted);
    margin-top: 0.2rem;
}

/* Chart Canvas Container */
.pgd-chart-container {
    position: relative;
    width: 100%;
    height: 380px;
    margin-bottom: 1.5rem;
}

/* Collapsible Table Section */
.pgd-table-toggle-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 0.75rem;
    border-top: 1px dashed var(--pgd-border);
    margin-top: 1rem;
}

.pgd-table-card {
    background: var(--pgd-surface-elevated);
    border: 1px solid var(--pgd-border);
    border-radius: 0.75rem;
    padding: 1rem;
    margin-top: 1rem;
}

.pgd-table-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
    gap: 1rem;
    flex-wrap: wrap;
}

.pgd-table-wrapper {
    overflow-x: auto;
    max-height: 420px;
    border-radius: 0.5rem;
    border: 1px solid var(--pgd-border);
}

.pgd-data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.875rem;
    color: var(--pgd-text-main);
}

.pgd-data-table th {
    background: var(--pgd-table-header-bg);
    color: var(--pgd-text-main);
    font-weight: 600;
    padding: 0.75rem 1rem;
    border-bottom: 2px solid var(--pgd-border);
    position: sticky;
    top: 0;
    z-index: 2;
    text-align: left;
    white-space: nowrap;
}

.pgd-data-table td {
    padding: 0.65rem 1rem;
    border-bottom: 1px solid var(--pgd-border);
    white-space: nowrap;
}

.pgd-data-table tbody tr:hover {
    background-color: var(--pgd-table-row-hover);
}

.pgd-badge {
    padding: 0.25rem 0.6rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
}

.pgd-badge-blue { background: rgba(99, 102, 241, 0.15); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.3); }
.pgd-badge-green { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
.pgd-badge-yellow { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }

/* Quick Highlight Indicators */
.pgd-indicator-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 6px;
}

/* Fullscreen styles for Dashboard container */
#pgd-app:fullscreen,
#pgd-app:-webkit-full-screen {
    width: 100vw !important;
    height: 100vh !important;
    overflow-y: auto !important;
    padding: 1.5rem 1.5rem 3rem 1.5rem !important;
    background: var(--pgd-bg) !important;
}

#pgd-app.pgd-fallback-fullscreen {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 99999 !important;
    overflow-y: auto !important;
    padding: 1.5rem 1.5rem 3rem 1.5rem !important;
    background: var(--pgd-bg) !important;
}

/* Card Maximize Overlay */
.pgd-card.pgd-card-maximized {
    position: fixed !important;
    top: 15px !important;
    left: 15px !important;
    right: 15px !important;
    bottom: 15px !important;
    z-index: 100000 !important;
    overflow-y: auto !important;
    box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.75), 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
}
.pgd-card.pgd-card-maximized .pgd-chart-container {
    height: 60vh !important;
}
.pgd-btn-sm {
    padding: 0.3rem 0.6rem !important;
    font-size: 0.8rem !important;
}
</style>

<!-- ==========================================================================
     PAGE MARKUP & HTML STRUCTURE
     ========================================================================== -->
<div id="pgd-app" class="pgd-theme-dark">
    <div class="container-fluid px-md-4">

        <!-- HEADER BANNER -->
        <div class="pgd-header-card">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h1 class="pgd-header-title">
                        <i class="fa-solid fa-chart-line text-primary"></i>
                        Patient Global Analytics Dashboard
                    </h1>
                    <div class="pgd-header-subtitle">
                        แดชบอร์ดติดตามประสิทธิภาพ: Turn Around Time, ปริมาณเคสรับเข้า, ปริมาณการออกผล และสถิติงานพยาธิแพทย์
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
                    <a href="patient_global_dashboard_main.php" class="pgd-btn pgd-btn-primary" title="เปิดหน้าแดชบอร์ดหลัก 1 ปีย้อนหลัง">
                        <i class="fa-solid fa-gauge-high"></i> แดชบอร์ดหลัก (Main 1-Year)
                    </a>
                    <!-- FULLSCREEN BUTTON -->
                    <button id="pgd-fullscreen-btn" class="pgd-btn pgd-btn-theme-toggle" type="button" title="สลับเต็มหน้าจอ (Fullscreen)" onclick="pgdToggleFullscreen()">
                        <i id="pgd-fullscreen-icon" class="fa-solid fa-expand"></i>
                        <span id="pgd-fullscreen-text" class="d-none d-sm-inline">เต็มจอ</span>
                    </button>
                    <button id="pgd-theme-toggle-btn" class="pgd-btn pgd-btn-theme-toggle" type="button" title="สลับธีม">
                        <i id="pgd-theme-icon" class="fa-solid fa-moon"></i>
                        <span id="pgd-theme-text">โหมดมืด (Dark)</span>
                    </button>
                    <a href="patient.php" class="pgd-btn pgd-btn-theme-toggle" title="กลับหน้ารายชื่อผู้ป่วย">
                        <i class="fa-solid fa-bed"></i> ข้อมูลผู้รักษา
                    </a>
                </div>
            </div>
        </div>

        <!-- FILTER CONTROLS BAR -->
        <form id="pgd-filter-form" method="GET" action="patient_global_dashboard.php">
            <div class="pgd-filter-bar">
                
                <!-- Time Unit Switcher -->
                <div class="pgd-filter-group">
                    <label class="pgd-filter-label"><i class="fa-regular fa-clock me-1"></i> หน่วยเวลา:</label>
                    <div class="pgd-pill-nav">
                        <button type="button" class="pgd-pill-btn <?= $time_unit === 'weeks' ? 'active' : '' ?>" onclick="pgdSetTimeUnit('weeks')">
                            <i class="fa-solid fa-calendar-week me-1"></i> รายสัปดาห์ (Weeks)
                        </button>
                        <button type="button" class="pgd-pill-btn <?= $time_unit === 'month' ? 'active' : '' ?>" onclick="pgdSetTimeUnit('month')">
                            <i class="fa-solid fa-calendar-days me-1"></i> รายเดือน (Month)
                        </button>
                    </div>
                    <input type="hidden" name="time_unit" id="pgd_input_time_unit" value="<?= htmlspecialchars($time_unit) ?>">
                </div>

                <!-- Period Length Dropdown -->
                <div class="pgd-filter-group">
                    <label class="pgd-filter-label" for="pgd_select_period"><i class="fa-solid fa-arrows-split-up-and-left me-1"></i> ช่วงเวลาย้อนหลัง:</label>
                    <select name="period_count" id="pgd_select_period" class="pgd-select">
                        <?php if ($time_unit === 'weeks'): ?>
                            <option value="6" <?= $period_count == 6 ? 'selected' : '' ?>>6 สัปดาห์ (Default)</option>
                            <option value="12" <?= $period_count == 12 ? 'selected' : '' ?>>12 สัปดาห์</option>
                            <option value="24" <?= $period_count == 24 ? 'selected' : '' ?>>24 สัปดาห์</option>
                            <option value="32" <?= $period_count == 32 ? 'selected' : '' ?>>32 สัปดาห์</option>
                        <?php else: ?>
                            <option value="6" <?= $period_count == 6 ? 'selected' : '' ?>>6 เดือน (Default)</option>
                            <option value="12" <?= $period_count == 12 ? 'selected' : '' ?>>12 เดือน</option>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Scope / Calendar Year Dropdown -->
                <div class="pgd-filter-group">
                    <label class="pgd-filter-label" for="pgd_select_year"><i class="fa-solid fa-calendar me-1"></i> ขอบเขตปี:</label>
                    <select name="year_scope" id="pgd_select_year" class="pgd-select">
                        <option value="rolling" <?= $year_scope === 'rolling' ? 'selected' : '' ?>>
                            ช่วงเวลาย้อนหลัง (Rolling ล่าสุด)
                        </option>
                        <?php foreach ($available_years as $yr): ?>
                            <option value="<?= $yr ?>" <?= $year_scope === strval($yr) ? 'selected' : '' ?>>
                                ประจำปี <?= $yr ?> (พ.ศ. <?= $yr + 543 ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Submit / Refresh Button -->
                <div class="pgd-filter-group">
                    <button type="submit" class="pgd-btn pgd-btn-primary">
                        <i class="fa-solid fa-filter"></i> นำไปใช้ (Apply)
                    </button>
                    <a href="patient_global_dashboard.php" class="pgd-btn pgd-btn-theme-toggle" title="รีเซ็ตค่าเริ่มต้น">
                        <i class="fa-solid fa-rotate-left"></i> รีเซ็ต
                    </a>
                </div>

            </div>
        </form>

        <!-- ======================================================================
             CHART 1: TURN AROUND TIME (TAT)
             ====================================================================== -->
        <div class="pgd-card" id="pgd-card-tat">
            <div class="pgd-card-header">
                <div class="pgd-card-title-box">
                    <div class="pgd-card-icon pgd-icon-cyan">
                        <i class="fa-solid fa-stopwatch"></i>
                    </div>
                    <div>
                        <h3 class="pgd-card-title">Turn Around Time (ระยะเวลาออกผล)</h3>
                        <p class="pgd-card-desc">
                            ค่าเฉลี่ยระยะเวลาการออกผล (วัน) ต่อหนึ่ง<?= $time_unit === 'weeks' ? 'สัปดาห์' : 'เดือน' ?> จากวันรับเข้าถึงวันออกผลครั้งแรก
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="pgd-filter-label"><i class="fa-solid fa-palette me-1"></i> ใส่สีตาม:</label>
                    <select id="pgd_dim_tat" class="pgd-select" onchange="PGD.updateTatChart()">
                        <option value="none">ไม่แยกสี (สีเดียว Default)</option>
                        <option value="name_patho">ชื่อพยาธิผู้ออกผล</option>
                        <?php if (empty($scoped_hospital_id)): ?>
                        <option value="h_hospitial">โรงพยาบาล</option>
                        <?php endif; ?>
                        <option value="u_clinician">แพทย์ผู้ส่ง</option>
                        <option value="p_create_by">คนรับเข้า</option>
                    </select>
                    <button type="button" class="pgd-btn pgd-btn-theme-toggle pgd-btn-sm pgd-card-max-btn" title="ขยายกราฟนี้เต็มจอ" onclick="pgdToggleCardFullscreen('pgd-card-tat')">
                        <i class="fa-solid fa-maximize"></i>
                    </button>
                </div>
            </div>

            <!-- KPI Row -->
            <div class="pgd-kpi-row" id="pgd-kpi-tat">
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-chart-line text-info"></i> ค่าเฉลี่ย (Mean &mu;)</div>
                    <div class="pgd-kpi-value" id="kpi-tat-mean">-</div>
                    <div class="pgd-kpi-sub">วันทำการเฉลี่ย</div>
                </div>
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-chart-area text-warning"></i> เบี่ยงเบน Std Dev (&sigma;)</div>
                    <div class="pgd-kpi-value" id="kpi-tat-std">-</div>
                    <div class="pgd-kpi-sub">ความผันแปรของ TAT</div>
                </div>
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-arrow-down text-success"></i> ค่าต่ำสุด (Min)</div>
                    <div class="pgd-kpi-value" id="kpi-tat-min">-</div>
                    <div class="pgd-kpi-sub">TAT เร็วที่สุด</div>
                </div>
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-arrow-up text-danger"></i> ค่าสูงสุด (Max)</div>
                    <div class="pgd-kpi-value" id="kpi-tat-max">-</div>
                    <div class="pgd-kpi-sub">TAT ช้าที่สุด</div>
                </div>
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-check-double text-primary"></i> จำนวนเคสที่มีผล</div>
                    <div class="pgd-kpi-value" id="kpi-tat-cases">-</div>
                    <div class="pgd-kpi-sub">เคสออกผลแล้วทั้งหมด</div>
                </div>
            </div>

            <!-- Chart Canvas -->
            <div class="pgd-chart-container">
                <canvas id="chart-canvas-tat"></canvas>
            </div>

            <!-- Table Toggle Button & Collapsible Table -->
            <div class="pgd-table-toggle-wrapper">
                <button class="pgd-btn pgd-btn-theme-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#pgd-collapse-tat" aria-expanded="false">
                    <i class="fa-solid fa-table me-1"></i> ตารางสรุปข้อมูล Combination Breakdown (คลิกแสดง/ซ่อน)
                </button>
                <button class="pgd-btn pgd-btn-theme-toggle" type="button" onclick="PGD.exportTableToCSV('table-tat-data', 'TAT_Breakdown.csv')">
                    <i class="fa-solid fa-file-csv me-1"></i> ส่งออก CSV
                </button>
            </div>

            <div class="collapse" id="pgd-collapse-tat">
                <div class="pgd-table-card">
                    <div class="pgd-table-controls">
                        <span class="fw-bold"><i class="fa-solid fa-list-check me-1"></i> รายละเอียดข้อมูล Turn Around Time</span>
                        <input type="text" class="pgd-input" placeholder="ค้นหาในตาราง..." onkeyup="PGD.filterTable('table-tat-data', this.value)">
                    </div>
                    <div class="pgd-table-wrapper">
                        <table class="pgd-data-table" id="table-tat-data">
                            <thead>
                                <tr>
                                    <th>ช่วงเวลา</th>
                                    <th>หมวดหมู่ / มิติข้อมูล</th>
                                    <th>จำนวนเคส</th>
                                    <th>TAT เฉลี่ย (วัน)</th>
                                    <th>TAT ต่ำสุด</th>
                                    <th>TAT สูงสุด</th>
                                    <th>ส่วนเบี่ยงเบน (&sigma;)</th>
                                </tr>
                            </thead>
                            <tbody><!-- Dynamic --></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- ======================================================================
             CHART 2: INCOMING CASES (เคสรับเข้า)
             ====================================================================== -->
        <div class="pgd-card" id="pgd-card-incoming">
            <div class="pgd-card-header">
                <div class="pgd-card-title-box">
                    <div class="pgd-card-icon pgd-icon-emerald">
                        <i class="fa-solid fa-inbox"></i>
                    </div>
                    <div>
                        <h3 class="pgd-card-title">จำนวนเคสรับเข้า (Incoming Cases)</h3>
                        <p class="pgd-card-desc">
                            จำนวนเคสที่บันทึกรับเข้า (date_1000) ต่อหนึ่ง<?= $time_unit === 'weeks' ? 'สัปดาห์' : 'เดือน' ?>
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="pgd-filter-label"><i class="fa-solid fa-palette me-1"></i> ใส่สีตาม:</label>
                    <select id="pgd_dim_incoming" class="pgd-select" onchange="PGD.updateIncomingChart()">
                        <option value="none">ไม่แยกสี (สีเดียว Default)</option>
                        <option value="name_patho">ชื่อพยาธิผู้ออกผล</option>
                        <?php if (empty($scoped_hospital_id)): ?>
                        <option value="h_hospitial">โรงพยาบาล</option>
                        <?php endif; ?>
                        <option value="u_clinician">แพทย์ผู้ส่ง</option>
                        <option value="p_create_by">คนรับเข้า</option>
                    </select>
                    <button type="button" class="pgd-btn pgd-btn-theme-toggle pgd-btn-sm pgd-card-max-btn" title="ขยายกราฟนี้เต็มจอ" onclick="pgdToggleCardFullscreen('pgd-card-incoming')">
                        <i class="fa-solid fa-maximize"></i>
                    </button>
                </div>
            </div>

            <!-- KPI Row -->
            <div class="pgd-kpi-row" id="pgd-kpi-incoming">
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-boxes-stacked text-success"></i> ยอดรับเข้ารวม</div>
                    <div class="pgd-kpi-value" id="kpi-in-total">-</div>
                    <div class="pgd-kpi-sub">เคสทั้งหมดในช่วงเวลา</div>
                </div>
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-chart-line text-info"></i> เฉลี่ยต่อช่วง (Mean &mu;)</div>
                    <div class="pgd-kpi-value" id="kpi-in-mean">-</div>
                    <div class="pgd-kpi-sub">เคสต่อ<?= $time_unit === 'weeks' ? 'สัปดาห์' : 'เดือน' ?></div>
                </div>
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-chart-area text-warning"></i> เบี่ยงเบน Std Dev (&sigma;)</div>
                    <div class="pgd-kpi-value" id="kpi-in-std">-</div>
                    <div class="pgd-kpi-sub">ความแปรผันของยอดเข้า</div>
                </div>
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-fire text-danger"></i> ช่วงเวลารับเข้าสูงสุด</div>
                    <div class="pgd-kpi-value" id="kpi-in-peak">-</div>
                    <div class="pgd-kpi-sub" id="kpi-in-peak-sub">-</div>
                </div>
            </div>

            <!-- Chart Canvas -->
            <div class="pgd-chart-container">
                <canvas id="chart-canvas-incoming"></canvas>
            </div>

            <!-- Table Toggle Button & Collapsible Table -->
            <div class="pgd-table-toggle-wrapper">
                <button class="pgd-btn pgd-btn-theme-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#pgd-collapse-incoming" aria-expanded="false">
                    <i class="fa-solid fa-table me-1"></i> ตารางสรุปข้อมูล Combination Breakdown (คลิกแสดง/ซ่อน)
                </button>
                <button class="pgd-btn pgd-btn-theme-toggle" type="button" onclick="PGD.exportTableToCSV('table-incoming-data', 'Incoming_Breakdown.csv')">
                    <i class="fa-solid fa-file-csv me-1"></i> ส่งออก CSV
                </button>
            </div>

            <div class="collapse" id="pgd-collapse-incoming">
                <div class="pgd-table-card">
                    <div class="pgd-table-controls">
                        <span class="fw-bold"><i class="fa-solid fa-list-check me-1"></i> รายละเอียดเคสรับเข้า</span>
                        <input type="text" class="pgd-input" placeholder="ค้นหาในตาราง..." onkeyup="PGD.filterTable('table-incoming-data', this.value)">
                    </div>
                    <div class="pgd-table-wrapper">
                        <table class="pgd-data-table" id="table-incoming-data">
                            <thead>
                                <tr>
                                    <th>ช่วงเวลา</th>
                                    <th>หมวดหมู่ / มิติข้อมูล</th>
                                    <th>จำนวนเคสรับเข้า</th>
                                    <th>สัดส่วนในรอบนั้น (% of Period)</th>
                                    <th>สัดส่วนจากยอดรวม (% of Total)</th>
                                </tr>
                            </thead>
                            <tbody><!-- Dynamic --></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- ======================================================================
             CHART 3: REPORTED CASES (จำนวนการออกผล)
             ====================================================================== -->
        <div class="pgd-card" id="pgd-card-reported">
            <div class="pgd-card-header">
                <div class="pgd-card-title-box">
                    <div class="pgd-card-icon pgd-icon-amber">
                        <i class="fa-solid fa-file-circle-check"></i>
                    </div>
                    <div>
                        <h3 class="pgd-card-title">จำนวนการออกผล (Reported Cases)</h3>
                        <p class="pgd-card-desc">
                            จำนวนเคสที่ออกผลการตรวจ (date_first_report) ต่อหนึ่ง<?= $time_unit === 'weeks' ? 'สัปดาห์' : 'เดือน' ?>
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="pgd-filter-label"><i class="fa-solid fa-palette me-1"></i> ใส่สีตาม:</label>
                    <select id="pgd_dim_reported" class="pgd-select" onchange="PGD.updateReportedChart()">
                        <option value="none">ไม่แยกสี (สีเดียว Default)</option>
                        <option value="name_patho">ชื่อพยาธิผู้ออกผล</option>
                        <?php if (empty($scoped_hospital_id)): ?>
                        <option value="h_hospitial">โรงพยาบาล</option>
                        <?php endif; ?>
                        <option value="u_clinician">แพทย์ผู้ส่ง</option>
                        <option value="p_create_by">คนรับเข้า</option>
                    </select>
                    <button type="button" class="pgd-btn pgd-btn-theme-toggle pgd-btn-sm pgd-card-max-btn" title="ขยายกราฟนี้เต็มจอ" onclick="pgdToggleCardFullscreen('pgd-card-reported')">
                        <i class="fa-solid fa-maximize"></i>
                    </button>
                </div>
            </div>

            <!-- KPI Row -->
            <div class="pgd-kpi-row" id="pgd-kpi-reported">
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-clipboard-check text-warning"></i> ยอดออกผลรวม</div>
                    <div class="pgd-kpi-value" id="kpi-rep-total">-</div>
                    <div class="pgd-kpi-sub">เคสทั้งหมดที่รายงานผล</div>
                </div>
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-chart-line text-info"></i> เฉลี่ยต่อช่วง (Mean &mu;)</div>
                    <div class="pgd-kpi-value" id="kpi-rep-mean">-</div>
                    <div class="pgd-kpi-sub">เคสต่อ<?= $time_unit === 'weeks' ? 'สัปดาห์' : 'เดือน' ?></div>
                </div>
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-chart-area text-warning"></i> เบี่ยงเบน Std Dev (&sigma;)</div>
                    <div class="pgd-kpi-value" id="kpi-rep-std">-</div>
                    <div class="pgd-kpi-sub">ความแปรผันการออกผล</div>
                </div>
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-bolt text-danger"></i> ช่วงเวลาออกผลสูงสุด</div>
                    <div class="pgd-kpi-value" id="kpi-rep-peak">-</div>
                    <div class="pgd-kpi-sub" id="kpi-rep-peak-sub">-</div>
                </div>
            </div>

            <!-- Chart Canvas -->
            <div class="pgd-chart-container">
                <canvas id="chart-canvas-reported"></canvas>
            </div>

            <!-- Table Toggle Button & Collapsible Table -->
            <div class="pgd-table-toggle-wrapper">
                <button class="pgd-btn pgd-btn-theme-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#pgd-collapse-reported" aria-expanded="false">
                    <i class="fa-solid fa-table me-1"></i> ตารางสรุปข้อมูล Combination Breakdown (คลิกแสดง/ซ่อน)
                </button>
                <button class="pgd-btn pgd-btn-theme-toggle" type="button" onclick="PGD.exportTableToCSV('table-reported-data', 'Reported_Breakdown.csv')">
                    <i class="fa-solid fa-file-csv me-1"></i> ส่งออก CSV
                </button>
            </div>

            <div class="collapse" id="pgd-collapse-reported">
                <div class="pgd-table-card">
                    <div class="pgd-table-controls">
                        <span class="fw-bold"><i class="fa-solid fa-list-check me-1"></i> รายละเอียดเคสที่ออกผล</span>
                        <input type="text" class="pgd-input" placeholder="ค้นหาในตาราง..." onkeyup="PGD.filterTable('table-reported-data', this.value)">
                    </div>
                    <div class="pgd-table-wrapper">
                        <table class="pgd-data-table" id="table-reported-data">
                            <thead>
                                <tr>
                                    <th>ช่วงเวลา</th>
                                    <th>หมวดหมู่ / มิติข้อมูล</th>
                                    <th>จำนวนเคสออกผล</th>
                                    <th>สัดส่วนในรอบนั้น (% of Period)</th>
                                    <th>สัดส่วนจากยอดรวม (% of Total)</th>
                                </tr>
                            </thead>
                            <tbody><!-- Dynamic --></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- ======================================================================
             CHART 4: PATHOLOGIST CASELOAD (การออกผลของพยาธิแต่ละท่าน)
             ====================================================================== -->
        <div class="pgd-card" id="pgd-card-patho">
            <div class="pgd-card-header">
                <div class="pgd-card-title-box">
                    <div class="pgd-card-icon pgd-icon-purple">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <div>
                        <h3 class="pgd-card-title">จำนวนการออกผล ของ พยาธิแต่ละท่าน</h3>
                        <p class="pgd-card-desc">
                            เปรียบเทียบภาระงานการออกผลตรวจพยาธิวิทยา หนึ่งท่านหนึ่งแท่ง เรียงลำดับตามปริมาณเคส
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="pgd-badge pgd-badge-blue">
                        <i class="fa-solid fa-sliders me-1"></i> เรียงตามจำนวนเคส (มากไปน้อย)
                    </span>
                    <button type="button" class="pgd-btn pgd-btn-theme-toggle pgd-btn-sm pgd-card-max-btn" title="ขยายกราฟนี้เต็มจอ" onclick="pgdToggleCardFullscreen('pgd-card-patho')">
                        <i class="fa-solid fa-maximize"></i>
                    </button>
                </div>
            </div>

            <!-- KPI Row -->
            <div class="pgd-kpi-row" id="pgd-kpi-patho">
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-users text-purple"></i> จำนวนพยาธิแพทย์</div>
                    <div class="pgd-kpi-value" id="kpi-pat-count">-</div>
                    <div class="pgd-kpi-sub">ท่านที่มีผลงานในช่วงนี้</div>
                </div>
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-chart-line text-info"></i> เฉลี่ยต่อท่าน (Mean &mu;)</div>
                    <div class="pgd-kpi-value" id="kpi-pat-mean">-</div>
                    <div class="pgd-kpi-sub">เคสเฉลี่ยต่อพยาธิแพทย์</div>
                </div>
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-chart-area text-warning"></i> เบี่ยงเบน Std Dev (&sigma;)</div>
                    <div class="pgd-kpi-value" id="kpi-pat-std">-</div>
                    <div class="pgd-kpi-sub">ความต่างของภาระงาน</div>
                </div>
                <div class="pgd-kpi-item">
                    <div class="pgd-kpi-title"><i class="fa-solid fa-trophy text-amber"></i> พยาธิแพทย์ผู้ออกผลสูงสุด</div>
                    <div class="pgd-kpi-value" id="kpi-pat-top">-</div>
                    <div class="pgd-kpi-sub" id="kpi-pat-top-sub">-</div>
                </div>
            </div>

            <!-- Chart Canvas -->
            <div class="pgd-chart-container">
                <canvas id="chart-canvas-patho"></canvas>
            </div>

            <!-- Table Toggle Button & Collapsible Table -->
            <div class="pgd-table-toggle-wrapper">
                <button class="pgd-btn pgd-btn-theme-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#pgd-collapse-patho" aria-expanded="false">
                    <i class="fa-solid fa-table me-1"></i> ตารางสรุปข้อมูลพยาธิแพทย์ (คลิกแสดง/ซ่อน)
                </button>
                <button class="pgd-btn pgd-btn-theme-toggle" type="button" onclick="PGD.exportTableToCSV('table-patho-data', 'Pathologist_Caseload.csv')">
                    <i class="fa-solid fa-file-csv me-1"></i> ส่งออก CSV
                </button>
            </div>

            <div class="collapse" id="pgd-collapse-patho">
                <div class="pgd-table-card">
                    <div class="pgd-table-controls">
                        <span class="fw-bold"><i class="fa-solid fa-list-check me-1"></i> รายละเอียดภาระงานพยาธิแพทย์</span>
                        <input type="text" class="pgd-input" placeholder="ค้นหาในตาราง..." onkeyup="PGD.filterTable('table-patho-data', this.value)">
                    </div>
                    <div class="pgd-table-wrapper">
                        <table class="pgd-data-table" id="table-patho-data">
                            <thead>
                                <tr>
                                    <th>อันดับ</th>
                                    <th>ชื่อพยาธิผู้ออกผล</th>
                                    <th>จำนวนเคสที่ออกผล</th>
                                    <th>สัดส่วนจากทั้งหมด (% of Total)</th>
                                    <th>TAT เฉลี่ย (วัน)</th>
                                </tr>
                            </thead>
                            <tbody><!-- Dynamic --></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- ==========================================================================
     SCRIPTS: Chart.js Library + Standalone Dashboard Engine
     ========================================================================== -->
<!-- Chart.js CDN with Local Fallback -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
if (typeof Chart === 'undefined') {
    document.write('<script src="lib/chart/chart.min.js"><\/script>');
}
</script>

<!-- Injected PHP Payload -->
<script>
const PGD_PAYLOAD = <?= json_encode($payload_data, JSON_UNESCAPED_UNICODE) ?>;
</script>

<!-- Standalone Dashboard Logic -->
<script>
/**
 * Switch Time Unit and automatically adjust period length dropdown
 */
function pgdSetTimeUnit(unit) {
    document.getElementById('pgd_input_time_unit').value = unit;
    const periodSelect = document.getElementById('pgd_select_period');
    periodSelect.innerHTML = '';
    
    if (unit === 'weeks') {
        const options = [
            { val: 6, text: '6 สัปดาห์ (Default)' },
            { val: 12, text: '12 สัปดาห์' },
            { val: 24, text: '24 สัปดาห์' },
            { val: 32, text: '32 สัปดาห์' }
        ];
        options.forEach(opt => {
            const el = document.createElement('option');
            el.value = opt.val;
            el.textContent = opt.text;
            if (opt.val === 6) el.selected = true;
            periodSelect.appendChild(el);
        });
    } else {
        const options = [
            { val: 6, text: '6 เดือน (Default)' },
            { val: 12, text: '12 เดือน' }
        ];
        options.forEach(opt => {
            const el = document.createElement('option');
            el.value = opt.val;
            el.textContent = opt.text;
            if (opt.val === 6) el.selected = true;
            periodSelect.appendChild(el);
        });
    }
    document.getElementById('pgd-filter-form').submit();
}

/**
 * Main Standalone Dashboard Engine Object
 */
const PGD = {
    // Theme state
    isDark: true,
    
    // Chart instances
    chartTat: null,
    chartIncoming: null,
    chartReported: null,
    chartPatho: null,

    // Pre-allocated harmonious color palette
    palette: [
        '#6366f1', '#06b6d4', '#10b981', '#f59e0b', '#ec4899',
        '#8b5cf6', '#14b8a6', '#f97316', '#3b82f6', '#84cc16',
        '#d946ef', '#0ea5e9', '#eab308', '#a855f7', '#64748b'
    ],

    init: function() {
        this.initTheme();
        this.renderAllCharts();
    },

    // =========================================================================
    // THEME MANAGEMENT (Dark / Light)
    // =========================================================================
    initTheme: function() {
        const savedTheme = localStorage.getItem('pgd_theme');
        if (savedTheme) {
            this.isDark = (savedTheme === 'dark');
        } else {
            this.isDark = true; // Default theme is Dark
        }
        this.applyTheme();

        const toggleBtn = document.getElementById('pgd-theme-toggle-btn');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                this.isDark = !this.isDark;
                localStorage.setItem('pgd_theme', this.isDark ? 'dark' : 'light');
                this.applyTheme();
                this.updateAllChartsTheme();
            });
        }
    },

    applyTheme: function() {
        const app = document.getElementById('pgd-app');
        const icon = document.getElementById('pgd-theme-icon');
        const text = document.getElementById('pgd-theme-text');
        
        if (this.isDark) {
            document.body.classList.remove('pgd-theme-light');
            document.body.classList.add('pgd-theme-dark');
            app.classList.remove('pgd-theme-light');
            app.classList.add('pgd-theme-dark');
            if (icon) icon.className = 'fa-solid fa-moon';
            if (text) text.textContent = 'โหมดมืด (Dark)';
        } else {
            document.body.classList.remove('pgd-theme-dark');
            document.body.classList.add('pgd-theme-light');
            app.classList.remove('pgd-theme-dark');
            app.classList.add('pgd-theme-light');
            if (icon) icon.className = 'fa-solid fa-sun';
            if (text) text.textContent = 'โหมดสว่าง (Light)';
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

    // =========================================================================
    // STATISTICAL CALCULATION HELPERS (Mean, Std Dev, Min, Max)
    // =========================================================================
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
        return {
            min: Math.min(...valid),
            max: Math.max(...valid)
        };
    },

    // =========================================================================
    // MASTER CHART RENDERING
    // =========================================================================
    renderAllCharts: function() {
        this.updateTatChart();
        this.updateIncomingChart();
        this.updateReportedChart();
        this.updatePathoChart();
    },

    // =========================================================================
    // 1. CHART: TURN AROUND TIME (TAT)
    // =========================================================================
    updateTatChart: function() {
        const dim = document.getElementById('pgd_dim_tat').value;
        const buckets = PGD_PAYLOAD.buckets;
        const records = PGD_PAYLOAD.records;
        const theme = this.getThemeColors();

        // Filter records that have valid reported date and turn around time
        const tatRecords = records.filter(r => r.p_date_first_report && (parseFloat(r.p_tr_time) > 0));

        // Group by period bucket
        const bucketTatValues = buckets.map(b => []);
        const bucketDimGroups = buckets.map(b => ({}));

        // Identify top dimensions if broken down
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
                        if (!bucketDimGroups[i][category]) {
                            bucketDimGroups[i][category] = [];
                        }
                        bucketDimGroups[i][category].push(tr);
                        dimTotalCounts[category] = (dimTotalCounts[category] || 0) + 1;
                    }
                    break;
                }
            }
        });

        // Calculate KPI Stats
        const allTats = tatRecords.map(r => parseFloat(r.p_tr_time));
        const overallMean = this.calcMean(allTats);
        const overallStd = this.calcStdDev(allTats);
        const { min, max } = this.calcMinMax(allTats);

        document.getElementById('kpi-tat-mean').textContent = overallMean > 0 ? overallMean.toFixed(2) + ' วัน' : '-';
        document.getElementById('kpi-tat-std').textContent = overallStd > 0 ? '± ' + overallStd.toFixed(2) + ' วัน' : '-';
        document.getElementById('kpi-tat-min').textContent = min > 0 ? min.toFixed(2) + ' วัน' : '-';
        document.getElementById('kpi-tat-max').textContent = max > 0 ? max.toFixed(2) + ' วัน' : '-';
        document.getElementById('kpi-tat-cases').textContent = tatRecords.length.toLocaleString() + ' เคส';

        // Prepare Datasets
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
                borderRadius: 6
            });
        } else {
            // Pick Top 7 categories, rest to 'อื่นๆ'
            const sortedDims = Object.keys(dimTotalCounts).sort((a, b) => dimTotalCounts[b] - dimTotalCounts[a]);
            const topDims = sortedDims.slice(0, 7);
            const hasOthers = sortedDims.length > 7;

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
                        if (!topDims.includes(c)) {
                            otherVals = otherVals.concat(bucketDimGroups[bIdx][c]);
                        }
                    });
                    const m = this.calcMean(otherVals);
                    return m > 0 ? Number(m.toFixed(2)) : null;
                });
                datasets.push({
                    type: 'bar',
                    label: 'อื่นๆ (' + (sortedDims.length - 7) + ' รายการ)',
                    data: otherData,
                    backgroundColor: '#64748b',
                    borderRadius: 4
                });
            }
        }

        // Add Reference Line for Overall Mean
        if (overallMean > 0) {
            datasets.push({
                type: 'line',
                label: 'ค่าเฉลี่ยรวม (Mean: ' + overallMean.toFixed(2) + ' วัน)',
                data: Array(buckets.length).fill(Number(overallMean.toFixed(2))),
                borderColor: '#ef4444',
                borderWidth: 2,
                borderDash: [6, 4],
                pointRadius: 0,
                fill: false,
                order: 0
            });
        }

        // Render Chart.js
        const ctx = document.getElementById('chart-canvas-tat').getContext('2d');
        if (this.chartTat) this.chartTat.destroy();

        this.chartTat = new Chart(ctx, {
            data: { labels, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { color: theme.text, font: { family: 'Heebo', size: 12 } }
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
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 11 } }
                    },
                    y: {
                        title: { display: true, text: 'TAT เฉลี่ย (วัน)', color: theme.textMuted },
                        grid: { color: theme.grid },
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 11 } },
                        beginAtZero: true
                    }
                }
            }
        });

        // Render Breakdown Table
        this.renderTatTable(buckets, bucketTatValues, bucketDimGroups, dim);
    },

    renderTatTable: function(buckets, bucketTatValues, bucketDimGroups, dim) {
        const tbody = document.querySelector('#table-tat-data tbody');
        let html = '';

        buckets.forEach((b, idx) => {
            if (dim === 'none') {
                const vals = bucketTatValues[idx];
                const mean = this.calcMean(vals);
                const std = this.calcStdDev(vals);
                const { min, max } = this.calcMinMax(vals);
                html += `<tr>
                    <td><strong>${b.label}</strong></td>
                    <td><span class="pgd-badge pgd-badge-blue">ภาพรวมทั้งหมด</span></td>
                    <td>${vals.length.toLocaleString()}</td>
                    <td><strong class="text-info">${mean > 0 ? mean.toFixed(2) : '-'}</strong></td>
                    <td>${min > 0 ? min.toFixed(2) : '-'}</td>
                    <td>${max > 0 ? max.toFixed(2) : '-'}</td>
                    <td>${std > 0 ? std.toFixed(2) : '-'}</td>
                </tr>`;
            } else {
                const groups = bucketDimGroups[idx];
                const keys = Object.keys(groups).sort((a, b) => groups[b].length - groups[a].length);
                if (keys.length === 0) {
                    html += `<tr>
                        <td><strong>${b.label}</strong></td>
                        <td colspan="6" class="text-muted text-center">ไม่มีข้อมูลในรอบนี้</td>
                    </tr>`;
                } else {
                    keys.forEach(k => {
                        const vals = groups[k];
                        const mean = this.calcMean(vals);
                        const std = this.calcStdDev(vals);
                        const { min, max } = this.calcMinMax(vals);
                        html += `<tr>
                            <td><strong>${b.label}</strong></td>
                            <td>${k}</td>
                            <td>${vals.length.toLocaleString()}</td>
                            <td><strong class="text-info">${mean > 0 ? mean.toFixed(2) : '-'}</strong></td>
                            <td>${min > 0 ? min.toFixed(2) : '-'}</td>
                            <td>${max > 0 ? max.toFixed(2) : '-'}</td>
                            <td>${std > 0 ? std.toFixed(2) : '-'}</td>
                        </tr>`;
                    });
                }
            }
        });

        tbody.innerHTML = html;
    },

    // =========================================================================
    // 2. CHART: INCOMING CASES (เคสรับเข้า)
    // =========================================================================
    updateIncomingChart: function() {
        const dim = document.getElementById('pgd_dim_incoming').value;
        const buckets = PGD_PAYLOAD.buckets;
        const records = PGD_PAYLOAD.records;
        const theme = this.getThemeColors();

        // Filter records that have valid intake date (date_1000)
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

        // KPI Stats
        const totalIn = bucketCounts.reduce((a, b) => a + b, 0);
        const meanIn = this.calcMean(bucketCounts);
        const stdIn = this.calcStdDev(bucketCounts);
        const maxIn = Math.max(...bucketCounts);
        const maxIdx = bucketCounts.indexOf(maxIn);
        const peakLabel = maxIdx >= 0 ? buckets[maxIdx].label.split(' ')[0] : '-';

        document.getElementById('kpi-in-total').textContent = totalIn.toLocaleString() + ' เคส';
        document.getElementById('kpi-in-mean').textContent = meanIn.toFixed(1) + ' เคส';
        document.getElementById('kpi-in-std').textContent = '± ' + stdIn.toFixed(1) + ' เคส';
        document.getElementById('kpi-in-peak').textContent = maxIn.toLocaleString() + ' เคส';
        document.getElementById('kpi-in-peak-sub').textContent = 'รอบสูงสุด: ' + peakLabel;

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
                borderRadius: 6
            });
        } else {
            const sortedDims = Object.keys(dimTotalCounts).sort((a, b) => dimTotalCounts[b] - dimTotalCounts[a]);
            const topDims = sortedDims.slice(0, 7);
            const hasOthers = sortedDims.length > 7;

            topDims.forEach((cat, idx) => {
                const color = this.palette[idx % this.palette.length];
                const data = buckets.map((b, bIdx) => bucketDimCounts[bIdx][cat] || 0);
                datasets.push({
                    type: 'bar',
                    label: cat,
                    data: data,
                    backgroundColor: color,
                    borderRadius: 4
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
                    label: 'อื่นๆ (' + (sortedDims.length - 7) + ' รายการ)',
                    data: otherData,
                    backgroundColor: '#64748b',
                    borderRadius: 4
                });
            }
        }

        // Add Mean line
        if (meanIn > 0) {
            datasets.push({
                type: 'line',
                label: 'ค่าเฉลี่ยต่อรอบ (Mean: ' + meanIn.toFixed(1) + ' เคส)',
                data: Array(buckets.length).fill(Number(meanIn.toFixed(1))),
                borderColor: '#f59e0b',
                borderWidth: 2,
                borderDash: [6, 4],
                pointRadius: 0,
                fill: false,
                order: 0
            });
        }

        const ctx = document.getElementById('chart-canvas-incoming').getContext('2d');
        if (this.chartIncoming) this.chartIncoming.destroy();

        this.chartIncoming = new Chart(ctx, {
            data: { labels, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { color: theme.text, font: { family: 'Heebo', size: 12 } }
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
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 11 } }
                    },
                    y: {
                        stacked: (dim !== 'none'),
                        title: { display: true, text: 'จำนวนเคสรับเข้า (เคส)', color: theme.textMuted },
                        grid: { color: theme.grid },
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 11 } },
                        beginAtZero: true
                    }
                }
            }
        });

        // Table
        this.renderCountTable('table-incoming-data', buckets, bucketCounts, bucketDimCounts, dim, totalIn);
    },

    // =========================================================================
    // 3. CHART: REPORTED CASES (จำนวนการออกผล)
    // =========================================================================
    updateReportedChart: function() {
        const dim = document.getElementById('pgd_dim_reported').value;
        const buckets = PGD_PAYLOAD.buckets;
        const records = PGD_PAYLOAD.records;
        const theme = this.getThemeColors();

        // Filter records that have valid reported date
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

        // KPI Stats
        const totalRep = bucketCounts.reduce((a, b) => a + b, 0);
        const meanRep = this.calcMean(bucketCounts);
        const stdRep = this.calcStdDev(bucketCounts);
        const maxRep = Math.max(...bucketCounts);
        const maxIdx = bucketCounts.indexOf(maxRep);
        const peakLabel = maxIdx >= 0 ? buckets[maxIdx].label.split(' ')[0] : '-';

        document.getElementById('kpi-rep-total').textContent = totalRep.toLocaleString() + ' เคส';
        document.getElementById('kpi-rep-mean').textContent = meanRep.toFixed(1) + ' เคส';
        document.getElementById('kpi-rep-std').textContent = '± ' + stdRep.toFixed(1) + ' เคส';
        document.getElementById('kpi-rep-peak').textContent = maxRep.toLocaleString() + ' เคส';
        document.getElementById('kpi-rep-peak-sub').textContent = 'รอบสูงสุด: ' + peakLabel;

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
                borderRadius: 6
            });
        } else {
            const sortedDims = Object.keys(dimTotalCounts).sort((a, b) => dimTotalCounts[b] - dimTotalCounts[a]);
            const topDims = sortedDims.slice(0, 7);
            const hasOthers = sortedDims.length > 7;

            topDims.forEach((cat, idx) => {
                const color = this.palette[idx % this.palette.length];
                const data = buckets.map((b, bIdx) => bucketDimCounts[bIdx][cat] || 0);
                datasets.push({
                    type: 'bar',
                    label: cat,
                    data: data,
                    backgroundColor: color,
                    borderRadius: 4
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
                    label: 'อื่นๆ (' + (sortedDims.length - 7) + ' รายการ)',
                    data: otherData,
                    backgroundColor: '#64748b',
                    borderRadius: 4
                });
            }
        }

        // Add Mean line
        if (meanRep > 0) {
            datasets.push({
                type: 'line',
                label: 'ค่าเฉลี่ยต่อรอบ (Mean: ' + meanRep.toFixed(1) + ' เคส)',
                data: Array(buckets.length).fill(Number(meanRep.toFixed(1))),
                borderColor: '#6366f1',
                borderWidth: 2,
                borderDash: [6, 4],
                pointRadius: 0,
                fill: false,
                order: 0
            });
        }

        const ctx = document.getElementById('chart-canvas-reported').getContext('2d');
        if (this.chartReported) this.chartReported.destroy();

        this.chartReported = new Chart(ctx, {
            data: { labels, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { color: theme.text, font: { family: 'Heebo', size: 12 } }
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
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 11 } }
                    },
                    y: {
                        stacked: (dim !== 'none'),
                        title: { display: true, text: 'จำนวนเคสออกผล (เคส)', color: theme.textMuted },
                        grid: { color: theme.grid },
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 11 } },
                        beginAtZero: true
                    }
                }
            }
        });

        // Table
        this.renderCountTable('table-reported-data', buckets, bucketCounts, bucketDimCounts, dim, totalRep);
    },

    renderCountTable: function(tableId, buckets, bucketCounts, bucketDimCounts, dim, overallTotal) {
        const tbody = document.querySelector(`#${tableId} tbody`);
        let html = '';

        buckets.forEach((b, idx) => {
            const periodTotal = bucketCounts[idx];
            if (dim === 'none') {
                const pctOfTotal = overallTotal > 0 ? ((periodTotal / overallTotal) * 100).toFixed(1) : '0.0';
                html += `<tr>
                    <td><strong>${b.label}</strong></td>
                    <td><span class="pgd-badge pgd-badge-green">เคสทั้งหมด</span></td>
                    <td><strong>${periodTotal.toLocaleString()}</strong></td>
                    <td>100.0%</td>
                    <td>${pctOfTotal}%</td>
                </tr>`;
            } else {
                const groups = bucketDimCounts[idx];
                const keys = Object.keys(groups).sort((a, b) => groups[b] - groups[a]);
                if (keys.length === 0) {
                    html += `<tr>
                        <td><strong>${b.label}</strong></td>
                        <td colspan="4" class="text-muted text-center">ไม่มีข้อมูลในรอบนี้</td>
                    </tr>`;
                } else {
                    keys.forEach(k => {
                        const cnt = groups[k];
                        const pctPeriod = periodTotal > 0 ? ((cnt / periodTotal) * 100).toFixed(1) : '0.0';
                        const pctTotal = overallTotal > 0 ? ((cnt / overallTotal) * 100).toFixed(1) : '0.0';
                        html += `<tr>
                            <td><strong>${b.label}</strong></td>
                            <td>${k}</td>
                            <td><strong>${cnt.toLocaleString()}</strong></td>
                            <td>${pctPeriod}%</td>
                            <td>${pctTotal}%</td>
                        </tr>`;
                    });
                }
            }
        });

        tbody.innerHTML = html;
    },

    // =========================================================================
    // 4. CHART: PATHOLOGIST CASELOAD (หนึ่งท่านหนึ่งแท่ง)
    // =========================================================================
    updatePathoChart: function() {
        const records = PGD_PAYLOAD.records;
        const theme = this.getThemeColors();

        // Group reported cases by pathologist
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
        const totalCases = counts.reduce((a, b) => a + b, 0);

        // Stats
        const meanCases = this.calcMean(counts);
        const stdCases = this.calcStdDev(counts);
        const topPathoName = sortedPathos.length > 0 ? sortedPathos[0] : '-';
        const topPathoCount = sortedPathos.length > 0 ? pathoCounts[topPathoName] : 0;

        document.getElementById('kpi-pat-count').textContent = sortedPathos.length + ' ท่าน';
        document.getElementById('kpi-pat-mean').textContent = meanCases.toFixed(1) + ' เคส';
        document.getElementById('kpi-pat-std').textContent = '± ' + stdCases.toFixed(1) + ' เคส';
        document.getElementById('kpi-pat-top').textContent = topPathoName;
        document.getElementById('kpi-pat-top-sub').textContent = 'สูงสุด: ' + topPathoCount.toLocaleString() + ' เคส';

        // Palette for each pathologist bar
        const barColors = sortedPathos.map((p, idx) => this.palette[idx % this.palette.length]);

        const datasets = [
            {
                type: 'bar',
                label: 'จำนวนเคสที่ออกผล (เคส)',
                data: counts,
                backgroundColor: barColors,
                borderRadius: 6
            }
        ];

        // Add Mean line
        if (meanCases > 0) {
            datasets.push({
                type: 'line',
                label: 'ค่าเฉลี่ยต่อท่าน (Mean: ' + meanCases.toFixed(1) + ' เคส)',
                data: Array(sortedPathos.length).fill(Number(meanCases.toFixed(1))),
                borderColor: '#ec4899',
                borderWidth: 2,
                borderDash: [6, 4],
                pointRadius: 0,
                fill: false,
                order: 0
            });
        }

        const ctx = document.getElementById('chart-canvas-patho').getContext('2d');
        if (this.chartPatho) this.chartPatho.destroy();

        this.chartPatho = new Chart(ctx, {
            data: { labels: sortedPathos, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { color: theme.text, font: { family: 'Heebo', size: 12 } }
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
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 11 }, maxRotation: 45, minRotation: 25 }
                    },
                    y: {
                        title: { display: true, text: 'จำนวนเคสออกผล (เคส)', color: theme.textMuted },
                        grid: { color: theme.grid },
                        ticks: { color: theme.textMuted, font: { family: 'Heebo', size: 11 } },
                        beginAtZero: true
                    }
                }
            }
        });

        // Table
        const tbody = document.querySelector('#table-patho-data tbody');
        let html = '';
        sortedPathos.forEach((p, idx) => {
            const cnt = pathoCounts[p];
            const pct = totalCases > 0 ? ((cnt / totalCases) * 100).toFixed(1) : '0.0';
            const tats = pathoTats[p] || [];
            const avgTat = tats.length > 0 ? (tats.reduce((a, b) => a + b, 0) / tats.length).toFixed(2) : '-';

            html += `<tr>
                <td><strong>#${idx + 1}</strong></td>
                <td><strong class="text-primary">${p}</strong></td>
                <td><strong>${cnt.toLocaleString()}</strong></td>
                <td>${pct}%</td>
                <td>${avgTat} วัน</td>
            </tr>`;
        });
        tbody.innerHTML = html;
    },

    // =========================================================================
    // UTILITY: FILTER HTML TABLE ROWS
    // =========================================================================
    filterTable: function(tableId, query) {
        const q = query.toLowerCase();
        const rows = document.querySelectorAll(`#${tableId} tbody tr`);
        rows.forEach(tr => {
            const text = tr.textContent.toLowerCase();
            tr.style.display = text.includes(q) ? '' : 'none';
        });
    },

    // =========================================================================
    // UTILITY: EXPORT TABLE TO CSV
    // =========================================================================
    exportTableToCSV: function(tableId, filename) {
        const table = document.getElementById(tableId);
        if (!table) return;

        let csv = '\uFEFF'; // UTF-8 BOM for Thai Excel compatibility
        const rows = table.querySelectorAll('tr');

        rows.forEach(tr => {
            if (tr.style.display === 'none') return;
            const cols = tr.querySelectorAll('th, td');
            const rowData = [];
            cols.forEach(col => {
                let text = col.innerText.replace(/"/g, '""').trim();
                rowData.push('"' + text + '"');
            });
            csv += rowData.join(',') + '\r\n';
        });

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
};

// =========================================================================
// FULLSCREEN TOGGLE HANDLERS
// =========================================================================
function pgdToggleFullscreen() {
    const elem = document.getElementById('pgd-app');
    const icon = document.getElementById('pgd-fullscreen-icon');
    const text = document.getElementById('pgd-fullscreen-text');

    if (!document.fullscreenElement && !document.webkitFullscreenElement && !elem.classList.contains('pgd-fallback-fullscreen')) {
        if (elem.requestFullscreen) {
            elem.requestFullscreen().catch(() => {
                elem.classList.add('pgd-fallback-fullscreen');
                updateBtn(true);
            });
        } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen();
        } else {
            elem.classList.add('pgd-fallback-fullscreen');
            updateBtn(true);
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen().catch(() => {});
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
        elem.classList.remove('pgd-fallback-fullscreen');
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
    const elem = document.getElementById('pgd-app');
    const icon = document.getElementById('pgd-fullscreen-icon');
    const text = document.getElementById('pgd-fullscreen-text');
    const isFull = !!document.fullscreenElement;
    if (icon) icon.className = isFull ? 'fa-solid fa-compress' : 'fa-solid fa-expand';
    if (text) text.textContent = isFull ? 'ย่อจอ' : 'เต็มจอ';
    if (!isFull && elem) elem.classList.remove('pgd-fallback-fullscreen');
    setTimeout(() => {
        if (window.PGD && PGD.charts) {
            Object.values(PGD.charts).forEach(c => c && c.resize());
        }
    }, 150);
});

function pgdToggleCardFullscreen(cardId) {
    const card = document.getElementById(cardId);
    if (!card) return;
    card.classList.toggle('pgd-card-maximized');
    const isMax = card.classList.contains('pgd-card-maximized');
    const btn = card.querySelector('.pgd-card-max-btn i');
    if (btn) {
        btn.className = isMax ? 'fa-solid fa-compress' : 'fa-solid fa-maximize';
    }
    setTimeout(() => {
        if (window.PGD && PGD.charts) {
            Object.values(PGD.charts).forEach(c => c && c.resize());
        }
    }, 150);
}

// Initialize Dashboard when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    PGD.init();
});
</script>

<?php require 'includes/footer.php'; ?>
