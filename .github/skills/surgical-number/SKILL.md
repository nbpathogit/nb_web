---
name: surgical-number
description: "Use when working with Surgical Number (SN), patient number prefixes, sn_type, sn_prefix, or patient number generation and formatting."
---

# Surgical Number Prefixes

Reference for the prefix abbreviations shown in the patient-add prefix selector and used as `patient.sn_type`. Use the exact uppercase prefix because patient number generation and workflow branching compare these values directly.

## Prefix mapping

| Prefix | Meaning shown in the supplied selector | English description |
|---|---|---|
| `SN` | ตรวจชิ้นเนื้อศัลยพยาธิวิทยา | Surgical pathology |
| `CN` | ตรวจเซลล์วิทยา | Cytology |
| `LN` | ตรวจเซลล์มะเร็งปากมดลูกแบบใหม่ (Liquid base cytology) | Liquid-based cervical cytology |
| `PN` | ตรวจเซลล์มะเร็งปากมดลูกแบบเก่า (conventional cervical cytology) | Conventional cervical cytology |
| `FN` | ตรวจชิ้นเนื้อโดยอุณหภูมิเยือกแข็ง | Frozen-section tissue examination |
| `DN` | ตรวจพิเศษอณูชีววิทยา | Special molecular biology examination |
| `IN` | ตรวจชิ้นเนื้อศัลยพยาธิ (กรณีเรียกเก็บเงินแล้ว) | Surgical pathology tissue examination (billing-related category per selector) |
| `ZN` | ตรวจ นิติเวช สไลด์ | Forensic slide examination |

The Thai labels above follow the supplied application screenshot. Confirm the `sn_prefix` table in the target database if labels or active options may have changed.

## Number format and generation

- The generated patient number is built as `prefix + year + five-digit running number`, for example `SN2600001`.
- `patient_add.php` reads the selected prefix from `snprefix`, gets the current year through `Util::get_curreint_year()`, and pads the running number to five digits using `Util::prepend_string_with_zero(5, $runnum)`.
- In automatic mode, the maximum `sn_run` is retrieved per `sn_year` and `sn_type` by `Patient::get_max_sn_run_by_year()`. Deleted/trash rows (`movetotrash = 1`) are excluded from that maximum.
- Manual mode (`is_autogen == "no"`) accepts the prefix from `prenum`, year from `yearnum`, and run number from `runnum`, then formats the same combined patient number.
- `patient_add.php` loads prefix choices from `SnPrefix::getAll($conn)`, backed by `sn_prefix`. Prefix meaning/label configuration is therefore database-managed; do not hardcode display labels elsewhere unless needed by established business logic.
- `patient.sn_type` stores the type separately from `sn_year` and `sn_run`; `pnum` stores the combined number.

## Workflow distinctions in code

- `patient_pdf.php` has special handling for `SN`; it also groups `CN`, `PN`, and `LN` in report-title logic. PN/LN are handled together in the result workflow in `patient_edit.php`.
- `patient_add.php` has additional questionnaire creation branches for `PN`/`LN`, `SN`, and `CN`.
- Special-slide/specimen UI has explicit `IN` branches in `includes/patient_form_015_slide1_add_specimen.php` and `includes/patient_form_050_slide2_prepare_sp_slide.php`.
- Do not infer that similarly named prefixes share workflow behavior. Search exact `sn_type` checks before changing a report, billing, questionnaire, or specimen flow.
- Prefix names are business abbreviations, not a reliable source of authorization or billing rules. Verify those in their respective code paths and database configuration.

## When changing prefixes or numbering

1. Keep prefix values uppercase and consistent across `sn_prefix`, `patient.sn_type`, `pnum`, forms, model queries, reports, and billing.
2. Check both auto-generated and manually entered patient numbers.
3. Search for explicit `sn_type` conditions in `patient_add.php`, `patient_edit.php`, `patient_pdf.php`, section partials, reports, and billing queries.
4. Preserve the five-digit run formatting unless the numbering requirement explicitly changes.
5. Treat the current max-run query's year-and-prefix filtering and trash-row behavior as part of the existing numbering rule; validate collisions and concurrent creation before changing numbering logic.

## Main source files

- `patient_add.php` — prefix selection, automatic/manual number generation, and initial type-specific questionnaire logic.
- `classes/SnPrefix.php` — reads prefix records from `sn_prefix`.
- `classes/Patient.php` — persists `sn_type` and retrieves the maximum run by year and prefix.
- `patient_edit.php`, `patient_pdf.php` — type-specific clinical workflow and report behavior.
- `includes/patient_form_015_slide1_add_specimen.php`, `includes/patient_form_050_slide2_prepare_sp_slide.php` — `IN`-specific UI behavior.
