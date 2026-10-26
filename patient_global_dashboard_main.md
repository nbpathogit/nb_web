# Main Executive Dashboard (patient_global_dashboard_main.php)
**ไฟล์:** `C:\anuchit2\nb_web\dev3\patient_global_dashboard_main.php`  
**เอกสารประกอบและ Prompt สำหรับการโมดิฟายต่อ (Customization & Modification Prompt Guide)**

---

## 1. บทสรุปภาพรวมของระบบ (System Overview)

หน้า `patient_global_dashboard_main.php` เป็นแดชบอร์ดสรุปภาพรวมระดับผู้บริหาร (Executive Overview Dashboard) สำหรับระบบ **NB Patho (dev3)** ถูกออกแบบให้กระชับ โหลดเร็ว และเห็นภาพรวมทั้งปีในหน้าจอเดียว:

### คุณสมบัติหลักที่พัฒนา:
1. **การจัดวางแบบ 1 แถวแสดง 2 กราฟ (2 Charts per Row - 2x2 Grid):**
   - กราฟมีขนาดกะทัดรัดขึ้น (ความสูง 290px)
   - แสดงผลแบบ 2 คอลัมน์บนหน้าจอเดสก์ท็อป (`col-12 col-xl-6`) และปรับเป็น 1 คอลัมน์บนมือถือโดยอัตโนมัติ
2. **ฟิกช่วงเวลาย้อนหลัง 1 ปี แสดงผลรายเดือน (Fixed 1-Year Scope, Plot by Month):**
   - คำนวณช่วงเวลาย้อนหลัง 12 เดือนตามปฏิทินแบบอัตโนมัติ (เช่น พ.ย. 2568 - ต.ค. 2569)
   - แท่งกราฟแต่ละแท่งแสดงข้อมูลรวมของแต่ละเดือน
3. **ค่าเริ่มต้นแยกสีตามโรงพยาบาล (Default Colored by Hospital):**
   - **กราฟที่ 1, 2 และ 3** ตั้งค่าเริ่มต้น (Default) ให้**แยกสีตามโรงพยาบาล (`h_hospitial`)**
   - มี Dropdown ให้สลับใส่สีตาม: `ชื่อพยาธิผู้ออกผล`, `แพทย์ผู้ส่ง`, `คนรับเข้า`, หรือ `ไม่แยกสี (สีเดียว)`
4. **ตัดตารางคอมบิเนชั่นออก (No Combination Breakdown Table):**
   - ไม่แสดงตารางด้านล่างตามที่ระบุ ช่วยให้หน้าเว็บเบา โหลดเร็ว ไม่รกสายตา
5. **ลิงก์เชื่อมโยงไปยัง Full Dashboard:**
   - มีปุ่ม **"แดชบอร์ดแบบละเอียด (Full Dashboard)"** เพื่อข้ามไปยัง `patient_global_dashboard.php` ได้ทันที (และมีปุ่มข้ามกลับมายังหน้า Main Dashboard เช่นกัน)
6. **สถิติและเกณฑ์วัดครบถ้วน:**
   - ทุกกราฟแสดง **ค่าเฉลี่ย ($\bar{x}$ หรือ Mean &mu;)** และ **ส่วนเบี่ยงเบนมาตรฐาน ($\sigma$ หรือ Std Dev)**
   - มีเส้นประอ้างอิงค่าเฉลี่ย (Mean reference line) พาดผ่านบนกราฟทุกตัว
7. **ธีมแสดงผลแบบคู่ (Dark / Light Theme):**
   - ค่าเริ่มต้นเป็น **ธีมสีเข้ม (Dark Theme)**
   - สลับเป็นธีมสว่างได้ และจดจำการตั้งค่าผ่าน `localStorage`
8. **ปุ่มเต็มจอ (Fullscreen Mode) & ขยายกราฟเดี่ยว (Card Maximize):**
   - **ปุ่มเต็มจอ (Fullscreen Button):** อยู่บนแถบ Header Banner สลับหน้าจอสู่โหมดเต็มจอแบบไร้ขอบ (ซ่อน Sidebar/Navbar) ให้เห็นกราฟทั้ง 4 ตัวอย่างชัดเจน เหมาะสำหรับการฉาย Presentation/มอนิเตอร์
   - **ปุ่มขยายกราฟเดี่ยว (Card Maximize):** มุมขวาบนของการ์ดกราฟแต่ละตัว (`fa-maximize`) เมื่อกดจะขยายเฉพาะกราฟนั้นๆ ให้ลอยเต็มหน้าจอ (Overlay)
9. **การจำกัดสิทธิ์ข้อมูลโรงพยาบาล (Hospital Data Isolation):**
   - ใช้คลาสแยกอิสระใน `classes/Patientdash.php` โดยไม่กระทบกับ `patient.php`
   - **ผู้ใช้ศูนย์พยาธิวิทยา เอ็น.บี. (NB Pathology / Admin / Internal Staff):** ไม่กรองโรงพยาบาล สามารถดูข้อมูลภาพรวมได้ทุกโรงพยาบาล
   - **ผู้ใช้โรงพยาบาลลูกค้า (Customer / Non-NB Hospital):** ระบบจะกรองแสดงผลเฉพาะข้อมูลของโรงพยาบาลตนเองเท่านั้น (`p.phospital_id = $_SESSION['user']->uhospital_id`) เพื่อรักษาความเป็นส่วนตัวของข้อมูลลูกค้า แม้กรณีแอดมินจำลองผู้ใช้ (Simulation)

---

## 2. รายละเอียด 4 กราฟในหน้า Main Dashboard

| ตำแหน่ง | กราฟ | เมตริกหลัก | ค่าเริ่มต้น (Default) | Dropdown สลับมิติ |
|---|---|---|---|---|
| **แถวที่ 1 (ซ้าย)** | **Turn Around Time (TAT)** | ระยะเวลาออกผลเฉลี่ย (วัน) ในแต่ละเดือน | **แยกสีตามโรงพยาบาล** | โรงพยาบาล, พยาธิ, แพทย์ผู้ส่ง, คนรับเข้า, สีเดียว |
| **แถวที่ 1 (ขวา)** | **จำนวนเคสรับเข้า (Incoming)** | จำนวนเคสรับเข้า (`date_1000`) รายเดือน | **แยกสีตามโรงพยาบาล** (Stacked Bar) | โรงพยาบาล, พยาธิ, แพทย์ผู้ส่ง, คนรับเข้า, สีเดียว |
| **แถวที่ 2 (ซ้าย)** | **จำนวนการออกผล (Reported)** | จำนวนเคสออกผล (`date_first_report`) รายเดือน | **แยกสีตามโรงพยาบาล** (Stacked Bar) | โรงพยาบาล, พยาธิ, แพทย์ผู้ส่ง, คนรับเข้า, สีเดียว |
| **แถวที่ 2 (ขวา)** | **ภาระงานพยาธิแพทย์ (Caseload)** | ยอดรวมเคสออกผล 1 ปี **หนึ่งท่านหนึ่งแท่ง** | เรียงจากมากไปน้อย (สีเฉพาะแต่ละท่าน) | มี Tooltip แสดง TAT เฉลี่ยของแต่ละท่าน |

---

## 3. สถาปัตยกรรมโค้ด (Code Architecture)

- **Backend ฟังก์ชั่นแยกอิสระด้วย Prefix `pgdm_`:**
  - `pgdm_generate_12_month_buckets()`: สร้าง 12 เดือนย้อนหลัง พร้อมขอบเขตเวลาและชื่อเดือนภาษาไทย
  - `pgdm_fetch_patient_records($conn, $start_date, $end_date)`: ดึงข้อมูลผู้ป่วย 1 ปีย้อนหลังด้วย SQL Base
  - หุ้มด้วย `if (!function_exists('...'))` ป้องกันการประกาศซ้ำ
- **Frontend JavaScript `PGDM` Controller:**
  - จัดการ Theme สลับมืด/สว่าง
  - จัดการ Chart.js instances: `chartTat`, `chartIncoming`, `chartReported`, `chartPatho`
  - คำนวณสถิติสดบนเบราว์เซอร์: `calcMean()`, `calcStdDev()`, `calcMinMax()`
  - สลับ Dropdown มิติข้อมูลและเรนเดอร์กราฟใหม่ทันทีแบบเรียลไทม์

---

## 4. ตัวอย่าง Prompts สำหรับนำไปสั่งโมดิฟายต่อ (Ready-to-use Prompts)

สามารถคัดลอกข้อความด้านล่างนี้ไปสั่งใน AI Assistant เพื่อปรับแต่งหน้า `patient_global_dashboard_main.php`:

### 🎯 Prompt A: ปรับเพิ่มเกณฑ์ SLA เส้นสีแดงบนกราฟ TAT
```text
ในไฟล์ C:\anuchit2\nb_web\dev3\patient_global_dashboard_main.php
ในกราฟที่ 1 (Turn Around Time รายเดือน)
ต้องการเพิ่มเส้นเกณฑ์มาตรฐาน (Target SLA Benchmark) สีแดงประที่ระดับ 5 วัน
และใน Tooltip เมื่อชี้ที่แท่งกราฟของเดือนที่ TAT เกิน 5 วัน ให้แสดงข้อความเตือนว่า "(เกินเกณฑ์ SLA)"
```

### 🎯 Prompt B: เพิ่มตัวเลือกสลับเป็น 6 เดือน หรือ 2 ปี
```text
ในไฟล์ C:\anuchit2\nb_web\dev3\patient_global_dashboard_main.php
ที่แถบ Header ด้านบน ต้องการเพิ่มปุ่มกดสลับช่วงเวลาแบบด่วน:
- 6 เดือนล่าสุด
- 1 ปีย้อนหลัง (Default)
- 2 ปีย้อนหลัง
เมื่อกดแล้ว ให้โหลดข้อมูลตามช่วงเวลานั้นและอัปเดตกราฟทั้ง 4 กราฟ
```

### 🎯 Prompt C: เพิ่มตัวกรองประเภทสิ่งส่งตรวจ (SN, CN, LN)
```text
ในไฟล์ C:\anuchit2\nb_web\dev3\patient_global_dashboard_main.php
ต้องการเพิ่มตัวกรอง "ประเภทสิ่งส่งตรวจ" ที่ Header ด้านบน โดยมี Checkbox หรือ Dropdown:
- ทั้งหมด (Default)
- SN (Surgical)
- CN (Cytology)
- LN, FN, DN
เมื่อเลือกแล้ว ให้กรองข้อมูลกราฟทั้ง 4 ตัวตามประเภทสิ่งส่งตรวจที่เลือก
```

### 🎯 Prompt D: เพิ่มปุ่มดาวน์โหลดกราฟเป็นภาพ PNG
```text
ในไฟล์ C:\anuchit2\nb_web\dev3\patient_global_dashboard_main.php
ที่มุมขวาบนของการ์ดแต่ละกราฟ ให้เพิ่มปุ่มไอคอนกล้องถ่ายรูป/ดาวน์โหลด
เมื่อคลิก ให้บันทึกกราฟนั้นเป็นไฟล์รูปภาพ PNG (ความละเอียดสูง) เพื่อนำไปแปะในสไลด์นำเสนอของผู้บริหารได้ทันที
```

---

## 5. การตรวจสอบความถูกต้อง (Verification & Links)

- **ลิงก์เปิดใช้งานหน้านี้:**
  ```text
  http://localhost/nb_web/dev3/patient_global_dashboard_main.php
  ```
- **ลิงก์หน้าแดชบอร์ดแบบละเอียด:**
  ```text
  http://localhost/nb_web/dev3/patient_global_dashboard.php
  ```
- **การทดสอบความถูกต้องของไวยากรณ์ PHP:**
  ```powershell
  C:\xampp\php\php.exe -l C:\anuchit2\nb_web\dev3\patient_global_dashboard_main.php
  ```
