---
name: service
description: "Use when working on service information pages in this PHP app: public service descriptions, content pages, and related navigation state."
---

# Service Workflow

This skill captures the service information page in `service.php`.

## Scope

Use this skill when modifying or extending:
- service description pages
- public content sections
- navigation state highlighting for service-related pages

## Business purpose

The page displays the clinic’s service offerings and related details in a simple informational layout.

## Core implementation pattern

### 1. Static content layout
The page is primarily a content page with HTML lists and service descriptions.

### 2. Navigation state
The script sets active menu state using jQuery selectors such as `#about_main` and `#service`.

## Matching implementation rules
When modifying this page:
- keep the content structure simple and readable
- preserve the active navigation state behavior
- avoid introducing complex business logic unless needed

## Typical file touchpoints
- `service.php` — public service content
- related navigation and header layout
