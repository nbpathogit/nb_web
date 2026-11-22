# Billing & Billing PDF V3 System Reference — NB Patho (`nb_web`)

## Part 1: Standard Billing Report (`billing.php` & `js/billing_a.js`)

- **Purpose:** Displays date-range billing reports and service cost tables per patient, clinician, hospital, or pathologist (typically filtered by accept date `p.date_1000` or billing date `b.create_date`).
- **Key Touchpoints:**
  - `billing.php`, `billing_by_billingdate.php` — Report UI and `daterangepicker` controls.
  - `js/billing_a.js` — Populates the DataTable (billing id, type, SN, HN, patient, clinician, hospital, pathologist, accept date, billing date, service type, code, description, block, cost).
  - `classes/ServiceBilling.php` — Core billing query model.

---

## Part 2: Billing PDF V3 System (`billing_pdf_3.php`)

The **Billing PDF V3** system generates official invoice documents (ใบแจ้งหนี้) for NB Pathology Center. It is **self-contained** with `_3`-suffixed files, independent of V1 (`billing_pdf.php`) and V2 (`billing_pdf_2.php`).

### File Map (All Files Under `C:\anuchit2\nb_web\dev3`)

1. **Main UI & PDF Generator:**
   - `billing_pdf_3.php`: Main UI page — select hospital, date range, fill invoice details, preview on web, and export PDF.
   - `billing_pdf_show_3.php`: Receives POST from `billing_pdf_3.php` and renders the PDF via **mPDF** (Pages 1–3 & 5 in Portrait A4, Page 4 in Landscape A4; chunks HTML at 1,000,000 bytes for `WriteHTML`).
2. **AJAX Endpoints (`ajax_billing_v3/` — V3-Exclusive):**
   - `getBillbyHospitalbyDateRange_3.php` — Fetch billing records for `#billing_table`
   - `getBillbyHospitalbyDateRangeSumPrice_3.php` — Total net price (`#bill_hospital_net_price`)
   - `getCostGroupbyServiceTyoebyHospitalbyDateRange_3.php` — Cost grouped by service type (`#bill_hospital_by_service_price`)
   - `getBillingGroupBySN_3.php` & `getBillingGroupBySNCount_3.php` — Group 2 table (by SN) & count
   - `getBillingGroupByCode_3.php` & `getBillingGroupByCodeCount_3.php` — Group 3 table (by code) & count
   - `getBillingHospitalOneByOne_3.php` — Group 4 itemized table (`drawbill_list_all_page_g4`)
   - `getHospital_3.php` — Hospital details (`#bill_hospitalname`, `#bill_hospital_taxid`, `#bill_hospital_address`)
   - `getCostSpelling_3.php` — Convert numeric cost to Thai baht text (`#bill_hospital_net_price_spell`)
   - `serviceBillingSetCode_3.php` — Update `b_code2` field on a billing record
3. **PDF Invoice Templates (`pdf_invoice/` — V3-Exclusive):**
   - `billingletter1_3.php` (`#bill_page1`, Portrait A4): Cover letter (หนังสือนำ)
   - `billingletterinvoice_3.php` (`#bill_page2` ต้นฉบับ & `#bill_page3` สำเนา, Portrait A4): Invoice letter
   - `billinngListAll_g2_3.php` (`#bill_page4`, Landscape A4): List by SN (Group 2)
   - `billinngListAll_g3_3.php` (`#bill_page5`, Portrait A4): Summary by billing code (Group 3)
   - `billinngListAll_g4_3.php` (`#bill_page6`, Web-only collapsible): Full itemized list (Group 4)

### 4-Step Invoice Generation Workflow in `billing_pdf_3.php`

1. **Step 1: Preview Bill by Date Range (`#btn_get_bill_by_range`)**
   - User selects hospital (`#phospital_id_bill`), start date (`#startdate_billing`), and end date (`#enddate_billing`).
   - Clicking this button executes a sequential chain of AJAX queries:
     1. `getBillbyHospitalbyDateRange_3.php` → Populates main `#billing_table` DataTable with export buttons (Excel, CSV, PDF).
     2. `getBillbyHospitalbyDateRangeSumPrice_3.php` → Populates `#bill_hospital_net_price` and `#bill_count_all_list`.
     3. `getCostGroupbyServiceTyoebyHospitalbyDateRange_3.php` → Builds editable summary table `#bill_hospital_by_service_price`.
     4. `getBillingGroupBySN_3.php` & `getBillingGroupBySNCount_3.php` → Renders Group 2 preview (`drawbill_list_all_page_g2`) and sets `#bill_count_bysn_list`.
     5. `getBillingGroupByCode_3.php` & `getBillingGroupByCodeCount_3.php` → Renders Group 3 preview (`drawbill_list_all_page_g3`) and sets `#bill_count_bycode_list`.
     6. `getBillingHospitalOneByOne_3.php` → Renders Group 4 itemized preview (`drawbill_list_all_page_g4`).
     7. `getHospital_3.php` → Fetches tax ID & address for `#bill_hospital_taxid` and `#bill_hospital_address`.
     8. Formats Thai dates (พ.ศ.) for `#bill_todaydate_thai`, `#bill_startdate_thai`, `#bill_enddate_thai`, `#bill_issuedate_thai`.
     9. `getCostSpelling_3.php` → Converts total amount into Thai Baht text spelling (`#bill_hospital_net_price_spell`).
     10. Sets manager name `#bill_manager` ("นาย อนุสรณ์ ชุมทอง") and alerts "done".

2. **Step 2: User Reviews & Edits Invoice Fields**
   - Fills invoice numbers (`#bill_invoice_sub_number`, `#bill_hospital_invoice_number`).
   - May manually adjust quantities/prices in `#bill_hospital_by_service_price`.

3. **Step 3: Preview on Web Page (`#btn_bill_preview_web`)**
   - Injects summary tables into `#bill_page1` (หนังสือนำ) and `#bill_page2` / `#bill_page3` (ใบแจ้งหนี้ ต้นฉบับ/สำเนา).
   - Syncs all `#bill_*` inputs into `.bill_*` display spans across all pages.

4. **Step 4: Generate Official PDF (`#btn_export_bill_pdf`) or Layout Preview (`#btn_export_bill_pdf_layout`)**
   - Collects `#bill_page1` through `#bill_page5` HTML and POSTs to `billing_pdf_show_3.php` for mPDF rendering.

---

### Rules When Modifying Billing PDF V3
1. **Never edit V1 or V2 files** when making changes for V3 — keep all edits within `_3`-suffixed files.
2. **To create V4**, clone all `_3` files to `_4` and add the V4 menu link in `includes/header.php`.
