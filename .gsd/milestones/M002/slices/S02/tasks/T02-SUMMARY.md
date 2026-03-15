---
id: T02
parent: S02
milestone: M002
provides:
  - Admin resource list/create/edit pages
  - Shared admin resource form supporting note and file resource fields
  - Bilingual admin resource labels in French and Arabic
  - Course-management links into the resource manager
requires:
  - slice: S02
    provides: nested course-resource CRUD backend from T01
  - slice: S01
    provides: child resource model and bilingual resource fields
affects: [S03, S04]
key_files:
  - resources/views/admin/course-resources/index.blade.php
  - resources/views/admin/course-resources/create.blade.php
  - resources/views/admin/course-resources/edit.blade.php
  - resources/views/admin/course-resources/partials/form.blade.php
  - resources/views/admin/courses/index.blade.php
  - resources/views/admin/courses/edit.blade.php
  - lang/fr/ui.php
  - lang/ar/ui.php
  - tests/Feature/AdminCourseResourceTest.php
key_decisions:
  - "The first admin resource UI is a dedicated manager flow with separate pages, not a complex repeater embedded into the course form."
patterns_established:
  - "Admin resource forms always expose manual order and bilingual title/note fields, regardless of type."
drill_down_paths:
  - .gsd/milestones/M002/slices/S02/tasks/T02-PLAN.md
duration: 50m
verification_result: pass
completed_at: 2026-03-15T18:15:00Z
---

# T02: Build admin resource management pages and forms

**Admins now have a real resource manager UI for listing, creating, and editing ordered supports inside a course.**

## What Happened

Added dedicated admin resource-management pages under each course, including list, create, edit, and shared form partial views. The form supports manual order, resource type, bilingual titles, bilingual note bodies, file input for file resources, and active-state control.

The course management pages now link into this resource manager so admins can reach the new workflow from the existing course area. Resource UI copy was added in French and Arabic alongside the existing admin course translations.

Verification extended `AdminCourseResourceTest` to prove the management pages render successfully, then reran the suite plus the frontend build.

## Deviations

The current-file indicator is intentionally informational rather than a clickable preview inside the form because resource-level preview will be handled through the student/protected-viewer path later.

## Files Created/Modified

- `resources/views/admin/course-resources/*.blade.php` — admin resource management pages
- `resources/views/admin/courses/index.blade.php` / `edit.blade.php` — links into the resource manager
- `lang/fr/ui.php` / `lang/ar/ui.php` — admin resource UI labels
- `tests/Feature/AdminCourseResourceTest.php` — page-render verification
