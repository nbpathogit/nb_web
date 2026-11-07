# PHP 7.3 + PHP 8.2+ & MySQL 8.4 Compatibility Guide (`nb_web`)

The `nb_web` application must run cleanly across **both**:
- **PHP 7.3** (typically paired with MariaDB 10.x / MySQL 5.x)
- **PHP 8.2+** (paired with MySQL 8.4)

Follow these rules whenever writing or fixing PHP and SQL code in `C:\anuchit2\nb_web\dev3`.

---

## 1. PHP 8.2+ Dynamic Property Deprecations

### Problem
When `PDO::FETCH_CLASS` populates model objects (such as `User` or `Ugroup`) or when `session_start()` in `includes/init.php` unserializes `$_SESSION['user']` and `$_SESSION['usergroup']`, PHP 8.2+ emits:
```text
Deprecated: Creation of dynamic property ClassName::$prop is deprecated
```
if `$prop` is a column in the database table that was not declared on the PHP class.

### Solution (Compatible with PHP 7.3 and PHP 8.2+)
1. **Explicitly declare** the missing `public $property;` fields in the class (`classes/User.php`, `classes/Ugroup.php`, etc.).
2. **Add `#[\AllowDynamicProperties]` on its own line** immediately before `class ClassName`:
   ```php
   #[\AllowDynamicProperties]
   class User
   {
       public $id;
       // ...
   ```
   - **Why it works on PHP 8.2+:** Parsed as a native PHP 8 Attribute that allows any undeclared database columns to be set dynamically without warnings.
   - **Why it works on PHP 7.3:** Because `#` starts a single-line comment in PHP 7.x, PHP 7.3 ignores that line and parses `class User` on the next line without syntax errors.

---

## 2. MySQL 8.4 `ONLY_FULL_GROUP_BY` (SQL Error 1055)

### Problem
MySQL 8.4 enables `sql_mode=only_full_group_by` by default. Queries that use `GROUP BY` while selecting non-aggregated columns from joined tables throw:
```text
SQLSTATE[42000]: Syntax error or access violation: 1055 Expression #N of SELECT list is not in GROUP BY clause and contains nonaggregated column ... which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by
```

### Rules for Fixing Error 1055
1. **Fix the SQL query directly in the class file** — do **not** rely on disabling `ONLY_FULL_GROUP_BY` in `classes/Database.php`.
2. **Never use `ANY_VALUE()`** — `ANY_VALUE()` is MySQL-specific and causes a fatal SQL error in MariaDB (used on PHP 7.3 environments).
3. **Choose the right SQL fix based on the relationship:**
   - **Case A: One-to-Many Join with `GROUP_CONCAT` (e.g., `Patient::getAllJob1/2/3FinishPhp` grouping by `p.id`):**
     - Do **not** add the joined table's ID (`j.id`) to `GROUP BY p.id`, because that would split one patient into multiple rows and break `GROUP_CONCAT`.
     - Instead, wrap the non-aggregated joined columns in `MAX(...)`:
       ```sql
       MAX(j.id) as j_id,
       MAX(j.patient_id) as j_patient_id,
       MAX(j.user_id) as j_user_id,
       MAX(j.job_role_id) as j_job_role_id,
       MAX(j.jobname) as j_jobname,
       MAX(j.qty) as j_qty,
       p.job1qty as p_job1qty,
       GROUP_CONCAT(CONCAT(j.pre_name, j.name, ' ', j.lastname) SEPARATOR ',') AS j_owners,
       MAX(DATE(j.finish_date)) as j_finish_date
       ```
   - **Case B: Functionally Dependent Primary Keys (e.g., `ReqSpSlideID::getBillandJobTableWithStart_v2`):**
     - Add the joined primary keys (`j4.id, j5.id`) to the `GROUP BY` clause if each joined record is 1-to-1 per group, or wrap non-grouped columns in `MAX(...)`.

---

## 3. `Log::add()` PDO vs `mysqli` Connection Guard (`classes/Log.php`)

### Problem
Most pages use PDO (`$conn = require 'includes/db.php';`), and `Log::add()` uses PDO named parameters (`:username, :name, :event, :detail, :event_date`). However, pages like `gross_examination_record.php`, `nbnote_record.php`, `changehistory.php`, and `printersetting.php` instantiate `$conn = (new Database())->getConnMysqli();`. Passing a `mysqli` object to `Log::add()` throws `mysqli_sql_exception: You have an error in your SQL syntax ... near ':username...'` on PHP 8+.

### Solution
`Log::add()` in `classes/Log.php` automatically verifies `$conn instanceof PDO` and falls back to `(new Database())->getConn()` when a non-PDO object is passed:
```php
if (!($conn instanceof PDO)) {
    $conn = (new Database())->getConn();
}
```

---

## 4. mPDF PHP 8.3+ `unserialize()` Warning & Output Buffering (`Mpdf.php` & `patient_pdf_for_note_v2.php`)

### Problem
1. In `vendor/mpdf/mpdf/src/Mpdf.php` (`_getObjAttr()`), `$c = explode("\xbb\xa4\xac", $t, 2);` left a trailing 3-byte delimiter (`\xbb\xa4\xac`) attached to `$sp['objattr']`. PHP 8.3+ emits `Warning: unserialize(): Extra data starting at offset ...` when trailing bytes exist.
2. Any HTML/debug output or PHP warnings emitted before `$mpdf->Output()` causes `MpdfException: Data has already been sent to output`.

### Solution
1. **In `vendor/mpdf/mpdf/src/Mpdf.php` (line 9076):** Changed `$c = explode("\xbb\xa4\xac", $t, 2);` to `$c = explode("\xbb\xa4\xac", $t);` (compatible with PHP 7.3 and PHP 8.3+).
2. **In `patient_pdf_for_note_v2.php`:** Added `ob_start();` at line 2, moved `<link href="css/bootstrap.min.css" rel="stylesheet">` inside the HTML selector `else` branch, removed debug `echo` statements, and added `if (ob_get_length()) { ob_clean(); }` before `$mpdf->Output()`.

---

## 5. Production Database `CREATE TABLE` Privilege Handling (`classes/ServicePriceExcelFile.php`)

On production (`nbpathology.com`), the MySQL user may not have `CREATE` privileges (`SQLSTATE[42000]: 1142 CREATE command denied`). Always check table existence via `SELECT 1 FROM table_name LIMIT 1` inside `try ... catch`, wrap `CREATE TABLE IF NOT EXISTS` in `try ... catch`, and provide a safe fallback (such as `nb_price_8c_uploads/history_index.json`) if the table has not yet been created via phpMyAdmin.
