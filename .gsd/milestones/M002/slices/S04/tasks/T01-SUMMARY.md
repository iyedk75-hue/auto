---
id: T01
parent: S04
milestone: M002
provides:
  - Protected child-resource file route for candidate viewing
  - Access control for child file resources mirroring legacy protected course assets
  - Protection regressions covering child-resource HTML and file delivery
requires:
  - slice: S03
    provides: candidate viewer contract and selected-resource path
  - milestone: M001
    provides: protected inline delivery baseline
affects: []
key_files:
  - app/Http/Controllers/CandidateCourseController.php
  - routes/web.php
  - tests/Feature/CourseProtectionTest.php
key_decisions:
  - "Child file resources use a dedicated protected route rather than leaking direct storage URLs into the candidate page."
patterns_established:
  - "Protection tests now guard both legacy course-level assets and child-resource assets."
drill_down_paths:
  - .gsd/milestones/M002/slices/S04/tasks/T01-PLAN.md
duration: 30m
verification_result: pass
completed_at: 2026-03-15T18:41:00Z
---

# T01: Finalize protected child-resource delivery and regression coverage

**Child video and PDF resources now use authenticated inline delivery and are covered by the protection regression suite.**

## What Happened

Added `courses.resources.file` and corresponding controller logic so child file resources can be streamed inline through authenticated responses. The route validates course/resource ownership, respects active-state checks for non-admins, and reuses the same private response headers as the legacy protected media/PDF endpoints.

`CourseProtectionTest` now covers guest denial, authenticated access, and candidate HTML assertions for both legacy course assets and child-resource assets.

## Deviations

This route landed while the S03 candidate viewer was being assembled because the page could not truthfully render child file resources without it.

## Files Created/Modified

- `app/Http/Controllers/CandidateCourseController.php` — child-resource file responses
- `routes/web.php` — `courses.resources.file`
- `tests/Feature/CourseProtectionTest.php` — protection regressions for child resources
