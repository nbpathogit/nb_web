# การติดตั้ง ImageMagick 7 + Ghostscript ใน Docker (บันทึกการติดตั้ง)

> ติดตั้งเมื่อ 2026-10-20 — ผลลัพธ์: image `nb_web-app` มี `/usr/local/bin/magick`
> (ImageMagick 7.1.2-34), `gs` (Ghostscript 9.53.3) และ `zip` พร้อมเรียกจาก PHP `exec()`
>
> **อัปเดต 2026-10-25 (ย้าย base เป็น `php:8.5-apache`):** base ใหม่ (bookworm/trixie)
> apt ใช้ได้ปกติ → **ตัด workaround เขียน sources.list ทับ + fallback archive.debian.org
> ออกจาก Dockerfile แล้ว** (gotcha ข้อ 1, 2, 5 เป็นของ bullseye เท่านั้น) — ยังคง build
> IM 7.1.2-34 จาก source เหมือนเดิม (gotcha ข้อ 4 ยังบังคับอยู่) + เพิ่ม CFLAGS กัน gcc
> รุ่นใหม่ (trixie) ยก warning เป็น error; Ghostscript จะเป็นรุ่นตาม base ใหม่
> (สูงกว่า 9.53.3 — ตรวจด้วย `gs --version`)

## ทำไมต้องทำ

- `patient_pdf.php` / `pdf_attach_upload.php` แปลง PDF→JPG ผ่าน `exec()` ด้วยคำสั่ง:

  ```
  /usr/local/bin/magick -density 300 ./cus/xxx.pdf -density 300 ./cus/xxx.jpg
  cd <folder> && zip <name>.zip *.jpg *.pdf *.txt
  ```

- เดิม container ไม่มีโปรแกรมเหล่านี้ → `exec()` คืน status **127** (command not found)
- Base image `php:7.4-apache` = Debian **bullseye** → apt มีแต่ ImageMagick 6 (คำสั่ง `convert`)
  ไม่มีคำสั่ง `magick` ของ IM7 → ต้อง build IM7 จาก source

## สิ่งที่ติดตั้ง (อยู่ใน layer เดียวของ `docker/php/Dockerfile`)

| โปรแกรม | รุ่น | ที่มา | หน้าที่ |
|---|---|---|---|
| ImageMagick 7 | 7.1.2-34 | build จาก source (GitHub tag tarball) | คำสั่ง `magick` |
| Ghostscript | 9.53.3 | apt bullseye main | delegate อ่าน PDF (ขาดไม่ได้) |
| zip | 3.0 | apt | รวมรายงานเป็น .zip (command2 ใน patient_pdf.php) |

- configure: `--prefix=/usr/local --with-magick-plus-plus=no --disable-docs --disable-hdri`
- delegates ที่ build ได้: `freetype jng jpeg png zip zlib`
  (มาจาก `libpng-dev libjpeg-dev libfreetype6-dev libzip-dev` ของ layer แรกใน Dockerfile — JPEG/PNG จึงเขียนได้)

## Gotchas ที่เจอระหว่างติดตั้ง — ห้ามแก้กลับ

1. **bullseye EOL ทำให้ apt install 404 (exit 100):** repo `-updates`/`-security` เหลือ
   index ให้ดึง แต่ pool ไฟล์ .deb ถูกลบแล้ว → `apt-get update` *ผ่าน* แต่ `install` พัง
   → Dockerfile เขียน `/etc/apt/sources.list` ทับให้เหลือ `deb http://deb.debian.org/debian bullseye main`
   บรรทัดเดียว (ชื่อ suite ดึงจากไฟล์เดิมด้วย awk)
2. **security repo ของ image นี้อยู่บน host `deb.debian.org/debian-security`** ไม่ใช่
   `security.debian.org` แบบเก่า — การ sed หา `security.debian.org` เลยไม่จับ ถึงได้เขียนทับ
  ทั้งไฟล์แทน
3. **ใน Dockerfile `RUN` ห้ามใช้ `$$var`:** `$$` ไม่ใช่ escape ของ Dockerfile (เป็นของ compose
   file) — shell จะมองเป็น PID ของตัวเอง (= `1` ใน build container) ได้ `1suite` → ใช้การฝัง
   `$(คำสั่ง)` ตรงๆ แทนตัวแปร
4. **ImageMagick 7.1.1-3x crash ตอนอ่าน PDF** (`Assertion 'string_info->signature ==
   MagickCoreSignature' failed` ที่ MagickCore/string.c) — เจอทั้ง build HDRI และ non-HDRI
   → แก้ด้วยการใช้รุ่น **7.1.2-34** อย่าใช้รุ่น 7.1.1-3x
5. **fallback `archive.debian.org`** ถูกเก็บไว้ใน Dockerfile สำหรับวันที่ bullseye main
   ถูกย้ายออกจาก deb.debian.org (สลับ mirror อัตโนมัติเมื่อ update ล้ม)

## การแก้ Dockerfile / rebuild

Flow: แก้ไฟล์ → `git push` → `git pull` ที่เครื่อง build → สั่ง build → ตรวจสอบ

```bash
docker compose build app     # ครั้งแรก/แก้ Dockerfile ~3-5 นาที (compile IM7)
docker compose up -d
```

- แก้โค้ด PHP ธรรมดา **ไม่ต้อง rebuild** (volume mount ทั้ง repo ที่ `/var/www/html`)
- ต้อง rebuild เฉพาะเมื่อแก้ `docker/php/*`
- อย่าใส่ `--no-cache` โดยไม่จำเป็น — จะ compile ใหม่หมดทั้งชุด
- ใน Dockerfile มี **smoke test ฝังไว้**: `gs` สร้าง PDF (มีข้อความ) → `magick` แปลง `.png`
  → แปลง `.jpg` → ตรวจไฟล์เกิดจริง — ถ้า delegate chain พัง build จะ fail ทันที (ตั้งใจ)

## การตรวจสอบ

```bash
docker exec nb_web-app magick -version    # ต้องได้ Version: ImageMagick 7.1.2-34 Q16 (ไม่มี HDRI)
docker exec nb_web-app gs --version       # 9.53.3
docker exec nb_web-app which zip          # /usr/bin/zip
```

ทดสอบแปลงไฟล์จริง (สร้าง PDF ทดสอบด้วย gs แล้วแปลง):

```bash
docker exec nb_web-app gs -q -sDEVICE=pdfwrite -o /var/www/html/cus/test.pdf -c "0 0 moveto 100 100 lineto 100 0 lineto closepath fill 72 700 moveto /Helvetica findfont 24 scalefont setfont (NB Patho test) show showpage"

docker exec nb_web-app bash -c "cd /var/www/html && /usr/local/bin/magick -density 300 ./cus/test.pdf -density 300 ./cus/test.jpg && ls -la cus/"
```

## พฤติกรรมที่รู้ (ไม่ใช่บั๊ก)

- **PDF หลายหน้า** → ได้ `xxx-0.jpg`, `xxx-1.jpg` ตามจำนวนหน้า (เหมือนบน Windows)
- **Warning `delegate library support not built-in ... (XML)`** ตอนแปลง PDF ที่มี XMP
  metadata — เป็น warning อย่างเดียว งานสำเร็จ (build นี้ไม่ได้ใส่ libxml2 delegate) ปล่อยได้

## จุดที่โค้ดเรียกใช้

| ไฟล์ | ตำแหน่ง | คำสั่งที่ใช้ |
|---|---|---|
| `patient_pdf.php` | ~L907-912 | command1: magick แปลง PDF→JPG (Linux: `/usr/local/bin/magick`) |
| `patient_pdf.php` | ~L1300-1308 | command1 + command2: `cd <folder> && zip <name>.zip *.jpg *.pdf *.txt` |
| `pdf_attach_upload.php` | ~L52-72 | เช็ค `/usr/bin/magick` ก่อน แล้ว fallback `/usr/local/bin/magick` |
| `sandbox/pdf2jpg/*.php` | — | โค้ดทดลอง (ใช้ path เดียวกัน) |
