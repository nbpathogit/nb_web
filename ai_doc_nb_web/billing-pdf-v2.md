# Billing & Billing PDF V2 System Reference — NB Patho (`nb_web`)

## Overview

The **Billing PDF V2** system (`billing_pdf_2.php`) generates official invoice documents (ใบแจ้งหนี้) for NB Pathology Center. It represents the second generation of invoice generation, sitting between legacy V1 (`billing_pdf.php`) and modern self-contained V3 (`billing_pdf_3.php`).

- **Main UI File:** `billing_pdf_2.php`
- **Active Navigation Menu:** `includes/header.php` -> `#manage_bill_dropdown` -> `<a id="billing_pdf_tab_2" href="billing_pdf_2.php">ออกใบแจ้งหนี้ V2(PDF)</a>`
- **Shared PDF Generator:** `billing_pdf_show.php` (shared with V1)
- **Shared Templates:** `pdf_invoice/` (base templates without `_3` suffix)

---

## Detailed Execution Flow: "1) preview bill by date range" (`#btn_get_bill_by_range`)

When the user clicks `#btn_get_bill_by_range` in `billing_pdf_2.php` (lines 464–995), the following sequence runs:

### 1. Read Filter Inputs
- Extracts selected `hospital_id` and `hospital_name` from `#phospital_id_bill`.
- Extracts `startdate` and `enddate` from `#startdate_billing` and `#enddate_billing`.

### 2. Populate Reference DataTable (`#billing_table`)
- Reinitializes the DataTable on `#billing_table` via DataTables AJAX:
  ```text
  ajax_billing/getBillbyHospitalbyDateRange.php?skey=...&hospital_id=...&startdate=...&enddate=...
  ```
- Configures export buttons for Excel, CSV, and PDF at the bottom of the page.
- Enables inline double-click editing on `code2` column (target 14) which opens `#editNumberModal`.

### 3. Sequential Synchronous AJAX Requests (`async: false`)

The browser executes 7 synchronous AJAX calls in strict succession:

1. **`ajax_billing/getBillbyHospitalbyDateRangeSumPrice.php`**
   - Retrieves total net amount (`bcost`) and total service item count (`bcount`).
   - Stores result in local variable `net_price`.

2. **`ajax_billing/getCostGroupbyServiceTyoebyHospitalbyDateRange.php`**
   - Retrieves total cost aggregated by service type (`service_type_bill`, `bcost_count`, `bcost_sum`).
   - Builds an HTML summary table and appends it to `#bill_hospital_by_service_price`.

3. **`ajax_billing/getBillingGroupBySN_2.php`** (V2 Key Innovation: ตารางนับตาม SN)
   - ดึงข้อมูลแบบ Hierarchical Parent-Children ผ่าน **`classes/ServiceBilling.php`**:
     1. `ServiceBilling::getBillbyHospitalbyDateRangeGroupBySN_2()` (L935) -> ดึงเคสหลักจัดกลุ่มตาม SN (`p_sn`)
     2. วนลูป `foreach ($billings as $key => $billing)` ดึงรายการย่อย x2:
        - `ServiceBilling::getBillbyHospitalbyDateRangeGroupBySN_2_subarray_nm()` (L1561) -> ดึงรายการย้อมปกติ (`service_typea_id = 1`)
        - `ServiceBilling::getBillbyHospitalbyDateRangeGroupBySN_2_subarray_sp()` (L1652) -> ดึงรายการย้อมพิเศษ (`service_typea_id = 2`)
     3. รวมอาร์เรย์ลูกเข้าด้วยกัน `$billings[$key]['subarray'] = array_merge($aa, $bb)` แล้วส่งกลับแบบ JSON
   - ฝั่ง Client เรียกฟังก์ชัน **`drawbill_list_all_page_g2(datajson)`** (L1249) เพื่อวาดตาราง Landscape A4 ใน `#bill_list_all_g2` โดยผสานเซลล์ด้วย `rowspan="'+subarray.length+'"`

4. **`ajax_billing/getBillingGroupBySNCount.php`**
   - Queries the total count of distinct SN cases in the date range.
   - Populates `#bill_count_bysn_list`.

5. **`ajax_billing/getBillingGroupByCode.php`**
   - Aggregates cases by Ministry of Finance official code (รหัสกรมบัญชีกลาง).
   - Calls `drawbill_list_all_page_g3(datajson)` to construct Page 5 (Group 3) summary table, including unit price column ("ราคาต่อหน่วย(บาท)"), injected into `#bill_list_all_g3`.

6. **`ajax_billing/getBillingGroupByCodeCount.php`**
   - Queries the total count of distinct billing code entries.
   - Populates `#bill_count_bycode_list`.

7. **`ajax_billing/getBillingHospitalOneByOne.php`**
   - Fetches full itemized case breakdown.
   - Calls `drawbill_list_all_page_g4(datajson)` to construct Page 6 (Group 4) itemized table injected into `#bill_list_all_g4`.

8. **`ajax_billing/getHospital.php`**
   - Retrieves hospital metadata: full name, tax ID (`tax_id`), and billing address (`address`).

### 4. Date Formatting & Parameter Population
- Formats dates into Thai Buddhist Era format (e.g., `27 พฤศจิกายน 2569`):
  - `#bill_todaydate_thai`: Today's date
  - `#bill_startdate_thai`: Formatted start date
  - `#bill_enddate_thai`: Formatted end date
  - `#bill_issuedate_thai`: Issue date
- Populates hospital parameters:
  - `#bill_hospitalname`: Hospital name
  - `#bill_hospital_taxid`: Hospital Tax ID
  - `#bill_hospital_address`: Hospital billing address
- Populates financial parameters:
  - `#bill_hospital_net_price`: Net price numeric value
  - Calculates and populates Thai Baht text spelling into `#bill_hospital_net_price_spell` via `ajax_billing/getCostSpelling.php` (or client spelling).
  - `#bill_count_all_list`: Total item count.
  - `#bill_manager`: Manager name (`นาย อนุสรณ์ ชุมทอง`).

### 5. Completion Alert
- Displays browser alert:
  ```javascript
  alert("Done Preview step 1.");
  ```
- Unblocks the user to proceed to **Step 2 (`2) Preview on web page`)** to populate Pages 1, 2, and 3.

---

## Key Template & Component Touchpoints

| Template File | Page / Section | Target Element | Description |
|---|---|---|---|
| `pdf_invoice/billingletter1.php` | Page 1 (หนังสือนำ) | `#bill_page1` | Cover letter with service summary table |
| `pdf_invoice/billingletterinvoice.php` | Page 2 (ใบแจ้งหนี้ ต้นฉบับ) | `#bill_page2` | Official invoice cover (original) |
| `pdf_invoice/billingletterinvoice.php` | Page 3 (ใบแจ้งหนี้ สำเนา) | `#bill_page3` | Official invoice cover (copy) |
| `pdf_invoice/billinngListAll_g2.php` | Page 4 (Group 2) | `#bill_list_all_g2` | Case list sorted by Surgical Number (Landscape A4) |
| `pdf_invoice/billinngListAll_g3.php` | Page 5 (Group 3) | `#bill_list_all_g3` | Summary table by Comptroller General code with unit price |
| `pdf_invoice/billinngListAll_g4.php` | Page 6 (Group 4) | `#bill_list_all_g4` | Full itemized case breakdown (Web view) |

---

## Critical Maintenance Warning
Because V2 shares `pdf_invoice/` templates and `ajax_billing/` files with legacy V1, any modifications to shared files directly impact both systems. For new independent features, always develop inside the isolated V3 architecture (`billing_pdf_3.php` and `*_3.php`).
