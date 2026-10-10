---
name: specimen
description: "Use when working on specimen master data in this PHP app: listing specimen types, adding editing, and managing specimen pricing metadata."
---

# Specimen Workflow

This skill captures the specimen management screen in `specimen.php`.

## Scope

Use this skill when modifying or extending:
- specimen listing screens
- specimen creation and editing pages
- specimen pricing and metadata display
- admin-level specimen management actions

## Business purpose

The page manages a master list of specimen types used elsewhere in the workflow, with basic CRUD actions and a table listing.

## Core implementation pattern

### 1. Authorization checks
The page verifies the user is logged in and ensures the user is not a clinician or hospital customer when the section is restricted.

### 2. List rendering
The page renders a DataTable with columns such as:
- number
- specimen name
- price
- manage actions

The DataTable loads data from `data/specimen.php`.

### 3. Action buttons
Each record includes:
- edit button to `specimen_edit.php?id={id}`
- delete button for admin users only

Delete actions confirm before submitting to `specimen_del.php`.

## Matching implementation rules
When changing this workflow:
- keep DataTable and backend payload schema in sync
- preserve edit/delete action logic and role restrictions
- update both page and client script when adding new specimen metadata fields

## Typical file touchpoints
- `specimen.php` — list page
- `specimen_add.php` — add page
- `specimen_edit.php` — edit page
- `specimen_del.php` — delete page
- data feed under `data/specimen.php`
