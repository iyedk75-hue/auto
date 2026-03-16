---
id: T01
parent: S03
milestone: M002
provides:
  - Candidate-facing selected-resource contract on `courses.show`
  - Query-string selection with deterministic fallback
  - Resource-level authenticated file route for child video/PDF resources
  - Feature proof for default, explicit, and legacy resource selection
requires:
  - slice: S01
    provides: normalized `resolvedResources()` payload and legacy fallback
  - slice: S02
    provides: admin-authored child resources
affects: [S04]
key_files:
  - app/Http/Controllers/CandidateCourseController.php
  - routes/web.php
  - tests/Feature/CandidateCourseResourceViewTest.php
key_decisions:
  - "Candidate resource selection state stays on the existing `courses.show` route via `?resource=` rather than introducing a new page or client-only state."
patterns_established:
  - "Only the selected support emits a viewer URL; list items link back to `courses.show` with a selected resource key."
drill_down_paths:
  - .gsd/milestones/M002/slices/S03/tasks/T01-PLAN.md
duration: 45m
verification_result: pass
completed_at: 2026-03-15T18:32:00Z
---

# T01: Add candidate resource-selection contract and feature proof

**The candidate course page now receives a real selected-resource contract instead of guessing from the old course media/PDF fields.**

## What Happened

`CandidateCourseController` now builds a presented resource list from `resolvedResources()`, adds localized type/date metadata, exposes the selected resource through the existing `courses.show` route, and falls back cleanly to the first resource when the query string is missing or invalid.

A new authenticated `courses.resources.file` route was added so child video/PDF resources can render inline through the same protected-delivery model as the old course-level media/PDF routes.

`CandidateCourseResourceViewTest` proves default selection, explicit selection, and legacy fallback behavior through the real candidate page.

## Deviations

Selection state uses a shareable `?resource=` query parameter rather than purely client-side state. This was intentional to keep the new viewer server-rendered and testable.

## Files Created/Modified

- `app/Http/Controllers/CandidateCourseController.php` — candidate resource presentation and protected child-resource delivery
- `routes/web.php` — child resource file route
- `tests/Feature/CandidateCourseResourceViewTest.php` — selected-resource contract coverage
