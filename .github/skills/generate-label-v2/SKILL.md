---
name: generate-label-v2
description: "Use when working on the label-printing workflow in this PHP app: generating sticker labels from SN numbers, filtering by accept date, adding records to the print list, or creating PDF exports for label sheets."
---

# Generate Label V2

This skill captures the workflow and conventions used by the sticker-label page in `generate_labelv2.php`.

## Scope

Use this skill when modifying or extending:
- the main label creation screen in `generate_labelv2.php`
- AJAX handlers under `ajax_data/`
- PDF sticker generation pages such as `sn_pdf1.php`, `sn_pdf2.php`, `sn_sp_pdf1.php`, and `sn_sp_pdf2.php`
- label queue logic tied to `LabelPrint` records and patient SN selection

## Business purpose

The page lets users:
1. view the current user’s pending print list
2. filter SN numbers by accept date
3. select one patient record or many records together
4. assign letter/number ranges such as `A-1`, `B-2`, etc.
5. add all selected records to the queue for sticker generation
6. generate PDF sticker sheets for printing

## Core implementation pattern

### 1. Entry flow
- `generate_labelv2.php` loads the user’s current label queue via `LabelPrint::getAllbyUserID()`.
- It also loads patient data via `Patient::getAllJoin_forlableprint($conn, 1)`.
- The page renders the current queue table and the patient/SN selection UI.

### 2. Accept-date filtering
- A selected date triggers AJAX requests to:
  - `ajax_data/generate_label_get_record.php` to fetch already-added records
  - `ajax_data/generate_label_get_SN_by_date.php` to fetch valid patient SN numbers for that date
- The response is used to rebuild the patient dropdown and the hidden DOM list used by the form logic.
- Existing SN numbers are marked as `Finish` so users do not re-add duplicates.

### 3. Multi-range selection
- For each SN row, the UI shows checkboxes for letters `A` through `E`.
- Each letter has a `start_num` and `end_num` selector.
- If a letter is checked, its number selectors are enabled.
- When the user clicks “Add All to List”, the browser gathers all checked rows and sends them as a single JSON payload to `generate_label_add_multiple_record.php`.

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
- The backend expands ranges like `A:3-5` into multiple print entries as needed.

### 5. PDF generation
- The page exposes multiple preview buttons using `window.open(...)`.
- The generated PDF variants include:
  - standard A4 sticker paper
  - 76mm x 20mm paper
  - specimen sticker formats with grid/no-grid options

## Data conventions

### Dates
- Database and AJAX values are typically ISO `YYYY-MM-DD`.
- UI display converts them to `DD/MM/YYYY` with `convertDateFormat()`.

### Hidden patient list
The page keeps a hidden `ul.patientlist` container with `li` elements containing attributes such as:
- `tabindex` (patient id)
- `pnum`
- `hn_num`
- `patho_abbreviation`
- `accept_date`

This DOM list is rebuilt after date filtering and is the source for `resultArray` logic used by the form.

## Frontend patterns to preserve
- Re-initialize or destroy DataTables after AJAX refreshes.
- Use `selectize` for dropdowns when populating dynamic options.
- Keep both the queue table and the SN table synchronized after record insertion.
- Use `user_id` from `$_SESSION["userid"]` consistently.
- Preserve the current user’s queue separation by filtering by `userid`.

## Matching implementation rules
When making changes in this workflow:
- keep the same user-specific queue behavior
- maintain duplicate prevention by checking existing SN values
- preserve the `A-E` letter range logic
- keep PDF generation methods triggered via the same button flow
- update related AJAX handlers whenever the record payload changes

## Typical file touchpoints
- `generate_labelv2.php` — main screen, UI, JS logic
- `ajax_data/generate_label_get_record.php` — fetch existing queued records
- `ajax_data/generate_label_get_SN_by_date.php` — fetch SNs by date
- `ajax_data/generate_label_add_record.php` — add a single record
- `ajax_data/generate_label_add_multiple_record.php` — add many records in one batch
- `sn_pdf1.php` / `sn_pdf2.php` / `sn_sp_pdf1.php` / `sn_sp_pdf2.php` — sticker export templates

## Example coding principle
The main page’s JavaScript uses two pattern loops repeatedly:
- fetch records from backend
- rebuild the DataTable and dropdown state from the JSON response

When extending the feature, keep that same lifecycle:
1. fetch
2. parse
3. refresh UI
4. rebind events if necessary
5. update queue table
