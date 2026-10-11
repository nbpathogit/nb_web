# Workflow Skills Index

This folder stores the repository's Copilot workflow skill metadata for the clinic/pathology PHP application.

## Purpose

Each skill describes a major business workflow, the relevant screens, and the data conventions used by the codebase so future edits stay consistent and easy to trace.

## Skill registry

The central metadata manifest is kept in [skill-index.json](skill-index.json). It provides a machine-readable reference for:
- skill name and folder
- category / business domain
- short description
- primary entry-point files
- relevant workflow areas

## Categories

- Database reference: [database-schema](database-schema/SKILL.md)
- Patient workflows: [patient-add](patient-add/SKILL.md), [patient-edit](patient-edit/SKILL.md), [patient-list](patient-list/SKILL.md)
- Label generation: [generate-label](generate-label/SKILL.md), [generate-label-v2](generate-label-v2/SKILL.md)
- Billing and cost reporting: [billing](billing/SKILL.md)
- Administration and master data: [hospital](hospital/SKILL.md), [labfluid](labfluid/SKILL.md), [specimen](specimen/SKILL.md), [template-report](template-report/SKILL.md), [job-workflow](job-workflow/SKILL.md)
- User and access management: [user-add](user-add/SKILL.md)
- Public content and service pages: [service](service/SKILL.md)

## Quick index

- [billing](billing/SKILL.md)
- [database-schema](database-schema/SKILL.md)
- [generate-label](generate-label/SKILL.md)
- [generate-label-v2](generate-label-v2/SKILL.md)
- [hospital](hospital/SKILL.md)
- [job-workflow](job-workflow/SKILL.md)
- [labfluid](labfluid/SKILL.md)
- [patient-add](patient-add/SKILL.md)
- [patient-edit](patient-edit/SKILL.md)
- [patient-list](patient-list/SKILL.md)
- [service](service/SKILL.md)
- [specimen](specimen/SKILL.md)
- [template-report](template-report/SKILL.md)
- [user-add](user-add/SKILL.md)

## Metadata conventions

- Folder name matches the skill key.
- Each skill folder contains a single `SKILL.md` file.
- The `name` value in frontmatter should stay stable for Copilot lookup.
- The manifest should be updated whenever a new skill is added or a skill description changes.

## Supporting summaries

- [../skill-summary.html](../skill-summary.html)
- [../database_schema.md](../database_schema.md)
- [../database_schema.html](../database_schema.html)
