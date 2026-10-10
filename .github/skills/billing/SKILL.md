---
name: billing
description: "Use when working on billing pages in this PHP app: date-range billing reports, service cost tables, and invoice-like patient summaries."
---

# Billing Workflow

This skill captures the billing report flow in `billing.php`.

## Scope

Use this skill when modifying or extending:
- billing dashboards
- date-range invoice reports
- service type and charge listings
- cost display per patient, clinician, hospital, or pathologist

## Business purpose

The page helps users review billing information for patient cases, usually filtered by accepted date range. It presents costs and service details in a report-style table.

## Core implementation pattern

### 1. Page setup
The file requires init, DB connection, auth, and header setup.

It also includes a date-range selector and uses `daterangepicker` for choosing the billing period.

### 2. Report table rendering
The page loads a DataTable with columns such as:
- billing id
- type
- SN
- HN
- patient
- clinician
- hospital
- pathologist
- accept date
- billing date
- service type
- code
- description
- block
- cost

The client script `js/billing_a.js` populates the table.

### 3. Date-driven filtering
Billing is typically driven by accept-date ranges, which makes the date selector central to the data retrieval.

## Matching implementation rules
When changing this workflow:
- keep the date-range selector and DataTable in sync
- preserve the underlying billing report query contract expected by `billing_a.js`
- keep the service/cost columns aligned with the backend query output

## Typical file touchpoints
- `billing.php` — report shell and date controls
- `js/billing_a.js` — AJAX fetching and table rendering
- billing-related model and reporting classes in the app
