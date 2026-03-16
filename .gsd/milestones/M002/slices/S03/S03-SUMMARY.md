---
id: S03
parent: M002
milestone: M002
provides:
  - Classroom-style stacked support list on the candidate course page
  - Same-page selected-resource viewer below the list
  - Query-string-backed selected-resource contract shared by legacy and child-resource courses
  - Candidate protection regressions for child-resource file routes
requires:
  - slice: S01
    provides: normalized resource payload and legacy compatibility
  - slice: S02
    provides: admin-authored ordered resources
affects: [S04]
key_files:
  - app/Http/Controllers/CandidateCourseController.php
  - routes/web.php
  - resources/views/candidate/courses/show.blade.php
  - resources/css/app.css
  - lang/fr/ui.php
  - lang/ar/ui.php
  - tests/Feature/CandidateCourseResourceViewTest.php
  - tests/Feature/CourseProtectionTest.php
  - tests/Feature/MilestoneIntegrationTest.php
key_decisions:
  - "Resource selection remains on `courses.show` through a `?resource=` query parameter and same-page viewer anchor."
patterns_established:
  - "Only the active support emits a viewer URL; list items link back to the same course page with a selected key."
  - "Candidate course rendering now flows entirely through the resolved resource contract."
drill_down_paths:
  - .gsd/milestones/M002/slices/S03/tasks/T01-SUMMARY.md
  - .gsd/milestones/M002/slices/S03/tasks/T02-SUMMARY.md
  - .gsd/milestones/M002/slices/S03/tasks/T03-SUMMARY.md
verification_result: pass
completed_at: 2026-03-15T18:49:00Z
---

# S03: Student classroom-style resource list

**Candidates now see a Classroom-style support feed inside each course and open the selected support below that list on the same page.**

## What Happened

S03 replaced the old one-media/one-PDF candidate layout with a support-first classroom flow. The candidate page now consumes `resolvedResources()`, presents ordered support cards with type/date metadata, and uses a selected-resource contract that works for both new child resources and legacy single-resource courses.

The selected support renders below the list in the same page. Notes render inline, file resources render through authenticated URLs, and legacy course records no longer need a separate candidate layout path.

The slice also kept the protection surface honest by adding child-resource file-route regressions and updating milestone integration assertions to the new viewer contract.

## Deviations

Selection currently reloads the same course route with `?resource=` instead of switching purely client-side. This preserves the requested same-page viewer while keeping selection state explicit and resilient.

## Files Created/Modified

- `app/Http/Controllers/CandidateCourseController.php` — selected-resource contract and child-resource file delivery
- `routes/web.php` — child resource file route
- `resources/views/candidate/courses/show.blade.php` — stacked feed plus same-page viewer
- `resources/css/app.css` — classroom feed/viewer styling
- `lang/fr/ui.php` / `lang/ar/ui.php` — support-list labels
- `tests/Feature/CandidateCourseResourceViewTest.php` — candidate viewer contract proof
- `tests/Feature/CourseProtectionTest.php` / `tests/Feature/MilestoneIntegrationTest.php` — regression and integration proof
