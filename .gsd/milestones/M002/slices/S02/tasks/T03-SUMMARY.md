---
id: T03
parent: S02
milestone: M002
provides:
  - Resource counts and transition-state labels on the admin course listing
  - Clear admin visibility into legacy versus child-resource-backed courses
  - Stronger admin tests for legacy/new mixed management state
requires:
  - slice: S02
    provides: resource manager UI from T02
  - slice: S01
    provides: legacy compatibility and resource precedence rules
affects: [S03, S04]
key_files:
  - app/Http/Controllers/AdminCourseController.php
  - resources/views/admin/courses/index.blade.php
  - lang/fr/ui.php
  - lang/ar/ui.php
  - tests/Feature/AdminCourseResourceTest.php
key_decisions:
  - "The course list should explicitly distinguish legacy support state from full multi-resource state during the transition."
patterns_established:
  - "Transition-sensitive admin surfaces should expose model state directly instead of hiding it behind generic labels."
drill_down_paths:
  - .gsd/milestones/M002/slices/S02/tasks/T03-PLAN.md
duration: 25m
verification_result: pass
completed_at: 2026-03-15T18:22:00Z
---

# T03: Surface resource counts and transition-safe admin behavior

**The admin course list now shows which courses are still legacy-backed and which already use the new multi-resource model.**

## What Happened

Updated the admin course listing to include resource counts, a management entry point, and explicit transition-state labels. Courses with child resources now read as multi-support courses, while legacy-only courses are visibly marked as inherited support state instead of looking identical.

This keeps the transition truthful for admins: they can tell at a glance whether a course still depends on the old single-resource fields or already uses the new child-resource workflow.

The admin resource test suite was extended to verify course-list visibility for both legacy and child-resource-backed courses, and transition tests remained green.

## Deviations

None.

## Files Created/Modified

- `app/Http/Controllers/AdminCourseController.php` — eager resource counts for listing
- `resources/views/admin/courses/index.blade.php` — resource counts and transition-state labels
- `lang/fr/ui.php` / `lang/ar/ui.php` — transition-state and count labels
- `tests/Feature/AdminCourseResourceTest.php` — mixed legacy/new admin state verification
