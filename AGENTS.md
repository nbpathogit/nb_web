# AGENTS.md

คู่มือสำหรับ AI agent ที่ทำงานกับโปรเจคนี้

## ภาพรวมโปรเจค

**NB Patho (nb_web)** — ระบบ Laboratory Information System (LIS) สำหรับห้องปฏิบัติการ Pathology / Cytology

- **ภาษา:** PHP 7.4+ (มี typed properties) — **ไม่ใช้ framework** เป็น vanilla PHP
- **ฐานข้อมูล:** MySQL 5.7 (utf8) ผ่าน PDO (บางจุดใช้ mysqli)
- **Frontend:** Bootstrap 5, jQuery, DataTables (CDN + local), Font Awesome, Selectize, Tempus Dominus
- **PDF:** mPDF และ TCPDF (สองตัวใช้คู่กัน ผ่าน composer)
- **UI:** ภาษาไทย (`lang="th"`), ฟอนต์ TH Sarabun New (`fonts/THSarabunNew*.ttf`), primary color `#009CFF`

## การรันโปรเจค (Docker)

```bash
docker compose up -d
```

| Service | URL / Port | หมายเหตุ |
|---|---|---|
| app (PHP 7.4 + Apache) | http://localhost (**ต้อง port 80 เท่านั้น**) | เข้าผ่าน `:8080` ไม่ได้ เพราะ `Database.php` เช็ค `HTTP_HOST == "localhost"` แบบตรงตัว |
| db (MySQL 5.7) | 3306 | db: `nbpa_data`, user: `nbpa_nbpatho` |
| phpMyAdmin | http://localhost:8081 | |
| ftp | 21 | |

- app กับ db ใช้ network namespace เดียวกัน (`network_mode: service:db`) เพื่อให้ `localhost` ใน container ชี้มาที่ MySQL
- Volume mount ทั้ง repo ไปที่ `/var/www/html` — แก้โค้ดแล้วรีเฟรชได้เลย ไม่ต้อง rebuild (ยกเว้นแก้ `docker/php/`)

## โครงสร้างไดเรกทอรี

```
nb_web/
├── *.php                  # หน้าเว็บหลัก (patient.php, billing*.php, job*.php ฯลฯ)
├── classes/               # โมเดล/โดเมนคลาส 1 ไฟล์ 1 คลาส (autoload)
├── includes/              # shared: init.php, db.php, header.php, footer.php,
│                          #   patient_form_XXX_*.php (ฟอร์มแยกส่วนตามลำดับขั้นเวิร์กโฟลว์)
├── ajax_<feature>/        # AJAX endpoints แยกเป็นโฟลเดอร์ตามฟีเจอร์ (ajax_patient, ajax_billing, ...)
├── css/ js/ lib/ scss/    # assets
├── fonts/                 # ฟอนต์ไทยสำหรับ mPDF/TCPDF
├── docker/                # Dockerfile + config สำหรับ docker compose
├── docs/                  # design-system.md, design-system-css-reference.md
├── vendor/                # composer (ห้ามแก้)
├── sandbox/ sandbox2/     # พื้นที่ทดลองโค้ด
├── release1/ dbg/         # release/debug scratch — ห้ามแตะ
└── nbproject/             # NetBeans project meta — ห้ามแตะ
```

## Convention ที่ต้องถือ

### PHP bootstrap (ทุกหน้า)

```php
<?php
require '../includes/init.php';      // autoload + session_start (ปรับ path ตามความลึก)
$conn = require '../includes/db.php'; // คืน PDO connection
```

- Autoload: `spl_autoload_register` โหลดคลาสจาก `classes/{ClassName}.php` — ตั้งชื่อไฟล์ให้ตรงชื่อคลาสเป๊ะ (PascalCase)
- โฟลเดอร์ `classes/` และ `includes/` มี `.htaccess` ป้องกันการเข้าถึงโดยตรง — ห้ามลบ

### AJAX endpoint

- วางใน `ajax_<feature>/` ที่เกี่ยวข้อง ชื่อไฟล์ camelCase เช่น `getHospital.php`
- รับ `$_POST` / `$_GET` → เรียก method ของคลาส → `echo json_encode(...)` แล้วจบ

### คลาสโมเดล (`classes/`)

- รับ `$conn` (PDO) เป็นพารามิเตอร์แรกของ method เสมอ
- Static method เช่น `Patient::getAll($conn, ...)`, `ServiceBilling::getBillbyHospitalbyDateRange($conn, ...)`
- SQL เขียนในคลาส ใช้ prepared statements (`prepare` + `execute`)

### Auth / Logging

- ตรวจสิทธิ์ด้วย `Auth::requireLogin(...)` (หลายไฟล์ถูก comment ไว้ — ระวังเรื่อง security ตอนแก้)
- Log การใช้งาน: `Log::add($conn, $_SESSION['log_username'], $_SESSION['log_name'], "<action>", "")`

## จุดที่ต้องระวัง (Gotchas)

1. **Multi-tenant ผ่าน subfolder:** `Url::getSubfolder()` ถ้ามี subfolder จะต่อท้ายชื่อ db เป็น `nbpa_data_<subfolder>` — โค้ด URL ที่เกี่ยวกับ path/db ต้องคำนึงถึงเรื่องนี้เสมอ ใช้ `Url::getSubFolder1()` ตอนสร้าง link
2. **DB credentials กระจายอยู่ 2 ที่:** `classes/Database.php` (hardcode ต่อ host) และ `docker-compose.yml` — ถ้าเปลี่ยนต้องแก้ทั้งคู่
3. **`Database.php` เลือก credential ตาม `$_SERVER['HTTP_HOST']`** (localhost / nb_web.localhost / nbpatho.iotkiddie.com / nbpathology.com) — อย่า break เงื่อนไขชุดนี้
4. **Charset ต้องเป็น utf8** ทั้ง DSN, MySQL server (`--character-set-server=utf8`) และไฟล์ PHP — ข้อมูลเป็นภาษาไทยทั้งหมด
5. **PDF มี 2 ไลบรารี:** mPDF (composer) และ TCPDF — ดูไฟล์เดิมก่อนว่าใช้ตัวไหน แล้วตามตัวนั้น อย่าสลับในไฟล์เดียวกัน
6. **Flag กลาง:** `$GLOBALS['isBillByAcceptDate']` / `$GLOBALS['isBillByServiceDate']` ใน `init.php` คุมตรรกะการรวมบิล (ตามวันที่รับ vs วันที่ทำ service)
7. **Datatables CSS local:** `cssdatatablesdotnet/` เก็บ CSS ที่เคยโหลดจาก CDN แบบ mirror — เลือกใช้ให้ตรงกับ version ที่ header ดึง

## สิ่งที่ห้ามทำ

- ห้ามแก้/ลบ `vendor/`, `nbproject/`, `release1/`, `dbg/`
- ห้ามลบ `.htaccess` ใน `classes/`, `includes/`, และ root
- ห้าม commit ไฟล์ scratch ใน `sandbox/`, `tmp/`, `customerfile*/` ถ้าไม่จำเป็น
- อย่า refactor ข้ามขอบเขตงาน — โปรเจคนี้ legacy และไฟล์จำนวนมากพันกัน แก้เฉพาะจุดที่ task ต้องการ

## Git

- Convention ที่ใช้อยู่: commit message เป็นวันที่ `YYYYMMDD` (เช่น `20261013`)
- Branch: `main`

## การตรวจสอบงาน

- ไม่มี test suite / linter ติดตั้งไว้ — ตรวจ syntax ด้วย `php -l <file>` ได้ (หรือรันใน container: `docker exec nb_web-app php -l /var/www/html/<file>`)
- ทดสอบจริงผ่าน http://localhost หลัง `docker compose up -d`
