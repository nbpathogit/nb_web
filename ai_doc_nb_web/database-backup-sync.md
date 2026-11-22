# Database Backup & Hourly Sync Architecture — NB Patho (`nb_web`)

## Overview

The database backup and replication system for NB Patho provides incremental hourly database backups from the primary web server (`nbpathology.com`) to local/backup servers, with support for automatic Webhook triggers, FTP downloading, and MySQL import.

---

## Core Components & File Map

| Component | Path / File | Role |
| :--- | :--- | :--- |
| **Manual Web Backup & Schema Viewer** | `C:\anuchit2\nb_web\dev3\dbtosqlfile.php` | Browser UI for triggering backup, looking back 1–365 days, inspecting table schema (`SHOW FULL COLUMNS`), downloading ZIP, and deleting files. |
| **Automated Background Webhook** | `C:\anuchit2\nb_web\dev3\dbtosqlfile_auto.php` | Background endpoint for CLI / Cron / Webhook (no UI login required), runs hourly to generate missing `.sql` files into `db_bk_sql/`. |
| **GUI Sync & Importer Tool** | `C:\anuchit2\nb_web\dev3\db_bk_sql_py\db_backup_sync_gui.py` (and `dev1`) | Python Tkinter desktop GUI running on the backup machine: manages hourly timers, sends triggers, downloads via FTP, and imports into local MySQL. |
| **Backup Storage Directory** | `C:\anuchit2\nb_web\dev3\db_bk_sql/` | Stores generated `.sql` files split by ~10 MB chunks. |

---

## 1. Time Partitioning Architecture (Dual-Tier Backup)

When a backup is triggered (default lookback: **10 days**), data is divided into two distinct tiers:

```
                              [ Cutoff Date: 10 Days Ago (e.g. 2026-11-12 00:00:00) ]
                                                        │
 ◀── Tier 1: Historical Data (Prior to 10 Days) ────────┼──────── Tier 2: Recent Active Data ──▶
     • One consolidated historical batch                │         • Granular hourly files
     • All rows with stampdatecreate < cutoffTime       │         • Captures stampdatecreate & edit
     • Split into ~10 MB files                          │         • Split into ~10 MB files
     • Named: 0000-00-00_to_YYYY-MM-DD_01.sql, ...      │         • Named: YYYY-MM-DD_HH0000_to_HH5959_01.sql
```

### Tier 1: Historical Data Batch (Prior to 10 Days)
- **Target:** All records where `stampdatecreate < :cutoffTime` (including `0000-00-00 00:00:00` or `NULL`).
- **Grouping:** Grouped as **one single historical batch** rather than creating hundreds of empty or fragmented hourly files for past years.
- **Chunking:** Automatically chunked into ~10 MB parts when file size approaches `10 * 1024 * 1024` bytes:
  - `0000-00-00_to_YYYY-MM-DD_01.sql`
  - `0000-00-00_to_YYYY-MM-DD_02.sql` ...
- **Alphabetical Ordering Advantage:** Because filenames start with `0000-00-00_to_...`, sorting files ascending (A–Z) ensures **historical data is imported before recent hourly files**, maintaining strict chronological consistency.

### Tier 2: Recent Incremental Hourly Data (Last 10 Days)
- **Target:** Data created (`stampdatecreate`) or updated (`stampdateedit`) within the last 10 days.
- **Grouping:** Individual files for each active hour (`YYYY-MM-DD_HH0000_to_HH5959_01.sql`).
- **Chunking:** Chunked at ~10 MB per part if an hour exceeds the file size limit.

---

## 2. SQL Upsert Logic (`ON DUPLICATE KEY UPDATE`)

All generated SQL backup files use MySQL / MariaDB **Upsert syntax**:

```sql
INSERT INTO `table_name` (`id`, `col1`, `col2`, ...)
VALUES (101, 'val1', 'val2', ...)
ON DUPLICATE KEY UPDATE `col1`=VALUES(`col1`), `col2`=VALUES(`col2`), ...;
```

### Safety Guarantees:
1. **New Record:** Performs standard `INSERT`.
2. **Existing Record (Primary Key or Unique Key collision):** Automatically switches to `UPDATE`, overwriting column values with the latest state.
3. **Idempotent Imports:** Backup files can be imported multiple times or force-imported completely without failing on `#1062 - Duplicate entry` errors.

---

## 3. Initial Table Schema (`a_initial_table.sql`)

Every backup run automatically generates and refreshes `a_initial_table.sql` in `db_bk_sql/`.
- Contains `CREATE TABLE IF NOT EXISTS` for all 36 tables in the database.
- Does not contain row data (data is in the split `.sql` files).
- **Execution Rule:** The backup sync tool (`db_backup_sync_gui.py`) **always runs `a_initial_table.sql` first** before importing any data files, ensuring new tables or columns exist prior to inserts.

---

## 4. Import Execution Order (On Backup Server)

When importing into MySQL:
1. **Step 1:** Run `a_initial_table.sql` (Schema preparation).
2. **Step 2:** Run `0000-00-00_to_YYYY-MM-DD_*.sql` (Historical data prior to 10 days, in part order 01, 02...).
3. **Step 3:** Run `YYYY-MM-DD_HH0000_to_...sql` (Hourly incremental files, oldest to newest).

---

## 5. Desktop Sync Tool (`db_backup_sync_gui.py`)

Located in both `C:\anuchit2\nb_web\dev3\db_bk_sql_py\` and `dev1\db_bk_sql_py\`.

### Key Functions & UI Controls:
- **⏰ Hourly Scheduler:**
  - Automated Webhook trigger at minute 10 (configurable).
  - Automated FTP download & MySQL import at minute 20 (configurable).
- **File Management Actions:**
  - **🗑️ `delete local all`**: Deletes all `.sql` and `.tmp` files in `downloaded_sql/` and resets `imported_files.txt` (with confirmation popup).
  - **⬇️ `download remain`**: Downloads only missing `.sql` files from FTP (always overwrites `a_initial_table.sql`).
  - **⏬ `download all`**: Downloads all `.sql` files from FTP, overwriting all local files (with confirmation popup).
- **Database Import Actions:**
  - **⚡ `อัปเดต SQL ที่ค้างอยู่เข้าฐานข้อมูล`**: Imports only files not yet recorded in `imported_files.txt`.
  - **🔥 `import (ทั้งหมด ทันที)`**: Force-imports all local `.sql` files from oldest to newest regardless of past import history (with confirmation popup).
