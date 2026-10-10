---
name: template-report
description: "Use when working on report template management in this PHP app: listing templates, editing content, and handling template definitions for output formatting."
---

# Template Report Workflow

This skill captures the report template list in `templateReport.php`.

## Scope

Use this skill when modifying or extending:
- template report listing screens
- template metadata and report-type configuration
- template creation/edit pages
- JS-driven table rendering for report templates

## Business purpose

The page manages template definitions used for producing report output and exported layouts.

## Core implementation pattern

### 1. Page shell
The page checks authentication and gives a button to add a new template.

### 2. DataTable rendering
The table shows template metadata such as:
- id
- name
- result type
- template name
- template content
- management actions

The front-end script loads from `js/template_report.js`.

## Matching implementation rules
When changing this workflow:
- preserve the template listing structure
- keep the JS and backend response schema aligned
- update template metadata only where it is truly needed for report generation

## Typical file touchpoints
- `templateReport.php` — list page
- `templateReport_add.php` — add page
- `templateReport_edit.php` — edit page
- `js/template_report.js` — list rendering behavior
