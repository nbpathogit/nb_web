---
name: service-type
description: "Use when working with service_type records, service type groups, billing labels, specimen/service price categories, or service_typea_id mappings."
---

# Service Type Reference

Service Type records are stored in the `service_type` table. This skill documents the table and its use in billing and specimen-price categories. The mapping below is transcribed from the supplied database screenshot. Confirm the live database when exact labels or IDs matter because service-type definitions are database-managed.

## Fields and distinctions

- `id`: primary key used by `service_billing.slide_type` and `service_price_list.jobtype`.
- `order_list`: display/order sequence. `ServiceType::getAll_v2()` sorts by this field.
- `group_type`: grouping used by `ServiceType::getG1()` and `ServiceType::getG2()`; it is distinct from `service_typea_id` and the service-type row's `id`.
- `service_type`: display name for the billable service category.
- `service_typea_id`: category/group identifier used in billing queries to split service-type categories. The supplied screenshot's small header appears to show this field; values observed are `1` and `2`.
- `service_typea_bill`: category billing label; `ServiceBilling` selects it as `s_service_typea_bill`.
- `service_type_bill`: billing text also selected by billing queries.
- `service_type_e`: English label, where configured.
- `name_by_spcimen`: specimen-oriented description used for billing/detail grouping. This is the exact misspelling in the dev3 schema and model property.

The dev3 schema snapshot at `.github/database_schema.md` confirms the exact column names. Some screenshot headers and values are small or truncated; verify the live schema and data before relying on them in another environment. The screenshot's `service_type_bill` values are not transcribed here where they could not be read reliably.

## Current rows shown in screenshot

| id | order_list | group_type | Service type | service_typea_id | service_typea_bill | service_type_e | name_by_spcimen |
|---:|---:|---:|---|---:|---|---|---|
| 1 | 1 | 1 | `1 ตรวจชิ้นเนื้อศัลยพยาธิวิทยา (SN or IN)` | 1 | ค่าตรวจ | — | สิ่งส่งตรวจ (SN or IN) with group type 1 |
| 2 | 5 | 2 | `5 ตรวจพิเศษ (Special Staining)` | 2 | ตรวจพิเศษ | Special Staining | ย้อมพิเศษ group type 2 |
| 3 | 6 | 2 | `6 ตรวจพิเศษ (Immuno Staining)` | 2 | ตรวจพิเศษ | Immuno Staining | ย้อมพิเศษ group type 2 |
| 4 | 2 | 1 | `2 ตรวจเซลล์วิทยา (CN)` | 1 | ค่าตรวจ | — | สิ่งส่งตรวจ CN group type 1 |
| 5 | 3 | 1 | `3 ตรวจเซลล์มะเร็งปากมดลูก (PN or LN)` | 1 | ค่าตรวจ | — | สิ่งส่งตรวจ PN LN group type 1 |
| 6 | 4 | 2 | `4 ตรวจชิ้นเนื้อโดยอุณหภูมิเยือกแข็ง (Frozen Staining)` | 2 | ตรวจพิเศษ | Frozen Staining | FN with group type 2 |
| 7 | 7 | 2 | `7 ตรวจพิเศษอณูชีววิทยา (Molecular Staining)` | 2 | ตรวจพิเศษ | Molecular Staining | DN with group type 2 |
| 8 | 8 | 2 | `8 สไลด์นิติเวช` | 1 | สไลด์นิติเวช | — | ZN สไลด์นิติเวช |

The table preserves the grouping values as displayed: row 8 has `group_type = 2` even though `service_typea_id = 1`. Do not assume those fields are interchangeable.

## Code usage

- `classes/ServiceType.php`:
  - `getAll($conn)` returns all `service_type` rows.
  - `getAll_v2($conn)` returns all rows ordered by `order_list`.
  - `getG1($conn)` filters by `group_type = 1`.
  - `getG2($conn)` filters by `group_type = 2`.
- `ajax_servicetype/getServiceTypeGroup2.php` returns `ServiceType::getG2()` as JSON. No corresponding Group 1 endpoint was found during this review.
- `service_price_list.jobtype` references a service-type row's `id`; for example, `ServicePriceList::getAll_v2()` joins `spl.jobtype = st.id`.
- `service_billing.slide_type` references `service_type.id`. Billing queries join `service_type` and select both `service_type_bill` and `service_typea_bill`.
- `classes/ServicePriceList.php` contains category-specific selectors. The standard specimen selector uses `jobtype = 1`; a special-staining selector uses IDs `2`, `3`, `6`, and `7`; another specimen-category selector includes IDs `1`, `4`, `5`, and `8`. Check those helpers before changing row IDs or category membership.
- `nb_price.php`, `nb_price_8c.php`, and `nb_price_9c.php` use `getAll_v2()` to populate price-list filters.
- `classes/ServiceBilling.php` contains billing aggregation and service-label formatting; check it when adjusting invoice/report category output.

## Change guidance

1. Verify the deployed `service_type` schema and data before editing table rows or relying on screenshot values.
2. Keep primary key `id`, `group_type`, and `service_typea_id` conceptually separate; each serves a different lookup/filtering purpose.
3. When adding/remapping a row, inspect `service_price_list.jobtype`, `service_billing.slide_type`, all hard-coded ID lists in `ServicePriceList`, Group 1/2 selectors, and billing joins/labels.
4. Preserve `order_list` behavior in UI dropdowns.
5. Update both Thai/primary billing labels and English/specimen labels consistently when requirements call for all display surfaces.
6. Validate related pricing and billing outputs; a changed ID or label can affect patient charges, billing summaries, and service/specimen groupings.

## Main source files

- `classes/ServiceType.php` — service-type retrieval and `group_type` filters.
- `classes/ServicePriceList.php` — hospital-specific service price rows and service-category selectors.
- `classes/ServiceBilling.php` — billing joins, groupings, and labels.
- `ajax_servicetype/getServiceTypeGroup2.php` — Group 2 JSON endpoint.
- `nb_price.php`, `nb_price_8c.php`, `nb_price_9c.php` — price-list management screens.
