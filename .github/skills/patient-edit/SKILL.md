---
name: patient-edit
description: "Use when working on patient_edit.php: the single-patient workflow, patient status transitions, section save/edit state, result and report handling, questionnaires, or patient PDF attachments."
---

# Patient Edit Workflow

Knowledge base for the current implementation of the single-patient editor in `patient_edit.php`. The page combines request handling, data loading, workflow state, section rendering, and client-side AJAX behavior. Verify the current file before changing behavior; several legacy handlers and sections are retained but are not currently reachable from the rendered UI.

## Request lifecycle and access

1. `patient_edit.php` requires `includes/init.php`, sets `$inited`, opens the PDO connection from `includes/db.php`, then calls `Auth::requireLogin("patient_edit.php", $_GET['id'])`.
2. POST handlers run before the record is loaded for rendering. Most use `$_GET['id']` as the patient ID; preserve this patient scope and redirect-after-save behavior.
3. The page loads the patient, related records, and workflow lists, then requires `includes/status_cur.php` and `user_auth.php`.
4. The rendered page includes the shared header/footer. Customer users are stopped from viewing a patient outside their hospital group (`$isUnderCurHospital`).
5. `$isEnableEditButton` and section-specific role/status conditions control UI availability. Do not assume the UI gate itself provides server-side authorization; review the POST endpoint and model methods whenever changing a permission-sensitive operation.

## POST action map

The handler uses separate `if (isset($_POST[...]))` branches, not a single controller or `elseif` chain. Typical actions submit only one button name, but preserve this structure when modifying it.

| POST key | Current behavior |
|---|---|
| `refreshpage` | Captures posted `pautoscroll` for the render phase. |
| `status` | Calls `Patient::updateStatusWithMoveDATE()` with current/target status and first-report-date flag. On transition from `12000` or `13000` to `20000`, updates `reported_as` and runs `patient_pdf.php` with output-to-file settings. Persists posted auto-scroll/edit-mode state, then redirects if the status/release flags pass. |
| `add_u_result` | Creates a `Presultupdate` row for the patient using result type and pathologist, then redirects. |
| `save_u_result` | Updates a result and both pathologist assignments. Then updates the patient's critical-report flag from the checkbox and redirects. |
| `discard_u_result` | Redirects to the patient page. |
| `save` | Legacy whole-page save; hydrates many patient fields and calls `Patient::update()`. |
| `discard_patient_detail`, `discard_patient_plan`, `discard_interim_result` | Set the corresponding auto-scroll target and redirect. |
| `save_patient_detail` | Hydrates patient/detail, planning, and result fields; sets `isautoeditmode = "NA"` and scroll target `patient_detail_section`; calls `updatePatientDetail()`. |
| `save_patient_detail_next` | Moves status `1000` to `2000`, hydrates the same broad set of patient fields, sets the next edit section to `patient_plan_section`, then calls `updatePatientDetail()`. Status is changed before the save call. |
| `save_patient_plan` | Hydrates patient fields, sets the plan scroll target and view-after-save state, then calls `updatePatientPlan()`. |
| `save_patient_plan_next` | Advances status `2000` to `3000` for `p_speciment_type == "lump"` or `10000` otherwise, then calls `updatePatientPlan()`. Scrolls to specimen preparation or lab-fluid section. |
| `save_sp_prep_slide` | Updates special-slide fields through `updateSpcialSlide()` and scrolls to `slide_sp_prep_section`. |
| `save_interim_result` | Hydrates patient fields and calls `updateInterimResult()`, returning to `interim_result_section`. |

Failed saves generally emit a JavaScript alert; preserve existing handling unless improving the error contract deliberately.

## Current workflow and rendered sections

The status/section map documented in the page is:

| Status | Intended section/step |
|---:|---|
| `1000` | `patient_detail_section` |
| `2000` | `patient_plan_section` |
| `3000` | `specimen_prep_section` |
| `6000` | `slide_prep_section` |
| `10000` | `lab_fluid_section_section` |
| `11000` | `interim_result_section` |
| `12000` | `diag_result_section` |
| `13000` | `confirm_result_section` |
| `20000` | `finish_section` |

Actual rendered layout has these key sections:

- `patient_detail_section`: patient detail form partial `includes/patient_form_010_detail.php`; edit/save/finish-next controls depend on role, status, and edit state.
- `patient_plan_section`: specimen/clinician assignment and specimen list, plus assigned pathologist/cytologist partials. The standard `patient_plan` form and its save controls are currently wrapped in `if (false)`, although its POST handlers remain. Confirm current markup before assuming those actions can be submitted.
- PN/LN cases render `patient_form_080_job7_result.php` in the diagnosis/result section.
- Non-PN/LN cases render specimen preparation (`patient_form_030_job1_prepare_specimen.php`, `patient_form_035_job2_prepare_specimen.php`), slide preparation (`patient_form_040_job3_prepare_slide.php`), special-slide preparation (`patient_form_050_slide2_prepare_sp_slide.php`), and `patient_form_080_job6_result.php`.
- SN/CN quality questionnaires render only when matching patient questionnaire rows exist.
- The lab-fluid section is wrapped in a condition ending with `&& FALSE`, so it is currently disabled in the UI even though related handlers and data loading remain.
- The finish section is rendered at status `20000`. The report PDF partial is included from `patient_form_100_pdf.php`.
- Internal notes render only for `$isCurUserNB`.
- The page includes the `attach_pdf_section` near the bottom. Every user who can access the patient page can see the patient-scoped list of uploaded PDF attachments; only the assigned/owning pathologist (`$isCurrentPathoIsOwnerThisCase`) sees the upload form. The form posts `file`, `patient_id`, and `pnum` to `pdf_attach_upload.php` through AJAX. The list is queried by patient ID from `pdf_attach` and uses `subfolder` plus `fileName` to construct links. See the dedicated `patient-edit-attach-pdf` skill for upload/storage details.

Some IDs such as `diag_result_section` and `finish_section` are reused in multiple parts of the page. Do not assume every DOM ID is unique or maps to exactly one workflow section without checking the rendered markup and JS selectors.

## Edit mode and auto-scroll

The page has a global `$isEditModePageOn` and section flags for patient details, planning, interim results, final results, and special-slide preparation. POST action names such as `edit_patient_detail`, `edit_patient_plan`, `edit_interim_result`, `edit_u_result`, and `edit_sp_prep_slide` select which flag is enabled.

`pautoscroll` and `isautoeditmode` are persisted through `Patient` helpers/model fields:

- Section saves usually set `isautoeditmode` to `"NA"` and set `pautoscroll` to their section.
- `save_patient_detail_next` sets the next edit state to `patient_plan_section`.
- `save_patient_plan_next` chooses the next scroll section according to specimen type.
- During GET rendering, `?pautoscroll=...`, `?focus=...`, and posted refresh state can override the scroll destination.
- After fetching the patient, the page calls `Patient::clearAutoScroll()` and `Patient::clearisautoeditmode()`, then uses the loaded/overridden values to decide render state.
- `discard` clears edit flags for the render; the detail and plan Discard links use `focus=` URLs rather than the POST discard branch.

Keep section button names, `isautoeditmode` values, `pautoscroll` targets, DOM anchors, and backend POST branches aligned. Use the spelling of current anchors exactly (including `lab_fluid_section_section`).

## Data loading and related systems

The page is an aggregation view. It loads the patient with `Patient::getAll($conn, $_GET['id'])`, then loads questionnaire rows, patient/subpatient relations, hospitals, specimen lists, user/role lists, priorities/statuses, report types, result groups, billing, jobs, outside contracts, and hire rows. Relevant classes include `Patient`, `QuesSN`, `QuesCN`, `Hospital`, `ServicePriceList`, `User`, `Priority`, `Status`, `LabFluid`, `Ugroup`, `JobRole`, `ReportType`, `Presultupdate`, `ServiceBilling`, `Job`, `OutsideContract`, and `HireList`.

It also computes release state, whether `date_first_report` exists, current status/navigation targets, current pathologist ownership, and billing closing-date state. Prefer existing loaded data and model methods over adding ad-hoc SQL in the page.

Common patient fields hydrated by the page's save handlers include:

- Identity and intake: `pnum`, `plabnum`, `ppre_name`, `pname`, `plastname`, `pgender`, `pedge`, `date_1000`.
- Workflow and assignments: `status_id`, `priority_id`, `phospital_id`, `phospital_num`, `ppathologist_id`, `pspecimen_id`, `pclinician_id`.
- Work preparation: `p_cross_section_id`, `p_cross_section_ass_id`, `p_slide_prep_id`, `p_slide_prep_sp_id`, `p_slide_lab_id`, `p_slide_lab_price`.
- Pricing and result text: `pprice`, `pspprice`, `p_rs_specimen`, `p_rs_clinical_diag`, `p_rs_gross_desc`, `p_rs_microscopic_desc`.
- Special-slide/interim-result data: `p_sp_patho_comment` and the related pathologist/result fields posted to their dedicated handlers.

## Client-side behavior

- Script includes near the footer provide AJAX behavior for specimen/job/result/report/status workflows.
- Changing hospital triggers an AJAX request to `ajax_patient/getUserByHospitalID.php` and repopulates the clinician selector.
- The attachment form uses `FormData` to upload asynchronously, shows the endpoint response in `#pdfUploadStatus`, and refreshes `#uploadedPdfListSection`.
- SN/CN quality questionnaire edit/save controls toggle disabled fields and call their `ajax_patient/patient_edit_qu_*_update_db.php` endpoints. Preserve patient/questionnaire IDs and the current pathologist-owner checks.
- Input changes attach a `beforeunload` warning; submitting a form removes it.

## Conventions and implementation rules

- Keep patient reads and writes scoped to `$_GET['id']`, and follow the existing model methods for persistence.
- Map only posted fields that are present when hydrating models; these handlers intentionally preserve fields omitted from a section form.
- Preserve redirect-after-success to `/patient_edit.php?id=...` unless changing the UX explicitly.
- A POST handler does not prove its action is currently exposed in the page: check rendered controls and enclosing conditions, especially the disabled plan and lab-fluid sections.
- The page handles PN/LN and non-PN/LN through distinct result partials; maintain both paths when changing shared behavior.
- For workflow state changes, inspect both `patient_edit.php` and `Patient` methods plus `includes/status_cur.php` / `includes/patient_status.php`.
- Keep attachment changes coordinated with `pdf_attach_upload.php`, `Pdfattach`, and the dedicated attachment skill.

## Typical touchpoints

- `patient_edit.php` — request actions, aggregate data loading, section layout, edit/scroll state, and page JavaScript.
- `classes/Patient.php` — patient persistence, workflow status, critical-report, release, and edit-state helpers.
- `classes/Presultupdate.php` — interim/final result persistence.
- `includes/status_cur.php`, `includes/patient_status.php` — current status flags and transition UI.
- `includes/patient_form_*.php` — section-specific forms and AJAX-backed work lists.
- `ajax_patient/` and `ajax_*` — questionnaire and job/specimen interactions.
- `patient_pdf.php` and `includes/patient_form_100_pdf.php` — report generation and PDF controls.
- `pdf_attach_upload.php`, `classes/Pdfattach.php` — attached PDF upload and database persistence.
