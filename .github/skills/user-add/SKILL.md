---
name: user-add
description: "Use when creating a new user account in this PHP app: user role assignment, hospital linkage, and credential creation."
---

# User Add Workflow

This skill captures the new-user creation flow in `user_add.php`.

## Scope

Use this skill when modifying or extending:
- user creation screens
- role and hospital assignment logic
- password hashing and credential setup
- admin-only user registration workflow

## Business purpose

This page creates a new application user, assigns them to a user group and optional hospital, and stores authentication data.

## Core implementation pattern

### 1. Role/hospital selection
The page loads available groups and hospitals, and restricts the options depending on the current user’s admin status.

### 2. User object creation
On POST, it creates a `User` object and sets:
- `pre_name`
- `name`
- `lastname`
- `ugroup_id`
- `uhospital_id`
- `username`
- `password` (hashed with `password_hash()`)
- `create_by`

### 3. Persistence and post-create linkage
If creation succeeds, the page redirects to the user edit screen. If the user group is a specific hospital-linked role, it also calls `Hospital::setUserID()` to link the hospital to the user.

## Matching implementation rules
When changing this workflow:
- keep password hashing secure
- preserve group/hospital assignment behavior
- keep redirect logic consistent with the user edit flow
- maintain admin restrictions on who can create users

## Typical file touchpoints
- `user_add.php` — add form and creation logic
- `User` model — creation and validation
- `Hospital::setUserID()` — linked hospital assignment
- includes under `includes_user/` for form rendering
