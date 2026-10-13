---
name: patient-edit-attach-pdf
description: 'Use when working on PDF attachment upload from patient_edit.php, including the admin-only append-PDF form, AJAX upload endpoint, patient attachment metadata, or pdf_attach records.'
---

# Patient Edit PDF Attachments

This skill documents the Admin-only **Upload** button workflow for appending a PDF file to a patient record, including its optional PDF-to-JPG conversion.

## When to Use

- Add or change the **PDF File for append** form or its **Upload** button on `patient_edit.php`.
- Change file validation, storage, or response behavior in `pdf_attach_upload.php`.
- Change persistence or retrieval through `Pdfattach` and the `pdf_attach` table.

## Current Workflow

1. `patient_edit.php` renders the **PDF File for append** form near the end of the page, inside `$isCurUserAdmin` (the Admin page flag).
2. The form uses `multipart/form-data`; its required file input accepts `application/pdf`. It sends hidden `patient_id` and `pnum` values for the current patient.
3. Clicking **Upload** submits the form through a jQuery handler using `FormData` to `pdf_attach_upload.php` (normal form submission is prevented). The endpoint response is inserted as HTML into `#pdfUploadStatus`; the AJAX error callback displays `File upload failed.`.
4. `pdf_attach_upload.php` checks the sanitized filename extension for `.pdf`. It detects the MIME type but does not currently reject uploads based on it. It creates a timestamp prefix from the Thai current date/time, then moves the upload into a folder built from the posted `pnum` and the first 16 characters of the timestamp. For example, the folder shape is `pdf_attach_uploads/QT-69-04-00681NB_2026-10-11_22373/`, and the PDF filename uses the full timestamp prefix.
5. After moving the PDF, the endpoint attempts a 300-dpi PDF-to-JPG conversion with ImageMagick when it finds a configured command for the operating system. Conversion failure or unavailable ImageMagick is reported in the endpoint response; conversion does not prevent the later metadata insert.
6. The handler stores the relative `filelocation`, `subfolder`, posted `patient_id`, and timestamp-prefixed `fileName` through `Pdfattach::create()`. That model sets `rec_create_date` and inserts the row into `pdf_attach`. `Pdfattach::getAll($conn, $patient_id)` retrieves attachment rows for a patient ordered by ID.

## Database Requirement

The original dev3 dump does not include `pdf_attach.subfolder`. Apply `add_pdf_attach_subfolder.sql` once to each database before deploying the updated `Pdfattach::create()` insert.

## Implementation Guidance

- Keep the upload patient-scoped and preserve the existing AJAX response target unless the UI is intentionally redesigned.
- Keep the Upload form field names and endpoint inputs aligned (`file`, `patient_id`, and `pnum`); the endpoint uses `pnum` to construct the storage directory.
- Keep the `pdf_attach` schema and `Pdfattach::create()` field names aligned: `filelocation`, `subfolder`, `rec_create_date`, `patient_id`, and `fileName`.
- When adding a display/list or download action, use the stored attachment row for the current patient; do not trust a client-supplied file path.
- Preserve the Admin-only page gate unless the requested access policy explicitly changes.
- Do not treat the page gate as server-side authorization. `pdf_attach_upload.php` currently includes `user_auth.php` but has no explicit Admin check of its own; enforce authorization in the endpoint before broadening or relying on its access control.
- Keep the response suitable for insertion into `#pdfUploadStatus`, or update the UI and response contract together. Do not include untrusted request values or shell command strings in the response.

## Existing Risks to Consider

- The endpoint currently checks only the filename extension; MIME detection is performed but its result is unused. Validate upload error state and size, and fail closed if MIME detection cannot be performed.
- Both `patient_id` and `pnum` come from POST data. Verify that they identify the same patient the current user is allowed to access, and validate `pnum` before using it in a filesystem path.
- The endpoint currently emits the ImageMagick command in its response. Avoid returning command strings or other internal details to the browser.
- If the file move succeeds but the database insert fails, consider removing the orphaned file and returning a clear failure response.
- The Upload success text is emitted before conversion and database persistence complete; keep user-visible success aligned with the final operation outcome when changing response behavior.

## Source Files

- [Patient edit page](../../../patient_edit.php)
- [PDF upload endpoint](../../../pdf_attach_upload.php)
- [Attachment model](../../../classes/Pdfattach.php)
- [Authorization rules](../../../user_auth.php)