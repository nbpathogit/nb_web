---
name: job-workflow
description: "Use when working on job management pages in this PHP app: list generation, daily work assignments, and job role data for patient workflow tasks."
---

# Job Workflow

This skill captures the job management listing flow in `job.php`.

## Scope

Use this skill when modifying or extending:
- job listing pages
- patient job assignment data
- job-role table rendering
- access checks for job-related workflows

## Business purpose

The page displays a list of work items grouped by task roles and patient assignments, allowing staff to track job queues and operational work.

## Core implementation pattern

### 1. Authorization checks
The page starts by requiring login and loading the user auth context.

It restricts access based on whether the current user is a clinician/hospital customer.

### 2. DataTable-based listing
The page renders a DataTable with columns such as:
- job role id
- patient id
- patient name
- user id
- job name
- pay
- comment
- request date
- finish date
- quantity
- insert time

The JS file `js/job.js` builds the listing and provides the table behavior.

### 3. Operational workflow
The job list is a queue/dashboard for tasks rather than a data-entry page, so the logic is mostly about listing and monitoring jobs.

## Matching implementation rules
When changing this workflow:
- preserve auth checks and user visibility restrictions
- keep the table schema in sync with the underlying job data source
- update both page and JS if the job columns or query payload change

## Typical file touchpoints
- `job.php` — page shell and DataTable setup
- `js/job.js` — AJAX data feed and job rendering
- job-related model classes and role mappings in the app
