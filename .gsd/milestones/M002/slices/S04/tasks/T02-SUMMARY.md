---
id: T02
parent: S04
milestone: M002
provides:
  - Final admin-authored mixed-resource end-to-end milestone proof
  - Coverage for note, video, and PDF resources inside one assembled course flow
  - Verification that protected child-resource files persist and remain candidate-accessible only when authenticated
requires:
  - slice: S02
    provides: admin nested resource CRUD
  - slice: S03
    provides: candidate classroom list/viewer
  - slice: S04
    provides: protected child-resource file route from T01
affects: []
key_files:
  - tests/Feature/MilestoneIntegrationTest.php
  - tests/Feature/AdminCourseResourceTest.php
key_decisions:
  - "Milestone completion is proved through the real admin and candidate routes, not by fabricating DB rows around the edges."
patterns_established:
  - "Final integrated tests should cross authoring, rendering, storage, and auth boundaries in one flow."
drill_down_paths:
  - .gsd/milestones/M002/slices/S04/tasks/T02-PLAN.md
duration: 35m
verification_result: pass
completed_at: 2026-03-15T18:52:00Z
---

# T02: Add final admin-authored mixed-resource integration proof

**M002 now has a real end-to-end test proving an admin can author a mixed-resource course and a candidate can consume it through the same-page viewer.**

## What Happened

Extended `MilestoneIntegrationTest` with a final assembled flow: an admin creates a course, adds a note, a video, and a PDF through the real nested resource routes, then a candidate opens the course, sees the ordered feed, selects a file resource, and accesses protected child-resource file routes while authenticated.

The same test also proves private file persistence on the local disk and guest denial after logout, which closes the milestone-level integration boundary instead of leaving the assembled flow implied.

## Deviations

None.

## Files Created/Modified

- `tests/Feature/MilestoneIntegrationTest.php` — final mixed-resource end-to-end proof
- `tests/Feature/AdminCourseResourceTest.php` — rerun as supporting admin regression during final verification
