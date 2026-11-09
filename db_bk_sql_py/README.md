# NB Patho - Database Backup Syncer & Importer (Python GUI)

โปรแกรมสำหรับตั้งเวลาทริกเกอร์แบคอัพเซิร์ฟเวอร์, ดาวน์โหลดไฟล์ SQL ผ่าน FTP และนำเข้าฐานข้อมูล MySQL อัตโนมัติ

## ตำแหน่งไฟล์
`C:\anuchit2\nb_web\dev3\db_bk_sql_py\`

## ไฟล์ในโฟลเดอร์
- `db_backup_sync_gui.py`: ซอร์สโค้ดหลักของโปรแกรม GUI (Python Tkinter)
- `run_gui.bat`: ไฟล์ Batch สำหรับดับเบิลคลิกเปิดโปรแกรมทันที
- `config.json`: ไฟล์เก็บการตั้งค่า URL, นาทีทริกเกอร์, FTP, ฐานข้อมูล MySQL
- `trigger_history.log`: ไฟล์เก็บประวัติการทริกเกอร์ Webhook
- `download_history.log`: ไฟล์เก็บประวัติการดาวน์โหลดไฟล์ผ่าน FTP
- `imported_files.txt`: ไฟล์บันทึกรายชื่อไฟล์ SQL ที่นำเข้าฐานข้อมูลแล้ว (เพื่อไม่ให้นำเข้าซ้ำ)
- `downloaded_sql/`: โฟลเดอร์เก็บไฟล์ `.sql` ที่ดาวน์โหลดมาจาก FTP Server

## วิธีเปิดใช้งาน
1. ดับเบิลคลิกไฟล์ `run_gui.bat` หรือเปิด Terminal แล้วรันคำสั่ง:
   ```cmd
   python C:\anuchit2\nb_web\dev3\db_bk_sql_py\db_backup_sync_gui.py
   ```
2. หน้าต่างโปรแกรมจะแสดงสถานะและทำงานอัตโนมัติ:
   - **Trigger Server Backup:** ทำงานทุกชั่วโมงตามนาทีที่กำหนด (Default: นาทีที่ `10`) ไปที่ `https://nbpathology.com/dbtosqlfile_auto.php`
   - **Download FTP & Import:** ทำงานทุกชั่วโมงตามนาทีที่กำหนด (Default: นาทีที่ `20`) เชื่อมต่อไปยัง FTP `45.91.134.194`
   - **Database Import:** นำเข้าไฟล์ SQL ที่ยังไม่เคยรัน เข้าสู่ฐานข้อมูล MySQL (เรียงจากไฟล์เก่าสุดไปหาใหม่สุด)

