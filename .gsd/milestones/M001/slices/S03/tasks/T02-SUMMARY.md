---
id: T02
parent: S03
milestone: M001
provides:
  - Authenticated inline media route for course lesson assets
  - Protected PDF route reused with disk-aware path resolution
  - Candidate lesson HTML wired to route-based media/PDF URLs instead of public storage URLs
  - Admin preview links compatible with protected lesson storage
  - Route-level access-control verification for guest, candidate, and admin asset access
requires:
  - slice: S03
    provides: Protected storage contract and disk-resolution helpers from T01
  - slice: S01
    provides: Existing classroom shell and localized labels
affects: [S05]
key_files:
  - routes/web.php
  - app/Http/Controllers/CandidateCourseController.php
  - resources/views/candidate/courses/show.blade.php
  - resources/views/admin/courses/partials/form.blade.php
  - tests/Feature/CourseProtectionTest.php
key_decisions:
  - "Authenticated asset routes are shared between candidates and admins so course management previews keep working after the storage cutover."
patterns_established:
  - "Protected lesson assets are consumed through application routes, never directly through `Storage::url(...)`."
drill_down_paths:
  - .gsd/milestones/M001/slices/S03/tasks/T02-PLAN.md
duration: 35m
verification_result: pass
completed_at: 2026-03-15T08:35:00Z
---

# T02: Add authenticated inline asset endpoints and wire the viewer to them

**Lesson media and PDFs now flow through authenticated inline routes, and the candidate course page no longer leaks public storage URLs.**

## What Happened

Added an authenticated `courses.media` route alongside the existing PDF route and rewired `CandidateCourseController` so both media and PDF responses resolve the correct storage disk before returning inline file responses. Access control now denies guests and keeps inactive-course protection for non-admins, while allowing admins to preview assets during course management.

The candidate course detail page now embeds media and PDF through route-based URLs instead of direct public storage paths. Admin course form preview links were updated to use the same protected routes, so private storage did not break file previews in the back office.

The protection suite now proves guests cannot access lesson assets, candidates can, admins can preview them, and the lesson HTML no longer contains `/storage/courses/...` protected file URLs.

## Deviations

None.

## Files Created/Modified

- `routes/web.php` — authenticated media route
- `app/Http/Controllers/CandidateCourseController.php` — disk-aware inline asset delivery and access control
- `resources/views/candidate/courses/show.blade.php` — protected media/PDF route wiring
- `resources/views/admin/courses/partials/form.blade.php` — admin preview route wiring
- `tests/Feature/CourseProtectionTest.php` — route and HTML protection verification
