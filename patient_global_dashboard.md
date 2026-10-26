# Patient Global Dashboard (patient_global_dashboard.php)
**ไฟล์:** `C:\anuchit2\nb_web\dev3\patient_global_dashboard.php`  
**เอกสารประกอบและ Prompt สำหรับการโมดิฟายต่อ (Customization & Modification Prompt Guide)**

---

## 1. บทสรุปภาพรวมของระบบ (System Overview)

หน้า `patient_global_dashboard.php` เป็นหน้าผู้บริหารและแดชบอร์ดสถิติวิเคราะห์ (Executive Analytics Dashboard) สำหรับระบบ **NB Patho (dev3)** ถูกพัฒนาขึ้นด้วยสถาปัตยกรรม **Standalone & Self-Contained** โดยแยกฟังก์ชั่นทั้งหมดเป็นเอกเทศด้วยคำนำหน้า `pgd_` เพื่อหลีกเลี่ยงการกระทบกับฟังก์ชั่นเดิมของระบบ

### คุณสมบัติหลักที่พัฒนาเสร็จสมบูรณ์:
1. **ธีมแสดงผลแบบคู่ (Dual Theme):**
   - **ธีมมืด (Dark Theme - ค่าเริ่มต้น):** โทนสี Midnight Slate (`#090d16`, `#151d30`) ถนอมสายตา ดูพรีเมียม
   - **ธีมสว่าง (Light Theme):** โทนสี Crisp Slate (`#f8fafc`, `#ffffff`)
   - มีปุ่มสลับธีม 🌙/☀️ พร้อมบันทึกสถานะลงใน `localStorage`
2. **ตัวกรองช่วงเวลาอัจฉริยะ (Time Interval & Scope Filter):**
   - **รายสัปดาห์ (by weeks - ค่าเริ่มต้น):** ตัวเลือกย้อนหลัง 6 (Default), 12, 24, 32 สัปดาห์
   - **รายเดือน (by month):** ตัวเลือกย้อนหลัง 6 (Default), 12 เดือน
   - **ขอบเขตเวลา (Scope / Year):** เลือกระหว่าง **ช่วงเวลาย้อนหลัง (Rolling จากปัจจุบัน)** หรือ **สโคปเฉพาะรายปี** (เช่น 2026 / พ.ศ. 2569, 2025, 2024 โดยดึงปีที่มีข้อมูลในระบบโดยอัตโนมัติ)
3. **4 กราฟแท่งหลัก (4 Core Bar Charts):**
   - **กราฟที่ 1: Turn Around Time (ระยะเวลาออกผลตรวจ):**
     - ค่าเริ่มต้น: แท่งสีเดียว Cyan แสดง TAT เฉลี่ยในแต่ละรอบเวลา
     - Dropdown เลือกใส่สี/แยกมิติ: `ชื่อพยาธิผู้ออกผล`, `โรงพยาบาล`, `แพทย์ผู้ส่ง`, `คนรับเข้า`
     - แสดงเส้นประค่าเฉลี่ยรวม (Overall Mean Line) บนกราฟ
   - **กราฟที่ 2: จำนวนเคสรับเข้า (Incoming Cases):**
     - นับตามวันที่รับเข้า `date_1000`
     - ค่าเริ่มต้น: แท่งสีเดียว Emerald แสดงจำนวนเคสรับเข้ารวม
     - Dropdown เลือกใส่สี/แยกมิติ: แสดงเป็น Stacked Bar Chart แยกตามสัดส่วน
     - แสดงเส้นประค่าเฉลี่ยเคสรับเข้าต่อรอบ
   - **กราฟที่ 3: จำนวนการออกผล (Reported Cases):**
     - นับตามวันที่ออกผล `date_first_report`
     - ค่าเริ่มต้น: แท่งสีเดียว Amber แสดงจำนวนเคสออกผลรวม
     - Dropdown เลือกใส่สี/แยกมิติ: แสดงเป็น Stacked Bar Chart แยกตามสัดส่วน
     - แสดงเส้นประค่าเฉลี่ยเคสออกผลต่อรอบ
   - **กราฟที่ 4: จำนวนการออกผลของพยาธิแต่ละท่าน (Pathologist Caseload):**
     - แสดง **หนึ่งท่านหนึ่งแท่ง** เรียงลำดับจากภาระงานมากไปน้อย
     - คำนวณ TAT เฉลี่ยของพยาธิแต่ละท่านแสดงใน Tooltip และตาราง
4. **การคำนวณสถิติครบถ้วนทุกกราฟ (Statistical KPIs):**
   - **ค่าเฉลี่ย ($\bar{x}$ หรือ Mean &mu;)**
   - **ส่วนเบี่ยงเบนมาตรฐาน ($\sigma$ หรือ Std Dev)**
   - ค่าต่ำสุด (Min), ค่าสูงสุด (Max), ยอดรวม (Total), รอบเวลาสูงสุด (Peak)
5. **ตาราง Combination Breakdown ใต้กราฟ:**
   - ปุ่มคลิก แสดง/ซ่อน (Show/Hide Collapse) แยกแต่ละกราฟ
   - ช่องค้นหาด่วนแบบ Realtime ภายในตาราง
   - ปุ่มส่งออกข้อมูลเป็นไฟล์ Excel/CSV (UTF-8 with BOM รองรับภาษาไทยสมบูรณ์)
6. **ปุ่มเต็มจอ (Fullscreen Mode) & ขยายกราฟเดี่ยว (Card Maximize):**
   - **ปุ่มเต็มจอ (Fullscreen Button):** อยู่บนแถบ Header Banner เมื่อกดแล้วระบบจะเข้าสู่โหมดเต็มจอระดับเบราว์เซอร์ พร้อมซ่อน Sidebar/Navbar อัตโนมัติเพื่อให้เห็นเฉพาะแดชบอร์ดเต็มพื้นที่หน้าจอ พร้อมปรับขนาด Chart Canvas อัตโนมัติ (สลับกลับได้ด้วยปุ่มย่อจอ หรือกด ESC)
   - **ปุ่มขยายกราฟเดี่ยว (Card Maximize):** อยู่ที่มุมขวาของหัวการ์ดแต่ละกราฟ (`fa-maximize`) เมื่อกดจะขยายเฉพาะกราฟนั้นๆ ให้ลอยเต็มหน้าจอ (Overlay) พร้อมตารางสรุป
7. **การจำกัดสิทธิ์ข้อมูลโรงพยาบาล (Hospital Data Isolation):**
   - พัฒนาแยกเป็นคลาสอิสระใน `classes/Patientdash.php` โดยไม่กระทบกับ `patient.php`
   - **ผู้ใช้ศูนย์พยาธิวิทยา เอ็น.บี. (NB Pathology / Admin / Internal Staff):** ไม่กรองโรงพยาบาล สามารถดูข้อมูลภาพรวมได้ทุกโรงพยาบาลทั่วประเทศ
   - **ผู้ใช้โรงพยาบาลลูกค้า (Customer / Non-NB Hospital):** ระบบจะตรวจจับและกรองแสดงผลเฉพาะข้อมูลของโรงพยาบาลตนเองเท่านั้น (`p.phospital_id = $_SESSION['user']->uhospital_id`) เพื่อความปลอดภัยและความเป็นส่วนตัวของข้อมูลลูกค้า แม้กรณีแอดมินจำลองผู้ใช้ (Simulation) ก็จะจำลองมุมมองของลูกค้ารายนั้นอย่างถูกต้อง

---

## 2. โครงสร้างคำสั่ง SQL ฐานข้อมูล (Database Query)

แดชบอร์ดใช้คำสั่ง SQL สำหรับดึงข้อมูลผู้ป่วยที่เชื่อมโยงกับมิติต่างๆ ดังนี้:

```sql
SELECT 
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
```

---

## 3. สถาปัตยกรรมโค้ด (Code Architecture)

ไฟล์ `patient_global_dashboard.php` ถูกแบ่งออกเป็น 4 ส่วนหลัก:

### ส่วนที่ 1: PHP Backend Helper Functions
- `pgd_get_available_years($conn)`: ค้นหาปีที่มีข้อมูลในระบบแบบไดนามิก
- `pgd_generate_period_buckets($time_unit, $period_count, $year_scope)`: สร้างชุดข้อมูลช่วงเวลา (ISO Weeks หรือ Month) พร้อมคำนวณวันเริ่มต้น-สิ้นสุด และชื่อสัปดาห์/เดือนภาษาไทย
- `pgd_fetch_patient_records($conn, $start_date, $end_date)`: รันคำสั่ง SQL ดึงข้อมูลดิบในช่วงเวลาที่กำหนด

### ส่วนที่ 2: CSS Theming & Responsive Layout
- CSS Variables ใน `:root` และ `.pgd-theme-light`
- รองรับขนาดหน้าจอทุกระดับ (Desktop, Laptop, Tablet, Mobile)
- Glassmorphism Cards พร้อมเงาและสีที่ตัดกันอย่างลงตัว

### ส่วนที่ 3: HTML Markup
- Top Banner Header พร้อมปุ่มสลับธีมและปุ่มนำทาง
- Filter Controls Bar (สลับ Weeks/Month, Dropdown ช่วงเวลา, Dropdown ปี)
- การ์ดแสดงผลทั้ง 4 การ์ด (Header, Dropdown แยกสี, แถบ KPI สถิติ, Canvas กราฟ, ส่วนตารางซ่อนได้)

### ส่วนที่ 4: JavaScript & Chart.js Engine (`PGD`)
- วัตถุ `PGD` จัดการ State และ Chart Instances ทั้งหมด:
  - `PGD.init()`: เริ่มต้นโหลดธีมและเรนเดอร์กราฟ
  - `PGD.calcMean()`, `PGD.calcStdDev()`, `PGD.calcMinMax()`: คำนวณสถิติ
  - `PGD.updateTatChart()`: อัปเดตกราฟ TAT และตาราง Breakdown
  - `PGD.updateIncomingChart()`: อัปเดตกราฟเคสรับเข้า
  - `PGD.updateReportedChart()`: อัปเดตกราฟเคสออกผล
  - `PGD.updatePathoChart()`: อัปเดตกราฟพยาธิแพทย์
  - `PGD.filterTable()`: กรองค้นหาในตาราง
  - `PGD.exportTableToCSV()`: ดาวน์โหลดตารางเป็น CSV

---

## 4. ตัวอย่าง Prompts สำหรับนำไปสั่ง AI เพื่อโมดิฟายต่อ (Ready-to-use Continuation Prompts)

สามารถคัดลอก Prompt ด้านล่างนี้ไปสั่งในแชทเมื่อต้องการแก้ไขหรือเพิ่มฟีเจอร์ใหม่:

### 🎯 Prompt A: เพิ่มตัวกรองความด่วน (Priority Filter)
```text
ในไฟล์ C:\anuchit2\nb_web\dev3\patient_global_dashboard.php
ต้องการเพิ่มตัวกรอง "ความเร่งด่วน (Priority)" ใน Filter Bar ด้านบน
โดยมีตัวเลือก:
- ทั้งหมด (Default)
- ปกติ
- ด่วน
- ด่วนที่สุด
เมื่อผู้ใช้เลือกแล้ว ให้ส่งค่าไปกรองใน SQL WHERE clause (pri.priority = :priority) หรือกรองที่ฝั่ง JavaScript
และอัปเดตข้อมูลกราฟทั้ง 4 กราฟตามความด่วนที่เลือก
```

### 🎯 Prompt B: เพิ่มเส้นเป้าหมาย SLA Benchmark ให้กับกราฟ TAT
```text
ในไฟล์ C:\anuchit2\nb_web\dev3\patient_global_dashboard.php
ในกราฟที่ 1 (Turn Around Time)
ต้องการเพิ่มเส้นเป้าหมายเกณฑ์มาตรฐาน (Target SLA Line) สีแดงประที่ระดับ 5.0 วัน (หรือให้มี Input ให้ผู้ใช้กรอกตัวเลขเป้าหมายได้เอง)
และในตาราง Breakdown ให้ใส่สัญลักษณ์ไฮไลท์สีแดงแถวที่มี TAT เฉลี่ยเกินกว่าเกณฑ์มาตรฐาน
```

### 🎯 Prompt C: เพิ่มตัวเลือกมิติ "ประเภทสิ่งส่งตรวจ (Specimen SN Type)"
```text
ในไฟล์ C:\anuchit2\nb_web\dev3\patient_global_dashboard.php
ใน Dropdown "ใส่สีตาม" ของกราฟที่ 1, 2, 3
ต้องการเพิ่มตัวเลือก:
- ประเภทสิ่งส่งตรวจ (p_sn_type เช่น SN, CN, LN, FN, DN)
ให้กราฟสามารถตัดกลุ่มและลงสีตามประเภทสิ่งส่งตรวจได้เหมือนกับพยาธิและโรงพยาบาล
```

### 🎯 Prompt D: เพิ่มปุ่มพิมพ์รายงานหรือ Export หน้าแดชบอร์ดเป็น PDF
```text
ในไฟล์ C:\anuchit2\nb_web\dev3\patient_global_dashboard.php
ต้องการเพิ่มปุ่ม "พิมพ์รายงาน / พิมพ์ PDF" ที่มุมขวาบนของหน้า
เมื่อกดปุ่ม ให้แสดงหน้า Print Preview ที่จัดหน้าให้อ่านง่าย ซ่อนปุ่ม Filter ที่ไม่จำเป็น และจัดกราฟและตารางให้อยู่ในหน้า A4 สวยงาม
```

### 🎯 Prompt E: เพิ่มระบบ Drilldown คลิกที่แท่งกราฟแล้วดูรายชื่อเคส
```text
ในไฟล์ C:\anuchit2\nb_web\dev3\patient_global_dashboard.php
ต้องการให้เมื่อคลิกที่แท่งกราฟของสัปดาห์ใด หรือแท่งของพยาธิท่านใด
ให้เปิด Modal แสดงรายชื่อเคสทั้งหมดในกลุ่มนั้น (ประกอบด้วย pnum, ชื่อ-นามสกุล, โรงพยาบาล, วันที่, TAT, สถานะ) พร้อมลิงก์คลิกไปหน้า patient_edit.php?id=XXX ได้ทันที
```

---

## 5. การตรวจสอบความถูกต้อง (Verification & Testing)

- **การทดสอบความถูกต้องของไวยากรณ์ PHP:**
  ```powershell
  C:\xampp\php\php.exe -l C:\anuchit2\nb_web\dev3\patient_global_dashboard.php
  ```
- **การเข้าใช้งานผ่านเบราว์เซอร์:**
  ```text
  http://localhost/nb_web/dev3/patient_global_dashboard.php
  ```
- **การทดสอบพารามิเตอร์ URL ตัวอย่าง:**
  - `patient_global_dashboard.php?time_unit=weeks&period_count=12&year_scope=rolling`
  - `patient_global_dashboard.php?time_unit=month&period_count=12&year_scope=2025`
  - `patient_global_dashboard.php?time_unit=weeks&period_count=32&year_scope=2026`
