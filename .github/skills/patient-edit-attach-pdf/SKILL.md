---
name: patient-edit-attach-pdf
description: 'Use when working on PDF attachment upload from patient_edit.php, including the admin-only append-PDF form, AJAX upload endpoint, patient attachment metadata, or pdf_attach records.'
---

# Patient Edit PDF Attachments

This skill documents the existing workflow for appending a PDF file to a patient record.

## When to Use

- Add or change the PDF attachment control on `patient_edit.php`.
- Change file validation, storage, or response behavior in `pdf_attach_upload.php`.
- Change persistence or retrieval through `Pdfattach` and the `pdf_attach` table.

## Current Workflow

1. `patient_edit.php` renders the **PDF File for append** form near the end of the page, inside the `$isCurUserAdmin` condition. This page flag represents user group `1000` (Admin).
2. The form uses `multipart/form-data`, accepts `application/pdf`, and sends the current patient ID in a hidden `patient_id` field.
3. A jQuery submit handler sends the form with `FormData` to `pdf_attach_upload.php` and displays the response in `#pdfUploadStatus`.
4. `pdf_attach_upload.php` includes app initialization, the database connection, and `user_auth.php`; checks the filename extension and detected MIME type; then moves the upload into a timestamp-prefix subfolder. For example, `2026-10-11 22:37:38` is cleaned to `2026-10-11_223738`; the directory uses its first 16 characters and the filename keeps the full timestamp prefix, producing `pdf_attach_uploads/2026-10-11_22373/2026-10-11_223738_QT-69-04-00681NB.pdf`.
5. The handler sets `Pdfattach::$subfolder` to the relative directory, such as `pdf_attach_uploads/2026-10-11_22373/`, and stores it with `filelocation`, `rec_create_date`, `patient_id`, and `fileName` in `pdf_attach`.
6. `Pdfattach::getAll($conn, $patient_id)` retrieves attachment rows for a patient ordered by ID.

## Database Requirement

The original dev3 dump does not include `pdf_attach.subfolder`. Apply `add_pdf_attach_subfolder.sql` once to each database before deploying the updated `Pdfattach::create()` insert.

## Implementation Guidance

- Keep the upload patient-scoped and preserve the existing AJAX response target unless the UI is intentionally redesigned.
- Keep the `pdf_attach` schema and `Pdfattach::create()` field names aligned: `filelocation`, `subfolder`, `rec_create_date`, `patient_id`, and `fileName`.
- When adding a display/list or download action, use the stored attachment row for the current patient; do not trust a client-supplied file path.
- Preserve the Admin-only page gate unless the requested access policy explicitly changes.
- Do not treat the page gate as server-side authorization. `pdf_attach_upload.php` currently includes `user_auth.php` but has no explicit Admin check of its own; enforce authorization in the endpoint before broadening or relying on its access control.

## Existing Risks to Consider

- The upload handler currently continues to create a `pdf_attach` row even if `move_uploaded_file()` fails. Handle the failure before inserting metadata when modifying this code.
- The handler should validate upload error state and size, and should fail closed if MIME detection cannot be performed.
- `patient_id` is currently taken from POST data. Validate that it is a valid patient the current user is allowed to access.
- The handler writes the posted patient ID to `dbg.txt`; avoid adding or retaining debug writes of request data in production paths.
- If the file move succeeds but the database insert fails, consider removing the orphaned file and returning a clear failure response.

## Source Files

- [Patient edit page](../../../patient_edit.php)
- [PDF upload endpoint](../../../pdf_attach_upload.php)
- [Attachment model](../../../classes/Pdfattach.php)
- [Authorization rules](../../../user_auth.php)