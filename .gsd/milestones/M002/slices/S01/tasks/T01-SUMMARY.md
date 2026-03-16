---
id: T01
parent: S01
milestone: M002
provides:
  - `course_resources` table for repeated per-course support items
  - `CourseResource` model with first-class `video`, `pdf`, and `note` types
  - ordered `resources()` relation on `Course`
  - focused resource-model feature coverage
requires:
  - milestone: M001
    provides: bilingual course foundation and protected asset patterns
affects: [S02, S03, S04]
key_files:
  - database/migrations/2026_03_15_000015_create_course_resources_table.php
  - app/Models/CourseResource.php
  - app/Models/Course.php
  - tests/Feature/CourseResourceModelTest.php
key_decisions:
  - "Resource titles and note bodies are bilingual from the start so the new child model matches the existing bilingual course pattern."
patterns_established:
  - "A course owns many ordered child resources through `resources()`; repeated supports are no longer modeled as extra fields on `courses`."
drill_down_paths:
  - .gsd/milestones/M002/slices/S01/tasks/T01-PLAN.md
duration: 30m
verification_result: pass
completed_at: 2026-03-15T16:48:00Z
---

# T01: Add the child resource schema and model contract

**Courses now have a real repeated child-resource model instead of being limited to one media slot and one PDF slot.**

## What Happened

Added `course_resources` as a new table keyed by UUID and linked to `courses`, with explicit `resource_type`, bilingual title fields, optional bilingual note bodies, optional file metadata, deterministic `sort_order`, and active state. This gives the codebase a proper repeated support structure for the first time.

Created the `CourseResource` model with first-class `video`, `pdf`, and `note` types plus small type helpers. The `Course` model now exposes an ordered `resources()` relation, which becomes the new downstream seam for admin and student work.

Verification applied the migration cleanly in the Windows-side verification copy and passed `CourseResourceModelTest`, proving ordered persistence and the basic type contract.

## Deviations

None.

## Files Created/Modified

- `database/migrations/2026_03_15_000015_create_course_resources_table.php` — repeated resource storage contract
- `app/Models/CourseResource.php` — typed child-resource model
- `app/Models/Course.php` — ordered `resources()` relation
- `tests/Feature/CourseResourceModelTest.php` — resource schema/model verification
