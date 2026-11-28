# Database Management & SQL Query Console (`manage_database.php`)

## Overview

The `manage_database.php` module provides an administrative portal (restricted to User Group `1000`) for inspecting all database tables, examining column schemas, running custom `SELECT` queries with DataTables SearchPanes, and streaming large result sets directly to Excel (`.xls`) and CSV (`.csv`) without web-page plotting or browser memory limits.

---

## Key Features & Architecture

### 1. All Tables Overview (View 1)
- Shows all 36 database tables in `nbpa_data_dev3`.
- **Exact Row Counts:** Uses `SELECT COUNT(*)` for every table to provide 100% accurate row numbers (bypassing InnoDB's rough estimates in `information_schema.TABLES`).
- Displays Data size (KB), Index size (KB), Storage Engine, and Table Comments.
- **Direct 1-Click Excel Export:** Every row in the table list has an **Excel** button to stream the entire table directly without entering the view.

### 2. Single Table Viewer (View 2)
- Table quick-switcher dropdown showing row count for each table.
- **Total Records Badge:** Shows total rows in the database.
- **Columns Schema Collapsible:** Inspects `information_schema.COLUMNS` (Field, Type, Nullable, Primary/Unique/Index Key, Default, Extra, Comment).
- **Full Database Export Buttons:** Streams all records directly from the database into Excel or CSV.

### 3. SQL Query Console (Available on All Views)
- Located at the top of every view.
- Supports custom `SELECT`, `SHOW`, `DESCRIBE`, and `EXPLAIN` queries.
- **Security Check:** Blocks destructive statements (`INSERT`, `UPDATE`, `DELETE`, `DROP`, `ALTER`).
- **Benchmark:** Measures query execution time in milliseconds (`microtime`).
- **Interactive DataTable:**
  - SearchPanes organized in 5 columns per row (`layout: 'columns-5'`).
  - Search box, pagination, and export buttons.

### 4. Direct Streaming Export (Fastest, No Web Plotting)
- **`ดาวน์โหลดเป็น Excel โดยตรง`:** Generates Microsoft Excel XML Envelope table (`.xls`) with `DisplayGridlines` and `mso-number-format:\@;`.
- **`CSV`:** Streams RFC 4180 CSV with UTF-8 BOM (`EF BB BF`) for native Thai character rendering in Excel.
- **Buffer Cleaning:** Executes `while (ob_get_level()) { ob_end_clean(); }` before sending headers to prevent corrupt bytes.
- **0-Row Protection:** If a query returns 0 rows, it halts export and renders an alert banner on screen, preventing confusing empty file downloads.

### 5. Saved SQL Queries System (Database-backed)
- **Persistence:** Stored directly in MySQL database table `saved_sql_query` (`id`, `title`, `sql_query`, `created_by`, `stampdatecreate`, `stampdateedit`).
- **Save / Save As New:** Click "บันทึก SQL นี้" or "เซฟเป็นอันใหม่" to open the Bootstrap modal, enter/modify a title, and insert a new record into `saved_sql_query` via AJAX (`action_ajax=save_query`) recording `stampdatecreate`.
- **Update (Overwrite):** When editing a loaded query, the UI detects modifications (`มีการแก้ไข`) and offers the "อัพเดท (เซฟทับ)" button. Saving updates the existing record and updates `stampdateedit` via AJAX (`action_ajax=update_query`).
- **State Preservation Across Runs:** The active query ID (`loaded_sql_id`) is preserved during form POST (`Run SQL (แสดงบนเว็บ)`), ensuring that after reviewing query results on screen, the "อัพเดท (เซฟทับ)" and "เซฟเป็นอันใหม่" buttons remain active and ready to save.
- **Load Query:** Select a saved query from the dropdown to automatically populate the SQL textarea.
- **Delete Query:** With a saved query selected, click the red trash can button to delete from `saved_sql_query` via AJAX (`action_ajax=delete_query`).
- **Pre-seeded Templates:**
  1. `service_billing พร้อมชื่อผู้ป่วย (JOIN patient)`
  2. `ผู้ป่วย 100 รายล่าสุด (patient)`
  3. `สรุปจำนวนเคสและยอดเงิน แยกตามโรงพยาบาล (service_billing)`
  4. `รายการสไลด์พิเศษ 100 รายการล่าสุด (job4_special_slide)`

---

## Important Data Model Note: `service_billing`

In `service_billing`, the `name` and `lastname` fields are **empty string (`""`) for all 78,514 rows** because patient demographics are stored in the `patient` table. To query billing with patient names, use a `JOIN`:

```sql
SELECT 
    b.id, 
    b.number AS sn_number, 
    p.pname AS patient_name, 
    p.plastname AS patient_lastname, 
    p.phospital_num AS hn,
    b.code_description, 
    b.description, 
    b.cost, 
    b.import_date
FROM service_billing b
LEFT JOIN patient p ON b.patient_id = p.id
ORDER BY b.id DESC
LIMIT 100;
```
