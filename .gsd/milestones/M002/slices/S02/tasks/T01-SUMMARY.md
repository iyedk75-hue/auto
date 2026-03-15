---
id: T01
parent: S02
milestone: M002
provides:
  - Nested admin course-resource CRUD routes
  - `AdminCourseResourceController` with type-aware create/update/delete logic
  - Protected file persistence and cleanup for resource-level file supports
  - Resource CRUD feature coverage
requires:
  - slice: S01
    provides: child resource schema, ordering, and transition contract
affects: [S03, S04]
key_files:
  - routes/web.php
  - app/Http/Controllers/AdminCourseResourceController.php
  - app/Models/CourseResource.php
  - tests/Feature/AdminCourseResourceTest.php
key_decisions:
  - "Admin resource management is nested under courses rather than stuffed into the course form as a giant repeater."
  - "Type-specific validation starts at the controller layer so notes and file resources are treated differently from the first implementation."
patterns_established:
  - "Resource-level file cleanup happens on replacement and deletion through the child resource model."
drill_down_paths:
  - .gsd/milestones/M002/slices/S02/tasks/T01-PLAN.md
duration: 40m
verification_result: pass
completed_at: 2026-03-15T18:05:00Z
---

# T01: Add nested admin resource CRUD routes and controller logic

**Admin resource management now has real nested CRUD endpoints with type-aware persistence and protected-file cleanup.**

## What Happened

Added nested course-resource routes under admin courses and introduced `AdminCourseResourceController` with index/create/edit/store/update/destroy actions. Notes and file resources are validated differently, and file resources persist to protected local storage directories instead of public storage.

Update and delete flows now clean up protected files when a resource is replaced or removed. This moved file lifecycle management down to the resource level instead of leaving it coupled to the course’s older single-file fields.

`AdminCourseResourceTest` now proves note creation, protected PDF creation, file replacement with updated order, and resource deletion with protected-file cleanup.

## Deviations

None.

## Files Created/Modified

- `routes/web.php` — nested course-resource admin routes
- `app/Http/Controllers/AdminCourseResourceController.php` — admin resource CRUD backend
- `app/Models/CourseResource.php` — resource-level file cleanup helpers
- `tests/Feature/AdminCourseResourceTest.php` — backend admin resource workflow verification
