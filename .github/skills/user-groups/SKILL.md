---
name: user-groups
description: "Use when working with user groups, group IDs, role-based access, account creation/editing, or group-specific user queries in this PHP application."
---

# User Groups and Access Control

User group records are stored in the database table `user_groups` (`id`, `ugroup`, and `ugroup_e` in the dev3 schema). The `user` table links to a group through `ugroup_id`. Use the numeric IDs in code, not localized group labels. The screenshot supplied for this KB shows the labels below; verify the target database's `user_groups` table when exact labels or configured groups matter.

## Group IDs

| ID | Display name shown in the supplied table | English name | Current code notes |
|---:|---|---|---|
| `1000` | ผู้ดูแลระบบ | admin | `$isCurUserAdmin`; included in `$isCurUserNB`. |
| `1900` | พยาธิ_แอดมิน | pathologist_admin | `$isCurUserPathoAdmin`; included in `$isCurUserNB`. Separate from group `2000`. |
| `2000` | พยาธิแพทย์ | pathologist | `$isCurUserPatho`; included in `$isCurUserNB` and `$isCurUserGeneralNB`. Owner checks use job role 5 plus user ID or `role_a`. |
| `2100` | ผู้ช่วยพยาธิแพทย์ | pathologist assistant | `$isCurUserPathoAssis`; included in `$isCurUserNB` and `$isCurUserGeneralNB`. |
| `2200` | เจ้าหน้าที่แลป | Lab officer (NB) | `$isCurUserLabOfficerNB`; included in `$isCurUserNB` and `$isCurUserGeneralNB`. |
| `2500` | เจ้าหน้าที่ธุรการ | AdminStaff | `$isCurUserAdminStaff`; included in `$isCurUserNB` and `$isCurUserGeneralNB`. |
| `2600` | เจ้าหน้าที่การตลาด | Draft | Listed in `user_auth.php` comments, but no corresponding current group flag is defined there. Do not infer access from the label. |
| `2700` | เจ้าหน้าที่งานคุณภาพ | Draft | Listed in `user_auth.php` comments, but no corresponding current group flag is defined there. Do not infer access from the label. |
| `5000` | แพทย์ผู้ส่งตรวจ (ลูกค้า) | Clinician (Customer) | `$isCurUserClinicianCust`; included in `$isCurUserCust`. Clinician user lists commonly select group `5000`. |
| `5100` | โรงพยาบาล (ลูกค้า) | Lab officer (Customer) | `$isCurUserHospitalCust`; included in `$isCurUserCust`. Customer user creation associates the created account with its hospital through `Hospital::setUserID()`. |

## Where group identity comes from

- The `user` table stores the group foreign key in `ugroup_id`; group records are in `user_groups`.
- At login, `Auth::login()` loads the user's group with `Ugroup::getByID()` and stores the resulting object in `$_SESSION['usergroup']`.
- `Auth::getUserGroup()` is the shared way to retrieve the active group object. Existing authorization code generally checks `$u_cur_group_id->id`.
- `Ugroup::getAll($conn)` loads all groups. `Ugroup::getCust($conn)` currently limits the selector to IDs `0`, `5000`, and `5100`.
- `user_add.php` shows all groups only to group `1000`; other users get the customer-group list. `user_edit.php` loads all groups for its edit form, and separately computes whether the target account is NB or customer.

## Group-derived flags in `user_auth.php`

The current helper derives these flags from the session group ID:

| Flag | Group IDs |
|---|---|
| `$isCurUserAdmin` | `1000` |
| `$isCurUserPathoAdmin` | `1900` |
| `$isCurUserPatho` | `2000` |
| `$isCurUserPathoAssis` | `2100` |
| `$isCurUserLabOfficerNB` | `2200` |
| `$isCurUserAdminStaff` | `2500` |
| `$isCurUserClinicianCust` | `5000` |
| `$isCurUserHospitalCust` | `5100` |
| `$isCurUserNB` | `1000`, `1900`, `2000`, `2100`, `2200`, `2500` |
| `$isCurUserGeneralNB` | `2000`, `2100`, `2200`, `2500` |
| `$isCurUserCust` | `5000`, `5100` |

Patient-section `canView...` and `canEdit...` variables are also calculated in `user_auth.php`, but their checks mix group IDs, patient status, and legacy empty-string comparisons. Review the exact condition and its use in the target page before relying on it as an authorization rule. Some group lists include customers for viewing, while edit conditions generally differ.

## Group ID is not the same as user role flags

Do not conflate `ugroup_id` with per-user capability fields such as `role_a` or `role_7`:

- The active user's group establishes broad identity flags.
- `role_a` is also consulted in the assigned pathologist ownership condition for group `2000` and pathologist-admin group `1900`; the owning user ID is read from job role `5`.
- Cytologist ownership is based on the user's ID matching the assigned user in job role `7`.
- `role_7` is used by `User::getAllbyCytologist()` to select cytologist-capable users independently of their group ID.
- Other `User` query methods use hard-coded group IDs (for example, pathologist lists select `2000`; technical-user lists select `2000`, `2100`, and `2200`; customer-clinician lists select `5000`). When changing groups, search and update relevant selectors as well as authorization flags.

## Safe change procedure

When adding a group, changing an ID, or changing its permissions:

1. Verify the actual `user_groups` table contents and the ID-to-label mapping in the target database.
2. Search for numeric `ugroup_id` comparisons across `user_auth.php`, `classes/`, account management pages, user selectors, menus, AJAX endpoints, and feature pages.
3. Update broad identity flags and each feature's view/edit/owner conditions deliberately. Do not assume membership in `$isCurUserNB` or `$isCurUserCust` grants all individual page permissions.
4. Update user creation/edit selectors and the group-scoped `User` queries so users in the new group can be managed and selected where intended.
5. Keep user-specific capability flags (`role_a`, `role_7`, etc.) separate from the group assignment unless a requirement explicitly couples them.
6. Check both server-side actions and UI visibility. Hiding a button or menu is not a substitute for authorization at the endpoint.

## Main source files

- `user_auth.php` — group-derived identity, patient section access flags, pathologist/cytologist ownership checks.
- `classes/Auth.php` — login, session group loading, current user/group accessors.
- `classes/Ugroup.php` — group table retrieval and customer subset.
- `classes/User.php` — group-scoped user selectors.
- `user_add.php`, `user_edit.php` — group selection and account changes.
- `includes/header.php` — group-dependent navigation.
