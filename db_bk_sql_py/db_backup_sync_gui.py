"""
db_backup_sync_gui.py
GUI Application for triggering remote backup, downloading SQL files via FTP, and importing into MySQL.
"""

import os
import sys
import json
import time
import ftplib
import ssl
import urllib.request
import urllib.error
import subprocess
import threading
from datetime import datetime
import tkinter as tk
from tkinter import ttk, messagebox, scrolledtext

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
CONFIG_FILE = os.path.join(BASE_DIR, "config.json")
TRIGGER_LOG_FILE = os.path.join(BASE_DIR, "trigger_history.log")
DOWNLOAD_LOG_FILE = os.path.join(BASE_DIR, "download_history.log")
IMPORTED_LIST_FILE = os.path.join(BASE_DIR, "imported_files.txt")

DEFAULT_CONFIG = {
    "trigger_url": "https://nbpathology.com/dbtosqlfile_auto.php",
    "trigger_minute": 10,
    "trigger_enabled": True,
    "download_minute": 20,
    "download_enabled": True,
    "ftp_host": "45.91.134.194",
    "ftp_port": 21,
    "ftp_user": "admin_dbbk",
    "ftp_pass": "pbmCpDGL1aD7zbDc",
    "ftp_remote_dir": "/",
    "local_download_dir": "downloaded_sql",
    "db_host": "127.0.0.1",
    "db_port": 3306,
    "db_user": "nbpa_nbpatho",
    "db_pass": "@6i3#xdyJusmDfBJ",
    "db_name": "nbpa_data_dev3",
    "mysql_bin_path": "C:\\xampp\\mysql\\bin\\mysql.exe",
    "auto_import_after_download": True
}


def load_config():
    if not os.path.isfile(CONFIG_FILE):
        save_config(DEFAULT_CONFIG)
        return dict(DEFAULT_CONFIG)
    try:
        with open(CONFIG_FILE, "r", encoding="utf-8") as f:
            cfg = json.load(f)
            merged = dict(DEFAULT_CONFIG)
            merged.update(cfg)
            return merged
    except Exception:
        return dict(DEFAULT_CONFIG)


def save_config(cfg):
    try:
        with open(CONFIG_FILE, "w", encoding="utf-8") as f:
            json.dump(cfg, f, indent=4, ensure_ascii=False)
    except Exception as e:
        print("Error saving config:", e)


def append_log(file_path, msg):
    try:
        ts = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        with open(file_path, "a", encoding="utf-8") as f:
            f.write(f"[{ts}] {msg}\n")
    except Exception as e:
        print("Error appending log:", e)


def get_imported_set():
    if not os.path.isfile(IMPORTED_LIST_FILE):
        return set()
    try:
        with open(IMPORTED_LIST_FILE, "r", encoding="utf-8") as f:
            lines = [line.strip() for line in f if line.strip() and not line.startswith("#")]
            return set(lines)
    except Exception:
        return set()


def add_imported_file(fname):
    try:
        with open(IMPORTED_LIST_FILE, "a", encoding="utf-8") as f:
            f.write(f"{fname}\n")
    except Exception as e:
        print("Error saving imported file:", e)


class BackupSyncApp(tk.Tk):
    def __init__(self):
        super().__init__()
        self.title("NB Patho - Database Backup Syncer & Importer")
        self.geometry("980x750")
        self.minsize(850, 650)

        self.config_data = load_config()

        # Background scheduler state
        self.scheduler_running = True
        self.is_busy = False
        self.last_trigger_hour_key = None
        self.last_download_hour_key = None

        self._build_ui()
        self._load_values_into_ui()

        # Start timer thread for schedule checking
        self.schedule_thread = threading.Thread(target=self._scheduler_loop, daemon=True)
        self.schedule_thread.start()

        self.log_console("โปรแกรมเริ่มต้นทำงานพร้อมใช้งาน", "INFO")
        self.protocol("WM_DELETE_WINDOW", self._on_close)

    def _build_ui(self):
        # Top Config Notebook / Frame
        top_frame = ttk.Frame(self, padding=8)
        top_frame.pack(side=tk.TOP, fill=tk.X)

        # Style
        style = ttk.Style()
        style.theme_use("clam")

        # Group 1: Scheduler Settings
        sched_group = ttk.LabelFrame(top_frame, text=" ⏰ ตั้งเวลาการทำงานอัตโนมัติ (Hourly Scheduler) ", padding=8)
        sched_group.pack(side=tk.TOP, fill=tk.X, pady=4)

        # Countdown Display Banner Row
        cd_row = tk.Frame(sched_group, bg="#0f172a", padx=8, pady=6)
        cd_row.pack(fill=tk.X, pady=(0, 4))

        tk.Label(cd_row, text="🕒 เวลาปัจจุบัน:", font=("Segoe UI", 9), fg="#94a3b8", bg="#0f172a").pack(side=tk.LEFT)
        self.lbl_clock_now = tk.Label(cd_row, text="--:--:--", font=("Consolas", 10, "bold"), fg="#38bdf8", bg="#0f172a")
        self.lbl_clock_now.pack(side=tk.LEFT, padx=(4, 16))

        tk.Label(cd_row, text="⏳ นับถอยหลัง Trigger:", font=("Segoe UI", 9), fg="#94a3b8", bg="#0f172a").pack(side=tk.LEFT)
        self.lbl_cd_trigger = tk.Label(cd_row, text="--:--", font=("Consolas", 11, "bold"), fg="#f59e0b", bg="#0f172a")
        self.lbl_cd_trigger.pack(side=tk.LEFT, padx=(4, 16))

        tk.Label(cd_row, text="⏳ นับถอยหลัง Download & Import:", font=("Segoe UI", 9), fg="#94a3b8", bg="#0f172a").pack(side=tk.LEFT)
        self.lbl_cd_download = tk.Label(cd_row, text="--:--", font=("Consolas", 11, "bold"), fg="#10b981", bg="#0f172a")
        self.lbl_cd_download.pack(side=tk.LEFT, padx=(4, 6))

        # Latest Job Status Row
        job_status_row = tk.Frame(sched_group, bg="#1e293b", padx=8, pady=5)
        job_status_row.pack(fill=tk.X, pady=(0, 6))

        tk.Label(job_status_row, text="📌 งานล่าสุด (Latest Job):", font=("Segoe UI", 9, "bold"), fg="#cbd5e1", bg="#1e293b").pack(side=tk.LEFT)

        tk.Label(job_status_row, text="Trigger:", font=("Segoe UI", 9), fg="#94a3b8", bg="#1e293b").pack(side=tk.LEFT, padx=(12, 2))
        self.lbl_last_trigger_status = tk.Label(job_status_row, text="ยังไม่มีการรัน", font=("Segoe UI", 9, "bold"), fg="#94a3b8", bg="#1e293b")
        self.lbl_last_trigger_status.pack(side=tk.LEFT, padx=(2, 16))

        tk.Label(job_status_row, text="Download:", font=("Segoe UI", 9), fg="#94a3b8", bg="#1e293b").pack(side=tk.LEFT, padx=(4, 2))
        self.lbl_last_download_status = tk.Label(job_status_row, text="ยังไม่มีการรัน", font=("Segoe UI", 9, "bold"), fg="#94a3b8", bg="#1e293b")
        self.lbl_last_download_status.pack(side=tk.LEFT, padx=(2, 16))

        tk.Label(job_status_row, text="Import DB:", font=("Segoe UI", 9), fg="#94a3b8", bg="#1e293b").pack(side=tk.LEFT, padx=(4, 2))
        self.lbl_last_import_status = tk.Label(job_status_row, text="ยังไม่มีการรัน", font=("Segoe UI", 9, "bold"), fg="#94a3b8", bg="#1e293b")
        self.lbl_last_import_status.pack(side=tk.LEFT, padx=(2, 6))

        # Trigger schedule row
        r1 = ttk.Frame(sched_group)
        r1.pack(fill=tk.X, pady=2)

        self.var_trigger_enabled = tk.BooleanVar(value=True)
        chk_trig = ttk.Checkbutton(r1, text="เปิดใช้งาน Trigger Webhook ทุกชั่วโมง", variable=self.var_trigger_enabled)
        chk_trig.pack(side=tk.LEFT)

        ttk.Label(r1, text=" ที่นาทีที่: ").pack(side=tk.LEFT, padx=(15, 2))
        self.var_trigger_minute = tk.IntVar(value=10)
        sp_trig = ttk.Spinbox(r1, from_=0, to=59, width=4, textvariable=self.var_trigger_minute)
        sp_trig.pack(side=tk.LEFT)
        ttk.Label(r1, text=" (0-59, Default: 10)").pack(side=tk.LEFT, padx=4)

        btn_trig_now = ttk.Button(r1, text="▶ Trigger ทันที", command=self.action_trigger_now)
        btn_trig_now.pack(side=tk.RIGHT, padx=4)

        # Download schedule row
        r2 = ttk.Frame(sched_group)
        r2.pack(fill=tk.X, pady=2)

        self.var_download_enabled = tk.BooleanVar(value=True)
        chk_dl = ttk.Checkbutton(r2, text="เปิดใช้งาน Download FTP & Import ทุกชั่วโมง", variable=self.var_download_enabled)
        chk_dl.pack(side=tk.LEFT)

        ttk.Label(r2, text=" ที่นาทีที่: ").pack(side=tk.LEFT, padx=(15, 2))
        self.var_download_minute = tk.IntVar(value=20)
        sp_dl = ttk.Spinbox(r2, from_=0, to=59, width=4, textvariable=self.var_download_minute)
        sp_dl.pack(side=tk.LEFT)
        ttk.Label(r2, text=" (0-59, Default: 20)").pack(side=tk.LEFT, padx=4)

        btn_dl_now = ttk.Button(r2, text="⬇ Download & Import ทันที", command=self.action_download_and_import_now)
        btn_dl_now.pack(side=tk.RIGHT, padx=4)

        # Notebook for detailed settings (Trigger URL, FTP, Database)
        nb = ttk.Notebook(top_frame)
        nb.pack(side=tk.TOP, fill=tk.X, pady=4)

        # Tab 1: Trigger Webhook Config
        tab_trigger = ttk.Frame(nb, padding=8)
        nb.add(tab_trigger, text="1. Trigger Webhook URL")

        t_row = ttk.Frame(tab_trigger)
        t_row.pack(fill=tk.X, pady=2)
        ttk.Label(t_row, text="Target URL: ").pack(side=tk.LEFT)
        self.var_trigger_url = tk.StringVar()
        ent_url = ttk.Entry(t_row, textvariable=self.var_trigger_url)
        ent_url.pack(side=tk.LEFT, fill=tk.X, expand=True, padx=4)

        # Tab 2: FTP Server Config
        tab_ftp = ttk.Frame(nb, padding=8)
        nb.add(tab_ftp, text="2. FTP Server")

        ftp_grid = ttk.Frame(tab_ftp)
        ftp_grid.pack(fill=tk.X)

        ttk.Label(ftp_grid, text="FTP Host:").grid(row=0, column=0, sticky=tk.W, pady=2)
        self.var_ftp_host = tk.StringVar()
        ttk.Entry(ftp_grid, textvariable=self.var_ftp_host, width=22).grid(row=0, column=1, sticky=tk.W, padx=4, pady=2)

        ttk.Label(ftp_grid, text="Port:").grid(row=0, column=2, sticky=tk.W, padx=(10, 2), pady=2)
        self.var_ftp_port = tk.IntVar()
        ttk.Entry(ftp_grid, textvariable=self.var_ftp_port, width=6).grid(row=0, column=3, sticky=tk.W, padx=4, pady=2)

        ttk.Label(ftp_grid, text="User:").grid(row=0, column=4, sticky=tk.W, padx=(10, 2), pady=2)
        self.var_ftp_user = tk.StringVar()
        ttk.Entry(ftp_grid, textvariable=self.var_ftp_user, width=18).grid(row=0, column=5, sticky=tk.W, padx=4, pady=2)

        ttk.Label(ftp_grid, text="Password:").grid(row=1, column=0, sticky=tk.W, pady=2)
        self.var_ftp_pass = tk.StringVar()
        ttk.Entry(ftp_grid, textvariable=self.var_ftp_pass, show="*", width=22).grid(row=1, column=1, sticky=tk.W, padx=4, pady=2)

        ttk.Label(ftp_grid, text="Remote Dir:").grid(row=1, column=2, sticky=tk.W, padx=(10, 2), pady=2)
        self.var_ftp_remote_dir = tk.StringVar()
        ttk.Entry(ftp_grid, textvariable=self.var_ftp_remote_dir, width=15).grid(row=1, column=3, columnspan=3, sticky=tk.W, padx=4, pady=2)

        # Tab 3: Local MySQL Database Config
        tab_db = ttk.Frame(nb, padding=8)
        nb.add(tab_db, text="3. Target Database (Localhost)")

        db_grid = ttk.Frame(tab_db)
        db_grid.pack(fill=tk.X)

        ttk.Label(db_grid, text="DB Host:").grid(row=0, column=0, sticky=tk.W, pady=2)
        self.var_db_host = tk.StringVar()
        ttk.Entry(db_grid, textvariable=self.var_db_host, width=16).grid(row=0, column=1, sticky=tk.W, padx=4, pady=2)

        ttk.Label(db_grid, text="Port:").grid(row=0, column=2, sticky=tk.W, padx=(8, 2), pady=2)
        self.var_db_port = tk.IntVar()
        ttk.Entry(db_grid, textvariable=self.var_db_port, width=6).grid(row=0, column=3, sticky=tk.W, padx=4, pady=2)

        ttk.Label(db_grid, text="DB Name:").grid(row=0, column=4, sticky=tk.W, padx=(8, 2), pady=2)
        self.var_db_name = tk.StringVar()
        ttk.Entry(db_grid, textvariable=self.var_db_name, width=20).grid(row=0, column=5, sticky=tk.W, padx=4, pady=2)

        ttk.Label(db_grid, text="DB User:").grid(row=1, column=0, sticky=tk.W, pady=2)
        self.var_db_user = tk.StringVar()
        ttk.Entry(db_grid, textvariable=self.var_db_user, width=16).grid(row=1, column=1, sticky=tk.W, padx=4, pady=2)

        ttk.Label(db_grid, text="Password:").grid(row=1, column=2, sticky=tk.W, padx=(8, 2), pady=2)
        self.var_db_pass = tk.StringVar()
        ttk.Entry(db_grid, textvariable=self.var_db_pass, show="*", width=16).grid(row=1, column=3, sticky=tk.W, padx=4, pady=2)

        ttk.Label(db_grid, text="mysql.exe:").grid(row=1, column=4, sticky=tk.W, padx=(8, 2), pady=2)
        self.var_mysql_bin = tk.StringVar()
        ttk.Entry(db_grid, textvariable=self.var_mysql_bin, width=24).grid(row=1, column=5, sticky=tk.W, padx=4, pady=2)

        # Save config button row
        act_row = ttk.Frame(top_frame)
        act_row.pack(side=tk.TOP, fill=tk.X, pady=(2, 6))

        self.lbl_status = ttk.Label(act_row, text="สถานะ: พร้อมทำงาน", foreground="#0d6efd", font=("Segoe UI", 9, "bold"))
        self.lbl_status.pack(side=tk.LEFT)

        btn_save_cfg = ttk.Button(act_row, text="💾 บันทึกการตั้งค่า (Save Config)", command=self.action_save_config)
        btn_save_cfg.pack(side=tk.RIGHT, padx=4)

        btn_clear_log = ttk.Button(act_row, text="ล้างข้อความคอนโซล", command=self.action_clear_console)
        btn_clear_log.pack(side=tk.RIGHT, padx=4)

        btn_import_only = ttk.Button(act_row, text="⚡ อัปเดต SQL ที่ค้างอยู่เข้าฐานข้อมูล", command=self.action_import_pending_now)
        btn_import_only.pack(side=tk.RIGHT, padx=4)

        # Bottom Frame: Console Log Window
        bottom_frame = ttk.LabelFrame(self, text=" 📜 คอนโซลแสดงสถานะการทำงาน (Status Log Console) ", padding=8)
        bottom_frame.pack(side=tk.BOTTOM, fill=tk.BOTH, expand=True, padx=8, pady=(0, 8))

        self.txt_console = scrolledtext.ScrolledText(
            bottom_frame,
            wrap=tk.WORD,
            bg="#1e1e1e",
            fg="#e0e0e0",
            insertbackground="#ffffff",
            font=("Consolas", 10)
        )
        self.txt_console.pack(fill=tk.BOTH, expand=True)

        self.txt_console.tag_config("INFO", foreground="#61afef")
        self.txt_console.tag_config("SUCCESS", foreground="#98c379")
        self.txt_console.tag_config("WARNING", foreground="#e5c07b")
        self.txt_console.tag_config("ERROR", foreground="#e06c75")
        self.txt_console.tag_config("MUTED", foreground="#abb2bf")

    def _load_values_into_ui(self):
        c = self.config_data
        self.var_trigger_enabled.set(c.get("trigger_enabled", True))
        self.var_trigger_minute.set(c.get("trigger_minute", 10))
        self.var_download_enabled.set(c.get("download_enabled", True))
        self.var_download_minute.set(c.get("download_minute", 20))

        self.var_trigger_url.set(c.get("trigger_url", DEFAULT_CONFIG["trigger_url"]))

        self.var_ftp_host.set(c.get("ftp_host", DEFAULT_CONFIG["ftp_host"]))
        self.var_ftp_port.set(c.get("ftp_port", DEFAULT_CONFIG["ftp_port"]))
        self.var_ftp_user.set(c.get("ftp_user", DEFAULT_CONFIG["ftp_user"]))
        self.var_ftp_pass.set(c.get("ftp_pass", DEFAULT_CONFIG["ftp_pass"]))
        self.var_ftp_remote_dir.set(c.get("ftp_remote_dir", DEFAULT_CONFIG["ftp_remote_dir"]))

        self.var_db_host.set(c.get("db_host", DEFAULT_CONFIG["db_host"]))
        self.var_db_port.set(c.get("db_port", DEFAULT_CONFIG["db_port"]))
        self.var_db_user.set(c.get("db_user", DEFAULT_CONFIG["db_user"]))
        self.var_db_pass.set(c.get("db_pass", DEFAULT_CONFIG["db_pass"]))
        self.var_db_name.set(c.get("db_name", DEFAULT_CONFIG["db_name"]))
        self.var_mysql_bin.set(c.get("mysql_bin_path", DEFAULT_CONFIG["mysql_bin_path"]))

    def _collect_values_from_ui(self):
        c = dict(self.config_data)
        c["trigger_enabled"] = bool(self.var_trigger_enabled.get())
        c["trigger_minute"] = int(self.var_trigger_minute.get())
        c["download_enabled"] = bool(self.var_download_enabled.get())
        c["download_minute"] = int(self.var_download_minute.get())

        c["trigger_url"] = self.var_trigger_url.get().strip()

        c["ftp_host"] = self.var_ftp_host.get().strip()
        c["ftp_port"] = int(self.var_ftp_port.get())
        c["ftp_user"] = self.var_ftp_user.get().strip()
        c["ftp_pass"] = self.var_ftp_pass.get()
        c["ftp_remote_dir"] = self.var_ftp_remote_dir.get().strip()

        c["db_host"] = self.var_db_host.get().strip()
        c["db_port"] = int(self.var_db_port.get())
        c["db_user"] = self.var_db_user.get().strip()
        c["db_pass"] = self.var_db_pass.get()
        c["db_name"] = self.var_db_name.get().strip()
        c["mysql_bin_path"] = self.var_mysql_bin.get().strip()
        return c

    def action_save_config(self):
        self.config_data = self._collect_values_from_ui()
        save_config(self.config_data)
        self.log_console("บันทึกการตั้งค่าลง config.json เรียบร้อยแล้ว", "SUCCESS")
        messagebox.showinfo("บันทึกสำเร็จ", "บันทึกการตั้งค่าเรียบร้อยแล้ว")

    def action_clear_console(self):
        self.txt_console.delete("1.0", tk.END)

    def log_console(self, msg, level="INFO"):
        def _append():
            ts = datetime.now().strftime("%H:%M:%S")
            line = f"[{ts}] [{level}] {msg}\n"
            self.txt_console.insert(tk.END, line, level)
            self.txt_console.see(tk.END)
        self.after(0, _append)

    def set_status_text(self, text, color="#0d6efd"):
        def _set():
            self.lbl_status.config(text=f"สถานะ: {text}", foreground=color)
        self.after(0, _set)

    # -------------------------------------------------------------------------
    # Core Tasks
    # -------------------------------------------------------------------------
    def trigger_remote_backup(self):
        url = self.config_data.get("trigger_url", DEFAULT_CONFIG["trigger_url"])
        self.log_console(f"เริ่มส่งคำสั่ง Trigger ไปยัง: {url}", "INFO")
        self.set_status_text("กำลังส่ง Trigger Webhook...", "#d97706")

        try:
            ctx = ssl.create_default_context()
            ctx.check_hostname = False
            ctx.verify_mode = ssl.CERT_NONE

            req = urllib.request.Request(
                url,
                headers={"User-Agent": "NBPathoBackupClient/1.0"}
            )
            start_t = time.time()
            with urllib.request.urlopen(req, timeout=180, context=ctx) as resp:
                status_code = resp.status
                body = resp.read().decode("utf-8", errors="ignore")
                elapsed = round(time.time() - start_t, 2)

                log_summary = f"Trigger OK (HTTP {status_code}, {elapsed}s) - Response: {body[:300].strip()}"
                self.log_console(f"Trigger สำเร็จ! HTTP {status_code} ({elapsed}s)", "SUCCESS")
                if len(body) < 400:
                    self.log_console(f"เซิร์ฟเวอร์ตอบกลับ: {body.strip()}", "MUTED")
                else:
                    self.log_console(f"เซิร์ฟเวอร์ตอบกลับ (ย่อ): {body[:300].strip()}...", "MUTED")

                append_log(TRIGGER_LOG_FILE, log_summary)
                ts_now = datetime.now().strftime("%H:%M:%S")
                self.after(0, lambda: self.lbl_last_trigger_status.config(text=f"GOOD ({ts_now})", fg="#4ade80"))
                return True
        except urllib.error.HTTPError as e:
            err_msg = f"HTTP Error {e.code}: {e.reason}"
            self.log_console(f"Trigger ล้มเหลว: {err_msg}", "ERROR")
            append_log(TRIGGER_LOG_FILE, f"Trigger FAILED: {err_msg}")
            ts_now = datetime.now().strftime("%H:%M:%S")
            self.after(0, lambda: self.lbl_last_trigger_status.config(text=f"FAIL ({ts_now})", fg="#f87171"))
            return False
        except Exception as e:
            err_msg = f"Error: {e}"
            self.log_console(f"Trigger ล้มเหลว: {err_msg}", "ERROR")
            append_log(TRIGGER_LOG_FILE, f"Trigger FAILED: {err_msg}")
            ts_now = datetime.now().strftime("%H:%M:%S")
            self.after(0, lambda: self.lbl_last_trigger_status.config(text=f"FAIL ({ts_now})", fg="#f87171"))
            return False

    def download_ftp_files(self):
        c = self.config_data
        host = c.get("ftp_host", "45.91.134.194")
        port = int(c.get("ftp_port", 21))
        user = c.get("ftp_user", "admin_dbbk")
        password = c.get("ftp_pass", "")
        remote_dir = c.get("ftp_remote_dir", "/")
        local_dir = os.path.join(BASE_DIR, c.get("local_download_dir", "downloaded_sql"))

        if not os.path.isdir(local_dir):
            os.makedirs(local_dir, exist_ok=True)

        self.log_console(f"กำลังเชื่อมต่อ FTP: {host}:{port} ({user})...", "INFO")
        self.set_status_text("กำลังเชื่อมต่อ FTP...", "#0284c7")

        downloaded_count = 0
        skipped_count = 0
        error_count = 0
        downloaded_filenames = []

        try:
            ftp = ftplib.FTP()
            ftp.connect(host, port, timeout=30)
            ftp.login(user, password)
            if remote_dir and remote_dir != "/":
                ftp.cwd(remote_dir)

            self.log_console(f"เข้าสู่ระบบ FTP สำเร็จ (ไดเรกทอรี: {ftp.pwd()}) กำลังอ่านรายชื่อไฟล์...", "SUCCESS")

            file_list = []
            try:
                # Try NLST first
                file_list = ftp.nlst()
            except Exception:
                # Fallback to dir
                lines = []
                ftp.dir(lines.append)
                for l in lines:
                    parts = l.split()
                    if parts:
                        file_list.append(parts[-1])

            sql_files = [f for f in file_list if f.lower().endswith(".sql")]
            sql_files.sort()  # Sort oldest to newest by name (YYYY-MM-DD...)

            total_found = len(sql_files)
            self.log_console(f"พบบัญชีไฟล์ .sql บน FTP ทั้งหมด {total_found} ไฟล์", "INFO")

            for fname in sql_files:
                local_path = os.path.join(local_dir, fname)

                # If file already exists locally and size is non-zero, skip
                if os.path.isfile(local_path) and os.path.getsize(local_path) > 0:
                    skipped_count += 1
                    continue

                self.log_console(f"กำลังดาวน์โหลด: {fname} ...", "INFO")
                self.set_status_text(f"กำลังโหลด {fname}...", "#0284c7")

                tmp_path = local_path + ".tmp"
                try:
                    with open(tmp_path, "wb") as f_out:
                        ftp.retrbinary(f"RETR {fname}", f_out.write)
                    os.replace(tmp_path, local_path)
                    downloaded_count += 1
                    downloaded_filenames.append(fname)
                    f_size = os.path.getsize(local_path) / 1024
                    self.log_console(f"ดาวน์โหลดสำเร็จ: {fname} ({f_size:.1f} KB)", "SUCCESS")
                    append_log(DOWNLOAD_LOG_FILE, f"DOWNLOADED: {fname} ({f_size:.1f} KB)")
                except Exception as down_err:
                    error_count += 1
                    if os.path.exists(tmp_path):
                        os.remove(tmp_path)
                    self.log_console(f"ดาวน์โหลดล้มเหลว {fname}: {down_err}", "ERROR")
                    append_log(DOWNLOAD_LOG_FILE, f"FAILED: {fname} ({down_err})")

            ftp.quit()

            summary = f"สรุปการดาวน์โหลด FTP: ไฟล์ใหม่ {downloaded_count} ไฟล์, ข้ามที่มีแล้ว {skipped_count} ไฟล์, ผิดพลาด {error_count} ไฟล์"
            self.log_console(summary, "SUCCESS" if error_count == 0 else "WARNING")
            append_log(DOWNLOAD_LOG_FILE, summary)

            ts_now = datetime.now().strftime("%H:%M:%S")
            if error_count == 0:
                self.after(0, lambda: self.lbl_last_download_status.config(text=f"GOOD ({ts_now})", fg="#4ade80"))
            else:
                self.after(0, lambda: self.lbl_last_download_status.config(text=f"FAIL: {error_count} err ({ts_now})", fg="#f87171"))

            return downloaded_filenames

        except Exception as e:
            err_msg = f"FTP Error: {e}"
            self.log_console(err_msg, "ERROR")
            append_log(DOWNLOAD_LOG_FILE, err_msg)
            ts_now = datetime.now().strftime("%H:%M:%S")
            self.after(0, lambda: self.lbl_last_download_status.config(text=f"FAIL ({ts_now})", fg="#f87171"))
            return []

    def import_sql_files_to_mysql(self):
        c = self.config_data
        db_host = c.get("db_host", "127.0.0.1")
        db_port = str(c.get("db_port", 3306))
        db_user = c.get("db_user", "nbpa_nbpatho")
        db_pass = c.get("db_pass", "")
        db_name = c.get("db_name", "nbpa_data_dev3")
        mysql_bin = c.get("mysql_bin_path", "C:\\xampp\\mysql\\bin\\mysql.exe")
        local_dir = os.path.join(BASE_DIR, c.get("local_download_dir", "downloaded_sql"))

        if not os.path.isfile(mysql_bin):
            self.log_console(f"ไม่พบโปรแกรม mysql.exe ที่: {mysql_bin} (กรุณาตรวจสอบการตั้งค่า)", "ERROR")
            return 0

        if not os.path.isdir(local_dir):
            self.log_console(f"ยังไม่มีโฟลเดอร์ {local_dir}", "WARNING")
            return 0

        # Scan all local .sql files
        all_sql_files = [f for f in os.listdir(local_dir) if f.lower().endswith(".sql")]
        all_sql_files.sort()  # Oldest to newest: e.g. 2026-11-01 -> 2026-11-08

        imported_set = get_imported_set()
        pending_files = [f for f in all_sql_files if f not in imported_set]

        total_pending = len(pending_files)
        if total_pending == 0:
            self.log_console("ไม่มีไฟล์ SQL ค้างการอัปเดตเข้าฐานข้อมูล (อัปเดตครบทั้งหมดแล้ว)", "MUTED")
            ts_now = datetime.now().strftime("%H:%M:%S")
            self.after(0, lambda: self.lbl_last_import_status.config(text=f"GOOD (0 pending, {ts_now})", fg="#4ade80"))
            return 0

        self.log_console(f"พบไฟล์ SQL ที่ต้องอัปเดตเข้า MySQL ทั้งหมด {total_pending} ไฟล์ (เรียงจากเก่าไปใหม่)", "INFO")
        self.set_status_text("กำลังอัปเดต SQL เข้าฐานข้อมูล...", "#7c3aed")

        success_count = 0
        fail_count = 0

        for idx, fname in enumerate(pending_files, 1):
            fpath = os.path.join(local_dir, fname)
            self.log_console(f"[{idx}/{total_pending}] กำลังรันไฟล์: {fname} ...", "INFO")
            self.set_status_text(f"กำลังรัน ({idx}/{total_pending}): {fname}", "#7c3aed")

            cmd = [
                mysql_bin,
                f"--host={db_host}",
                f"--port={db_port}",
                f"--user={db_user}",
                f"--database={db_name}",
                "--default-character-set=utf8mb4"
            ]
            if db_pass:
                cmd.append(f"--password={db_pass}")

            try:
                start_t = time.time()
                with open(fpath, "rb") as sql_in:
                    proc = subprocess.run(
                        cmd,
                        stdin=sql_in,
                        capture_output=True,
                        text=True,
                        timeout=300
                    )
                elapsed = round(time.time() - start_t, 2)

                if proc.returncode == 0:
                    success_count += 1
                    add_imported_file(fname)
                    self.log_console(f"อัปเดตสำเร็จ: {fname} ({elapsed}s)", "SUCCESS")
                else:
                    fail_count += 1
                    err_out = proc.stderr.strip() or proc.stdout.strip()
                    self.log_console(f"รันไฟล์ล้มเหลว {fname}: {err_out}", "ERROR")
            except Exception as ex:
                fail_count += 1
                self.log_console(f"เกิดข้อผิดพลาดในการรัน {fname}: {ex}", "ERROR")

        summary = f"สรุปการรัน SQL เข้าฐานข้อมูล: สำเร็จ {success_count} ไฟล์, ผิดพลาด {fail_count} ไฟล์"
        self.log_console(summary, "SUCCESS" if fail_count == 0 else "WARNING")
        ts_now = datetime.now().strftime("%H:%M:%S")
        if fail_count == 0:
            self.after(0, lambda: self.lbl_last_import_status.config(text=f"GOOD ({success_count} files, {ts_now})", fg="#4ade80"))
        else:
            self.after(0, lambda: self.lbl_last_import_status.config(text=f"FAIL: {fail_count} err ({ts_now})", fg="#f87171"))

        return success_count

    # -------------------------------------------------------------------------
    # UI Action Handlers (Run in background threads to avoid freezing UI)
    # -------------------------------------------------------------------------
    def action_trigger_now(self):
        if self.is_busy:
            messagebox.showwarning("กำลังทำงาน", "มีกระบวนการอื่นกำลังทำงานอยู่ กรุณารอสักครู่")
            return

        def _worker():
            self.is_busy = True
            try:
                self.trigger_remote_backup()
            finally:
                self.is_busy = False
                self.set_status_text("พร้อมทำงาน", "#0d6efd")

        threading.Thread(target=_worker, daemon=True).start()

    def action_download_and_import_now(self):
        if self.is_busy:
            messagebox.showwarning("กำลังทำงาน", "มีกระบวนการอื่นกำลังทำงานอยู่ กรุณารอสักครู่")
            return

        def _worker():
            self.is_busy = True
            try:
                self.download_ftp_files()
                self.import_sql_files_to_mysql()
            finally:
                self.is_busy = False
                self.set_status_text("พร้อมทำงาน", "#0d6efd")

        threading.Thread(target=_worker, daemon=True).start()

    def action_import_pending_now(self):
        if self.is_busy:
            messagebox.showwarning("กำลังทำงาน", "มีกระบวนการอื่นกำลังทำงานอยู่ กรุณารอสักครู่")
            return

        def _worker():
            self.is_busy = True
            try:
                self.import_sql_files_to_mysql()
            finally:
                self.is_busy = False
                self.set_status_text("พร้อมทำงาน", "#0d6efd")

        threading.Thread(target=_worker, daemon=True).start()

    # -------------------------------------------------------------------------
    # Scheduler Loop (Checks every second for countdown and minute triggers)
    # -------------------------------------------------------------------------
    def _scheduler_loop(self):
        while self.scheduler_running:
            try:
                now = datetime.now()
                cur_hour_key = now.strftime("%Y-%m-%d_%H")
                cur_minute = now.minute
                cur_second = now.second

                trig_enabled = bool(self.var_trigger_enabled.get())
                trig_min = int(self.var_trigger_minute.get())

                dl_enabled = bool(self.var_download_enabled.get())
                dl_min = int(self.var_download_minute.get())

                # Calculate seconds remaining until next target minute
                def get_seconds_until(target_min):
                    cur_sec_in_hour = cur_minute * 60 + cur_second
                    target_sec_in_hour = target_min * 60
                    diff = target_sec_in_hour - cur_sec_in_hour
                    if diff <= 0:
                        diff += 3600  # next hour
                    return diff

                def format_timer(secs):
                    m = secs // 60
                    s = secs % 60
                    return f"{m:02d}:{s:02d}"

                trig_diff = get_seconds_until(trig_min)
                dl_diff = get_seconds_until(dl_min)

                def _update_countdown_labels():
                    self.lbl_clock_now.config(text=now.strftime("%H:%M:%S"))
                    if trig_enabled:
                        self.lbl_cd_trigger.config(text=format_timer(trig_diff), fg="#f59e0b")
                    else:
                        self.lbl_cd_trigger.config(text="ปิดใช้งาน", fg="#64748b")

                    if dl_enabled:
                        self.lbl_cd_download.config(text=format_timer(dl_diff), fg="#10b981")
                    else:
                        self.lbl_cd_download.config(text="ปิดใช้งาน", fg="#64748b")

                self.after(0, _update_countdown_labels)

                # Trigger Task Check
                if trig_enabled and cur_minute == trig_min:
                    if self.last_trigger_hour_key != cur_hour_key and not self.is_busy:
                        self.last_trigger_hour_key = cur_hour_key
                        self.log_console(f"⏰ ถึงเวลาทริกเกอร์ตามกำหนด ({now.strftime('%H:%M:%S')} - นาทีที่ {trig_min})", "INFO")
                        self.action_trigger_now()

                # Download & Import Task Check
                if dl_enabled and cur_minute == dl_min:
                    if self.last_download_hour_key != cur_hour_key and not self.is_busy:
                        self.last_download_hour_key = cur_hour_key
                        self.log_console(f"⏰ ถึงเวลาดาวน์โหลดและอัปเดตตามกำหนด ({now.strftime('%H:%M:%S')} - นาทีที่ {dl_min})", "INFO")
                        self.action_download_and_import_now()

            except Exception as e:
                print("Scheduler loop error:", e)

            time.sleep(1)

    def _on_close(self):
        self.scheduler_running = False
        self.destroy()


if __name__ == "__main__":
    app = BackupSyncApp()
    app.mainloop()

