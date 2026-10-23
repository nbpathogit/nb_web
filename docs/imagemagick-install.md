# การติดตั้ง ImageMagick 7 + Ghostscript ใน Docker (บันทึกการติดตั้ง)

> ติดตั้งเมื่อ 2026-10-20 — ผลลัพธ์: image `nb_web-app` มี `/usr/local/bin/magick`
> (ImageMagick 7.1.2-34), `gs` และ `zip` พร้อมเรียกจาก PHP `exec()`
> อัปเดต 2026-10-23 — migrate PHP 7.4 → 8.5: base image เปลี่ยนจาก
> `php:7.4-apache` (bullseye) เป็น `php:8.5-apache-bookworm`

## ทำไมต้องทำ

- `patient_pdf.php` / `pdf_attach_upload.php` แปลง PDF→JPG ผ่าน `exec()` ด้วยคำสั่ง:

  ```
  /usr/local/bin/magick -density 300 ./cus/xxx.pdf -density 300 ./cus/xxx.jpg
  cd <folder> && zip <name>.zip *.jpg *.pdf *.txt
  ```

- เดิม container ไม่มีโปรแกรมเหล่านี้ → `exec()` คืน status **127** (command not found)
- apt ของ base (bullseye และ bookworm) มีแต่ ImageMagick 6 (คำสั่ง `convert`)
  ไม่มีคำสั่ง `magick` ของ IM7 → ต้อง build IM7 จาก source

## สิ่งที่ติดตั้ง (อยู่ใน layer เดียวของ `docker/php/Dockerfile`)

| โปรแกรม | รุ่น | ที่มา | หน้าที่ |
|---|---|---|---|
| ImageMagick 7 | 7.1.2-34 | build จาก source (GitHub tag tarball) | คำสั่ง `magick` |
| Ghostscript | 10.0.x (bookworm) — เดิม 9.53.3 (bullseye) | apt | delegate อ่าน PDF (ขาดไม่ได้) |
| zip | 3.0 | apt | รวมรายงานเป็น .zip (command2 ใน patient_pdf.php) |

- configure: `--prefix=/usr/local --with-magick-plus-plus=no --disable-docs --disable-hdri`
- delegates ที่ build ได้: `freetype jng jpeg png zip zlib`
  (มาจาก `libpng-dev libjpeg-dev libfreetype6-dev libzip-dev` ของ layer แรกใน Dockerfile — JPEG/PNG จึงเขียนได้)

## Gotchas ที่เจอ — อ่านก่อนแก้ Dockerfile

1. **ImageMagick 7.1.1-3x crash ตอนอ่าน PDF** (`Assertion 'string_info->signature ==
   MagickCoreSignature' failed` ที่ MagickCore/string.c) — เจอทั้ง build HDRI และ non-HDRI
   → แก้ด้วยการใช้รุ่น **7.1.2-34** อย่าใช้รุ่น 7.1.1-3x (**ยังบังคับอยู่**)
2. **ใน Dockerfile `RUN` ห้ามใช้ `$$var`:** `$$` ไม่ใช่ escape ของ Dockerfile (เป็นของ compose
   file) — shell จะมองเป็น PID ของตัวเอง (= `1` ใน build container) → ใช้การฝัง
   `$(คำสั่ง)` ตรงๆ แทนตัวแปร (**ยังบังคับอยู่**)
3. ~~bullseye EOL ทำให้ apt install 404~~ — เป็นปัญหาของ base `php:7.4-apache`
   (bullseye) สมัยก่อน ตอนนี้ base เป็น `php:8.5-apache-bookworm` แล้ว apt ปกติ
   (historical — วิธีแก้เดิมคือเขียน sources.list ทับเหลือบรรทัดเดียว)
4. **fallback `archive.debian.org`** ยังถูกเก็บไว้ใน Dockerfile อัตโนมัติ — เมื่อวันหนึ่ง
   bookworm EOL และถูกย้ายออกจาก deb.debian.org (คาด ~ปลายปี 2027 หลัง forky
   ออก) build จะสลับ mirror เองโดยไม่ต้องแก้โค้ด (ถ้า update ล้ม → sed เปลี่ยน host
   เป็น archive.debian.org แล้ว update ใหม่)

## การแก้ Dockerfile / rebuild

Flow: แก้ไฟล์ → push → pull ที่เครื่อง build → สั่ง build → ตรวจสอบ

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
docker exec nb_web-app php -v                 # ต้องได้ PHP 8.5.x
docker exec nb_web-app magick -version        # ต้องได้ Version: ImageMagick 7.1.2-34 Q16 (ไม่มี HDRI)
docker exec nb_web-app gs --version           # 10.0.x (bookworm)
docker exec nb_web-app which zip              # /usr/bin/zip
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
