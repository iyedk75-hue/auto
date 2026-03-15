---
id: S02
parent: M002
milestone: M002
provides:
  - Nested admin multi-resource CRUD workflow
  - Admin resource manager UI for note, video, and PDF supports
  - Manual ordering and bilingual resource authoring from the admin side
  - Transition-aware course list state showing legacy versus child-resource-backed courses
requires:
  - slice: S01
    provides: child resource schema, normalized resource contract, and legacy fallback
affects: [S03, S04]
key_files:
  - routes/web.php
  - app/Http/Controllers/AdminCourseResourceController.php
  - app/Http/Controllers/AdminCourseController.php
  - resources/views/admin/course-resources/index.blade.php
  - resources/views/admin/course-resources/create.blade.php
  - resources/views/admin/course-resources/edit.blade.php
  - resources/views/admin/course-resources/partials/form.blade.php
  - resources/views/admin/courses/index.blade.php
  - resources/views/admin/courses/edit.blade.php
  - tests/Feature/AdminCourseResourceTest.php
key_decisions:
  - "Admin resource management uses dedicated nested pages instead of an oversized repeater inside course editing."
  - "Transition-aware admin state is surfaced explicitly on the course list."
patterns_established:
  - "Course-level resource management is entered through nested `admin.courses.resources.*` routes."
  - "Resource CRUD tests cover both page rendering and file lifecycle behavior."
drill_down_paths:
  - .gsd/milestones/M002/slices/S02/tasks/T01-SUMMARY.md
  - .gsd/milestones/M002/slices/S02/tasks/T02-SUMMARY.md
  - .gsd/milestones/M002/slices/S02/tasks/T03-SUMMARY.md
verification_result: pass
completed_at: 2026-03-15T18:23:00Z
---

# S02: Admin multi-resource management

**Admins can now manage ordered note, video, and PDF supports inside each course through a dedicated nested workflow.**

## What Happened

S02 turned the M002 resource model into a usable admin workflow. Nested course-resource routes and controller logic now handle create, update, delete, manual order, and protected file cleanup at the individual resource level.

A dedicated resource manager UI was added so admins can list, create, and edit supports per course without overloading the course form. The forms support bilingual titles and note bodies, type-specific file input, manual ordering, and active-state control.

The course list now also shows transition-aware state, making it obvious which courses still depend on legacy single-resource fields and which already use the new child-resource path.

## Deviations

Resource file preview inside the admin form remains informational for now; interactive preview will come through the student/protected-viewer integration path later in the milestone.

## Files Created/Modified

- `routes/web.php` — nested admin resource routes
- `app/Http/Controllers/AdminCourseResourceController.php` — admin multi-resource CRUD backend
- `app/Http/Controllers/AdminCourseController.php` — resource counts on course listing
- `resources/views/admin/course-resources/*.blade.php` — resource management UI
- `resources/views/admin/courses/index.blade.php` / `edit.blade.php` — resource-manager entry points
- `tests/Feature/AdminCourseResourceTest.php` — admin resource workflow verification
