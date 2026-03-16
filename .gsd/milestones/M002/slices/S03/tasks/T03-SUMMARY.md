---
id: T03
parent: S03
milestone: M002
provides:
  - Protection regressions for child-resource viewer routes
  - Updated milestone integration assertions for the new candidate page shape
  - Live browser proof of same-page support switching on the running app
requires:
  - slice: S03
    provides: new candidate viewer and protected child-resource route
  - slice: M001/S03
    provides: existing deterrence/protected-viewer baseline
affects: [S04]
key_files:
  - tests/Feature/CourseProtectionTest.php
  - tests/Feature/MilestoneIntegrationTest.php
  - browser verification against `http://172.28.224.1:8000`
key_decisions:
  - "The new candidate page must keep protection assertions current instead of relying on outdated one-media-only tests."
patterns_established:
  - "Candidate browser verification checks both visible selected state and the viewer rendered below the feed."
drill_down_paths:
  - .gsd/milestones/M002/slices/S03/tasks/T03-PLAN.md
duration: 35m
verification_result: pass
completed_at: 2026-03-15T18:48:00Z
---

# T03: Prove legacy/new candidate flows and live classroom behavior

**The new classroom feed is covered by both regression tests and a live browser pass against the running app.**

## What Happened

Extended `CourseProtectionTest` so the candidate viewer now proves protected child-resource file routes, protected legacy selection, and absence of leaked storage URLs in the rendered page. `MilestoneIntegrationTest` was also updated so the candidate page assertions follow the new stacked-list/viewer contract instead of the old one-media assumptions.

In the browser, the running candidate course page was verified against the Classroom-style reference. The legacy seeded course now shows a stacked two-support feed, the selected support is visibly marked, and clicking the PDF support reloads the same course page with the viewer anchored below the list.

## Deviations

A pre-existing 403 on some course cover image URLs was observed on the course index during browser checking. It did not block the new classroom list/viewer flow and is unrelated to the S03 resource changes.

## Files Created/Modified

- `tests/Feature/CourseProtectionTest.php` — protected child-resource and candidate-page regression coverage
- `tests/Feature/MilestoneIntegrationTest.php` — candidate page integration assertions
- live browser session — same-page support switching verification
