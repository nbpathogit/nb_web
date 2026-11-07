# Service Price 8-Column Management (`nb_price_8c.php`)

> **วัตถุประสงค์หลัก (Core Purpose):** หน้านี้เป็นกลุ่มฟังก์ชันแยกต่างหาก มีจุดประสงค์เพื่อ**ใส่และจัดการราคาค่าบริการ (Service Pricing)** สำหรับโรงพยาบาลแต่ละแห่งและราคามาตรฐาน **เพื่อนําไปใช้คำนวณบิลและออกใบแจ้งหนี้ (Billing) ภายหลัง**

This reference details the architecture, data structures, Excel batch-import specifications, and AJAX endpoints for managing hospital-specific service prices and specimen catalogs via **`nb_price_8c.php`**.

---

## 1. Overview & System Purpose

`nb_price_8c.php` (**"ดูและจัดการรายการค่าบริการ8คอลั่ม"**) is the primary administrative interface for configuring pricing and catalog entries for pathology specimens and special stains across all client hospitals and standard baseline prices.

- **Primary Goal:** Maintain accurate pricing data in `service_price_list` so that clinical specimen orders (`ajax_slide1_specimen`) and special stain requests (`ajax_slide2__special`) accurately flow into patient billings (`service_billing`) and generate correct billing invoices (`billing_pdf_3.php`).
- **Menu Navigation:** Main Navigation &rarr; `จัดการตาราง และ ตั้งค่า` (`#manage_table`) &rarr; `ดูและจัดการรายการค่าบริการ8คอลั่ม` (`#price_tab_8c`).
- **Core Table:** `service_price_list`
- **Model Class:** `ServicePriceList` (`classes/ServicePriceList.php`)
- **Key Innovation over Legacy 6-Column (`nb_price.php`):**
  Legacy `nb_price.php` required selecting a single service type from a dropdown before pasting 6 columns. `nb_price_8c.php` embeds `service_id` (jobtype) and `service_description` directly in columns 7 and 8 of the pasted Excel rows, allowing **multi-category batch imports** in a single paste operation.

---

## 2. Authentication & Access Control

- **Session Guard:** Requires an active session via `Auth::requireLogin()`. Unauthenticated users receive an alert and execution halts (`die()`).
- **Role Restrictions (`user_auth.php`):**
  - Customer accounts (`$isCurUserClinicianCust` / `5000` and `$isCurUserHospitalCust` / `5100`) are **strictly blocked**:
    ```php
    if ($isCurUserClinicianCust || $isCurUserHospitalCust) {
        Util::alert("You have no authorize to access this page.");
    }
    ```
  - Only internal NB Patho staff (`$isCurUserNB`) and Administrators (`$isCurUserAdmin`) are authorized.
- **Activity Logging:** Writes an audit entry to the `log` table upon opening:
  ```php
  Log::add($conn, $_SESSION['log_username'], $_SESSION['log_name'], "open nb_price_8c.php", "");
  ```

---

## 3. Database Schema (`service_price_list`)

The page directly manipulates the `service_price_list` table:

```sql
CREATE TABLE `service_price_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(11) DEFAULT NULL COMMENT 'ลำดับ',
  `speciment_num` int(10) DEFAULT NULL COMMENT 'code',
  `code2` int(11) DEFAULT NULL COMMENT 'code2 (used in 9c)',
  `jobtype` tinyint(4) DEFAULT NULL COMMENT '1 ชิ้นเนื้อรับเข้า, 2 ย้อมพิเศษ (links to service_type.id)',
  `specimen` text DEFAULT NULL COMMENT 'รายละเอียดรายการ / specimen detail',
  `hospital_id` int(11) DEFAULT NULL COMMENT 'link to hospital id (0 = Standard Price)',
  `unit_count` varchar(30) DEFAULT NULL COMMENT 'หน่วยนับ (e.g. ชิ้น, ตัวอย่าง, สไลด์)',
  `price` int(11) DEFAULT NULL COMMENT 'ราคา (Baht)',
  `comment` text DEFAULT NULL COMMENT 'หมายเหตุ / ข้อคิดเห็น',
  `create_date` datetime DEFAULT NULL,
  `add_user_id` int(11) DEFAULT NULL COMMENT 'คนที่สร้างข้อมูลนี้',
  `edit_user_id` int(11) DEFAULT NULL COMMENT 'คนที่แก้ไขข้อมูลนี้',
  `service_des` varchar(256) DEFAULT NULL COMMENT 'ชื่อชนิดบริการ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
```

### Standard Hospital ID Convention
- `hospital_id = 0`: **ราคามาตรฐาน (Standard Baseline Price)**. Applies as default baseline pricing when a hospital lacks custom override rates.
- `hospital_id > 0`: **Hospital-specific custom pricing** linked to `hospital.id`.

---

## 4. Excel 8-Column Format Specification

When importing data via `#nb_price_add_txt_area_8c`, users copy tabular data directly from Microsoft Excel or Google Sheets (Tab-Separated Values `\t`):

### Column Layout

| Col Index | Header (Thai) | Field Name | Data Type | Description & Example |
| :---: | :--- | :--- | :--- | :--- |
| `0` | **ลำดับ** | `number` | `int` | Sequence number within the price sheet (e.g. `1`, `2`, `3`). |
| `1` | **code** | `speciment_num` | `int` | Primary item / procedure code (e.g. `33000`, `38001`). |
| `2` | **รายการ** | `specimen` | `text` | Item name and clinical description (e.g. `Biopsy or small specimen`). |
| `3` | **หน่วยนับ** | `unit_count` | `varchar` | Unit of measure (e.g. `ชิ้น`, `ตัวอย่าง`, `สไลด์`). |
| `4` | **ราคา** | `price` | `int` | Price in Thai Baht; commas automatically stripped (e.g. `400`, `1,200` &rarr; `1200`). |
| `5` | **comment** | `comment` | `text` | Notes, conditions, or price tier comments (e.g. `ราคามาตรฐาน`). |
| `6` | **service_id** | `jobtype` | `int` | Service category ID linking to `service_type.id` (1–8). |
| `7` | **service_description**| `service_des` | `varchar` | Category name (e.g. `ตรวจชิ้นเนื้อศัลยพยาธิวิทยา`, `ตรวจพิเศษ`). |

### Raw TSV Example
```text
1	33000	Biopsy or small specimen	ชิ้น	400	ราคามาตรฐาน	1	ตรวจชิ้นเนื้อศัลยพยาธิวิทยา
2	33001	Appendix	ชิ้น	500	ราคามาตรฐาน	1	ตรวจชิ้นเนื้อศัลยพยาธิวิทยา
3	34000	Special Stain (AFB)	สไลด์	200	ราคามาตรฐาน	2	ตรวจพิเศษ
```

---

## 5. UI Elements, Excel Download/Upload & Interactive Workflow

### A. Hospital & Service Type Selectors
1. **Hospital Dropdown (`#nb_price_hospital_select_8c`):**
   - Populated via `Hospital::getAll($conn)`.
   - Injects option `0` as `"ราคามาตรฐาน"`.
   - On change:
     - Enables buttons (`#nb_price_add_btn_8c`, `#nb_price_del_btn_8c`, `#nb_price_add_txt_area_8c`, `#nb_price_excel_btn_8c`, `#nb_price_upload_btn_8c`).
     - Resets service type dropdown to `0` (`แสดงทุกชนิดของโรงพยาบาลนี้`).
     - Updates heading `#price_of_hospital_8c` (`Price list of <hospital name>`).
     - Invokes `drawPriceTable()` and `loadExcelHistory()`.
2. **Service Type Dropdown (`#nb_price_type_8c`):**
   - Populated via `ServiceType::getAll_v2($conn)`.
   - Option `0`: `"แสดงทุกชนิดของโรงพยาบาลนี้"` (Show all types for this hospital).
   - On change:
     - Updates title spans with selected hospital and service type names.
     - Invokes `drawPriceTable()`.

### B. Table Display (`#nb_price_tbl_8c`)
Renders 8 visible columns synchronized with the Excel export/import headers, plus 3 hidden DOM columns (`style="display:none;"`) for debugging:
- **Hidden DOM Columns:** `row_id` (`id`), `hospital` (`hospital_id`), `create date` (`create_date`)
- **8 Visible Columns (matching Excel headers):**
  1. `ลำดับ(number)` (`number`)
  2. `โค้ด(code)` (`speciment_num`)
  3. `รายการ(detail)` (`specimen`)
  4. `ต่อหน่วย(unit)` (`unit_count`)
  5. `ราคา(price)` (`price`)
  6. `คอมเม้นท์(comment)` (`comment`)
  7. `ไอดีชนิด(type)` (`jobtype`)
  8. `ชนิดของรายการ(type Description)` (`service_des`)
- **Trailing Audit Columns:** `add by` (`add_user_id`), `edit by` (`edit_user_id`)

### C. Excel Download & Upload System (SheetJS + `ServicePriceExcelFile`)
1. **Download Excel (`#nb_price_excel_btn_8c`):**
   - Exports the currently displayed records in `#nb_price_tbl_8c` using SheetJS (`xlsx.full.min.js`) as an 8-column `.xlsx` file (`Price_8C_<hospital>_<type>_<YYYY-MM-DD>.xlsx`).
2. **Upload Excel (`#nb_price_upload_btn_8c` & `#nb_price_excel_file_input_8c`):**
   - Checkbox `#nb_price_replace_chk_8c` (`ทับข้อมูลเดิมเมื่ออัปโหลด`) is **checked and disabled by default** so uploads replace existing rows of the selected hospital/type cleanly.
   - Reads the `.xlsx`/`.xls` file on the client via SheetJS, validates every row, and posts both the parsed rows (`data`) and raw file (`excel_file`) via `FormData` to `ajax_nb_price_8c/upload_excel_8c.php`.
3. **Strict Row Validation Rules (Client-Side & Server-Side):**
   - `ลำดับ(number)`: Must be numeric only (`^\d+$`), non-empty.
   - `โค้ด(code)`: Must be numeric only (`^\d+$`), non-empty.
   - `รายการ(detail)`: Must be non-empty text.
   - `ราคา(price)`: Must be numeric (commas stripped, `^\d+(\.\d+)?$`), non-empty.
   - `ไอดีชนิด(type)`: Must be numeric (`^\d+$`) and **must exist in `service_type.id`** (`window.validServiceTypeIds` on client; `SELECT id FROM service_type` on server).
4. **Live `service_type` Reference Table:**
   - Displayed directly on `nb_price_8c.php` (columns `id` and `service_type`) alongside validation rules so users can verify valid `ไอดีชนิด(type)` values before uploading.
5. **Uploaded File History (`classes/ServicePriceExcelFile.php`):**
   - Stores uploaded `.xlsx` files in `nb_price_8c_uploads/` (tracked in Git via `.gitkeep`).
   - Logs metadata in table `service_price_excel_file` (with automatic JSON fallback to `nb_price_8c_uploads/history_index.json` if the DB user lacks `CREATE TABLE` privilege on production).
   - History table `#nb_price_excel_history_tbl_8c` lists the latest 20 uploaded files per hospital with download links via `ajax_nb_price_8c/download_excel_8c.php`.
6. **Legacy Paste Section:**
   - Wrapped in `<div id="legacy_paste_section_8c" style="display:none;">` (hidden in UI, preserved in code).

---

## 6. AJAX API Endpoints (`ajax_nb_price_8c/`)

### 1. Fetch Price Records (`get_specimen_8c.php`)
- **Request:** `POST` with `hospital_id` and `type_id`.
- **Backend Query:**
  ```php
  $specimens = ServicePriceList::getSpecimenByHospitalID($conn, $_POST['hospital_id'], $_POST['type_id']);
  ```
  - If `type_id == 0`:
    `SELECT * FROM service_price_list WHERE hospital_id = :h_id ORDER BY id;`
  - If `type_id != 0`:
    `SELECT * FROM service_price_list WHERE hospital_id = :h_id AND jobtype = :type_id ORDER BY id;`
- **Response:** JSON array of matching records.

### 2. Bulk Delete Records (`delSpecimen_8c.php`)
- **Trigger:** Click on `#nb_price_del_btn_8c` with `confirm("Are you sure to delete ?")`.
- **Request:** `POST` with `hospital_id` and `type_id`.
- **Backend Query:**
  ```php
  ServicePriceList::delSpecimenByHospitalID($conn, $_POST['hospital_id'], $_POST['type_id']);
  ```
  - Executes: `DELETE FROM service_price_list WHERE hospital_id = :h_id [AND jobtype = :type_id]`.
- **Response:** Updated (now empty or remaining) JSON list. Disables delete button if 0 rows remain.

### 3. Batch Multi-Record Insert (`addMultRecordiSpecimen_8c.php`)
- **Trigger:** Click on `#nb_price_add_btn_8c`.
- **Client Parsing (`nb_price_8c.js`):**
  - Trims and removes double-quotes from textarea content.
  - Splits text by lines (`\n`) and columns by tabs (`\t`).
  - Assembles JSON payload per line with:
    - `hospital_id`: From dropdown
    - `add_user_id`: From `get_cur_user_id()`
    - `edit_user_id`: `0`
  - Posts array of JSON objects (`data[]`) to endpoint.
- **Backend Assembly:**
  - Iterates `$data`, decodes each row, sanitizes strings via `addslashes()`, strips commas from prices.
  - Generates a single consolidated SQL insert:
    ```sql
    INSERT INTO `service_price_list`
    (`id`, `number`, `speciment_num`, `jobtype`, `specimen`, `hospital_id`, `unit_count`, `price`, `comment`, `create_date`, `add_user_id`, `edit_user_id`, `service_des`)
    VALUES
    (NULL, :number, :speciment_num, :jobtype, :specimen, :hospital_id, :unit_count, :price, :comment, :thai_time, :add_user_id, 0, :service_des), ...;
    ```
  - Executes via `ServicePriceList::createBySQL($conn, $sql)`.
  - Re-queries `getSpecimenByHospitalID()` and returns the newly refreshed dataset.
  - Clears textarea and displays `alert("Record added")`.

---

## 7. Comparative Feature Matrix (`nb_price*.php`)

| Screen File | Columns | Menu Status | Key Format / Features |
| :--- | :---: | :--- | :--- |
| **`nb_price.php`** | **6** | Disabled / Commented out | `ลำดับ -> code -> รายการ -> หน่วยนับ -> ราคา -> comment`<br>*Legacy: requires pre-selecting single service type from dropdown.* |
| **`nb_price_8c.php`** | **8** | **Active (Primary)** | `ลำดับ -> code -> รายการ -> หน่วยนับ -> ราคา -> comment -> service_id -> service_description`<br>*Embeds service type in each row; supports mixed multi-category imports.* |
| **`nb_price_9c.php`** | **9** | **Active (Advanced)** | `ลำดับ -> code -> code2 -> รายการ -> หน่วยนับ -> ราคา -> comment -> service_id -> service_description`<br>*Adds secondary hospital billing code (`code2`).* |
| **`nb_price_view.php`**| **13** | **Active (Read-Only)** | Filterable DataTables viewer joining `service_price_list`, `hospital`, `service_type`, and `user` (creator/editor). |

---

## 8. Downstream Integration in Pathology Workflows

Data stored in `service_price_list` directly fuels patient specimen entry and billing generation:

1. **Slide 1 / Specimen Selection (`ajax_slide1_specimen/getSpecimenList1.php`):**
   Calls `ServicePriceList::getSpecimenByHospitalIDJoptypeSpecimen($conn, $hospital_id)` to populate the gross examination specimen dropdown where `jobtype IN (1, 4, 5, 8)`.
2. **Slide 2 / Special Slide Selection (`ajax_slide2__special/getSpecimenList2.php`):**
   Calls `ServicePriceList::getSpecimenByHospitalIDJoptypeSpecialStainging($conn, $hospital_id, $service_id)` to populate special stains dropdown where `jobtype IN (2, 3, 6, 7)`.
3. **Billing Invoice (`service_billing`):**
   Billing line items copy `code_description`, `description`, and `cost` directly from the records maintained in `service_price_list`.
