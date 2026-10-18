---
name: invoice-list
description: "Use when working with invoice_list records, invoice display names, invoice grouping, or mapping invoice labels by specimen number type and service type."
---

# Invoice List Reference

Invoice display and grouping configuration is stored in the `invoice_list` database table. In the supplied database screenshot, rows map an invoice group/name to a `mapping_rule`, patient `sn_type`, and `service_type`. This reference is intended to help understand which invoice label should appear for each configured billing category.

## Schema

The dev3 schema snapshot documents these columns:

| Field | Type | Purpose |
|---|---|---|
| `id` | `int(11)` | Primary key. |
| `order_list` | `int(11)` | Display/order value. |
| `group_type` | `int(11)` | Invoice category/group identifier shown in the configuration. |
| `invoice_name` | `varchar(120)` | Main invoice display name. |
| `invoice_name_e` | `varchar(120)` | English invoice display name, when configured. |
| `mapping_rule` | `varchar(256)` | Human-readable description of the category mapping. |
| `sn_type` | `varchar(2)` | Patient Surgical Number type to which the row applies. |
| `service_type` | `int(11)` | Service type ID used by the mapping. |

The schema snapshot does not declare foreign keys for `invoice_list`; in particular, its `service_type` column should not be described as a database-enforced foreign key without checking the target database DDL. The `invoice_list` table is present in the schema and screenshot, but searches of the current PHP code found no direct query to this table. Confirm runtime behavior before assuming these configuration rows currently drive generated invoices.

## Rows shown in the supplied screenshot

These are the 14 rows and values displayed in the screenshot. `order_list` is `0` on each visible row. The Thai labels are transcribed from the image and should be verified against the target database before editing or using them as exact legal billing text.

| ID | `group_type` | `invoice_name` shown | `invoice_name_e` | `mapping_rule` shown | `sn_type` | `service_type` |
|---:|---:|---|---|---|---|---:|
| 1 | 1 | ตรวจชิ้นเนื้อศัลยพยาธิวิทยา | — | สิ่งส่งตรวจ (SN or IN) with group type 1 | SN | 1 |
| 2 | 1 | ตรวจชิ้นเนื้อศัลยพยาธิวิทยา | — | สิ่งส่งตรวจ (SN or IN) with group type 1 | IN | 1 |
| 3 | 2 | ตรวจเซลล์วิทยา | — | สิ่งส่งตรวจ CN group type 1 | CN | 1 |
| 4 | 3 | ตรวจเซลล์มะเร็งปากมดลูก | — | สิ่งส่งตรวจ PN or LN group type 1 | PN | 1 |
| 5 | 3 | ตรวจเซลล์มะเร็งปากมดลูก | — | สิ่งส่งตรวจ PN or LN group type 1 | LN | 1 |
| 6 | 4 | ตรวจชิ้นเนื้อโดยอุณหภูมิเยือกแข็ง | — | FN with group type 2 | FN | 2 |
| 7 | 5 | ตรวจพิเศษ | — | ย้อมพิเศษ group type 2 | SN | 2 |
| 8 | 5 | ตรวจพิเศษ | — | ย้อมพิเศษ group type 2 | CN | 2 |
| 9 | 5 | ตรวจพิเศษ | — | ย้อมพิเศษ group type 2 | LN | 2 |
| 10 | 5 | ตรวจพิเศษ | — | ย้อมพิเศษ group type 2 | PN | 2 |
| 11 | 5 | ตรวจพิเศษ | — | ย้อมพิเศษ group type 2 | FN | 2 |
| 12 | 5 | ตรวจพิเศษ | — | ย้อมพิเศษ group type 2 | DN | 2 |
| 13 | 5 | ตรวจพิเศษ | — | ย้อมพิเศษ group type 2 | IN | 2 |
| 14 | 5 | ตรวจพิเศษอณูชีววิทยา | Molecular Staining | DN with group type 2 | IN | 2 |

Screenshot values for `invoice_name`, `mapping_rule`, and row 14's `sn_type` are small; compare them with the live table if exact mapping matters. The screenshot shows a total of 14 rows.

## Mapping interpretation

- `sn_type` identifies the specimen/case number prefix (`SN`, `IN`, `CN`, `PN`, `LN`, `FN`, or `DN`).
- `service_type` is a separate numeric mapping value; the screenshot uses `1` for the first diagnostic category and `2` for special staining categories.
- `group_type` groups invoice rows and must not be confused with `service_type` or `sn_type`.
- `invoice_name` is the main configured label intended for display on an invoice; `invoice_name_e` provides an English label where present.
- `mapping_rule` explains the grouping in human-readable form; the screenshot includes references to service-type group 1 or 2.
- The screenshot includes a potentially inconsistent last row: `mapping_rule` says `DN with group type 2`, but `sn_type` appears as `IN`. Verify the actual row before changing logic or treating the table as executable mapping rules.

## Related tables and code

- `.github/database_schema.md` — schema reference for the `invoice_list` fields.
- `service_type` — service-type definitions; do not assume the `invoice_list.service_type` mapping is enforced by a foreign key.
- `patient.sn_type` — patient number type used to distinguish SN/IN/CN/PN/LN/FN/DN cases.
- `classes/ServiceBilling.php` — current billing-detail queries select service labels from `service_type`; no direct `invoice_list` usage was found in the PHP code search for this KB.
- `pdf_invoice/billingletterinvoice.php` — invoice PDF template; its visible heading is currently the literal “ใบแจ้งหนี้”, not a value read from `invoice_list`.

## Change guidance

1. Check the live `invoice_list` table before relying on screenshot labels, mappings, or IDs.
2. If wiring or changing invoice generation, verify the intended matching conditions for `sn_type`, `service_type`, `group_type`, and `mapping_rule`; do not infer executable SQL logic from the descriptive text alone.
3. Confirm whether `invoice_name` and `invoice_name_e` should be displayed in the invoice letter, billing summary, or both, and whether an empty English label should fall back to Thai.
4. Check the printed invoice output and any summary/attached-detail pages after changing labels or mapping behavior.
5. Keep `group_type`, `service_type`, and `sn_type` distinct; values that happen to look related are not interchangeable.
