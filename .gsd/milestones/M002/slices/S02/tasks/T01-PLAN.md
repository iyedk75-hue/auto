---
estimated_steps: 6
estimated_files: 4
---

# T01: Add nested admin resource CRUD routes and controller logic

**Slice:** S02 — Admin multi-resource management
**Milestone:** M002

## Description

Create the backend route/controller workflow for per-course resource CRUD, including note/file-type validation, manual ordering, and protected-file cleanup.

## Steps

1. Add nested resource routes under admin courses.
2. Create the resource controller with index/create/edit/store/update/destroy actions.
3. Validate type-specific fields for videos, PDFs, and notes.
4. Persist file resources to protected storage and note resources to text fields.
5. Delete protected files when a file resource is removed or replaced.
6. Write feature tests covering create/update/delete/order behavior.

## Must-Haves

- [ ] Admin can create and update note and file resources through real endpoints.
- [ ] Deleting a file resource also removes its protected asset.

## Verification

- `php artisan test --filter=AdminCourseResourceTest`

## Inputs

- `.gsd/milestones/M002/slices/S01/S01-SUMMARY.md` — child-resource and transition contract
- `app/Http/Controllers/AdminCourseController.php` — existing course admin patterns
- M001 protected asset storage decisions and helpers

## Expected Output

- `routes/web.php` — nested admin resource routes
- `app/Http/Controllers/AdminCourseResourceController.php` — resource CRUD backend
- `tests/Feature/AdminCourseResourceTest.php` — admin resource workflow verification
