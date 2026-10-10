---
name: hospital
description: "Use when working on hospital master data in this PHP app: listing hospitals, editing details, and managing hospital-specific metadata."
---

# Hospital Workflow

This skill captures the hospital master-data list in `hospital.php`.

## Scope

Use this skill when modifying or extending:
- hospital listing pages
- hospital detail/edit pages
- admin-managed hospital metadata
- role-based access to hospital data

## Business purpose

The page manages the list of hospitals used throughout the patient workflow, including address and detail information.

## Core implementation pattern

### 1. Authorization checks
The page verifies login and restricts access for clinician/hospital customer roles.

### 2. DataTable rendering
The table includes columns such as:
- hospital id
- hospital name
- address
- detail
- manage actions

The client script loads from `data/hospital.php` and adds edit/detail/delete links.

### 3. CRUD actions
The page exposes:
- detail link
- edit link
- delete link for admin users

This is a standard CRUD/list admin screen.

## Matching implementation rules
When modifying this workflow:
- keep the role checks consistent
- preserve DataTable columns and the backend data contract
- keep admin-only delete actions restricted to admin users

## Typical file touchpoints
- `hospital.php` — list page
- `hospital_add.php` — add page
- `hospital_edit.php` — edit page
- `hospital_detail.php` — detail page
- `data/hospital.php` — table data source
