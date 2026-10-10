---
name: patient-add
description: "Use when creating a new patient record in this PHP app: generating SN numbers, assigning default workflow values, and saving the first patient profile."
---

# Patient Add Workflow

This skill captures the patient creation flow in `patient_add.php`.

## Scope

Use this skill when working on:
- new patient creation screens
- auto-generated SN number logic
- initialize/default values when creating a patient
- first-time record setup and workflow assignments

## Business purpose

The page creates a new patient case and auto-generates or accepts a specimen number using a selected SN format and year/run number.

## Core implementation pattern

### 1. SN generation logic
The page can generate a number in two modes:
- automatic mode: `is_autogen == "yes"`
- manual mode: `is_autogen == "no"`

In auto mode, it:
- reads the current year with `Util::get_curreint_year()`
- checks how many SNs already exist for that year
- finds the max running number for the selected type via `Patient::get_max_sn_run_by_year()`
- increments the count and formats the result with zero padding

This produces values like `PN202600123` or similar based on the selected prefix.

### 2. Patient object creation
After the SN is determined, the page builds a `Patient` object and sets values from `$_POST`, including:
- `pnum`, `plabnum`, `pname`, `plastname`
- `pgender`, `pedge`, `date_1000`
- status and hospital metadata
- pathologist, specimen, clinician, price, and result-related fields

It also stores workflow-related flags such as:
- `isautoeditmode`
- `pautoscroll`

### 3. Persistence flow
The object is saved using `$patient->create($conn)`.

After creation, there is extra default initialization work for some patient types, including creation of initial pathology result records via `Presultupdate` when the SN type matches certain prefixes.

## Data conventions

- `sn_type` is usually the prefix like `PN`, `LN`, etc.
- `sn_year` carries the current year
- `sn_run` stores the numeric sequence
- `pnum` is the final combined patient number
- workflow metadata like `status_id`, `priority_id`, and `date_1000` are typically copied from posted values or defaults

## Matching implementation rules
When modifying this workflow:
- keep the auto/manual SN generation logic consistent
- do not bypass the `create($conn)` persistence step
- preserve default patient initialization and workflow state hints
- update any associated initial `Presultupdate` records when the patient type requires them

## Typical file touchpoints
- `patient_add.php` — main creation screen
- `Patient` model — SN generation and create logic
- `Presultupdate` model — extra initial result setup after creation
- utility helpers like `Util::prepend_string_with_zero()`
