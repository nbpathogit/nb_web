<?php
/**
 * Patient Dashboard Data Helper Class
 * File: classes/Patientdash.php
 * Path: C:\anuchit2\nb_web\dev3\classes\Patientdash.php
 *
 * Dedicated backend data retrieval and utility class specifically created for:
 * 1. patient_global_dashboard.php (Interactive multi-filter dashboard)
 * 2. patient_global_dashboard_main.php (Executive 12-month summary dashboard)
 *
 * Autoloaded via spl_autoload_register in includes/init.php.
 * Ensures patient.php and core Patient classes remain completely untouched.
 */

class Patientdash {

    /**
     * Determine if current user (or simulated user) is a customer hospital
     * Returns:
     *   - integer hospital_id: if user belongs to a customer hospital (group 5000/5100 or non-NB hospital)
     *   - null: if user is NB Pathology Center staff or system Administrator (no filter, sees all hospitals)
     */
    public static function getUserHospitalFilter($conn = null) {
        $cur_user = isset($_SESSION['user']) ? $_SESSION['user'] : (class_exists('Auth') ? Auth::getUser() : null);
        $uhosp_id = isset($cur_user->uhospital_id) ? intval($cur_user->uhospital_id) : 0;
        $ugroup_id = isset($cur_user->ugroup_id) ? intval($cur_user->ugroup_id) : 0;

        // If currently active user is Admin (group 1000) and NOT simulating another user:
        if ($ugroup_id === 1000 && !isset($_SESSION['adminusername'])) {
            return null; // Full admin sees all hospitals
        }

        // Hospital 15 is ศูนย์พยาธิวิทยา เอ็น.บี. (NB Pathology Center) -> No filter
        if ($uhosp_id === 15) {
            return null;
        }

        // NB Internal Staff (1000 Admin, 1900 PathoAdmin, 2000 Patho, 2100 PathoAssis, 2200 LabOfficer, 2500 AdminStaff)
        // when hospital is 15 or 0 -> No filter
        if (in_array($ugroup_id, [1000, 1900, 2000, 2100, 2200, 2500]) && ($uhosp_id === 0 || $uhosp_id === 15)) {
            return null;
        }

        // Customer hospital user (groups 5000, 5100, or any user with uhospital_id > 0 and != 15):
        // Filter strictly to only their own hospital ID
        if ($uhosp_id > 0) {
            return $uhosp_id;
        }

        return null;
    }

    /**
     * Fetch patient records for the dashboard with optional customer hospital filtering
     */
    public static function fetchRecords($conn, $start_date, $end_date, $filter_hospital_id = null) {
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

    /**
     * Fetch distinct years available in the database (with optional hospital filter)
     */
    public static function getAvailableYears($conn, $filter_hospital_id = null) {
        try {
            $params = [];
            $hosp_clause = "";
            if (!empty($filter_hospital_id)) {
                $hosp_clause = " AND phospital_id = :filter_hospital_id AND (sn_type NOT IN ('IN', 'ZN') OR sn_type IS NULL)";
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

    /**
     * Generate period buckets (Weeks or Months) with boundaries and Thai labels
     */
    public static function generatePeriodBuckets($time_unit, $period_count, $year_scope) {
        $buckets = [];
        $thai_months = [
            1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.',
            5 => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.',
            9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
        ];

        date_default_timezone_set('Asia/Bangkok');
        $now = new DateTime();

        if ($time_unit === 'month') {
            if ($year_scope === 'rolling') {
                for ($i = $period_count - 1; $i >= 0; $i--) {
                    $dt = (clone $now)->modify("first day of -$i month");
                    $key = $dt->format('Y-m');
                    $label = $thai_months[intval($dt->format('n'))] . ' ' . (intval($dt->format('Y')) + 543);
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
                $yr = intval($year_scope);
                for ($m = 1; $m <= 12; $m++) {
                    $dt = new DateTime(sprintf('%04d-%02d-01', $yr, $m));
                    if ($yr == intval($now->format('Y')) && $m > intval($now->format('n'))) {
                        break;
                    }
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
            }
        } else {
            // Weeks
            if ($year_scope === 'rolling') {
                $current_sunday = (clone $now)->modify('Sunday this week');
                for ($i = $period_count - 1; $i >= 0; $i--) {
                    $w_sunday = (clone $current_sunday)->modify("-$i week");
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
            } else {
                $yr = intval($year_scope);
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

    /**
     * Generate exactly 12 calendar month buckets for the past 1 year (Executive Main Dashboard)
     */
    public static function generate12MonthBuckets() {
        $buckets = [];
        $thai_months = [
            1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.',
            5 => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.',
            9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
        ];

        date_default_timezone_set('Asia/Bangkok');
        $now = new DateTime();

        for ($i = 11; $i >= 0; $i--) {
            $dt = (clone $now)->modify("first day of -$i month");
            $key = $dt->format('Y-m');
            $year_th = intval($dt->format('Y')) + 543;
            $month_num = intval($dt->format('n'));
            $label = $thai_months[$month_num] . ' ' . $year_th;
            $start = $dt->format('Y-m-01 00:00:00');
            $end = (clone $dt)->modify('last day of this month')->format('Y-m-d 23:59:59');

            $buckets[] = [
                'key' => $key,
                'label' => $label,
                'year_th' => $year_th,
                'month_name' => $thai_months[$month_num],
                'start' => $start,
                'end' => $end
            ];
        }

        return $buckets;
    }
}

// Alias for case tolerance if called as PatientDash or Patientdash
if (!class_exists('PatientDash', false)) {
    class_alias('Patientdash', 'PatientDash');
}

// ============================================================================
// GLOBAL PROCEDURAL WRAPPERS (FOR CONVENIENT INCLUSION IN DASHBOARD PAGES)
// ============================================================================

if (!function_exists('pgd_get_user_hospital_filter')) {
    function pgd_get_user_hospital_filter($conn) {
        return Patientdash::getUserHospitalFilter($conn);
    }
}

if (!function_exists('pgd_fetch_patient_records')) {
    function pgd_fetch_patient_records($conn, $start_date, $end_date, $filter_hospital_id = null) {
        return Patientdash::fetchRecords($conn, $start_date, $end_date, $filter_hospital_id);
    }
}

if (!function_exists('pgd_get_available_years')) {
    function pgd_get_available_years($conn, $filter_hospital_id = null) {
        return Patientdash::getAvailableYears($conn, $filter_hospital_id);
    }
}

if (!function_exists('pgd_generate_period_buckets')) {
    function pgd_generate_period_buckets($time_unit, $period_count, $year_scope) {
        return Patientdash::generatePeriodBuckets($time_unit, $period_count, $year_scope);
    }
}

if (!function_exists('pgdm_get_user_hospital_filter')) {
    function pgdm_get_user_hospital_filter($conn) {
        return Patientdash::getUserHospitalFilter($conn);
    }
}

if (!function_exists('pgdm_fetch_patient_records')) {
    function pgdm_fetch_patient_records($conn, $start_date, $end_date, $filter_hospital_id = null) {
        return Patientdash::fetchRecords($conn, $start_date, $end_date, $filter_hospital_id);
    }
}

if (!function_exists('pgdm_generate_12_month_buckets')) {
    function pgdm_generate_12_month_buckets() {
        return Patientdash::generate12MonthBuckets();
    }
}
