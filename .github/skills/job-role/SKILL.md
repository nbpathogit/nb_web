---
name: job-role
description: "Use when working with job_role records, job-role IDs, workflow task types, job costing, or the job.job_role_id relationship."
---

# Job Role Reference

Job-role definitions are stored in the `job_role` database table. A job-role row describes a type of workflow task and its cost settings; individual assignments and work records are stored in `job`, whose `job_role_id` references `job_role.id`.

## Schema

The dev3 schema snapshot documents these fields:

| Field | Type | Purpose |
|---|---|---|
| `id` | `int(11)` | Primary key / role ID. |
| `name` | `varchar(20)` | Thai/local role name. |
| `name_e` | `varchar(30)` | English role name. |
| `cost_per_job` | `float` | Cost per job/task. |
| `cost_count_per_day` | `int(11)` | Count threshold used by pay calculations. The schema comment says “Pay when count more then the value”. |
| `create_date` | `datetime` | Record creation date. |

The schema snapshot declares a foreign key from `job.job_role_id` to `job_role.id`. Confirm the target database before assuming it has the same schema or role configuration.

## Rows shown in the supplied screenshot

The following labels and values are transcribed from the screenshot. These are database contents from that image, not guaranteed defaults for every environment.

| ID | Thai name shown | English name | Cost per job | Cost count per day | Create date |
|---:|---|---|---:|---:|---|
| 1 | ตัดเนื้อ | Cross Section | 2.7 | 0 | `NULL` |
| 2 | คว้านชิ้นเนื้อ(คง) | Cross Section Assistent | 1.1 | 0 | `NULL` |
| 3 | เตรียมสไลด์ | Slide Preparation | 4.2 | 0 | `NULL` |
| 4 | เตรียมสไลด์พิเศษ | Special Slide Preparation | 5.6 | 0 | `NULL` |
| 5 | แพทย์ผู้ออกผล | Pathologist | 0 | 0 | `NULL` |
| 6 | แพทย์ผู้อนุมัติผล | Second pathologist | 0 | 0 | `NULL` |
| 7 | นักเซลล์วิทยา | Cytologist | 0 | 0 | `NULL` |

The Thai label for ID `2` is small in the supplied screenshot; re-check the database before using that exact text in UI or reports.

## Code usage

- `classes/JobRole.php`: `JobRole::getAll($conn, $id = 0)` selects rows from `job_role`, optionally filters by ID, then orders by `id`.
- `classes/Job.php`: job records store the selected role in `job_role_id`; newly initialized jobs use `0` until a role is assigned.
- Role IDs are used directly in workflow and billing queries. Examples found in code include role `4` for special-slide requests, role `5` for pathologist work, and role `7` for cytologist work. `ServiceBilling` also filters pathologist and cytologist assignments using IDs `5` and `7`.
- Do not treat the screenshot's role names or IDs as universally configurable constants without checking the relevant queries and target database.

## Change guidance

1. Verify the live `job_role` rows and schema before changing names, IDs, or cost settings.
2. When changing a role ID, search all comparisons and joins on `job.job_role_id`; update workflow selectors, special-slide handling, pathologist/cytologist assignment logic, and billing queries as applicable.
3. Keep role definition data (`job_role`) distinct from patient-specific assignment/work data (`job`).
4. Check both `cost_per_job` and `cost_count_per_day` handling before changing cost behavior.

## Main source files

- `.github/database_schema.md` — dev3 schema reference for `job_role` and declared relationships.
- `classes/JobRole.php` — job-role row retrieval.
- `classes/Job.php` — job assignment persistence and role-specific operations.
- `classes/ServiceBilling.php` — role-specific billing aggregation, including pathologist and cytologist assignments.
