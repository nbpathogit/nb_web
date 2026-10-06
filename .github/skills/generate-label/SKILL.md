---
name: generate-label
description: "Use when working on the original sticker label workflow in this PHP app: generating lists from patient SN numbers, filtering by accept date, adding records to the print queue, or creating PDF label exports."
---

# Generate Label

This skill captures the workflow and conventions used by the original sticker-label page in `generate_label.php`.

## Scope

Use this skill when modifying or extending:
- the original label creation screen in `generate_label.php`
- AJAX handlers under `ajax_data/`
- PDF sticker generation pages such as `sn_pdf1.php`, `sn_pdf2.php`, `sn_sp_pdf1.php`, and `sn_sp_pdf2.php`
- label queue logic tied to `LabelPrint` records and patient SN selection

## Business purpose

The page lets users:
1. view the current user’s pending print list
2. filter SN numbers by accept date
3. select one patient record and assign letter/number ranges
4. add the record to the label queue
5. generate PDF sticker sheets for printing

## Core implementation pattern

### 1. Entry flow
- `generate_label.php` loads the user’s current label queue via `LabelPrint::getAllbyUserID()`.
- It also loads patient data via `Patient::getAllJoin_forlableprint($conn, 1)`.
- The page renders the queue table and the SN selection UI.

### 2. Accept-date filtering
- A date selected in `target_accept_date` triggers `drawSelectionAndDOM()`.
- This fetches patient SN numbers using `ajax_data/generate_label_get_SN_by_date.php`.
- The response is parsed as JSON and used to rebuild the dropdown and hidden DOM list.

### 3. Single-record selection
- The user selects a patient SN from the dropdown.
- The selected item is stored in a global `targetpatient` object.
- The page fills hidden or visible fields like:
  - `patho_abbreviation`
  - `accept_date`
  - `hn_num`
- When the form submits, it sends the `patient_id`, `userid`, `sn_num`, `letter`, `start_num`, and `end_num` to `ajax_data/generate_label_add_record.php`.

### 4. Queue persistence
- Each queued record stores:
  - `patient_id`
  - `userid`
  - `sn_num`
  - `hn_num`
  - `patho_abbrev`
  - `accept_date`
  - `company_name`
  - `letter`
  - `start_num`
  - `end_num`
- The backend uses this to create printable label entries for the selected range.

### 5. PDF generation
- The page exposes multiple preview buttons using `window.open(...)`.
- Generated variants include:
  - A4 sticker sheet
  - 76mm x 20mm sticker sheet
  - specimen label formats with grid/no-grid options

## Data conventions

### Dates
- Database/JSON values are typically ISO `YYYY-MM-DD`.
- UI display converts them to `DD/MM/YYYY` using `convertDateFormat()`.

### Hidden patient list
The page stores patient data in a hidden `ul.patientlist` container with `li` elements that carry attributes like:
- `tabindex`
- `pnum`
- `hn_num`
- `patho_abbreviation`
- `accept_date`

This DOM list is rebuilt whenever the date filter changes and is then mapped to `resultArray` for selected patient lookup.

## Frontend patterns to preserve
- Rebuild the dropdown and DOM list after an accept-date change.
- Re-render the print table after every save/insert action.
- Use `selectize` for dynamic dropdowns.
- Keep queue data scoped to the current user via `$_SESSION["userid"]`.

## Matching implementation rules
When making changes in this workflow:
- keep user-specific queue behavior intact
- preserve the patient selection and letter range flow
- update both the UI and AJAX handlers when payload fields change
- keep PDF generation buttons tied to the same query-string conventions

## Typical file touchpoints
- `generate_label.php` — main screen, UI, JS flow
- `ajax_data/generate_label_get_record.php` — fetch existing queued records
- `ajax_data/generate_label_get_SN_by_date.php` — fetch SNs by date
- `ajax_data/generate_label_add_record.php` — add a single record
- `sn_pdf1.php` / `sn_pdf2.php` / `sn_sp_pdf1.php` / `sn_sp_pdf2.php` — sticker export templates

## Example coding principle
The page follows a clear lifecycle:
1. fetch list
2. parse JSON
3. rebuild dropdown/DOM
4. bind form submit to save records
5. refresh print table

When extending the feature, keep that same pattern.
