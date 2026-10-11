---
name: database-schema
description: 'Use when exploring or changing the database schema, SQL queries, table relationships, data dictionary, or database documentation for this PHP application. Covers every table in the nbpa_data_dev3 dump.'
---

# Database Schema

Use this skill for database work across the application, including tables outside the patient and billing workflows.

## Source of Truth

- The documented snapshot is `nbpa_data_dev3`, exported from MariaDB 10.4.22 on 2026-10-11 in `C:\Users\anuch\Downloads\nbpa_data_dev3.sql`.
- The dump defines 35 InnoDB tables. Consult [the schema reference](../../database_schema.md) for the complete table inventory and declared constraints.
- The dump is a development snapshot, not proof that production or other environments have identical schemas.
- Use `CREATE TABLE`, `ALTER TABLE`, indexes, and constraints as authoritative for the snapshot. Do not infer foreign keys just because application code joins two tables.
- Do not inspect or copy clinical data rows when a schema-only answer is sufficient.

## Complete Table Catalog

This is the full inventory from the dev3 dump, not a list of only the main tables. Total: **35 tables**.

| No. | Table | Scope / known purpose |
|---:|---|---|
| 1 | `antibody_list` | Antibody name lookup. |
| 2 | `changehistory` | Change-history content and timestamp. |
| 3 | `gross_examination_record` | Gross examination descriptions and content. |
| 4 | `hire_list` | Outsourced work records linked to a contract and patient. |
| 5 | `hospital` | Hospital details and report-folder settings. |
| 6 | `internal_note` | Internal patient notes and edit metadata. |
| 7 | `invoice_list` | Invoice names, grouping, mapping rules, and service type. |
| 8 | `job` | Patient workflow assignments, role, owner, payment, and completion. |
| 9 | `job_role` | Job role names and per-job/day cost settings. |
| 10 | `labelprint_tmp_a` | Temporary label-print queue. |
| 11 | `lab_fluid` | Laboratory fluid definitions. |
| 12 | `log` | User event and detail log. |
| 13 | `nbnote` | Rich-text note content. |
| 14 | `nbnotetitle` | Note title records. |
| 15 | `nb_note_record` | Note records with description and content. |
| 16 | `outside_contract` | External contract names and costs. |
| 17 | `patient` | Pathology case identity, workflow dates/status, assignments, and result text. |
| 18 | `pdf_attach` | Patient-related PDF attachment path and metadata. |
| 19 | `presultupdate` | Patient result updates, result type, pathologists, and release metadata. |
| 20 | `priority` | Case priority labels. |
| 21 | `questionnaire_quality_cn` | CN specimen quality questionnaire scores and notes. |
| 22 | `questionnaire_quality_sn` | SN slide quality questionnaire scores and notes. |
| 23 | `quilljs_test` | Rich-text editor test content. |
| 24 | `report_type` | Report groups, release type, and options. |
| 25 | `req_id_sp_slide` | Special-slide request and completion tracking. |
| 26 | `service_billing` | Patient service/billing line items and costs. |
| 27 | `service_price_list` | Hospital/service pricing and specimen metadata. |
| 28 | `service_type` | Service type labels and billing mappings. |
| 29 | `service_type_master` | Service codes, descriptions, units, and prices. |
| 30 | `sn_prefix` | Surgical-number prefix and descriptions. |
| 31 | `status` | Workflow status descriptions and next/back transitions. |
| 32 | `template_report` | User-owned report template name and description. |
| 33 | `testtiptap` | Rich-text editor test content. |
| 34 | `user` | Staff identity, roles, credentials, and hospital/group assignment. |
| 35 | `user_groups` | User group names. |

## Declared Constraints

The dev3 dump declares these six foreign keys, all with `ON DELETE CASCADE ON UPDATE CASCADE`:

- `job.patient_id` -> `patient.id`
- `job.job_role_id` -> `job_role.id`
- `job.req_id` -> `req_id_sp_slide.id`
- `req_id_sp_slide.patient_id` -> `patient.id`
- `req_id_sp_slide.job_id` -> `job.id`
- `service_billing.patient_id` -> `patient.id`

Other application relationships may be indexed or implemented in PHP without a declared foreign key. State that distinction explicitly. Notable unique indexes are `antibody_list.name`, `patient.pnum`, and `user.username`; `report_type.id` is unique but is not declared as a primary key in this dump.

## Working Procedure

1. Start from the complete table catalog above; do not limit schema analysis to `patient`, `job`, and billing tables.
2. Check the current SQL DDL and nearby PHP queries before asserting column names, types, or behavior.
3. Report declared primary/unique/index/foreign-key constraints separately from relationships inferred from code.
4. Update [database_schema.md](../../database_schema.md) and [database_schema.html](../../database_schema.html) when shared schema knowledge changes.
5. Preserve existing fillable comments and user-entered documentation when updating generated or summarized sections.