---
name: labfluid
description: "Use when working on laboratory fluid master data in this PHP app: listing, editing, and deleting fluid definitions."
---

# Lab Fluid Workflow

This skill captures the lab fluid management screen in `labfluid.php`.

## Scope

Use this skill when modifying or extending:
- lab fluid list pages
- fluid add/edit pages
- admin-managed fluid metadata

## Business purpose

The page manages a list of fluid types used in the laboratory workflow.

## Core implementation pattern

### 1. Authorization checks
The page confirms login status and restricts access for certain user groups.

### 2. DataTable rendering
The table includes:
- id
- fluid name
- detail
- manage actions

The client script loads from `data/labfluid.php`.

### 3. Action buttons
Each row includes:
- edit link to `labfluid_edit.php`
- delete button for admin users

## Matching implementation rules
When changing this workflow:
- keep role restrictions intact
- preserve the DataTable and backend data contract
- keep delete permissions restricted to admin users only

## Typical file touchpoints
- `labfluid.php` — list page
- `labfluid_add.php` — add page
- `labfluid_edit.php` — edit page
- `labfluid_del.php` — delete page
- `data/labfluid.php` — data feed
