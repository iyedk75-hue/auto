---
estimated_steps: 5
estimated_files: 6
---

# T02: Build admin resource management pages and forms

**Slice:** S02 — Admin multi-resource management
**Milestone:** M002

## Description

Build the admin-facing pages and forms for managing many ordered resources inside a course.

## Steps

1. Add resource index/create/edit Blade views.
2. Build a shared form partial supporting note and file resource types.
3. Show manual order, type, and bilingual title/note fields clearly.
4. Link the workflow from existing course management pages.
5. Re-run resource admin tests and frontend build.

## Must-Haves

- [ ] Admin has usable pages for listing and editing course resources.
- [ ] The form supports bilingual note/title fields plus type-specific file inputs.

## Verification

- `php artisan test --filter=AdminCourseResourceTest && npm run build`

## Inputs

- `.gsd/milestones/M002/slices/S02/tasks/T01-PLAN.md` — nested CRUD contract
- `resources/views/admin/courses/*.blade.php` — existing course management shell

## Expected Output

- `resources/views/admin/course-resources/*.blade.php` — admin resource management UI
- updated course admin views with links into the resource manager
