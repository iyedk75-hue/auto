---
id: T01
parent: S03
milestone: M001
provides:
  - Protected storage contract for new course media and PDF uploads
  - Course model disk-resolution helpers for protected and legacy public assets
  - Safe asset deletion helpers reused by update/destroy flows
  - Feature-test coverage for protected storage persistence and legacy compatibility
requires:
  - slice: S02
    provides: Bilingual course CRUD and lesson contract
affects: [S05]
key_files:
  - app/Models/Course.php
  - app/Http/Controllers/AdminCourseController.php
  - tests/Feature/CourseProtectionTest.php
key_decisions:
  - "New lesson media/PDF uploads now live on the private `local` disk, while cover images remain public."
  - "Legacy public asset paths are preserved through dynamic disk resolution instead of a disruptive data migration."
patterns_established:
  - "Course assets are resolved by model-level disk helpers rather than hard-coded disk assumptions in every controller."
drill_down_paths:
  - .gsd/milestones/M001/slices/S03/tasks/T01-PLAN.md
duration: 40m
verification_result: pass
completed_at: 2026-03-15T08:25:00Z
---

# T01: Move lesson asset persistence to a protected storage contract

**New lesson media and PDFs now persist to private storage, and legacy public assets still resolve without breaking old course records.**

## What Happened

Changed admin course create/update flows so lesson media and PDFs are stored on the private `local` disk under protected directories instead of the public disk. Cover images were left on public storage because they are decorative and already safe to expose.

The `Course` model now resolves whether a lesson asset lives on the private or legacy public disk, and exposes deletion helpers so update/destroy flows remove the right file from the right place. This avoided a forced migration of old course records while still moving new uploads to the protected path.

`CourseProtectionTest` now proves new uploads land on the private disk, legacy public assets still resolve correctly, and legacy public files are removed when replaced by protected ones.

## Deviations

None.

## Files Created/Modified

- `app/Models/Course.php` — protected/legacy disk resolution and asset deletion helpers
- `app/Http/Controllers/AdminCourseController.php` — private media/PDF persistence
- `tests/Feature/CourseProtectionTest.php` — protected storage verification
