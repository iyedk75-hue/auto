---
id: T03
parent: S01
milestone: M002
provides:
  - locale-aware title and note-body helpers on CourseResource
  - downstream-ready normalized resource payload with display fields
  - transition tests proving note/file metadata and Arabic display fields
  - milestone regression confirmation that M001’s integrated flow still passes
requires:
  - slice: S01
    provides: normalized resource resolution and compatibility from T02
  - milestone: M001
    provides: bilingual course and protected-viewer foundation
affects: [S02, S03, S04]
key_files:
  - app/Models/CourseResource.php
  - app/Models/Course.php
  - tests/Feature/CourseTransitionTest.php
  - tests/Feature/MilestoneIntegrationTest.php
key_decisions:
  - "The normalized resource payload includes locale-aware display fields so later slices don’t need to reimplement language selection per resource row."
patterns_established:
  - "Downstream views should consume `display_title` and `display_note_body` from the resolved resource payload instead of branching on raw bilingual fields."
drill_down_paths:
  - .gsd/milestones/M002/slices/S01/tasks/T03-PLAN.md
duration: 25m
verification_result: pass
completed_at: 2026-03-15T17:08:00Z
---

# T03: Expose a downstream-ready resource shape for later slices

**The transition layer now emits a locale-aware normalized payload that later admin and student slices can consume directly.**

## What Happened

Expanded `CourseResource` with locale-aware helpers for titles and note bodies, then extended the resolved payload to include `display_title`, `display_note_body`, and Arabic-translation metadata. `Course` now threads locale through both child-resource and legacy-resource resolution so later slices can render one uniform resource contract.

This shifts the complexity down into the model layer where it belongs. Later admin/student views can focus on list and viewer behavior instead of deciding how to translate or normalize every resource item.

Verification extended `CourseTransitionTest` to prove downstream-ready payload fields and reran `MilestoneIntegrationTest` to confirm the M001 integrated flow still holds after the model changes.

## Deviations

None.

## Files Created/Modified

- `app/Models/CourseResource.php` — locale-aware resource display helpers and normalized payload fields
- `app/Models/Course.php` — locale-threaded resolved resource contract
- `tests/Feature/CourseTransitionTest.php` — payload-shape verification
- `tests/Feature/MilestoneIntegrationTest.php` — regression confirmation
