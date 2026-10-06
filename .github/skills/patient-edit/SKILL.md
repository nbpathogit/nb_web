---
name: patient-edit
description: "Use when working on the patient record edit workflow in this PHP app: viewing/editing patient details, moving workflow status, saving section data, or handling result and report updates for a single patient."
---

# Patient Edit Workflow

This skill captures the patterns used by the main patient record editor in `patient_edit.php`.

## Scope

Use this skill when modifying or extending:
- the main patient details screen in `patient_edit.php`
- patient status transitions and display logic
- per-section save handlers for patient detail, plan, interim result, and final result flows
- report/result updates and critical-report handling
- patient-record auto-scroll / edit-mode state handling

## Business purpose

The page is a single-page clinical workflow for one patient. It supports:
1. viewing the full patient record
2. editing patient information and planning data in sections
3. moving the case through workflow statuses
4. saving interim/final pathology results
5. generating PDFs or release actions when reports are completed
6. keeping edit mode and auto-scroll state consistent across sections

## Core implementation pattern

### 1. Request handling
The top of the file begins by:
- requiring app init and database connection
- enforcing login authorization with `Auth::requireLogin()`
- checking `$_SERVER["REQUEST_METHOD"] == "POST"`
- handling different save actions by posted button names such as:
  - `save_patient_detail`
  - `save_patient_detail_next`
  - `save_patient_plan`
  - `save_patient_plan_next`
  - `save_interim_result`
  - `save_u_result`
  - `status`

The logic is not centralized through one controller; it uses explicit branch conditions based on form submit names.

### 2. Section-specific save actions
Each save routine does the same general pattern:
- instantiate a `Patient` object or fetch the current patient
- copy posted values into the model only if present
- set state properties like `isautoeditmode` and `pautoscroll`
- call a dedicated persistence method such as:
  - `updatePatientDetail()`
  - `updatePatientPlan()`
  - `updateInterimResult()`
  - `update()`
  - `updateStatusWithMoveDATE()`
- redirect back to `patient_edit.php?id={id}` on success

This is a typical “form post -> hydrate model -> save -> redirect” pattern.

### 3. Status transitions
The page supports workflow progression via status updates. For example:
- a patient can move between status codes like `1000`, `2000`, `3000`, `10000`, etc.
- helper methods such as `Patient::updateStatusWithMoveDATE()` are used to move status and optionally set dates
- release/report actions can trigger extra logic such as PDF generation when moving from a report state to a released state

The status flow is embedded directly in form handlers, not isolated in a service layer.

### 4. Edit mode and auto-scroll state
The page tracks editing state through flags such as:
- `$isEditModePageOn`
- `$isEditModePageForPatientInfoDataOn`
- `$isEditModePageForPlaningDataOn`
- `$isEditModePageForIniResultDataOn`
- `$isEditModePageForFinResultDataOn`
- `$isEditModePageForSpSlidePrepDataOn`

It also stores scroll target names in:
- `pautoscroll`
- `isautoeditmode`

This is used so that after a save or redirect, the user lands back in the correct section and edit mode.

### 5. Data hydration and setup
After POST handling, the page loads the patient record and related model data:
- `Patient::getAll($conn, $_GET['id'])`
- `QuesSN::getAllbyPatientID()`
- `QuesCN::getAllbyPatientID()`
- hospital/specimen/clinician/pathologist/user lists
- service billing, jobs, results, and contract data

This is a big “single-page dashboard” pattern where one page aggregates multiple subsystems for one patient.

## Data conventions

### Patient model fields
Common fields used throughout the file include:
- `pnum`, `plabnum`
- `ppre_name`, `pname`, `plastname`
- `pgender`, `pedge`
- `date_1000`
- `status_id`, `priority_id`
- `phospital_id`, `phospital_num`
- `ppathologist_id`, `pspecimen_id`, `pclinician_id`
- `pprice`, `pspprice`
- `p_rs_specimen`, `p_rs_clinical_diag`, `p_rs_gross_desc`, `p_rs_microscopic_desc`

### Redirect-after-save pattern
Most successful saves end with:
- `Url::redirect("/patient_edit.php?id=" . $_GET['id']);`

This keeps the page in a fresh state after each save.

## Frontend pattern to preserve
- Use section IDs like `patient_detail_section`, `patient_plan_section`, and `interim_result_section` for scroll targets.
- Keep section-specific buttons such as `save_patient_detail`, `save_patient_plan_next`, `discard`, and `edit_*` aligned with the backend branches.
- Preserve the page’s state model when toggling between View/Edit mode.

## Matching implementation rules
When changing this workflow:
- keep the save-branch naming consistent with the existing form buttons
- preserve the `$_GET['id']` patient-scoped behavior
- keep `pautoscroll` and `isautoeditmode` values in sync with section edits
- do not bypass the redirect pattern after successful save
- update the relevant patient model method when adding fields or new sections

## Typical file touchpoints
- `patient_edit.php` — main workflow and section UI
- `Patient` model methods — data persistence for patient detail, plan, result updates
- status definitions in `includes/status_cur.php`
- related result/report classes such as `Presultupdate`, `QuesSN`, `QuesCN`, etc.

## Example coding principle
The page uses a repeated pattern: fetch current patient -> map posted values -> save via model method -> redirect. When extending functionality, follow that lifecycle rather than introducing ad hoc database writes directly from the page.
