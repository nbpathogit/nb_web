---
name: workflow-reference-map
description: "Use when tracing how service types, Surgical Number prefixes, invoice_list mappings, user groups, and job roles relate across the NB Patho application."
---

# Workflow and Billing Reference Map

This KB combines the project references for service types, Surgical Number prefixes, invoice labels, user groups, and job roles. It documents how the concepts connect in the current schema, screenshots, and code; it does not create a new database table or imply that all five tables have a direct relationship.

## Combined relationship overview

| Concept | Database table / field | Connects to | Relationship status and purpose |
|---|---|---|---|
| Service type | `service_type.id`, `service_type.group_type`, `service_type.service_typea_id` | `service_billing.slide_type`; `service_price_list.jobtype`; possible invoice category mapping | Billing queries join `service_billing.slide_type` to `service_type.id`, and pricing code joins `service_price_list.jobtype` to `service_type.id`. These are code relationships; the dev3 schema does not declare FKs for these pairs. |
| Surgical Number type | `patient.sn_type`; prefix definitions in `sn_prefix` | `invoice_list.sn_type` by matching prefix value | Patient type is stored separately from the formatted patient number. The screenshot values appear to match invoice rows by prefix, but the schema does not declare an FK and current PHP search found no direct `invoice_list` query. |
| Invoice configuration | `invoice_list.sn_type`, `invoice_list.service_type`, `invoice_list.group_type` | Surgical Number prefix and apparent service-type category | The screenshot suggests mapping rows by SN prefix and category. The meaning of `invoice_list.service_type` as a match to `service_type.service_typea_id` is plausible from the displayed values, but is not confirmed by a current PHP query. |
| User group | `user_groups.id`; `user.ugroup_id` | User account and authorization | `user.ugroup_id` is used to load the user's group and drive authorization. This is a code relationship; the dev3 schema snapshot does not declare a foreign key. |
| Job role | `job_role.id`; `job.job_role_id` | Patient work assignments and workflow | `job.job_role_id` references `job_role.id`; this FK is declared in the dev3 schema. Role IDs identify task/assignment types, not service or invoice categories. |

## Invoice and service-type category mapping shown

The table below condenses the screenshots into category combinations. `invoice_list.service_type` values 1 and 2 appear to align with `service_type.service_typea_id` values 1 and 2, respectively. This is a screenshot-based interpretation only; verify live data and implementation before treating it as an executable join.

| Surgical Number (`sn_type`) | Invoice group/name shown | `invoice_list.service_type` | Apparent service category (`service_typea_id`) | Service-type examples from screenshot | Notes |
|---|---|---:|---:|---|---|
| `SN`, `IN` | Group 1 — ตรวจชิ้นเนื้อศัลยพยาธิวิทยา | 1 | 1 | ID 1: SN or IN | Invoice rows 1–2. |
| `CN` | Group 2 — ตรวจเซลล์วิทยา | 1 | 1 | ID 4: CN | Invoice row 3. |
| `PN`, `LN` | Group 3 — ตรวจเซลล์มะเร็งปากมดลูก | 1 | 1 | ID 5: PN or LN | Invoice rows 4–5. |
| `FN` | Group 4 — ตรวจชิ้นเนื้อโดยอุณหภูมิเยือกแข็ง | 2 | 2 | ID 6: Frozen Staining | Invoice row 6. |
| `SN`, `CN`, `LN`, `PN`, `FN`, `DN`, `IN` | Group 5 — ตรวจพิเศษ | 2 | 2 | IDs 2, 3, 6, 7 are special-staining categories | Invoice rows 7–13. Actual assignment may depend on each billing line's service type, not only the patient's SN prefix. |
| Screenshot row appears `IN`; mapping text says `DN` | Group 5 — ตรวจพิเศษอณูชีววิทยา / Molecular Staining | 2 | 2 | ID 7: Molecular Staining | Invoice row 14 is inconsistent in the screenshot; verify the row in the live database. |
| `ZN` | Forensic slide service | 1 | 1 | ID 8: สไลด์นิติเวช | The supplied invoice screenshot has no ZN row. Do not infer an invoice mapping from the service-type row alone. |

`group_type`, `service_type`, `service_typea_id`, and `sn_type` are distinct values/fields. Similar numeric values do not prove that they represent the same category. The invoice screenshot shows `order_list = 0` for the displayed rows.

## User group and job role references

These concepts are included for operational context, not as invoice matching dimensions:

| Area | Stored mapping | Examples from current KB/screenshots | How to use it |
|---|---|---|---|
| User-group identity | `user.ugroup_id` → `user_groups.id` by code | `1000` Admin, `1900` Pathologist Admin, `2000` Pathologist, `5000` Clinician customer, `5100` Hospital customer | Determines broad account identity and authorization flags. It does not map invoice names or service categories. |
| Workflow role | `job.job_role_id` → `job_role.id` (declared FK) | `1` Cross Section, `4` Special Slide Preparation, `5` Pathologist, `7` Cytologist | Identifies the work type/assignment on a patient job. It does not map invoice names or service categories. |

Do not produce a Cartesian product of all user groups and job roles against invoice rows. There is no documented table or code relationship that says every group/role has a particular invoice/service mapping. If an invoice report needs pathologist or cytologist details, follow the actual `job` joins and role filters in the billing query rather than adding those IDs to `invoice_list`.

## Practical trace for billing changes

1. Start with a billing line's `service_billing.slide_type` and resolve its `service_type.id` as the billing query does.
2. Read the patient's `patient.sn_type` separately; it is the prefix/type dimension.
3. Compare both values to `invoice_list` only after confirming the intended mapping in the live database or implementing code. The current PHP search did not find a direct `invoice_list` query.
4. If showing staff/access context, trace `job` rows by `patient_id` and `job_role_id`, and trace account authorization through `user.ugroup_id`; neither is an invoice category.
5. Verify the final invoice name in the generated output. The current `pdf_invoice/billingletterinvoice.php` template uses a literal “ใบแจ้งหนี้” heading and is not shown reading `invoice_list.invoice_name`.

## Source KBs and code

- `.github/skills/service-type/SKILL.md` — service-type rows and price/billing usage.
- `.github/skills/surgical-number/SKILL.md` — prefix meanings and patient-number generation.
- `.github/skills/invoice-list/SKILL.md` — invoice configuration rows from the supplied screenshot.
- `.github/skills/user-groups/SKILL.md` — group IDs and authorization behavior.
- `.github/skills/job-role/SKILL.md` — job-role rows, cost fields, and workflow usage.
- `.github/database_schema.md` — dev3 schema snapshot and declared FK inventory.
- `classes/ServiceBilling.php` — joins billing rows to service types and role-specific patient jobs.
- `classes/Job.php`, `classes/JobRole.php` — patient job assignments and job-role lookup.
- `classes/Auth.php`, `classes/Ugroup.php`, `user_auth.php` — user-group loading and authorization flags.
