---
name: patient-list
description: "Use when working on the patient list page in this PHP app: display, filtering, management actions, and patient table data for the clinic workflow."
---

# Patient List Workflow

This skill captures the list page in `patient.php`.

## Scope

Use this skill when modifying or extending:
- patient listing screens
- patient table rendering
- patient actions like trash/delete
- access checks and role-based visibility

## Business purpose

The page displays the patient table for clinical workflows and lets staff manage patient records from a single list.

## Core implementation pattern

### 1. Authorization and role checks
The top of the file ensures the user is logged in and loads role flags via `user_auth.php`.

The page may show different actions depending on whether the user is:
- admin
- clinician customer
- hospital customer

### 2. POST actions for management
It handles actions such as:
- `trash_id` → `Patient::movetotrash()`
- `delete_id` → `Patient::delete2()`

These actions redirect back to `/patient.php` after completion.

### 3. Data table rendering
The page loads a DataTable via `js/patient.js` and feeds it patient data.

The table includes columns such as:
- patient type
- SN number
- HN number
- patient name
- hospital name
- pathologist
- accept date
- report date
- status
- PDF
- management actions

## Data conventions

The page is mostly a read/management dashboard that uses client-side JS for table rendering and server-side actions for destructive operations.

## Matching implementation rules
When making changes:
- preserve the `$_SESSION` / role-based checks
- keep redirect-after-action behavior consistent
- update front-end DataTable columns if backend data changes
- keep or extend the admin-only management actions only where intended

## Typical file touchpoints
- `patient.php` — page shell and DataTable entry
- `js/patient.js` — AJAX/data table behavior
- `Patient` model — trash/delete actions and patient queries
