# User Groups, Access Control & Login Reference — NB Patho (`nb_web`)

## 1. User Group IDs (`user_groups` / `user.ugroup_id`)

User group records are stored in `user_groups` (`id`, `ugroup`, `ugroup_e`, `ppatient`, `pnbcenter`, `presult`). Always check numeric IDs (`$u_cur_group_id->id`), never localized text labels.

| Group ID | ภาษาไทย (Thai) | English Name | `user_auth.php` Flag & Notes |
| :-: | :--- | :--- | :--- |
| **1000** | ผู้ดูแลระบบ | `admin` | `$isCurUserAdmin`; included in `$isCurUserNB` |
| **1900** | พยาธิ_แอดมิน | `pathologist_admin` | `$isCurUserPathoAdmin`; included in `$isCurUserNB` |
| **2000** | พยาธิแพทย์ | `pathologist` | `$isCurUserPatho`; included in `$isCurUserNB` and `$isCurUserGeneralNB` |
| **2100** | ผู้ช่วยพยาธิแพทย์ | `pathologist assistant` | `$isCurUserPathoAssis`; included in `$isCurUserNB` and `$isCurUserGeneralNB` |
| **2200** | เจ้าหน้าที่แลป | `Lab officer (NB)` | `$isCurUserLabOfficerNB`; included in `$isCurUserNB` and `$isCurUserGeneralNB` |
| **2500** | เจ้าหน้าที่ธุรการ | `AdminStaff` | `$isCurUserAdminStaff`; included in `$isCurUserNB` and `$isCurUserGeneralNB` |
| **2600** | เจ้าหน้าที่การตลาด | `Draft` | Listed in `user_auth.php` comments; no dedicated flag |
| **2700** | เจ้าหน้าที่งานคุณภาพ | `Draft` | Listed in `user_auth.php` comments; no dedicated flag |
| **5000** | แพทย์ผู้ส่งตรวจ (ลูกค้า) | `Clinician (Customer)` | `$isCurUserClinicianCust`; included in `$isCurUserCust` |
| **5100** | โรงพยาบาล (ลูกค้า) | `Lab officer (Customer)` | `$isCurUserHospitalCust`; included in `$isCurUserCust`. Linked via `Hospital::setUserID()` |

### Composite Flags in `user_auth.php`
- **`$isCurUserNB`**: Groups `1000`, `1900`, `2000`, `2100`, `2200`, `2500`
- **`$isCurUserGeneralNB`**: Groups `2000`, `2100`, `2200`, `2500`
- **`$isCurUserCust`**: Groups `5000`, `5100`
- **`$isUnderCurHospital`**: Checks `$_SESSION['user']->uhospital_id == $patient[0]['phospital_id']` (restricts customer users to their own hospital's patients)

### Per-User Capability Flags vs Group IDs
Do not confuse `ugroup_id` with per-user columns on `user`:
- **`user.role_a`**: Super-pathologist / all-case ownership flag. Used in `$isCurrentPathoIsOwnerThisCase` (`$u_cur_group_id->id == 2000 && ($_SESSION['user']->id == $job5s[0]['user_id'] || $_SESSION['user']->role_a == 1)`).
- **`user.role_7`**: Cytologist capability flag (`User::getAllbyCytologist()` filters by `role_7 = 1`). Cytologist case ownership (`$isCurrentCytologistIsOwnerThisCase`) checks `$_SESSION['user']->id == $job7s[0]['user_id']`.

---

## 2. Login & Authentication Flow (`login.php`, `Auth`, `User`, `Url`)

### Step-by-Step Execution (`login.php`)
1. **Initialization & Subfolder Detection:**
   - Loads `includes/init.php` (autoloader for `classes/{$class}.php` + `session_start()`).
   - Extracts `$subfolder` (e.g., `dev3`) from `$_SERVER['PHP_SELF']` into a hidden `<input name="subfolder">` field.
2. **Already-Logged-In Check:**
   - Calls `Auth::isLoggedIn()`. If true, redirects immediately to `/patient.php`.
3. **POST Authentication:**
   - Checks `User::isUserNameActive($conn, $_POST['username'])` (`user_status == 1`).
   - Checks `User::authenticate($conn, $_POST['username'], $_POST['password'])` via `password_verify()`.
   - Calls `Auth::login($conn, $_POST['username'], $_POST['subfolder'])`.
4. **Post-Login Redirects:**
   - If password is still `"changeme"` &rarr; redirects to `/user_change_password.php`.
   - Else if `$_POST['page_name']` is set &rarr; redirects to `/{page_name}?id={page_id}`.
   - Otherwise &rarr; redirects to `/patient.php`.

### Session Variables Created by `Auth::login()`
| Session Key | Value / Type | Purpose |
| :--- | :--- | :--- |
| `$_SESSION['is_logged_in']` | `true` (bool) | Marks session as authenticated |
| `$_SESSION['username']` | `string` | Logged-in username |
| `$_SESSION['user']` | `User` object | Full record from `user` table |
| `$_SESSION['userid']` | `int` | `user.id` primary key |
| `$_SESSION['usergroup']` | `Ugroup` object | Record from `user_groups` table |
| `$_SESSION['subfolder']` | `string` | Deployment subfolder name (e.g., `dev3`) |
| `$_SESSION['skey']` | `string` (10 chars) | Random security token verified by AJAX endpoints |
| `$_SESSION['setSrvTimeLimitIntSecs']` | `1200` (int) | 20-minute server-side idle timeout |
| `$_SESSION['targetSrvTimeOutIntSecs']` | `int` | Expiration timestamp (`curTime + 1200`) |
| `$_SESSION['log_username']` / `$_SESSION['log_name']` | `string` | Used by `Log::add()` on login and logout |

---

## 3. User Account Creation (`user_add.php`)
- Only Admin (`1000`) sees all user groups via `Ugroup::getAll($conn)`; other allowed users only see customer groups (`0`, `5000`, `5100`) via `Ugroup::getCust($conn)`.
- Passwords are hashed using `password_hash($password, PASSWORD_DEFAULT)`.
- When creating a hospital customer user (`5100`), `Hospital::setUserID()` links the hospital record to the new user.

---

## 4. Sidebar Navigation Unique IDs & Active Tab Standard (`includes/header.php`)

All sidebar dropdowns and submenu items in `includes/header.php` have unique HTML `id` attributes, and all submenu items inside `<div class="dropdown-menu">` use `class="dropdown-item"`.

### Standard 3-Line Active Tab Pattern (from `billing_pdf.php`)
Every submenu page sets its active state using 3 lines:
```javascript
$("#<dropdown_id>").addClass("show");
$("#<parent_tab_id>").addClass("active");
$("#<sub_tab_id>").addClass("active");
```

### Complete Sidebar ID Map
| Parent Toggle ID | Dropdown Container ID | Submenu Item ID (`.dropdown-item`) | Target File |
| :--- | :--- | :--- | :--- |
| *(Top-level)* | — | `#addpatienttab` (`.nav-item.nav-link`) | `patient_add.php` |
| *(Top-level)* | — | `#patienttab` (`.nav-item.nav-link`) | `patient.php` (`js/patient.js`) |
| `#dashbroad_tab` | `#dashbroad_dropdown` | `#globaldashboard_main_tab` | `patient_global_dashboard_main.php` |
| `#dashbroad_tab` | `#dashbroad_dropdown` | `#globaldashboardtab` | `patient_global_dashboard.php` |
| `#admin_tab` | `#admin_dropdown` | `#user_sim_admin_tab` | `user_sim_admin.php` |
| `#admin_tab` | `#admin_dropdown` | `#curdate_sim_admin_tab` | `curdate_sim_admin.php` |
| `#admin_tab` | `#admin_dropdown` | `#datatable_patient_pn_rev1` | `datatable_patient_pn_rev1.php` |
| `#admin_tab` | `#admin_dropdown` | `#chart_data_tab` | `chart_data.php` |
| `#admin_tab` | `#admin_dropdown` | `#billing_pdf_tab_3` | `billing_pdf_3.php` |
| `#admin_tab` | `#admin_dropdown` | `#systemchecking_tab` | `systemchecking.php` |
| *(Top-level)* | — | `#patienttab_8000` (`.nav-item.nav-link`) | `patient_monitor_8000.php` (`js/patient_8000.js`) |
| `#view_qn_table` | `#view_qn_dropdown` | `#qn_sn_tab` | `questionare_sn_slide.php` |
| `#view_qn_table` | `#view_qn_dropdown` | `#qn_cn_tab` | `questionare_cn_slide.php` |
| `#view_log_table` | `#view_log_dropdown` | `#log_login_logout_tab` | `log_login_logout_datatable.php` |
| `#view_job_table` | `#view_job_dropdown` | `#job_tab` | `job.php` (`js/job.js`) |
| `#view_job_table` | `#view_job_dropdown` | `#job_tab_jobdaily` | `job_daily.php` |
| `#finish_job_table` | `#finish_job_dropdown` | `#job1_finish_tab`, `#job2_finish_tab`, `#job3_finish_tab` | `job1_finish.php`, `job2_finish.php`, `job3_finish.php` |
| `#manage_table` | `#manage_table_dropdown` | `#user_add`, `#user`, `#hospital_add`, `#hospital`, `#patient_edit_by_excel_tab`, `#patientconfirmtab`, `#price_tab_8c`, `#gross_examination_tab`, `#note_tab` | `user_add.php`, `user.php`, `hospital_add.php`, `hospital.php`, `patient_edit_by_excel.php`, `patient_confirm.php` (`js/patient_confirm.js`), `nb_price_8c.php`, `gross_examination_record.php`, `nbnote_record.php` |
| `#manage_bill` | `#manage_bill_dropdown` | `#billing_pdf_tab_2`, `#billing_pdf_patho_tab`, `#billing_pdf_cyto_tab`, `#billing_tab`, `#billing_by_billingdate_tab` | `billing_pdf_2.php`, `billing_by_patho_pdf.php`, `billing_by_cytologist_pdf.php`, `billing.php`, `billing_by_billingdate.php` |
| *(Top-level)* | — | `#template_report`, `#generate_label`, `#generate_labelv2` | `templateReport.php`, `generate_label.php`, `generate_labelv2.php` |
| `#about_main` | `#about_dropdown` | `#about`, `#webfeature`, `#featurehistory_tab`, `#printersetting_tab`, `#docdownload_tab`, `#stuff`, `#service`, `#learning_clip` | `about.php`, `webfeature.php`, `changehistory.php`, `printersetting.php`, `doc_download.php`, `stuff.php`, `service.php` |
